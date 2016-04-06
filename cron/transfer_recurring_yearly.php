<?php
/**
 * @author         Olga Banasinska
 * @copyright      Omines
 * @since          26-7-11 14:31
 * @package        System
 */

define('SYS_PATH',		'/var/www/clients/client1/web1/web/cron/');
$locale     = "en-US";
$payLogPath = SYS_PATH . 'payment/logs/';

define('BPE_MERCHANT_KEY', 'RZq7Zvqln9');




date_default_timezone_set('Europe/Amsterdam');

function errorHandler($errNo, $errStr, $errFile, $errLine, $errContext)
{
    global $payLogPath;
    $c = print_r($errContext, true);
    $f = $payLogPath . date('Ymd_His') . '_cron.txt';
    $t = 'ERROR';
    switch ($errNo)
    {
        case E_WARNING:
        case E_USER_WARNING:
            $t = 'WARNING';
            if (substr($errStr, 0, 5) == 'chmod')
                return;
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $t = 'NOTICE';
            break;
        default:
            $t = 'ERROR';
            break;
    }
    file_put_contents($f, "\n***************  {$t} #{$errNo} ***************\n", FILE_APPEND);
    file_put_contents($f, "  File: {$errFile}\n", FILE_APPEND);
    file_put_contents($f, "  Line: {$errLine}\n", FILE_APPEND);
    file_put_contents($f, "  Message: {$errStr}\n", FILE_APPEND);
    file_put_contents($f, "  Context: {$c}\n", FILE_APPEND);
    file_put_contents($f, "\n***************  END {$t}  ***************\n\n", FILE_APPEND);
}
set_error_handler('errorHandler');

/**
 * Soap client
 */
require_once SYS_PATH.'payment/classes/Buckaroo/loader.php';
//require_once SYS_PATH.'payment/classes/class-buckaroo.php';
require_once SYS_PATH.'payment/classes/class-buckaroo-v3.php';
require_once SYS_PATH.'payment/classes/class-transaction.php';
require_once SYS_PATH.'payment/classes/class-recurringpayment.php';

//echo BUCKAROO_PRIVATE_THUMB;
//exit();


$current_date = date('Y-m-d');
echo $current_date ."\n";

$yearago = date('Y-m-d',strtotime("-12 months"));

echo $yearago . "\n";
//exit();

$sql = "select ID from wp_posts where ID in (select post_id from wp_postmeta where meta_key = '_recurring_profile' and meta_value = 'yearly') and ID in (select post_id from wp_postmeta where meta_key = '_paid_date' and meta_value like '$yearago%') and post_status != 'trash' order by ID asc";
echo $sql . "\n";		
$result     = mysql_query($sql);
while ($row = mysql_fetch_array($result))
{
$orderid = $row['ID'];


//$orderid = $_POST['orderid'];
//$orderid = "1836";
$olduserid = "";


$sql = "select userid from wp_woocommerce_buckaroo_transactions_info where wc_orderid = '$orderid'";
		
$result     = mysql_query($sql);
while ($row = mysql_fetch_array($result))
{
$newuserid = $row['userid'];

// get old user id 
			$sqluo = "select UserId from User where Email = (select user_email from wp_users where ID = '$newuserid')";
					
			$resultuo     = mysql_query($sqluo);
			while ($rowuo = mysql_fetch_array($resultuo))
			{
				$olduserid = $rowuo['UserId'];
			}

}



if ($olduserid == "") {
	echo "FAILED , transaction info old user not found";
	exit();
}

// get donation total 
			$donationcheck = "";
			$sqluo = "select meta_value from wp_postmeta where post_id = '$orderid' and meta_key = '_order_total'";
					
			$resultuo     = mysql_query($sqluo);
			while ($rowuo = mysql_fetch_array($resultuo))
			{
				$donationcheck = $rowuo['meta_value'];
			}


if ($donationcheck == "")
{
	echo "FAILED , transaction info donation amount not found";
	exit();	
}



//get recurring transactions
$sql = "SELECT	rp.UserId, rp.RecurringPaymentId, rp.DonationAmount
		FROM	RecurringPayment rp
		LEFT	JOIN User u ON rp.UserId = u.UserId
			AND	u.Active = 1
		WHERE	rp.Status = '1'
			AND rp.UserId = '$olduserid' and rp.DonationAmount = '$donationcheck'";
//$result     = $db->Query($sql);
$result     = mysql_query($sql);
//$nrPayments = $result->Count();
$nrPayments = mysql_num_rows($result);
//echo $nrPayments . " payment meets push ";
//exit();



//echo $orderid . " - newuserid: " . $newuserid. " - olduserid " . $olduserid . " push test";

$userLines = array();
while ($row = mysql_fetch_array($result))
{
    $userLines[$row['UserId']][] = (int)$row['RecurringPaymentId'];
}
$nrUsers = count($userLines);

try {
file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', 'Recurring payments: ' . $nrUsers . ' users with ' . $nrPayments . ' payments found', FILE_APPEND);
//echo date('Ymd_His') . '_cron.txt';
} catch (Exception $e) {
	print_r($e); 
}








//print_r($nrUsers);

//exit(); 
if ($nrUsers > 0)
{
    $buckarooBatchInfo = array();
    foreach ($userLines as $userId => $payments)
    {
        $transaction = new Transaction();
        foreach ($payments as $rpId)
        {
            //load RecurringPayment
            try
            {
                $rPayment = new RecurringPayment($rpId);
                file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', "\n".print_r($rPayment,true), FILE_APPEND);
            }
            catch (Exception $e)
            {
                file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', "\n".$e->getMessage(), FILE_APPEND);
                continue;
            }

            $trLine = new TransactionLine();
            $trLine->LinkRecurringPayment($rPayment, true);
            $transaction->AddLine($trLine);
        }
        $transaction->SetUserId($userId);
        try
        {
            $transaction->Save();
        }
        catch (Exception $e)
        {
            file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', "\n".$e->getMessage(), FILE_APPEND);
            continue;
        }
        try
        {
            //$transaction->SendCreated();
        }
        catch (Exception $e)
        {
            file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', "\n".'Problem sending email: ' . $e->getMessage(), FILE_APPEND);
            continue;
        }


// get method 
// db->QueryScalar('SELECT brq_transaction_method FROM BuckarooV3Transaction WHERE brq_transactions LIKE %trx', array('trx' => $rPayment->Get('OriginalBuckarooId'))
			$originalid = $rPayment->Get('OriginalBuckarooId');
			$sqluo = "select brq_transaction_method FROM BuckarooV3Transaction WHERE brq_transactions = '$originalid'";
			$method = "";		
			$resultuo     = mysql_query($sqluo);
			while ($rowuo = mysql_fetch_array($resultuo))
			{
			$method = $rowuo['brq_transaction_method'];	
			}

        $buckarooBatchInfo[] = array(
            'TransactionId' => $transaction->TransactionId(),
            'OriginalTrx'   => $rPayment->Get('OriginalBuckarooId'),
            'CustomerId'    => $userId,
            'Invoice'       => $transaction->InvoiceNumber(),
            'Amount'        => $transaction->Amount(),
            'Description'   => 'Peduli Anak recurring donation. Transaction ' . $transaction->InvoiceNumber(),
            'method'        => $method,
        );
    }
	
	
	//print_r($buckarooBatchInfo);
	//echo BPE_MERCHANT_KEY;
	//exit();
	

    //create batch for Buckaroo
    if (!empty($buckarooBatchInfo))
    {
        $dateNow = date('Y-m-d');
        $timeNow = date('H:i:s');
        $sql     = 'INSERT	INTO BuckarooBatch (`DateTime`, `Contents`)
				VALUES	(\'' . $dateNow . ' ' . $timeNow . '\', '.print_r($buckarooBatchInfo, true).')';
        //$db->Query($sql, array('contents' => print_r($buckarooBatchInfo, true)));
		mysql_query($sql);
		$batchId=mysql_insert_id();
        //$batchId = $db->LastInsertId();

        $soapResponses = array(
            'request' => '',
            'response' => '',
        );
        foreach ($buckarooBatchInfo as $transactionData)
        {
            $wsdl_url = "https://checkout.buckaroo.nl/soap/soap.svc?wsdl";
            $client = new SoapClientWSSEC($wsdl_url, array('trace'=>1));

            $TransactionRequest = new Body();
            $TransactionRequest->Currency = 'EUR';
            $TransactionRequest->AmountDebit = $transactionData['Amount'];
            $TransactionRequest->Invoice = $transactionData['Invoice'];
            $TransactionRequest->Description = 'Peduli Anak recurring donation. Transaction '.$transactionData['Invoice'];
            $TransactionRequest->StartRecurrent = false;
            $TransactionRequest->OriginalTransactionKey = $transactionData['OriginalTrx'];

            $TransactionRequest->Services = new Services();
            $TransactionRequest->Services->Service = new Service();
            $TransactionRequest->Services->Service->Name = $transactionData['method'];
            $TransactionRequest->Services->Service->Action = 'PayRecurrent';
            $TransactionRequest->Services->Service->Version = 1;

            //$TransactionRequest->Services->Service->RequestParameter = new RequestParameter();
            //$TransactionRequest->Services->Service->RequestParameter->Name = 'issuer';
            //$TransactionRequest->Services->Service->RequestParameter->_ = '0031';

            $TransactionRequest->ClientIP = new IPAddress();
            $TransactionRequest->ClientIP->Type = 'IPv4';
            $TransactionRequest->ClientIP->_ = '139.162.11.243';

            $Header = new Header();
            $Header->MessageControlBlock = new MessageControlBlock();
            $Header->MessageControlBlock->Id = '_control';
            $Header->MessageControlBlock->WebsiteKey = BPE_MERCHANT_KEY;
            $Header->MessageControlBlock->Culture = 'nl-NL';
            $Header->MessageControlBlock->TimeStamp = time();
            $Header->MessageControlBlock->Channel = 'Web';

            $Header->Security = new SecurityType();
            $Header->Security->Signature = new SignatureType();

            $Header->Security->Signature->SignedInfo = new SignedInfoType();
            $Header->Security->Signature->SignedInfo->CanonicalizationMethod = new CanonicalizationMethodType();
            $Header->Security->Signature->SignedInfo->CanonicalizationMethod->Algorithm = 'http://www.w3.org/2001/10/xml-exc-c14n#';
            $Header->Security->Signature->SignedInfo->SignatureMethod = new SignatureMethodType();
            $Header->Security->Signature->SignedInfo->SignatureMethod->Algorithm = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';


            $Reference = new ReferenceType();
            $Reference->URI = '#_body';
            $Transform = new TransformType();
            $Transform->Algorithm = 'http://www.w3.org/2001/10/xml-exc-c14n#';
            $Reference->Transforms=array($Transform);

            $Reference->DigestMethod = new DigestMethodType();
            $Reference->DigestMethod->Algorithm = 'http://www.w3.org/2000/09/xmldsig#sha1';
            $Reference->DigestValue = '';

            $Transform2 = new TransformType();
            $Transform2->Algorithm = 'http://www.w3.org/2001/10/xml-exc-c14n#';
            $ReferenceControl = new ReferenceType();
            $ReferenceControl->URI = '#_control';
            $ReferenceControl->DigestMethod = new DigestMethodType();
            $ReferenceControl->DigestMethod->Algorithm = 'http://www.w3.org/2000/09/xmldsig#sha1';
            $ReferenceControl->DigestValue = '';
            $ReferenceControl->Transforms=array($Transform2);

            $Header->Security->Signature->SignedInfo->Reference = array($Reference,$ReferenceControl);
            $Header->Security->Signature->SignatureValue = '';

            try
            {

                $soapHeaders[] = new SOAPHeader('https://checkout.buckaroo.nl/PaymentEngine/', 'MessageControlBlock', $Header->MessageControlBlock);
                $soapHeaders[] = new SOAPHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', $Header->Security);
                $client->__setSoapHeaders($soapHeaders);
                $client->__SetLocation('https://checkout.buckaroo.nl/soap/');
                print "Starting SoapClient::TransactionRequest()\n";
                $result = $client->TransactionRequest($TransactionRequest);
                print "Completed SoapClient::TransactionRequest()\n";
                $reqResponse['request'] = $client->__getLastRequest();
                $dom = new DOMDocument();
                $dom->loadXML($client->__getLastRequest());
                $dom->formatOutput = true;
                $dom->save($payLogPath . date('Ymd_His') . '_buckaroo-request.xml');
            }
            catch (SoapFault $e)
            {
                $dom = new DOMDocument();
                $dom->loadXML($client->__getLastRequest());
                $dom->formatOutput = true;
                $dom->save($payLogPath . date('Ymd_His') . '_buckaroo-request-fault.txt');
                file_put_contents($payLogPath . date('Ymd_His') . '_buckaroo-request-fault.txt', "\n\nMessage: ".$e->getMessage(), FILE_APPEND);
            }

            $reqResponse['response'] = $client->__getLastResponse();
//            $reqResponse = $result->sendRequest($trxReq, 'transaction');

            //update transaction with BatchId
            $sql = 'UPDATE	Transaction
					SET		BuckarooBatchId = ' . $batchId . '
					WHERE	TransactionId = ' . $transactionData['TransactionId'] . '
					LIMIT	1';
            //$db->Query($sql);
			mysql_query($sql);

            $soapResponses['request'] .= $reqResponse['request'] . "\n\n";
            $soapResponses['response'] .= $reqResponse['response'] . "\n\n";
        }
        file_put_contents($payLogPath . date('Ymd_His') . '_buckaroo-request.txt', $soapResponses['request'], FILE_APPEND);
        file_put_contents($payLogPath . date('Ymd_His') . '_buckaroo-response.txt', $soapResponses['response'], FILE_APPEND);
    }
}

$soap     = simplexml_load_string($reqResponse['response']);
$response = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->TransactionResponse;
$Code = (string) $response->Status->Code['Code'];

if ($Code == 190) {
$sql = "update wp_posts set post_status = 'wc-completed' where ID = '$orderid'";
mysql_query($sql);
echo "Payment Success";
} else { 
$sql = "update wp_posts set post_status = 'wc-failed' where ID = '$orderid'";
mysql_query($sql);
echo "Payment FAILED";
}

$datum = date('Y-m-d H:m:s'); 
$sql = "update wp_postmeta set meta_value = '$datum' where meta_key = '_trans_date' and post_id = '$orderid'";
mysql_query($sql);
$sql = "update wp_postmeta set meta_value = '$datum' where meta_key = '_paid_date' and post_id = '$orderid'";
mysql_query($sql);



file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', 'Recurring payments complete', FILE_APPEND);
$mailMessage = 'Time run: ' . date('Y-m-d H:i:s') . "\nRecurring payments: " . $nrUsers . ' users with ' . $nrPayments . ' payments found'.
        "\n\n".file_get_contents($payLogPath . date('Ymd_His') . '_cron.txt').
        "\n\n".str_repeat('=', 80).
        "\n\nBuckaroo request:\n\n".
        file_get_contents($payLogPath . date('Ymd_His') . '_buckaroo-request.txt')."\n\n".str_repeat('=', 80).
        "\n\nBuckaroo response:\n\n".
        file_get_contents($payLogPath . date('Ymd_His') . '_buckaroo-response.txt');
		
		
		$mailMessage.="\n".$sql;
		
		
		
//mail('olga@omines.com', 'Peduli Cron Report', $mailMessage);

mail('paulsteenkist@me.com', 'Peduli Cron Report', $mailMessage);
mail('chaim@pedulianak.org', 'Peduli Cron Report', $mailMessage);
}


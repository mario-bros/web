<?php
/**
 * @author         Olga Banasinska
 * @copyright      Omines
 * @since          26-7-11 14:31
 * @package        System
 */

define('SYS_PATH',		'/var/www/clients/client1/web1/web/cron/');
$locale     = "en";
$payLogPath = SYS_PATH . 'payment/logs/';




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

//echo BUCKAROO_PRIVATE_THUMB;
//exit();


$sql = 'SELECT  RecurringPaymentId, OriginalBuckarooId, CheckedOriginalId
        FROM    RecurringPayment
        WHERE   OriginalBuckarooId != CheckedOriginalId';
		
$result     = mysql_query($sql);
while ($row = mysql_fetch_array($result))
{
    if ((string)$row['CheckedOriginalId'] == '')
    {
        //$db->Query('UPDATE RecurringPayment SET `CheckedOriginalId` = `OriginalBuckarooId` WHERE `RecurringPaymentId` = ' . $row->RecurringPaymentId);
		mysql_query('UPDATE RecurringPayment SET `CheckedOriginalId` = `OriginalBuckarooId` WHERE `RecurringPaymentId` = ' . $row['RecurringPaymentId']);
		echo "CheckedOriginalId " . $row['CheckedOriginalId'] . "\n";
    }
    elseif ($row->CheckedOriginalId != $row->OriginalBuckarooId)
    {
        //$db->Query('UPDATE RecurringPayment SET `OriginalBuckarooId` = `CheckedOriginalId` WHERE `RecurringPaymentId` = ' . $row->RecurringPaymentId);
		mysql_query('UPDATE RecurringPayment SET `OriginalBuckarooId` = `CheckedOriginalId` WHERE `RecurringPaymentId` = ' . $row['RecurringPaymentId']);
		echo "OriginalBuckarooId " . $row['OriginalBuckarooId'] . "\n";
    }
}

//exit();

//get recurring transactions
$sql = 'SELECT	rp.UserId, rp.RecurringPaymentId
		FROM	`RecurringPayment` rp
		LEFT	JOIN User u ON rp.UserId = u.UserId
			AND	u.Active = 1
		WHERE	rp.Status = 1
			AND	rp.NextTransactionDate < NOW()';
//$result     = $db->Query($sql);
$result     = mysql_query($sql);
//$nrPayments = $result->Count();
$nrPayments = mysql_num_rows($result);
echo $nrPayments . " aantal";
//exit();

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
            $transaction->SendCreated();
        }
        catch (Exception $e)
        {
            file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', "\n".'Problem sending email: ' . $e->getMessage(), FILE_APPEND);
            continue;
        }

        $buckarooBatchInfo[] = array(
            'TransactionId' => $transaction->TransactionId(),
            'OriginalTrx'   => $rPayment->Get('OriginalBuckarooId'),
            'CustomerId'    => $userId,
            'Invoice'       => $transaction->InvoiceNumber(),
            'Amount'        => $transaction->Amount(),
            'Description'   => 'Peduli Anak recurring donation. Transaction ' . $transaction->InvoiceNumber(),
            'method'        => $db->QueryScalar('SELECT brq_transaction_method FROM BuckarooV3Transaction WHERE brq_transactions LIKE %trx', array('trx' => $rPayment->Get('OriginalBuckarooId'))),
        );
    }

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
file_put_contents($payLogPath . date('Ymd_His') . '_cron.txt', 'Recurring payments complete', FILE_APPEND);
$mailMessage = 'Time run: ' . date('Y-m-d H:i:s') . "\nRecurring payments: " . $nrUsers . ' users with ' . $nrPayments . ' payments found'.
        "\n\n".file_get_contents($payLogPath . date('Ymd_His') . '_cron.txt').
        "\n\n".str_repeat('=', 80).
        "\n\nBuckaroo request:\n\n".
        file_get_contents($payLogPath . date('Ymd_His') . '_buckaroo-request.txt')."\n\n".str_repeat('=', 80).
        "\n\nBuckaroo response:\n\n".
        file_get_contents($payLogPath . date('Ymd_His') . '_buckaroo-response.txt');
//mail('olga@omines.com', 'Peduli Cron Report', $mailMessage);
mail('paul.steenkist@laucaconsultancy.nl', 'Peduli Cron Report', $mailMessage);
mail('paulsteenkist@me.com', 'Peduli Cron Report', $mailMessage);

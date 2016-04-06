<?php
/**
* Peduli Anak
* (c) Omines Internetbureau B.V.
*
 * User: Bas Scholts
 * Date: 22-1-14
 * Time: 15:05
*/

class BuckarooV3ValidationException extends Exception { }

abstract class BuckarooV3Base
{
    const BuckarooServices = 'creditcard,overschrijving,giropay,paypal';

    const StatusSuccess   = 1;
    const StatusCancelled = 2;
    const StatusRejected  = 3;
    const StatusPending   = 4;

    /**
   	 * @var MySQLDatabase
   	 */
   	protected $db;
   	/**
   	 * @var Transaction
   	 */
    protected $transaction;
    protected $id;
    protected $brqParameters = array();

    protected $data;

    public function __construct(Transaction $transaction)
    {
        /**
         * @var MySQLDatabase $db
         * @var Locale $locale
         */
        global $db, $locale;

        $this->transaction = $transaction;
        $this->db          = $db;
		$BPE_METHODS = "visa;mastercard;ideal";
        $this->data = array(
            'brq_websitekey'        => 'RZq7Zvqln9',
            'brq_currency'          => 'EUR',
            'brq_amount'            => $this->amount(),
            'brq_invoicenumber'     => $this->transaction()->InvoiceNumber(),
            'brq_description'       => 'Peduli Anak Foundation Invoice ' . $this->transaction()->InvoiceNumber(),
            'brq_culture'           => 'en-US',
            'brq_return'            => 'https://www.pedulianak.org/?wc-api=WC_Push_Buckaroo',
            'brq_returncancel'      => 'https://www.pedulianak.org/?wc-api=WC_Push_Buckaroo',
            'brq_returnerror'       => 'https://www.pedulianak.org/?wc-api=WC_Push_Buckaroo',
            'brq_returnreject'      => 'https://www.pedulianak.org/?wc-api=WC_Push_Buckaroo',
            'brq_requestedservices' => str_replace(';', ',', $BPE_METHODS),
        );
		$TransactionId = $this->transaction()->TransactionId();
		$sql = "SELECT * FROM BuckarooV3Transaction WHERE TransactionId = '$TransactionId'";
		$result     = mysql_query($sql);
		$teller = mysql_num_rows($result);			
		
        
        if ($teller)
        {
            $data = mysql_fetch_object($result);
            $this->id                 = (int)$data->BuckarooTransactionId;
            unset($data->BuckarooTransactionId);
            foreach ($data as $key => $value)
                $this->data[$key] = $value;
        }

        if ($this->id() == 0)
        {
			
			$TransactionId = $this->transaction()->TransactionId();
			$brq_amount = $this->amount();
			$brq_invoicenumber = $this->invoice();
			$brq_description = $this->description();
			$brq_culture = $this->culture();
			
			
            $sql = "INSERT INTO BuckarooV3Transaction (TransactionId, brq_amount, brq_invoicenumber, brq_description, brq_culture)
                    VALUES ('$TransactionId', '$brq_amount', '$brq_invoicenumber', '$brq_description', '$brq_culture')";

			mysql_query($sql);
			

            //$this->db()->Query($sql, $data);
            $this->id = mysql_insert_id();
        }
    }
    public function id() { return $this->id; }
    public function transaction() { return $this->transaction; }
    public function db() { return $this->db; }
    public function host() { return 'https://www.pedulianak.org/'; }

    public function addSignatureParameter($key, $value = null)
    {
        if ($value === null)
            $this->brqParameters[$key] = $this->data[$key];
        else
            $this->brqParameters[$key] = $value;
    }

    public $signatureString;
    public function generateSignature($data = array())
    {
        $this->signatureString = '';
        if (empty($data))
            $data = $this->brqParameters;
        if (array_key_exists('brq_signature', $data))
            unset($data['brq_signature']);
        uksort($data, 'strcasecmp');
        uksort($this->data, 'strcasecmp');
        foreach ($data as $key => $value)
        {
            $keyLower = strtolower($key);
            if (substr($keyLower, 0, 4) != 'brq_' && substr($keyLower, 0, 4) != 'add_' && substr($keyLower, 0, 5) != 'cust_')
                continue;
            if ($keyLower == 'brq_amount')
                $this->signatureString .= $key . '=' . $this->amount();
            elseif ($value != '')
                $this->signatureString .= $key . '=' . $value;
        }
        $this->signatureString .= $this->secretKey();

        $this->Log('Signature string: '.$this->signatureString);
        return sha1($this->signatureString);
    }

    /**
     * @return string
     */
    public function amount()
    {
        return number_format($this->data['brq_amount'], 2, '.', '');
    }

    public function customerId()
    {
        return $this->transaction()->UserId();
    }

    public function currency()
    {
        return $this->data['brq_currency'];
    }

    public function description()
    {
        return $this->data['brq_description'];
    }

    public function invoice()
    {
        return $this->data['brq_invoicenumber'];
    }

    public function culture()
    {
        return $this->data['brq_culture'];
    }

    public function websiteKey()
    {
        return $this->data['brq_websitekey'];
    }

    public function services()
    {
        return self::BuckarooServices;
    }

    public function secretKey()
    {
		$secret_key = "5cef3e5cac74f355a9581b659b2a7d91";
        return $secret_key;
    }

    public function setInvoice($invoiceNumber)
    {
        $this->data['brq_invoicenumber'] = $invoiceNumber;
        $this->addSignatureParameter('brq_invoicenumber');
    }

    public function setAmount($amountInCent)
    {
        if (!is_int($amountInCent))
            throw new Exception('Argument passed to '.__METHOD__.' should be an integer (price in cent), but received '.gettype($amountInCent));
        $this->data['brq_amount'] = $amountInCent / 100;
        $this->addSignatureParameter('brq_amount');
    }

    public function returnUrl()
    {
        return $this->data['brq_return'];
    }

    public function returnRejectUrl()
    {
        return $this->data['brq_returnreject'];
    }

    public function returnErrorUrl()
    {
        return $this->data['brq_returnerror'];
    }

    public function returnCancelUrl()
    {
        return $this->data['brq_returncancel'];
    }

    public function Log($message)
   	{
   		global $payLog;
   		if (!isset($payLog) || !($payLog instanceof PayLog))
        {
//            print "No paylog";
            return;
        }
   		$payLog->Log($message);
   	}
}

class BuckarooV3Request extends BuckarooV3Base
{
    protected $signature;
    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
        $trxAmount = $transaction->Amount();
        if (is_float($trxAmount))
            $trxAmount = intval($trxAmount * 100);
        $this->setAmount($trxAmount);
        $this->setInvoice($transaction->InvoiceNumber());

        $this->addSignatureParameter('brq_websitekey');
        $this->addSignatureParameter('brq_amount');
        $this->addSignatureParameter('brq_currency');
        $this->addSignatureParameter('brq_invoicenumber');
        $this->addSignatureParameter('brq_description');
        $this->addSignatureParameter('brq_culture');
        $this->addSignatureParameter('brq_return');
        $this->addSignatureParameter('brq_returncancel');
        $this->addSignatureParameter('brq_returnerror');
        $this->addSignatureParameter('brq_returnreject');
        if ($this->transaction()->HasRecurringPayment())
        {
            $this->data['brq_startrecurrent'] = 'true';
            $this->addSignatureParameter('brq_startrecurrent');
            $this->data['brq_requestedservices'] = 'visa,mastercard,amex';
            $this->addSignatureParameter('brq_requestedservices');
            foreach (array('visa','mastercard','amex') as $serviceCode)
            {
                $this->data['brq_service_'.$serviceCode.'_action'] = 'pay';
                $this->data['brq_service_'.$serviceCode.'_customercode'] = $this->transaction()->UserId();
                $this->addSignatureParameter('brq_service_'.$serviceCode.'_action');
                $this->addSignatureParameter('brq_service_'.$serviceCode.'_customercode');
            }
        }

    }

    public function signature()
    {
//        foreach ($postParameters as $key => $value)
//            $postParameters[$key] = urldecode($value);

        $this->signatureString = '';
        if (empty($data))
            $data = $this->brqParameters;
        $data['brq_websitekey'] = $this->websiteKey();
        if (array_key_exists('brq_signature', $data))
            unset($data['brq_signature']);
        uksort($data, 'strcasecmp');
        foreach ($data as $key => $value)
        {
            $keyLower = strtolower($key);
            if (substr($keyLower, 0, 4) != 'brq_' && substr($keyLower, 0, 4) != 'add_' && substr($keyLower, 0, 5) != 'cust_')
                continue;
            if ($keyLower == 'brq_amount')
                $this->signatureString .= $key . '=' . $this->amount();
            else
                $this->signatureString .= $key . '=' . urldecode($value);
//            print "<!-- Adding '{$key}={$value}' to signature -->\n";
        }
        $this->signatureString .= $this->secretKey();

//        print "<!-- Signature string: {$this->signatureString}-->\n";
        $this->Log('Signature string: '.$this->signatureString);
        $this->signature = sha1($this->signatureString);
//        print "<!-- Signature: {$this->signature}-->\n";
        $this->Log('Signature: '.$this->signature);

        return $this->signature;
    }
//
//    public function signature()
//    {
//        $this->signature = $this->generateSignature($this->brqParameters);
//        return $this->signature;
//    }

    public function dataAsString()
    {
        return print_r($this->data, true);
    }
}

class BuckarooV3Response extends BuckarooV3Base
{
    public function __construct(Transaction $transaction)
   	{
   		parent::__construct($transaction);
        $this->Log("BuckarooV3Response initialized");
//   		$this->setAmount(intval($transaction->Amount() * 100));
   	}

    private $signature;

    public function ResponseSignature($data = array())
    {
//        foreach ($postParameters as $key => $value)
//            $postParameters[$key] = urldecode($value);

        $this->signatureString = '';
//        $data['brq_websitekey'] = $this->websiteKey();
        if (array_key_exists('brq_signature', $data))
            unset($data['brq_signature']);
        uksort($data, 'strcasecmp');
        foreach ($data as $key => $value)
        {
            $keyLower = strtolower($key);
            if (substr($keyLower, 0, 4) != 'brq_' && substr($keyLower, 0, 4) != 'add_' && substr($keyLower, 0, 5) != 'cust_')
                continue;
            $this->Log('Adding "'.$key.'='.$value.'" to signature');
            $this->signatureString .= $key . '=' . urldecode($value);
        }
        $this->signatureString .= $this->secretKey();

        $this->Log('Signature string: '.$this->signatureString);
        $this->signature = sha1($this->signatureString);
        $this->Log('Signature: '.$this->signature);

        return $this->signature;
    }

    public function ResponseUpdate($postParameters = array())
    {
        $invoiceNumber  = $postParameters['brq_invoicenumber'];
        $sigReceived    = $postParameters['brq_signature'];
        $amountReceived = $postParameters['brq_amount'];

        try
        {
            $this->Log(date('Y-m-d H:i:s'));
            $this->Log('Received new payment response.');
            $this->Log('Parameters received:');
            foreach ($postParameters as $key => $value)
            {
                $this->Log(sprintf("\t%s => %s", str_pad($key, 30, ' ', STR_PAD_RIGHT), $value));
            }
            $sigGenerated   = $this->ResponseSignature($postParameters);

            $this->transaction = Transaction::FindByInvoice($invoiceNumber);
            $this->Log('Transaction found via invoice, ID '.$this->transaction->TransactionId());

            $this->Log('Signature received  : ' . $sigReceived);
            $this->Log('Signature calculated: ' . $sigGenerated);
            if ($sigGenerated != $sigReceived)
            {
                throw new BuckarooV3ValidationException('Transaction signature invalid');
            }
            if ($amountReceived != $this->amount())
            {
                throw new BuckarooV3ValidationException(sprintf('Amount in postback (%.2d) does not match transaction (%.2d)', $postParameters['brq_amount'], $this->amount()));
            }
            if ($postParameters['brq_currency'] != $this->currency())
            {
                throw new BuckarooV3ValidationException(sprintf('Amount in postback (%s) does not match transaction (%s)', $postParameters['brq_currency'], $this->currency()));
            }

            $this->Log('Passed validation, proceeding to completion');

            $sql = 'UPDATE  BuckarooV3Transaction
                    SET     brq_payment = %paymentId,
                            brq_payment_method = %paymentMethod,
                            brq_transactions = %transactionId,
                            brq_transaction_method = %transactionMethod,
                            brq_statuscode = %statusCode,
                            brq_statusmessage = %statusmessage,
                            brq_signature = %signature
                    WHERE   BuckarooTransactionId = %id';
            $this->db()->Query($sql, array(
                'id' => $this->id(),
                'paymentId' => @$postParameters['brq_payment'],
                'paymentMethod' => @$postParameters['brq_payment_method'],
                'transactionId' => @$postParameters['brq_transactions'],
                'transactionMethod' => @$postParameters['brq_transaction_method'],
                'statusCode' => $postParameters['brq_statuscode'],
                'statusmessage' => $postParameters['brq_statusmessage'],
                'signature' => $postParameters['brq_signature']
            ));

            $statusCode = (int)$postParameters['brq_statuscode'];
            $sql        = 'SELECT StatusType FROM BuckarooV3Status WHERE BuckarooStatusId = %type';
            $statusType = $this->db()->QueryScalar($sql, array('type' => $statusCode));
            switch ((int)$statusType)
            {
                case self::StatusSuccess:
                    $this->transaction()->Complete($statusCode);
                    if ($this->transaction()->HasRecurringPayment())
                    {
                        $transId = $postParameters['brq_transactions'];
                        foreach ($this->transaction()->Lines() as $line)
                        {
                            if ($line->RecurringPaymentId() > 0)
                            {
                                $rp = new RecurringPayment($line->RecurringPaymentId());
                                if ((string)$rp->Get('OriginalBuckarooId') == '')
                                {
                                    $sql = 'UPDATE  RecurringPayment
                                            SET     OriginalBuckarooId = %tid
                                            WHERE   RecurringPaymentId = %rid';
                                    $this->db()->Query($sql, array('rid' => $line->RecurringPaymentId(), 'tid' => $transId));
                                }
                            }
                        }
                    }
                    break;
                case self::StatusCancelled:
                    $this->transaction()->Cancel($statusCode);
                    break;
                case self::StatusRejected:
                    $this->transaction()->Reject($statusCode);
                    break;
                case self::StatusPending:
                    $this->Log('Status '.$statusCode.' received from buckaroo indicates transaction is not finished.');
                    break;
                default:
                    $this->Log('Unknown status code received from Buckaroo: '.$statusCode);
            }

            try
            {
                $this->transaction()->SendUpdated();
            }
            catch (Exception $e)
            {
                $this->Log("Problem sending email: ".$e->getMessage());
            }
        }
        catch (Exception $e)
        {
            $this->Log('An error occurred during the transaction: '.get_class($e));
            $this->Log("\t{$e->getMessage()}");
            throw $e;
        }
    }

    public function BatchResponseUpdate($post = array())
    {

    }
}
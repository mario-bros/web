<?php
require_once(dirname(__FILE__).'/../../logger.php');
require_once(dirname(__FILE__).'/../abstract.php');
require_once(dirname(__FILE__).'/../soap.php');
require_once(dirname(__FILE__).'/responsefactory.php');
abstract class PaymentMethod extends BuckarooAbstract {
    //put your code here
    protected $type;

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
    public $currency;
    public $amountDedit;
    public $amountCredit = 0;
    public $orderId;
    public $invoiceId;
    public $description;
    public $OriginalTransactionKey;
    public $returnUrl;
    public $mode;
    public $version;
    public $usecreditmanagment = 0;
    public $usenotification = 0;
    protected $data = array();
    
    public function Pay(){
        $this->data['services'][$this->type]['action'] = 'Pay';
        $this->data['services'][$this->type]['version'] = $this->version;
        
        return $this->PayGlobal();
    }

    public function Refund(){
        $this->data['services'][$this->type]['action'] = 'Refund';
        $this->data['services'][$this->type]['version'] = $this->version;

        return $this->RefundGlobal();
    }
    
    public function PayGlobal()
    {
        $this->data['currency'] = $this->currency;
        $this->data['amountDebit'] = $this->amountDedit;
        $this->data['amountCredit'] = $this->amountCredit;
        $this->data['invoice'] = $this->invoiceId;
        $this->data['order'] = $this->orderId;
        $this->data['description'] = $this->description;
        $this->data['returnUrl'] = $this->returnUrl;
        $this->data['mode'] = $this->mode;
        $soap = new Soap($this->data);
        return ResponseFactory::getResponse($soap->transactionRequest());
    }

    public function RefundGlobal()
    {
        $this->data['currency'] = $this->currency;
        $this->data['amountDebit'] = $this->amountDedit;
        $this->data['amountCredit'] = $this->amountCredit;
        $this->data['invoice'] = $this->invoiceId;
        $this->data['order'] = $this->orderId;
        $this->data['description'] = $this->description;
        $this->data['OriginalTransactionKey'] = $this->OriginalTransactionKey;
        $this->data['returnUrl'] = $this->returnUrl;
        $this->data['mode'] = $this->mode;
        $soap = new Soap($this->data);
        return ResponseFactory::getResponse($soap->transactionRequest());
    }
    
    public static function isIBAN($iban) {
 
      // Normalize input (remove spaces and make upcase)
      $iban = strtoupper(str_replace(' ', '', $iban));

      if (preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $iban)) {
        $country = substr($iban, 0, 2);
        $check = intval(substr($iban, 2, 2));
        $account = substr($iban, 4);

        // To numeric representation
        $search = range('A','Z');
        foreach (range(10,35) as $tmp)
          $replace[]=strval($tmp);
        $numstr=str_replace($search, $replace, $account.$country.'00');

        // Calculate checksum
        $checksum = intval(substr($numstr, 0, 1));
        for ($pos = 1; $pos < strlen($numstr); $pos++) {
          $checksum *= 10;
          $checksum += intval(substr($numstr, $pos,1));
          $checksum %= 97;
        }

        return ((98-$checksum) == $check);
      } else
        return false;
    }
}

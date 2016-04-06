<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class CreditCard extends PaymentMethod{
    //put your code here
    //public $cardtype = '';
    
    public function __construct()
    {
        $this->version = 1;
        $this->mode = Config::getMode('CREDITCARD');
        //$this->returnUrl = 'http://localhost/trunk/buckarooTest/response.php';

    }
    public function Refund()
    {
        return parent::Refund();
    }
    
    public function Pay()
    {
        
        //$this->type = $this->cardtype;
        //return parent::Pay();        
        $this->data['customVars']['servicesSelectableByClient'] = Config::get('BUCKAROO_CREDITCARD_ALLOWED_CARDS');
        $this->data['customVars']['continueOnIncomplete'] = 'RedirectToHTML';
        $this->data['services'] = array();
        return parent::PayGlobal();       
    }
}

?>
<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class BuckarooPayPal extends PaymentMethod{
    //put your code here
    
    public function __construct()
    {
        $this->type = "paypal";
        $this->version = 1;
        $this->mode = Config::getMode($this->type);
        //$this->returnUrl = 'http://localhost:54146/index.php?fc=module&module=buckaroo3&controller=return';
        
    }
}

?>
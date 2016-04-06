<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class PaySafeCard extends PaymentMethod{
    //put your code here
    
    public function __construct()
    {
        $this->type = "paysafecard";
        $this->version = 1;
        $this->mode = Config::getMode($this->type);        
        
    }
}

?>
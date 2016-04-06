<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class Sofortbanking extends PaymentMethod{
    //put your code here
    
    public function __construct()
    {
        $this->type = "sofortueberweisung";
        $this->version = 1;
        $this->mode = Config::getMode($this->type);        
        //$this->returnUrl = 'http://localhost/trunk/buckarooTest/response.php';
        
    }
}

?>
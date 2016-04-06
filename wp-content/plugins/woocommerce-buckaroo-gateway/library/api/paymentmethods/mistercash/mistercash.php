<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class MisterCash extends PaymentMethod{
    
    public function __construct()
    {
        $this->type = "bancontactmrcash";
        $this->version = 1;
        $this->mode = Config::getMode('MISTERCASH');        
        
    }
}

?>
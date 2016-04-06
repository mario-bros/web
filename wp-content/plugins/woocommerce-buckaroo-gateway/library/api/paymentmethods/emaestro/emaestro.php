<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class EMaestro extends PaymentMethod{
    //put your code here
    
    public function __construct()
    {
        $this->type = "maestro";
        $this->version = 1;
        $this->mode = Config::getMode($this->type);        
        
    }
}

?>
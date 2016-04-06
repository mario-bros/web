<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(__FILE__).'/../response.php');
class CreditCardResponse extends Response
{
    //put your code here
    public $cardNumberEnding = '';
    
    protected function _parseSoapResponseChild()
    {
        
    }
    
    
    protected function _parsePostResponseChild()
    {
        if (isset($_POST['brq_service_'.$this->payment_method.'_CardNumberEnding']))
            $this->cardNumberEnding = $_POST['brq_service_'.$this->payment_method.'_CardNumberEnding'];
    }
}

?>

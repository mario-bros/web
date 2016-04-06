<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(__FILE__).'/../response.php');
class PayGarantResponse extends Response
{
    public $paylink = '';
    
    protected function _parseSoapResponseChild()
    {
         if (isset($this->_response->Services->Service->ResponseParameter)&&isset($this->_response->Services->Service->Name))
        {
            if ($this->_response->Services->Service->Name == 'paymentguarantee' && $this->_response->Services->Service->ResponseParameter->Name == 'paylink')
            {
                $this->paylink = $this->_response->Services->Service->ResponseParameter->_;
            }
        }
    }
    
    
    protected function _parsePostResponseChild()
    {
         
    }
    
}

?>

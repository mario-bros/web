<?php

require_once(dirname(__FILE__).'/../paymentmethod.php');
class Transfer extends PaymentMethod
{
    //put your code here
    public function __construct()
    {
        $this->type = "transfer";
        $this->version = 1;
        $this->mode = Config::getMode($this->type);
    }
    
    public function Pay()
    {
        return null;
    }
    
    public function PayTransfer($customVars)
    {
        $this->data['services'][$this->type]['action'] = 'Pay';
        $this->data['services'][$this->type]['version'] = $this->version;      
        
        if (isset($customVars['CustomerGender']))
            $this->data['customVars'][$this->type]['customergender'] = $customVars['CustomerGender'];    
        if (isset($customVars['CustomerFirstName']))
            $this->data['customVars'][$this->type]['customerFirstName'] = $customVars['CustomerFirstName'];
        if (isset($customVars['CustomerLastName']))
            $this->data['customVars'][$this->type]['customerLastName'] = $customVars['CustomerLastName'];
        if (isset($customVars['CustomerEmail']))
            $this->data['customVars'][$this->type]['customeremail'] = $customVars['CustomerEmail'];
        if (isset($customVars['DateDue']))        
            $this->data['customVars'][$this->type]['DateDue'] = $customVars['DateDue'];
        if (isset($customVars['CustomerCountry']))        
            $this->data['customVars'][$this->type]['customercountry'] = $customVars['CustomerCountry'];
        $this->data['customVars'][$this->type]['SendMail'] = $customVars['SendMail'];      
        
        return parent::Pay();  
    }
}

?>

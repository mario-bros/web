<?php

require_once(dirname(__FILE__).'/../paymentmethod.php');
class DirectDebit extends PaymentMethod
{
    public $customeraccountname;
    public $customeraccountnumber;
    
    public function __construct()
    {
        $this->type = "directdebit";
        $this->version = '1';
        $this->mode = Config::getMode('DD');
    }
    
    public function Pay()
    {
        return null;
    }
    
    public function PayDirectDebit($customVars)
    {
            
        $this->data['customVars'][$this->type]['customeraccountname'] = $this->customeraccountname;
        $this->data['customVars'][$this->type]['customeraccountnumber'] = $this->customeraccountnumber;
        
        if ($this->usecreditmanagment){
         
            $this->data['services']['creditmanagement']['action'] = 'Invoice';
            $this->data['services']['creditmanagement']['version'] = '1';
            $this->data['customVars']['creditmanagement']['MaxReminderLevel'] = $customVars['MaxReminderLevel'];
            $this->data['customVars']['creditmanagement']['DateDue'] = $customVars['DateDue'];
            $this->data['customVars']['creditmanagement']['InvoiceDate'] = $customVars['InvoiceDate'];
            if (isset($customVars['CustomerCode']))
                $this->data['customVars']['creditmanagement']['CustomerCode'] = $customVars['CustomerCode'];
            if(!empty($customVars['CompanyName']))
                $this->data['customVars']['creditmanagement']['CompanyName'] = $customVars['CompanyName'];   
            $this->data['customVars']['creditmanagement']['CustomerFirstName'] = $customVars['CustomerFirstName'];
            $this->data['customVars']['creditmanagement']['CustomerLastName'] = $customVars['CustomerLastName'];
            $this->data['customVars']['creditmanagement']['CustomerInitials'] = $customVars['CustomerInitials'];
            $this->data['customVars']['creditmanagement']['Customergender'] = $customVars['Customergender'];
            $this->data['customVars']['creditmanagement']['Customeremail'] = $customVars['Customeremail'];

			if (!empty($customVars['PaymentMethodsAllowed'])) {
                $this->data['customVars']['creditmanagement']['PaymentMethodsAllowed'] = $customVars['PaymentMethodsAllowed'];
            }

             if (isset($customVars['MobilePhoneNumber'])){
                $this->data['customVars']['creditmanagement']['MobilePhoneNumber'] = $customVars['MobilePhoneNumber'];
                $this->data['customVars']['creditmanagement']['PhoneNumber'] = $customVars['MobilePhoneNumber'];
             }
             if (isset($customVars['PhoneNumber']))
                $this->data['customVars']['creditmanagement']['PhoneNumber'] = $customVars['PhoneNumber'];
             if (isset($customVars['CustomerBirthDate']))
                $this->data['customVars']['creditmanagement']['CustomerBirthDate'] = $customVars['CustomerBirthDate'];
            
            $this->data['customVars']['creditmanagement']['CustomerType'] = '0';
            $this->data['customVars']['creditmanagement']['AmountVat'] = $customVars['AmountVat'];
            
            foreach($customVars['ADDRESS'] as $key => $adress)
            {

                   $this->data['customVars']['creditmanagement'][$key]['value'] = $adress;
                   $this->data['customVars']['creditmanagement'][$key]['group'] = 'address';

            }
        }
        
        return parent::Pay();        
    }
}

?>

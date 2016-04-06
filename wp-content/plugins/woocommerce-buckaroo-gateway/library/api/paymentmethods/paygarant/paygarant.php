<?php

require_once(dirname(__FILE__).'/../paymentmethod.php');
class PayGarant extends PaymentMethod
{
    public function __construct()
    {
        $this->type = "paymentguarantee";
        $this->version = '1';
        $this->mode = Config::getMode('PAYGARANT');
    }
    
    public function Pay()
    {
        return null;
    }
    
    public function PaymentInvitation($customVars)
    {
        $this->data['services'][$this->type]['action'] = 'Paymentinvitation';
        $this->data['services'][$this->type]['version'] = $this->version;
        
        $this->data['currency'] = $this->currency;
        $this->data['amountDebit'] = $this->amountDedit;
        $this->data['amountCredit'] = $this->amountCredit;
        $this->data['invoice'] = $this->invoiceId;
        $this->data['order'] = $this->orderId;
        $this->data['description'] = $this->description;
        $this->data['returnUrl'] = $this->returnUrl;
        $this->data['mode'] = $this->mode;
        
        if (isset($customVars['CustomerCode']))
            $this->data['customVars'][$this->type]['CustomerCode'] = $customVars['CustomerCode'];        
        $this->data['customVars'][$this->type]['CustomerFirstName'] = $customVars['CustomerFirstName'];
        $this->data['customVars'][$this->type]['CustomerLastName'] = $customVars['CustomerLastName'];
        $this->data['customVars'][$this->type]['CustomerInitials'] = $customVars['CustomerInitials'];
        $this->data['customVars'][$this->type]['CustomerBirthDate'] = $customVars['CustomerBirthDate'];
        if (isset($customVars['CustomerGender']))
            $this->data['customVars'][$this->type]['CustomerGender'] = $customVars['CustomerGender'];
        $this->data['customVars'][$this->type]['CustomerEmail'] = $customVars['CustomerEmail'];
        
        foreach($customVars['ADDRESS'] as $key => $adress)
        {
            foreach ($adress as $key2 => $value)
            {
               $this->data['customVars'][$this->type][$key2][$key]['value'] = $value;
               $this->data['customVars'][$this->type][$key2][$key]['group'] = 'address';
            }
        }
        if (isset($customVars['PhoneNumber']))
            $this->data['customVars'][$this->type]['PhoneNumber'] = $customVars['PhoneNumber'];
        
        if (isset($customVars['MobilePhoneNumber']))
            $this->data['customVars'][$this->type]['MobilePhoneNumber'] = $customVars['MobilePhoneNumber'];
        
        $this->data['customVars'][$this->type]['DateDue'] = $customVars['DateDue'];
        $this->data['customVars'][$this->type]['InvoiceDate'] = $customVars['InvoiceDate'];
        $this->data['customVars'][$this->type]['AmountVat'] = $customVars['AmountVat'];

        if (!empty($customVars['PaymentMethodsAllowed'])) {
            $this->data['customVars'][$this->type]['PaymentMethodsAllowed'] = $customVars['PaymentMethodsAllowed'];
        }
        $this->data['customVars'][$this->type]['CustomerIBAN'] = $customVars['CustomerAccountNumber'];
        $this->data['customVars'][$this->type]['SendMail'] = $customVars['SendMail'];
        
        $soap = new Soap($this->data);
        
        return ResponseFactory::getResponse($soap->transactionRequest());
        
    }
    
    public function CreditNote()
    {
        
    }
}

?>

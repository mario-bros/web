<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class GiftCard extends PaymentMethod{
    //put your code here
    public $cardtype = '';
    
    public function __construct()
    {
        $this->mode = Config::getMode('GIFTCARD');
    }
    
    public function Pay()
    {
        $this->data['customVars']['servicesSelectableByClient'] = Config::get('BUCKAROO_GIFTCARD_ALLOWED_CARDS');
        $this->data['customVars']['continueOnIncomplete'] = 'RedirectToHTML';
        $this->data['services'] = array();
        return parent::PayGlobal();        
    }
}

?>
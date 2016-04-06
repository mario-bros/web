<?php
require_once(dirname(__FILE__).'/../paymentmethod.php');
class Giropay extends PaymentMethod{
    //put your code here
    public $bic = '';

    public function __construct()
    {
        $this->type = "giropay";
        $this->version = 2;
        $this->mode = Config::getMode($this->type);
    }

    public function Pay()
    {
        $this->data['customVars'][$this->type]['bic'] = $this->bic;

        return parent::Pay();
    }
}

?>
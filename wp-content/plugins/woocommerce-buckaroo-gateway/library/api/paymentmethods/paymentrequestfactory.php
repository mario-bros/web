<?php

include_once(dirname(__FILE__) . '/functions.php');


class PaymentRequestFactory {

    const REQUEST_TYPE_PAYPAL = 'buckaroopaypal';
    const REQUEST_TYPE_EMPAYMENT = 'empayment';
    const REQUEST_TYPE_IDEAL = 'ideal';
    const REQUEST_TYPE_PAYGUARANT = 'paygarant';
    const REQUEST_TYPE_PAYGARANTBYJUNO = 'paygarantbyjuno';
    const REQUEST_TYPE_GIROPAY = 'giropay';
    const REQUEST_TYPE_DIRECTDEBIT = 'directdebit';
    const REQUEST_TYPE_SEPADIRECTDEBIT = 'sepadirectdebit';
    const REQUEST_TYPE_PAYSAFECARD = 'paysafecard';
    const REQUEST_TYPE_MISTERCASH = 'bancontactmrcash';
    const REQUEST_TYPE_EMAESTRO = 'maestro';
    const REQUEST_TYPE_SOFORTBANKING = 'sofortueberweisung';
    const REQUEST_TYPE_GIFTCARD = 'giftcard';
    const REQUEST_TYPE_CREDITCARD = 'creditcard';
    //const REQUEST_TYPE_CASHTICKET = 'cashticket';
    const REQUEST_TYPE_TRANSFER = 'transfer';

    // Request types (Payment Methods).
    static public $valid_request_types = array(
        PaymentRequestFactory::REQUEST_TYPE_PAYPAL => 'BuckarooPayPal',
        PaymentRequestFactory::REQUEST_TYPE_EMPAYMENT => 'Empayment',
        PaymentRequestFactory::REQUEST_TYPE_IDEAL => 'IDeal',
        PaymentRequestFactory::REQUEST_TYPE_DIRECTDEBIT => 'DirectDebit',
        PaymentRequestFactory::REQUEST_TYPE_SEPADIRECTDEBIT => 'SepaDirectDebit',
        PaymentRequestFactory::REQUEST_TYPE_PAYGARANTBYJUNO => 'PayGarantByJuno',   
        PaymentRequestFactory::REQUEST_TYPE_PAYGUARANT => 'PayGarant', 
        PaymentRequestFactory::REQUEST_TYPE_GIROPAY => 'Giropay',        
        PaymentRequestFactory::REQUEST_TYPE_PAYSAFECARD => 'PaySafeCard',
        PaymentRequestFactory::REQUEST_TYPE_MISTERCASH => 'MisterCash',
        PaymentRequestFactory::REQUEST_TYPE_EMAESTRO => 'EMaestro',
        PaymentRequestFactory::REQUEST_TYPE_SOFORTBANKING => 'Sofortbanking',        
        PaymentRequestFactory::REQUEST_TYPE_GIFTCARD => 'GiftCard',
        PaymentRequestFactory::REQUEST_TYPE_CREDITCARD => 'CreditCard',
        //PaymentRequestFactory::REQUEST_TYPE_CASHTICKET => 'CashTicket',
        PaymentRequestFactory::REQUEST_TYPE_TRANSFER => 'Transfer',
    );

    final public static function Create($request_type_id, $data = array()) {

        $class_name = self::$valid_request_types[$request_type_id];
        autoload($class_name); //Try to find class in api directory
        if (!class_exists($class_name)) {
            throw new Exception('Payment method not found', '1'); //TODO: ExceptionPayment
        }
        return new $class_name($data);
    }

}
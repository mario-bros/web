<?php

require_once(dirname(__FILE__) . '/buckaroopaypal/paypalresponse.php');
require_once(dirname(__FILE__) . '/ideal/idealresponse.php');
require_once(dirname(__FILE__) . '/paygarant/paygarantresponse.php');
require_once(dirname(__FILE__) . '/transfer/transferresponse.php');
require_once(dirname(__FILE__) . '/creditcard/creditcardresponse.php');
require_once(dirname(__FILE__) . '/giftcard/giftcardresponse.php');
require_once(dirname(__FILE__) . '/responsedefault.php');

class ResponseFactory {
    
    final private static function getPaymentMethod($data = null) {

        $paymentMethod = 'default';

        //1) SOAP response
        if (!is_null($data) && ($data[0] != false )) {
            if (isset($data[0]->ServiceCode))
                $paymentMethod = $data[0]->ServiceCode;
        }//2) HTTP ???
        else if (isset($_POST['brq_payment_method'])) { //brq_payment_method - The service code identifying the type of payment that has occurred.
            $paymentMethod = $_POST['brq_payment_method'];
        } // HTTP ???
        else if (isset($_POST['brq_transaction_method'])) { //brq_ transaction_method The service code identifying the type of transaction that has occurred. (If no payment has occurred, for example when a customer cancels on the redirect page.
            $paymentMethod = $_POST['brq_transaction_method'];
        }
        
        return $paymentMethod;
    }

    //If $data is not null - SOAP response, otherwise HTTP response
    final public static function getResponse($data = null) {

        $paymentmethod = self::getPaymentMethod($data);

        switch ($paymentmethod) {
            case 'paypal':
                return new PayPalResponse($data);
                break;
            case 'ideal':
                return new IdealResponse($data);
                break;
            case 'paymentguarantee':
                return new PayGarantResponse($data);
                break;
            case 'transfer':
                return new TransferResponse($data);
                break;
            default:
                if (stripos(Config::get('BUCKAROO_CREDITCARD_CARDS'), $paymentmethod) !== false) {
                    return new CreditCardResponse($data);
                } else if (stripos(Config::get('BUCKAROO_GIFTCARD_CARDS'), $paymentmethod) !== false) {
                    return new GiftCardResponse($data);
                } else {
                    return new ResponseDefault($data);
                }
                break;
        }
    }
}
<?php

require_once 'library/config.php';
require_once 'gateway-buckaroo.php';
require_once(dirname(__FILE__) . '/library/api/paymentmethods/creditcard/creditcard.php');
class WC_Gateway_Buckaroo_Creditcard extends WC_Gateway_Buckaroo {
    
    function __construct() { 
        global $woocommerce;
        $this->id = 'buckaroo_creditcard';
        $this->title = 'Creditcards';
        $this->icon 		= apply_filters('woocommerce_buckaroo_creditcard_icon', plugins_url('library/buckaroo_images/24x24/cc.gif', __FILE__));
        $this->has_fields 	= false;
        $this->method_title = "Buckaroo Creditcards";
        $this->description = "Betaal met Creditcards";
        
        parent::__construct();

        $this->supports           = array(
            'products',
            'refunds'
        );
        $this->notify_url = "https://www.pedulianak.org/";
        
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

        } else {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_api_wc_gateway_buckaroo_creditcard', array( $this, 'response_handler' ) );
                $this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Buckaroo_Creditcard', $this->notify_url);
        }
    }


    /**
     * Can the order be refunded
     * @param  WC_Order $order
     * @return bool
     */
    public function can_refund_order( $order ) {
        return $order && $order->get_transaction_id();
    }

    public function process_refund( $order_id, $amount = null, $reason = '' ) {
        $order = wc_get_order( $order_id );
        if ( ! $this->can_refund_order( $order ) ) {
            return new WP_Error('error_refund_trid', __("Refund failed: Order not in ready state, Buckaroo transaction ID do not exists."));
        }
        update_post_meta($order_id, '_pushallowed', 'busy');
        $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
        $order = wc_get_order( $order_id );
        $creditcard = new CreditCard();
        $creditcard->amountDedit = 0;
        $creditcard->amountCredit = $amount;
        $creditcard->currency = $this->currency;
        $creditcard->description = $reason;
        $creditcard->invoiceId = $order_id;
        $creditcard->orderId = $order_id;
        $creditcard->OriginalTransactionKey = $order->get_transaction_id();
        $creditcard->returnUrl = $this->notify_url;
        $creditcard->setType(get_post_meta( $order->id, '_payment_method_transaction', true));
        try {
            $response = $creditcard->Refund();
        } catch (exception $e) {
            update_post_meta($order_id, '_pushallowed', 'ok');
        }
        return fn_buckaroo_process_refund($response, $order, $amount, $this->currency);
    }
    
    function process_payment($order_id) {
            global $woocommerce;

            $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
            $order = new WC_Order( $order_id );
            $creditcard = new CreditCard();
            if (method_exists($order, 'get_order_total')) {
                $creditcard->amountDedit = $order->get_order_total();
            } else {
                $creditcard->amountDedit = $order->get_total();
            }
            $creditcard->currency = $this->currency;
            $creditcard->description = $this->transactiondescription;
            $creditcard->invoiceId = (string)$order_id;
            $creditcard->orderId = (string)$order_id;
            $creditcard->returnUrl = $this->notify_url;
            $response = $creditcard->Pay();
            return fn_buckaroo_process_response($this, $response);
    }
    
            /**
	 * Check response data
	 */
    
	public function response_handler() {
            global $woocommerce;
            $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
            $result = fn_buckaroo_process_response($this);
            if (!is_null($result))
               wp_safe_redirect($result['redirect']);
            else
                wp_safe_redirect($this->get_failed_url());
            exit;
        }

}
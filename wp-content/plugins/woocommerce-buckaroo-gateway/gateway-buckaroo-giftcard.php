<?php

require_once 'library/config.php';
require_once 'gateway-buckaroo.php';
require_once(dirname(__FILE__) . '/library/api/paymentmethods/giftcard/giftcard.php');
class WC_Gateway_Buckaroo_Giftcard extends WC_Gateway_Buckaroo {
    
    function __construct() { 
        global $woocommerce;
        $this->id = 'buckaroo_giftcard';
        $this->title = 'Giftcards';
        $this->icon 		= apply_filters('woocommerce_buckaroo_giftcard_icon', plugins_url('library/buckaroo_images/24x24/giftcard.gif', __FILE__));
        $this->has_fields 	= false;
        $this->method_title = "Buckaroo Giftcards";
        $this->description = "Betaal met Giftcards";
        
        parent::__construct();

        $this->supports           = array(
            'products',
            'refunds'
        );
        
        $this->notify_url = home_url('/');
        
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

        } else {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_api_wc_gateway_buckaroo_giftcard', array( $this, 'response_handler' ) );
                $this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Buckaroo_Giftcard', $this->notify_url);
        }
        //add_action( 'woocommerce_api_callback', 'response_handler' );           
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
        $giftcard = new GiftCard();
        $giftcard->amountDedit = 0;
        $giftcard->amountCredit = $amount;
        $giftcard->currency = $this->currency;
        $giftcard->description = $reason;
        $giftcard->invoiceId = $order_id;
        $giftcard->orderId = $order_id;
        $giftcard->OriginalTransactionKey = $order->get_transaction_id();
        $giftcard->returnUrl = $this->notify_url;
        $giftcard->setType(get_post_meta( $order->id, '_payment_method_transaction', true));
        $giftcard->version = 1;
        try {
            $response = $giftcard->Refund();
        } catch (exception $e) {
            update_post_meta($order_id, '_pushallowed', 'ok');
        }
        return fn_buckaroo_process_refund($response, $order, $amount, $this->currency);
    }
    
    function process_payment($order_id) {
            global $woocommerce;

            $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
            $order = new WC_Order( $order_id );
            $giftcard = new GiftCard();
            if (method_exists($order, 'get_order_total')) {
                $giftcard->amountDedit = $order->get_order_total();
            } else {
                $giftcard->amountDedit = $order->get_total();
            }
            $giftcard->currency = $this->currency;
            $giftcard->description = $this->transactiondescription;
            $giftcard->invoiceId = (string)$order_id;
            $giftcard->orderId = (string)$order_id;
            $giftcard->returnUrl = $this->notify_url;
            $response = $giftcard->Pay();
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
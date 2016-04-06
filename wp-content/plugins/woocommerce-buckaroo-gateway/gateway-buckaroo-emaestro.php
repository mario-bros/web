<?php

require_once 'library/config.php';
require_once 'gateway-buckaroo.php';
require_once(dirname(__FILE__) . '/library/api/paymentmethods/emaestro/emaestro.php');
class WC_Gateway_Buckaroo_EMaestro extends WC_Gateway_Buckaroo {
    
    function __construct() { 
        global $woocommerce;
        $this->id = 'buckaroo_emaestro';
        $this->title = 'eMaestro';
        $this->icon 		= apply_filters('woocommerce_buckaroo_emaestro_icon', plugins_url('library/buckaroo_images/24x24/emaestro.png', __FILE__));
        $this->has_fields 	= false;
        $this->method_title = "Buckaroo eMaestro";
        $this->description = "Betaal met eMaestro";
        
        parent::__construct();

        $this->supports           = array(
            'products',
            'refunds'
        );
        $this->notify_url = "https://www.pedulianak.org/";
        
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

        } else {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_api_wc_gateway_buckaroo_emaestro', array( $this, 'response_handler' ) );
                $this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Buckaroo_EMaestro', $this->notify_url);
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
        $emaestro = new EMaestro();
        $emaestro->amountDedit = 0;
        $emaestro->amountCredit = $amount;
        $emaestro->currency = $this->currency;
        $emaestro->description = $reason;
        $emaestro->invoiceId = $order_id;
        $emaestro->orderId = $order_id;
        $emaestro->OriginalTransactionKey = $order->get_transaction_id();
        $emaestro->returnUrl = $this->notify_url;
        $emaestro->setType(get_post_meta( $order->id, '_payment_method_transaction', true));
        try {
            $response = $emaestro->Refund();
        } catch (exception $e) {
            update_post_meta($order_id, '_pushallowed', 'ok');
        }
        return fn_buckaroo_process_refund($response, $order, $amount, $this->currency);
    }
    
    function process_payment($order_id) {
		global $woocommerce;

		$GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
		$order = new WC_Order( $order_id );
		$emaestro = new EMaestro();
		if (method_exists($order, 'get_order_total')) {
			$emaestro->amountDedit = $order->get_order_total();
		} else {
			$emaestro->amountDedit = $order->get_total();
		}
		$emaestro->currency = $this->currency;
		$emaestro->description = $this->transactiondescription;
		$emaestro->invoiceId = (string)$order_id;
		$emaestro->orderId = (string)$order_id;
		$emaestro->returnUrl = $this->notify_url;
		$response = $emaestro->Pay();
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
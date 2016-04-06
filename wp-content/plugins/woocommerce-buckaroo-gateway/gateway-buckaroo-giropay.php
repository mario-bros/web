<?php

require_once 'library/config.php';
require_once 'gateway-buckaroo.php';
require_once(dirname(__FILE__) . '/library/api/paymentmethods/giropay/giropay.php');
class WC_Gateway_Buckaroo_Giropay extends WC_Gateway_Buckaroo {
    
    function __construct() { 
        global $woocommerce;
        $this->id = 'buckaroo_giropay';
        $this->title = 'Giropay';
        $this->icon 		= apply_filters('woocommerce_buckaroo_giropay_icon', plugins_url('library/buckaroo_images/24x24/giropay.gif', __FILE__));
        $this->has_fields 	= true;
        $this->method_title = "Buckaroo Giropay";
        $this->description = "Betaal met Giropay";
        
        parent::__construct();

        $this->supports           = array(
            'products',
            'refunds'
        );
        
        $this->notify_url = home_url('/');
        
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

        } else {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_api_wc_gateway_buckaroo_giropay', array( $this, 'response_handler' ) );
                $this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Buckaroo_Giropay', $this->notify_url);
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
        $giropay = new Giropay();
        $giropay->amountDedit = 0;
        $giropay->amountCredit = $amount;
        $giropay->currency = $this->currency;
        $giropay->description = $reason;
        $giropay->invoiceId = $order_id;
        $giropay->orderId = $order_id;
        $giropay->OriginalTransactionKey = $order->get_transaction_id();
        $giropay->returnUrl = $this->notify_url;
        try {
            $response = $giropay->Refund();
        } catch (exception $e) {
            update_post_meta($order_id, '_pushallowed', 'ok');
        }
        return fn_buckaroo_process_refund($response, $order, $amount, $this->currency);
    }
    
    function process_payment($order_id) {
            global $woocommerce;
            
            if (empty($_POST['buckaroo-giropay-bancaccount']))
            {
                wc_add_notice(__('Please provide correct BIC', 'woothemes'), 'error' );
                return;
            }
            $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
            $order = new WC_Order( $order_id );
            $giropay = new Giropay();
            if (method_exists($order, 'get_order_total')) {
                $giropay->amountDedit = $order->get_order_total();
            } else {
                $giropay->amountDedit = $order->get_total();
            }
            $giropay->currency = $this->currency;
            $giropay->description = $this->transactiondescription;
            $giropay->invoiceId = (string)$order_id;
            $giropay->orderId = (string)$order_id;
            $giropay->bic = $_POST['buckaroo-giropay-bancaccount'];
            $giropay->returnUrl = $this->notify_url;
            $response = $giropay->Pay();            
            return fn_buckaroo_process_response($this, $response);
    }
    
    function payment_fields() {
    ?>
                    <?php if ($this->mode=='test') : ?><p><?php _e('TEST MODE', 'woocommerce'); ?></p><?php endif; ?>
                    <?php if ($this->description) : ?><p><?php echo wpautop(wptexturize($this->description)); ?></p><?php endif; ?>
        <fieldset>
            <p class="form-row form-row-wide">
                    <label for="buckaroo-giropay-bancaccount"><?php echo _e('BIC:', 'woocommerce')?><span class="required">*</span></label>
                    <input id="buckaroo-giropay-bancaccount" name="buckaroo-giropay-bancaccount" class="input-text card-number" type="text" maxlength="11" autocomplete="off" value="" />
            </p>
        </fieldset>
                <?php
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
<?php

require_once 'library/config.php';
require_once 'gateway-buckaroo.php';
require_once(dirname(__FILE__) . '/library/api/paymentmethods/ideal/ideal.php');
class WC_Gateway_Buckaroo_Ideal extends WC_Gateway_Buckaroo {

    var $usenotification;
    var $notificationtype;
    var $notificationdelay;

    function __construct() {
        global $woocommerce;
        $this->id = 'buckaroo_ideal';
        $this->title = 'iDEAL';//$this->settings['title_ideal'];
        $this->icon 		= apply_filters('woocommerce_buckaroo_ideal_icon', plugins_url('library/buckaroo_images/24x24/ideal.png', __FILE__));
        $this->has_fields 	= true;
        $this->method_title = "Buckaroo iDEAL";
        $this->description = "Betaal met iDEAL";
        
        parent::__construct();
        if (!isset($this->settings['usenotification'])) {
            $this->usenotification = 'FALSE';
            $this->notificationtype = 'PreNotification';
            $this->notificationdelay = '0';

        } else {
            $this->usenotification = $this->settings['usenotification'];
            $this->notificationtype = $this->settings['notificationtype'];
            $this->notificationdelay = $this->settings['notificationdelay'];
        }
        $this->supports           = array(
            'products',
            'refunds'
        );
        
        $this->notify_url = "https://www.pedulianak.org/";
        
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

        } else {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_api_wc_gateway_buckaroo_ideal', array( $this, 'response_handler' ) );
                $this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Buckaroo_Ideal', $this->notify_url);
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
        $ideal = new IDeal();
        $ideal->amountDedit = 0;
        $ideal->amountCredit = $amount;
        $ideal->currency = $this->currency;
        $ideal->description = $reason;
        $ideal->invoiceId = $order_id;
        $ideal->orderId = $order_id;
        $ideal->OriginalTransactionKey = $order->get_transaction_id();
        $ideal->returnUrl = $this->notify_url;
		try {				
			$response = $ideal->Refund();
		} catch (exception $e) {			
			update_post_meta($order_id, '_pushallowed', 'ok');
		}
        return fn_buckaroo_process_refund($response, $order, $amount, $this->currency);
    }
    
    function process_payment($order_id) {
            global $woocommerce;

            $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
            $order = new WC_Order( $order_id );
            $ideal = new IDeal();
            if (method_exists($order, 'get_order_total')) {
                $ideal->amountDedit = $order->get_order_total();
            } else {
                $ideal->amountDedit = $order->get_total();
            }
            $ideal->currency = $this->currency;
            $ideal->description = $this->transactiondescription;
            $ideal->invoiceId = (string)$order_id;
            $ideal->orderId = (string)$order_id;
            $ideal->issuer =  $_POST['buckaroo-ideal-issuer'];
            $ideal->returnUrl = $this->notify_url;
            $customVars = Array();
            if ($this->usenotification == 'TRUE') {
                $ideal->usenotification = 1;
                $customVars['Customergender'] = 0;
                $customVars['CustomerFirstName'] = !empty($order->billing_first_name) ? $order->billing_first_name : '';
                $customVars['CustomerLastName'] = !empty($order->billing_last_name) ? $order->billing_last_name : '';
                $customVars['Customeremail'] = !empty($order->billing_email) ? $order->billing_email : '';
                $customVars['Notificationtype'] = 'PreNotification';
                $customVars['Notificationdelay'] = date('Y-m-d', strtotime(date('Y-m-d', strtotime('now + '. (int)$this->notificationdelay.' day'))));
            }
            $response = $ideal->Pay($customVars);            
            return fn_buckaroo_process_response($this, $response);
    }
    
    function payment_fields() {
    ?>
                    <?php if ($this->mode=='test') : ?><p><?php _e('TEST MODE', 'woocommerce'); ?></p><?php endif; ?>
                    <?php if ($this->description) : ?><p><?php echo wpautop(wptexturize($this->description)); ?></p><?php endif; ?>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('woocommerce-buckaroo-gateway/library/css/ideal.css')?>">
    <ul class="ideallist">
	<fieldset>
            <p class="form-row form-row-wide">               
    <?php
        $first = true;
        foreach(IDeal::getIssuerList() as $key => $issuer)
        {?>
		<li>
             <input type='radio' value='<?php echo $key; ?>' name='buckaroo-ideal-issuer' id='buckaroo-ideal-issuer' <?php (($first) ? 'checked' : '')?>/>
             <span class="<?php echo $key; ?>"></span>
        </li>
		     <?php
             $first = false;
			 
        }
        ?>
            </p>
    </fieldset>
     </ul>
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

    function init_form_fields() {

        parent::init_form_fields();

        $this->form_fields['usenotification'] = array(
            'title' => __( 'Use Notification Service', 'woocommerce' ),
            'type' => 'select',
            'description' => __( 'The notification service can be used to have the payment engine sent additional notifications at certain points. Different type of notifications can be sent and also using different methods to sent them.)', 'woocommerce' ),
            'options' => array('TRUE'=>'Yes', 'FALSE'=>'No'),
            'default' => 'FALSE');

        $this->form_fields['notificationtype'] = array(
            'title' => __( 'Notification Type', 'woocommerce' ),
            'type' => 'select',
            'description' => __( 'PreNotification: A pre-notification that is sent some time before performing a scheduled action. PaymentComplete: A notification that is sent when a transaction has been completed with success.', 'woocommerce' ),
            'options' => array('PreNotification'=>'Pre Notification', 'PaymentComplete'=>'Payment Complete'),
            'default' => 'FALSE');

        $this->form_fields['notificationdelay'] = array(
            'title' => __( 'Notification delay', 'woocommerce' ),
            'type' => 'text',
            'description' => __( 'The time at which the notification should be sent. If this is not specified, the notification is sent immediately.', 'woocommerce' ),
            'default' => '0');
    }

}
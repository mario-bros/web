
<?php

/**
 * The stripe gateway functionality for the plugin.
 *
 * 
 * @since      2.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

/**
 * The stripe gateway functionality for the plugin.
 *
 * @package    WPPD
 * @subpackage WPPD/public
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Stripe {

    public $gateway_name;
    public $easypay_options;

    public function __construct() {
        $this->gateway_name = 'Stripe';
        $this->easypay_stripe_setting = get_option('easypay_stripe_setting');
        $this->easypay_options = get_option('easypay_options');
        
        
        $this->id               = 'stripe';
		$this->has_fields       = true;
		$this->method_title     = 'Stripe';		
		//$this->title            = $this->easypay_stripe_setting['title'];
		//$this->stripe_privatekey= $this->easypay_stripe_setting['stripe_privatekey'];
		//$this->stripe_secretkey = $this->easypay_stripe_setting['stripe_secretkey'];
		//$this->stripe_testmode  = $this->easypay_stripe_setting['stripe_testmode'];
		//$this->stripe_verifySSL = $this->easypay_stripe_setting['stripe_verifySSL'];
		
		//define("STRIPE_PRIVATE_KEY", $this->stripe_privatekey); 
		//define("STRIPE_SECRET_KEY", $this->stripe_secretkey);
		//define("STRIPE_VERIFY_SSL", ($this->stripe_verifySSL=='yes'? false : true)); 
		//define("STRIPE_SANDBOX", ($this->stripe_testmode=='yes'? true : false));
    }
 
	   
    /**
     * Update the options settings for the gateway
     * 
     * @param array $data  $_POST
     */
    public function wppd_update_gateway_settings($data) {
    	//print_r($data);die;
        $easypay_strip_enable = isset($data['easypay_strip_enable']) ? stripslashes($data['easypay_strip_enable']) : '';
        $easypay_strip_description = isset($data['easypay_strip_description']) ? stripslashes($data['easypay_strip_description']) : '';
        $easypay_strip_title = isset($data['easypay_strip_title']) ? stripslashes($data['easypay_strip_title']) : '';
        $easypay_private_key = isset($data['easypay_private_key']) ? stripslashes($data['easypay_private_key']) : '';
        $easypay_secret_key = isset($data['easypay_secret_key']) ? stripslashes($data['easypay_secret_key']) : '';
        $easypay_strip_verifySSL = isset($data['easypay_strip_verifySSL']) ? stripslashes($data['easypay_strip_verifySSL']) : '';
        $easypay_strip_pay_mod = isset($data['easypay_strip_pay_mod']) ? stripslashes($data['easypay_strip_pay_mod']) : '';
       

        $strip_options = array(
                'easypay_strip_enable' => $easypay_strip_enable,
        		'easypay_strip_description' => $easypay_strip_description,
                'easypay_strip_title' => $easypay_strip_title,
                'easypay_private_key' => $easypay_private_key,
                'easypay_secret_key' => $easypay_secret_key,
                'easypay_strip_verifySSL' => $easypay_strip_verifySSL,
                'easypay_strip_pay_mod' => $easypay_strip_pay_mod,
        );
        return update_option('easypay_stripe_setting', $strip_options);
    }

	   
    public function wppd_send_to_gateway($post) {
		global $wppd_db;
        global $wppd_security;
        global $wppd_email;
        global $wppd_error;

       // $wppd_return = $easypay_options['easypay_success_url'];
        //$wppd_cancel_return = $easypay_options['easypay_fail_url'];
        $this->easypay_options = get_option('easypay_options');
        $easypay_stripe_currency = isset($this->easypay_options['easypay_pay_currency']) ? stripslashes($this->easypay_options['easypay_pay_currency']) : 'USD';
        
         $easypay_stripe_option = get_option('easypay_stripe_setting');
         $stripe_sk_key = isset($easypay_stripe_option['easypay_secret_key']) ? $easypay_stripe_option['easypay_secret_key'] : 'sk_test_bajvh4G8hxAwfjOsJZG36uSt';
       
		//$payment_fields_arr = $this->wppd_prepare_gateway_fields($post);
        $wppd_error = $this->wppd_validate_gateway_fields($post);
        $payment_fields_db = $this->wppd_prepare_fields_for_db($post);
         $custom_fields = $post;
         $custom_fields['gateway'] = $this->gateway_name;
          
         $hash = $wppd_security->generate_hash_code();
        
        $insert_id = 0;
        unset($post['card_holder_name']);
        unset($post['card_number']);
        unset($post['expiry_month']);
        unset($post['expiry_year']);
        unset($post['cvv']);
        unset($post['action']);
            $insert_id = $wppd_db->wppd_insert_payment_info($post, $this->wppd_obfuscate_stripe($custom_fields), $hash);
             if ($insert_id) {
                    $wppd_email->wppd_send_email('customer', 'wppd_customer_initiated', $insert_id);
             }
        	
		include(plugin_dir_path( __FILE__ )."/lib/Stripe.php");
		
                //print_r($post);
		Stripe::setApiKey($stripe_sk_key);
		// Stripe::setApiKey("<Stripe Secret Key>");
  $error = '';
  $success = '';

  try {
    if (!isset($post['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");
    $spayment = Stripe_Charge::create(array("amount" => floatval($post['actualamount']),
                                "currency" => $easypay_stripe_currency,
                                "card" => $post['stripeToken'],
								"description" => $post['email']), $stripe_sk_key);
    
//print_r($spayment['id']);
    //print_r(Stripe_Charge::retrieve($spayment['id'])->__toArray(true));
    $success = '<div class="alert alert-success">
                <strong>Success!</strong> Your payment was successful.
				</div>';
		if($spayment['status'] == 'succeeded'):
			$this->handle_strip_server_req($spayment->__toArray(true),$insert_id);
		endif;	
		
		$args["msg"]="success";
		$args["redirect_url"]=add_query_arg( array('item_id' => $_REQUEST['itemname'] , 'rsamount'=> $_REQUEST['amount']), $this->easypay_options['easypay_success_url']);
		
		echo json_encode($args);
		//header('Location: ' . $this->easypay_options['easypay_success_url']);
		

	 //header('Location: ' . $wppd_return);		
	//print_r($spayment);
        
        die;			
  }
  catch (Exception $e) {
	$error = '<div class="alert alert-danger"> <strong>Error!</strong> '.$e->getMessage().'</div>';
			  print_r($error);die;
      }
		
	}
	/**
     * 
     * @global type $wppd_validation
     * @param type $payment_fields_arr
     */
	 public function wppd_validate_gateway_fields($payment_fields_arr) {
        global $wppd_validation;
        $wppd_error = array();
        // Check valid email
        $is_email = $wppd_validation->is_email($payment_fields_arr['email']);
        if (!$is_email || $payment_fields_arr['email'] == '') {
            $wppd_error['email'] = "Please enter a valid email";
        }
        if (!is_numeric($payment_fields_arr['amount']) || $payment_fields_arr['amount'] == '') {
            $wppd_error['actualamount'] = "Please enter a valid amount";
        }
        if (!$wppd_validation->validateCVV($payment_fields_arr['cardcvv_stripe'], $payment_fields_arr['cardcvv_stripe'])) {
            $wppd_error['cvv'] = "Please enter a valid cvv for your card.";
        }
        if (!$wppd_validation->validateCCard($payment_fields_arr['cardno_stripe'])) {
            $wppd_error['acct'] = "Please enter a valid card number.";
        }
        return $wppd_error;
    }

    /**
     * function to prepare database fields to match the DB class requirement
     */
    public function wppd_prepare_fields_for_db($payment_fields_arr) {
        $payment_fields['amount'] = $payment_fields_arr['amount'];
        $payment_fields['actualamount'] = $payment_fields_arr['amount'];
        $payment_fields['item_name'] = $payment_fields_arr['itemname'];
        $payment_fields['email'] = $payment_fields_arr['email'];
        return $payment_fields;
    }
    
    /**
     * Hide credit card details before storing into database
     */
    public function wppd_obfuscate_stripe($custom_fields) {

        $custom_fields['cardno_stripe'] = 'XXXX-XXXX-XXXX-' . substr($custom_fields['cardno_stripe'], -4);
        $custom_fields['cardcvv_stripe'] = '***';

        return $custom_fields;
    }
    
    
    /**
     * 	Function for handling server request
     * 
     * 	@param  $request	(array)		Transaction data array
     * 	@return (void)  
     * 
     */
    function handle_strip_server_req($post,$insert_id) {
        global $wppd_db;
        global $wppd_email;

        $row_id_txn = $insert_id;
        // verify the transaction response
        $is_varified = $this->wppd_verify_notification($post, $row_id_txn);

        // update database for the transaction details
        if ($is_varified)
            $is_db_updated = $wppd_db->wppd_update_stripe_payment_info($post, $row_id_txn);
        if ($is_db_updated) {
            $wppd_email->wppd_send_email('admin', 'wppd_admin_new_order', $row_id_txn);
            $wppd_email->wppd_send_email('customer', 'wppd_customer_invoice', $row_id_txn);
        }
    }
    
    /**
     * Verify the post data returned in the data
     * 
     * @global object $wpdb
     * @param array $data
     * @return boolean
     */
    function wppd_verify_notification($data) {

        if ($data['status'] == "succeeded" && $this->is_unique_transaction($data['id'])) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Check if the contains a unique transaction ID
     * 
     * @global object $wpdb
     * @param int $trans_id
     * @return boolean
     */
    public function is_unique_transaction($trans_id) {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}easypay_payment_log WHERE txnid = '" . $trans_id."'";
        $is_match = $wpdb->get_row($query, ARRAY_A);
        if ($is_match) {
            return false;
        } else {
            return true;
        }
    }

}
?>


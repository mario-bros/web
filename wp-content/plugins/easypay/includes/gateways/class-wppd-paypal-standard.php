<?php

/**
 * The paypal standard gateway functionality for the plugin.
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

/**
 * The paypal standard gateway functionality for the plugin.
 *
 * @package    WPPD
 * @subpackage WPPD/public
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Paypal_Standard implements WPPD_Gateway {

    public $gateway_name;
    public $easypay_options;

    public function __construct() {
        $this->gateway_name = 'PayPal Payments Standard';
        $this->easypay_options = get_option('easypay_options');
    }

    /* -----------------------------------------------*
     * Function to send payment Request
     * 
     * @param1  $paypal_fields		(array) 		Default paypal fields
     * @param2  $insert_id			(int)			DB insert id. Use as a falg for transaction
     * @param3  $hash				(String)		Security string for transaction
     * @$wppd_payment_action        (URL)			Paypal URL
     * 
     * @return  $complete_payment_url_str			payment method URL str
     * ----------------------------------------------- */

    function wppd_prepare_payment_url($payment_fields_arr, $insert_id, $hash, $wppd_payment_action) {

        // alter the value of custom argument
        $payment_fields_arr['custom'] = base64_encode($insert_id) . '---' . $hash;

        $qry_str = '';

        $indx = 1;
        foreach ($payment_fields_arr as $key => $val) {
            if ($key == 'cancel_return') {
                $val = add_query_arg(array('payment_id' => base64_encode($insert_id), 'action_type' => 'retry'), $val);
                $val = urlencode($val);
            }
            if ($indx == 1) {

                $qry_str .= $key . '=' . $val;
            } else {

                $qry_str .= '&' . $key . '=' . $val;
            }


            $indx++;
        }

        $complete_payment_url_str = $wppd_payment_action . '?' . $qry_str;
        return $complete_payment_url_str;
    }

    /**
     * Prepare gateway post fields
     * 
     * @param array $post send $_POST
     * @return array
     */
    public function wppd_prepare_gateway_fields($post) {


        /**
         * extract $post to variabled
         * Sample $post array
         * Array
          (
          [actualamount] => 300
          [payment_currency] => AUD
          [amount] => 308.70
          [email] => example@example.com
          [gateway] => paypal
          )
         */
        extract($post);

        // Get "Custom form fields" saved in DB
        $easypay_options = $this->easypay_options;
       $rounupeasy =round($actualamount + ($actualamount * ($this->easypay_options['easypay_paypal_fee']) / 100 ), 2);
        //$easypay_paypal_fee=isset($easypay_options['easypay_paypal_fee'])?$easypay_options['easypay_paypal_fee']: '2.9';//-----------------
        //
       // $item_name = isset($easypay_options['easypay_user_defined_name'])?$easypay_options['easypay_user_defined_name']:'';
        $easypay_business_name = isset($easypay_options['easypay_business'])?$easypay_options['easypay_business']:'';
        $wppd_form_action = isset($easypay_options['easypay_pay_mod'])?$easypay_options['easypay_pay_mod']:'';
        //$wppd_pay_currency = $easypay_options['easypay_pay_currency'];
        $easypay_allow_currency = isset($this->easypay_options['easypay_allow_currency'])?$this->easypay_options['easypay_allow_currency']:false;
        $wppd_pay_currency = (isset($this->easypay_options['easypay_pay_currency']) && !$easypay_allow_currency) ? stripslashes($this->easypay_options['easypay_pay_currency']) : $payment_currency;
        $wppd_return = add_query_arg(array('item_id'=> $_REQUEST['itemname'],'rsamount' => $rounupeasy), $easypay_options['easypay_success_url']);
        $wppd_cancel_return = add_query_arg(array('item_id'=> $_REQUEST['itemname'],'rsamount' => $rounupeasy), $easypay_options['easypay_fail_url']);
        $wppd_notify_url = urlencode(add_query_arg(array('action' => 'wppd_ipnhandler', 'ipn_for' => 'paypal', 'item_id'=> $_REQUEST['itemname'], 'ramount' => $rounupeasy), admin_url('admin-ajax.php')));  // "wppd_ipnhandler" : "wp_ajax" hook action

        $recPoint = explode(',',$period);
        
        $actualamount = abs(sanitize_text_field($actualamount));
        $item_name = get_the_title($_REQUEST['itemname']);

        if($paymenttype == 'recurring'){
			$payment_fields_arr = array(
            'cmd' => '_xclick-subscriptions',
            'rm' => 2,
            'redirect_cmd' => '_xclick',
            'business' => $easypay_business_name,
            'item_name' => $item_name,
            'amount' => $rounupeasy,
            'quantity' => 1,
            'currency_code' => $wppd_pay_currency,
            'no_note' => $wppd_pay_currency,
            'return' => urlencode($wppd_return),
            'notify_url' => $wppd_notify_url,
            'cancel_return' => $wppd_cancel_return,
            'custom' => '',
            'a3' => $actualamount,
            'email' => sanitize_email($email),
              'p3' => $recPoint[0],
              't3' => $recPoint[1],
              'src' => '1',
              'sra' => '1',
            'payDirectSubmit' => $payDirectSubmit
         );
        return $payment_fields_arr; 
			
		}
		else{
        $payment_fields_arr = array(
            'cmd' => '_ext-enter',
            'rm' => 2,
            'redirect_cmd' => '_xclick',
            'business' => $easypay_business_name,
            'item_name' => $item_name,
            'amount' => round($actualamount + ($actualamount * ($this->easypay_options['easypay_paypal_fee']) / 100 ), 2),
            'quantity' => 1,
            'currency_code' => $wppd_pay_currency,
            'no_note' => $wppd_pay_currency,
            'return' => urlencode($wppd_return),
            'notify_url' => $wppd_notify_url,
            'cancel_return' => $wppd_cancel_return,
            'custom' => '',
            'actualamount' => $actualamount,
            'email' => sanitize_email($email),
            'payDirectSubmit' => $payDirectSubmit
        );

        return $payment_fields_arr;
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

        return $wppd_error;
    }

    /**
     * Send request to payment gateway
     * 
     * @param array $payment_fields_arr
     * @param int $insert_id
     * @param string $hash
     * @param string $wppd_form_action
     */
    public function wppd_send_to_gateway($post) {

        global $wppd_db;
        global $wppd_security;
        global $wppd_email;

        // unset unnecessary post data

        //unset($post['payDirectSubmit']);
        unset($post['actualamount_option']);

        // unset credit card fields

        unset($post['card_holder_name']);
        unset($post['card_number']);
        unset($post['expiry_month']);
        unset($post['expiry_year']);
        unset($post['cvv']);

        $easypay_options = $this->easypay_options;
        $wppd_form_action = $easypay_options['easypay_pay_mod'];

        $payment_fields_arr = $this->wppd_prepare_gateway_fields($post);

        $wppd_error = $this->wppd_validate_gateway_fields($payment_fields_arr);


        $custom_fields = array_diff_key($post, $payment_fields_arr);

        $custom_fields['gateway'] = $this->gateway_name;
        unset($custom_fields['payment_currency']);

        foreach ($custom_fields as $key => $value) {
            $custom_fields[$key] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
        }
        //push currency code in custom field
        $custom_fields['currency_code'] = $payment_fields_arr['currency_code'];

        $hash = $wppd_security->generate_hash_code();
        if (count($wppd_error) == 0) {
            $insert_id = 0;

            if (isset($_GET['action_type']) && $_GET['action_type'] == 'retry') {
                $insert_id = isset($_GET['payment_id']) ? base64_decode($_GET['payment_id']) : 0;
            } else {
                $insert_id = $wppd_db->wppd_insert_payment_info($payment_fields_arr, $custom_fields, $hash);
                if ($insert_id) {
                    $wppd_email->wppd_send_email('customer', 'wppd_customer_initiated', $insert_id);
                }
            }

            $payment_url = $this->wppd_prepare_payment_url($payment_fields_arr, $insert_id, $hash, $wppd_form_action);
           // print_r($payment_url);
            header('Location: ' . $payment_url);
            exit;
        }

        return $wppd_error;
    }

    public function wppd_gateway_hidden_fields() {
        
    }

    public function wppd_update_gateway_settings($data) {
        //easypay_paypal_enable
        $easypay_paypal_enable = isset($data['easypay_paypal_enable']) ? stripslashes($data['easypay_paypal_enable']) : '';
        $paypal_description = isset($data['easypay_paypal_description']) ? stripslashes($data['easypay_paypal_description']) : '';
        $paypal_business = isset($data['easypay_business']) ? stripslashes($data['easypay_business']) : '';
        $paypal_mode = isset($data['easypay_pay_mod']) ? $data['easypay_pay_mod'] : '';
        $easypay_paypal_fee = isset($data['easypay_paypal_fee']) ? stripslashes($data['easypay_paypal_fee']) : '';

        $standard_options = array(
            'easypay_paypal_enable' => $easypay_paypal_enable,
            'easypay_paypal_description' => $paypal_description,
            'easypay_business' => $paypal_business,
            'easypay_pay_mod' => $paypal_mode,
            'easypay_paypal_fee' => $easypay_paypal_fee
        );

        $get_options = $this->easypay_options;
        $standard_options = array_merge((array) $get_options, $standard_options);
        return update_option('easypay_options', $standard_options);
    }

    /**
     * 	Function for handling Paypal IPN
     * 
     * 	@param  $request	(array)		Transaction data array
     * 	@return (void)  
     * 
     */
    public function handle_ipn_req($post) {

        global $wppd_db;
        global $wppd_email;
        // getting the values of secure key & DB id where data need to be updated
        $custom_array = explode("---", $post['custom']);

        $row_id_txn = base64_decode($custom_array[0]);
        $has_txn = $custom_array[1];

        // verify the transaction response
        $is_varified = $this->wppd_verify_notification($post, $row_id_txn);

        // update database for the transaction details
        if ($is_varified)
            $is_db_updated = $wppd_db->wppd_update_payment_info($post, $row_id_txn, $has_txn);


        if ($is_db_updated) {

            $wppd_email->wppd_send_email('admin', 'wppd_admin_new_order', $row_id_txn);
            $wppd_email->wppd_send_email('customer', 'wppd_customer_invoice', $row_id_txn);
            
        }
    }

    /**
     * Verify the post data returned in the IPN
     * 
     * @global object $wpdb
     * @param array $data
     * @return boolean
     */
    function wppd_verify_notification($data) {

        // get account mode
        $easypay_options = $this->easypay_options;
        $url = $easypay_options['easypay_pay_mod'];
        //$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        // change cmd to validating mode
        $data['cmd'] = '_notify-validate';

        //url-ify the data for the POST
        $data_string = '';
        foreach ($data as $key => $value) {
            $data_string .= $key . '=' . $value . '&';
        }
        rtrim($data_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);

        if ($result == 'VERIFIED' && $data['payment_status'] == "Completed" && $data['receiver_email'] == $easypay_options['easypay_business'] && $this->is_unique_transaction($data['txn_id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the IPN contains a unique transaction ID
     * 
     * @global object $wpdb
     * @param int $trans_id
     * @return boolean
     */
    public function is_unique_transaction($trans_id) {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}easypay_payment_log WHERE txnid = " . $trans_id;
        $is_match = $wpdb->get_row($query, ARRAY_A);
        if ($is_match) {
            return false;
        } else {
            return true;
        }
    }

}

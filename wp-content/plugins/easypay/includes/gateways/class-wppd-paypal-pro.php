<?php

/**
 * The paypal standard gateway functionality for the plugin.
 *
 * 
 * @since      2.0.0
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
class WPPD_Paypal_Pro implements WPPD_Gateway {

    public $gateway_name;
    public $easypay_options;

    public function __construct() {
        $this->gateway_name = 'PayPal Pro';
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
        $payment_fields_arr['CUSTOM'] = base64_encode($insert_id) . '---' . $hash;

        $qry_str = '';

        $indx = 1;
        foreach ($payment_fields_arr as $key => $val) {
            if ($indx == 1) {

                $qry_str .= $key . '=' . $val;
            } else {

                $qry_str .= '&' . $key . '=' . $val;
            }


            $indx++;
        }

        $payment_url = $qry_str;
        return $payment_url; //exit;
    }

    /**
     * Prepare gateway post fields
     * 
     * @param array $post send $_POST
     * @return array
     */
    public function wppd_prepare_gateway_fields($post) {

        // get settings for easypay
        $pro_options = $this->easypay_options['pro_settings'];

        /**
         * extract $post to variabled
         * Sample $post array
         * Array
          (
          [actualamount] => 300
          [payment_currency] => AUD
          [amount] => 306.00
          [email] => example@example.com
          [gateway] => ccard
          [card_holder_name] => Pro IPN
          [card_number] => 4111111111111111
          [expiry_month] => 03
          [expiry_year] => 2019
          [cvv] => 123
          )
         */
        //echo "aaaaaaaaaa";
        extract($post);
        $item_name = isset($this->easypay_options['easypay_user_defined_name'])?$this->easypay_options['easypay_user_defined_name']:'';
        $easypay_pro_email = isset($pro_options['easypay_pro_email']) ? stripslashes($pro_options['easypay_pro_email']) : '';
        $easypay_pro_user = isset($pro_options['easypay_pro_user']) ? stripslashes($pro_options['easypay_pro_user']) : '';
        $easypay_pro_password = isset($pro_options['easypay_pro_password']) ? stripslashes($pro_options['easypay_pro_password']) : '';
        $easypay_pro_signature = isset($pro_options['easypay_pro_signature']) ? stripslashes($pro_options['easypay_pro_signature']) : '';
        $easypay_pro_mode = isset($pro_options['easypay_pro_mode']) ? $pro_options['easypay_pro_mode'] : '';
        $easypay_allow_currency = isset($this->easypay_options['easypay_allow_currency'])?$this->easypay_options['easypay_allow_currency']:false;
        $easypay_pro_currency = (isset($this->easypay_options['easypay_pay_currency']) && !$easypay_allow_currency) ? stripslashes($this->easypay_options['easypay_pay_currency']) : $payment_currency;
        $easypay_pro_fee = isset($pro_options['easypay_pro_fee']) ? stripslashes($pro_options['easypay_pro_fee']) : '';

        $card_holder_name = explode(' ', $card_holder_name);

        $actualamount = abs(sanitize_text_field($actualamount));
        $payment_fields_arr = array(
            'USER' => $easypay_pro_user,
            'PWD' => $easypay_pro_password,
            'SIGNATURE' => $easypay_pro_signature,
            'METHOD' => 'DoDirectPayment',
            'VERSION' => 86,
            'L_NAME0' => $item_name,
            'L_AMT0' => $actualamount,
            'L_NUMBER0' => 1,
            'L_QTY0' => 1,
            'AMT' => round($actualamount + ($actualamount * ($easypay_pro_fee) / 100 ), 2),
            'ITEMAMT' => round($actualamount + ($actualamount * ($easypay_pro_fee) / 100 ), 2),
            'CREDITCARDTYPE' => $this->wppd_credit_card_type(sanitize_text_field($card_number)),
            'ACCT' => sanitize_text_field($card_number),
            'CVV2' => sanitize_text_field($cvv),
            'FIRSTNAME' => $card_holder_name[0],
            'LASTNAME' => $card_holder_name[count($card_holder_name) - 1],
            'EMAIL' => sanitize_email($email),
            'CURRENCYCODE' => $easypay_pro_currency,
            'EXPDATE' => sanitize_text_field($expiry_month) . sanitize_text_field($expiry_year),
            'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
            'NOTIFYURL' => urlencode(add_query_arg(array('action' => 'wppd_ipnhandler', 'ipn_for' => 'ccard'), admin_url('admin-ajax.php'))),
            'CUSTOM' => ''
        );

        return $payment_fields_arr;
    }

    /**
     * function to prepare database fields to match the DB class requirement
     */
    public function wppd_prepare_fields_for_db($payment_fields_arr) {

        $payment_fields['amount'] = $payment_fields_arr['AMT'];
        $payment_fields['actualamount'] = $payment_fields_arr['L_AMT0'];

        $payment_fields['item_name'] = $payment_fields_arr['L_NAME0'];

        $payment_fields['email'] = $payment_fields_arr['EMAIL'];

        return $payment_fields;
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

        $is_email = $wppd_validation->is_email($payment_fields_arr['EMAIL']);

        if (!$is_email || $payment_fields_arr['EMAIL'] == '') {

            $wppd_error['email'] = "Please enter a valid email";
        }

        if (!is_numeric($payment_fields_arr['L_AMT0']) || $payment_fields_arr['L_AMT0'] == '') {

            $wppd_error['actualamount'] = "Please enter a valid amount";
        }

        if (!$wppd_validation->validateCVV($payment_fields_arr['ACCT'], $payment_fields_arr['CVV2'])) {
            $wppd_error['cvv'] = "Please enter a valid cvv for your card.";
        }

        if (!$wppd_validation->validateCCard($payment_fields_arr['ACCT'])) {
            $wppd_error['acct'] = "Please enter a valid card number.";
        }

        return $wppd_error;
    }

    /**
     * Adding credit card type identifier from http://wephp.co/detect-credit-card-type-php

     * Return credit card type if number is valid
     * @return string
     * @param $number string
     * */
    function wppd_credit_card_type($number) {
        $number = preg_replace('/[^\d]/', '', $number);
        if (preg_match('/^3[47][0-9]{13}$/', $number)) {
            return 'American Express';
        } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $number)) {
            return 'Diners Club';
        } elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/', $number)) {
            return 'Discover';
        } elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $number)) {
            return 'JCB';
        } elseif (preg_match('/^5[1-5][0-9]{14}$/', $number)) {
            return 'MasterCard';
        } elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)) {
            return 'Visa';
        } else {
            return 'Unknown';
        }
    }

    /**
     * Hide credit card details before storing into database
     */
    public function wppd_obfuscate_ccard($custom_fields) {

        $custom_fields['card_number'] = 'XXXX-XXXX-XXXX-' . substr($custom_fields['card_number'], -4);
        $custom_fields['cvv'] = '***';

        return $custom_fields;
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
        global $wppd_error;

        // unset unnecessary post data

        unset($post['payDirectSubmit']);
        unset($post['actualamount_option']);

        $easypay_options = $this->easypay_options;
        $wppd_form_action = $easypay_options['pro_settings']['easypay_pro_mode'];

        $payment_fields_arr = $this->wppd_prepare_gateway_fields($post);

        $wppd_error = $this->wppd_validate_gateway_fields($payment_fields_arr);

        $payment_fields_db = $this->wppd_prepare_fields_for_db($payment_fields_arr);

        $custom_fields = array_diff_key($post, $payment_fields_db);

        $custom_fields['gateway'] = $this->gateway_name;
        unset($custom_fields['payment_currency']);

        foreach ($custom_fields as $key => $value) {
            $custom_fields[$key] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
        }

        //push currency code in custom field
        $custom_fields['currency_code'] = $payment_fields_arr['CURRENCYCODE'];

        $hash = $wppd_security->generate_hash_code();
        //Set Auth Header

        if (count($wppd_error) == 0) {
            $insert_id = 0;

            $insert_id = $wppd_db->wppd_insert_payment_info($payment_fields_db, $this->wppd_obfuscate_ccard($custom_fields), $hash);
            if ($insert_id) {
                //disabling initiation email for paypal pro
                //$wppd_email->wppd_send_email('customer', 'wppd_customer_initiated', $insert_id);
            }

            $payment_url = $this->wppd_prepare_payment_url($payment_fields_arr, $insert_id, $hash, $wppd_form_action);
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $wppd_form_action);
            curl_setopt($ch, CURLOPT_POST, count($payment_fields_arr));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            parse_str(urldecode($result), $op_arr);
            //print_r($op_arr);

            if ($op_arr['ACK'] != 'Success') {
                foreach ($op_arr as $key => $val) {
                    if ($val == 'Warning' || $val == 'Error') {
                        $error_pos = str_replace('L_SEVERITYCODE', '', $key);
                        if ($op_arr['L_LONGMESSAGE' . $error_pos] != 'This transaction has been completed, but the total of items in the cart did not match the total of all items.') {
                            $wppd_error['gateway_error'][$error_pos] = $op_arr['L_LONGMESSAGE' . $error_pos];
                        }
                    }
                }
            }
            //echo $op_arr['ACK'];
            //print_r($_REQUEST);
            //var_dump(strpos(strtolower($op_arr['ACK']), 'success'));
            if (strpos(strtolower($op_arr['ACK']), 'success') !== false) {
                $args["msg"]="success";
                $args["redirect_url"]=add_query_arg( array('item_id' => $_REQUEST['itemname'] , 'rsamount'=> $_REQUEST['amount']), $this->easypay_options['easypay_success_url']);
                    
                echo json_encode($args);
                //header('Location: ' . $this->easypay_options['easypay_success_url']);
                exit;
            }
        }
        $this->errorUI($wppd_error);
       // print_r($wppd_error);
        //return $wppd_error;
    }
    
    function errorUI($wppd_error){
        $error_gateway="";
        if (count($wppd_error) > 0) {
            $error_gateway .= '<div class="alert alert-danger" role="alert"><strong> ' . __('Please fix the following error:', 'easypay') . ' </strong><ol>';

            foreach ($wppd_error as $key => $value) {
                if ($key == 'gateway_error') {
                    foreach ($wppd_error['gateway_error'] as $value) {
                        $error_gateway .= '<li>' . $value . '</li>';
                    }
                } else {
                    $error_gateway .= '<li>' . $value . '</li>';
                }
            }
            $error_gateway .= '</ol></div>';
        }
        echo $error_gateway;
    }

    /**
     * 
     */
    public function wppd_gateway_hidden_fields() {
        
    }

    /**
     * Update the options settings for the gateway
     * 
     * @param array $data  $_POST
     */
    public function wppd_update_gateway_settings($data) {

        $easypay_pro_enable = isset($data['easypay_pro_enable']) ? stripslashes($data['easypay_pro_enable']) : '';
        $easypay_pro_email = isset($data['easypay_pro_email']) ? stripslashes($data['easypay_pro_email']) : '';
        $easypay_pro_description = isset($data['easypay_pro_description']) ? stripslashes($data['easypay_pro_description']) : '';
        $easypay_pro_user = isset($data['easypay_pro_user']) ? stripslashes($data['easypay_pro_user']) : '';
        $easypay_pro_password = isset($data['easypay_pro_password']) ? stripslashes($data['easypay_pro_password']) : '';
        $easypay_pro_signature = isset($data['easypay_pro_signature']) ? stripslashes($data['easypay_pro_signature']) : '';
        $easypay_pro_mode = isset($data['easypay_pro_mode']) ? $data['easypay_pro_mode'] : '';
        $easypay_pro_fee = isset($data['easypay_pro_fee']) ? stripslashes($data['easypay_pro_fee']) : '';

        $pro_options = array('pro_settings' => array(
                'easypay_pro_enable' => $easypay_pro_enable,
                'easypay_pro_email' => $easypay_pro_email,
                'easypay_pro_description' => $easypay_pro_description,
                'easypay_pro_user' => $easypay_pro_user,
                'easypay_pro_password' => $easypay_pro_password,
                'easypay_pro_signature' => $easypay_pro_signature,
                'easypay_pro_mode' => $easypay_pro_mode,
                'easypay_pro_fee' => $easypay_pro_fee,
        ));
        $get_options = $this->easypay_options;
        $pro_options = array_merge((array) $get_options, $pro_options);
        return update_option('easypay_options', $pro_options);
    }

    /**
     * 	Function for handling Paypal IPN
     * 
     * 	@param  $request	(array)		Transaction data array
     * 	@return (void)  
     * 
     */
    function handle_ipn_req($post) {

        global $wppd_db;
        global $wppd_email;
        // getting the values of secure key & DB id where data need to be updated
        $custom_array = explode("---", $post['custom']);

        $row_id_txn = base64_decode($custom_array[0]);
        $hash = $custom_array[1];

        // verify the transaction response
        $is_varified = $this->wppd_verify_notification($post, $row_id_txn);

        // update database for the transaction details
        if ($is_varified)
            $is_db_updated = $wppd_db->wppd_update_payment_info($post, $row_id_txn, $hash);

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

        if ($easypay_options['pro_settings']['easypay_pro_mode'] == 'https://api-3t.sandbox.paypal.com/nvp') {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $url = 'https://www.paypal.com/cgi-bin/webscr';
        }
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

        if ($result == 'VERIFIED' && $data['payment_status'] == "Completed" && $data['receiver_email'] == $easypay_options['pro_settings']['easypay_pro_email'] && $this->is_unique_transaction($data['txn_id'])) {
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

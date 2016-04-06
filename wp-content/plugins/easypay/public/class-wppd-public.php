<?php

/**
 * The public-facing functionality of the plugin.
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    WPPD
 * @subpackage WPPD/public
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     *  Currrency array
     *  For more info:
     *  https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside
     * 
     */
    private $wppd_currency = array(
        'AUD' => 'Australian Dollar (A $)',
        'CAD' => 'Canadian Dollar (C $)',
        'EUR' => 'Euro (€)',
        'GBP' => 'British Pound (£)',
        'JPY' => 'Japanese Yen (¥)',
        'USD' => 'U.S. Dollar ($)',
        'NZD' => 'New Zealand Dollar ($)',
        'CHF' => 'Swiss Franc',
        'HKD' => 'Hong Kong Dollar ($)',
        'SGD' => 'Singapore Dollar ($)',
        'SEK' => 'Swedish Krona',
        'DKK' => 'Danish Krone',
        'PLN' => 'Polish Zloty',
        'NOK' => 'Norwegian Krone',
        'HUF' => 'Hungarian Forint',
        'CZK' => 'Czech Koruna',
        'ILS' => 'Israeli New Shekel',
        'MXN' => 'Mexican Peso',
        'BRL' => 'Brazilian Real (only for Brazilian members)',
        'MYR' => 'Malaysian Ringgit (only for Malaysian members)',
        'PHP' => 'Philippine Peso',
        'TWD' => 'New Taiwan Dollar',
        'THB' => 'Thai Baht',
        'TRY' => 'Turkish Lira (only for Turkish members)',
        'RUB' => 'Russian Ruble'
    );

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $plugin_name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->easypay_options = get_option('easypay_options');
    }
    

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        // Form display css

        wp_enqueue_style($this->plugin_name . '-style', plugin_dir_url(__FILE__) . 'css/wppd-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        //jQuery Validation
        wp_enqueue_script($this->plugin_name . '-validation', plugin_dir_url(__FILE__) . 'js/libs/jquery-validation/jquery.validate.min.js', array('jquery'), $this->version, false);

        //jQuery Validation
        wp_enqueue_script($this->plugin_name . '-validation-methods', plugin_dir_url(__FILE__) . 'js/libs/jquery-validation/additional-methods.min.js', array('jquery'), $this->version, false);
        //strip jquery 
        wp_enqueue_script($this->plugin_name . '-strip', 'https://js.stripe.com/v2/', array('jquery'), $this->version, false);
        // Form handler script
        wp_enqueue_script($this->plugin_name . '-script', plugin_dir_url(__FILE__) . 'js/wppd-public.js', array('jquery'), $this->version, false);

        // get paypal fees
        $wppd_options = get_option('easypay_options');
        $easypay_paypal_fee = isset($wppd_options['easypay_paypal_fee']) ? $wppd_options['easypay_paypal_fee'] : '2.9';

        $easypay_stripe_option = get_option('easypay_stripe_setting');
        $stripe_pk_key = isset($easypay_stripe_option['easypay_private_key']) ? $easypay_stripe_option['easypay_private_key'] : 'pk_test_y4Ot3N4ZDWL6jb2xH8nHABEp';

        // Localize
        $params = array(
            'loader_url' => plugin_dir_url(__FILE__) . 'images/icons/ajax-loader.gif',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'template_url' => get_bloginfo('template_directory'),
            'paypal_fee' => $easypay_paypal_fee,
        	'stripe_pk_key' => $stripe_pk_key,
        	'strip_success_url' => $this->easypay_options['easypay_success_url']
        );
        wp_localize_script($this->plugin_name . '-script', 'wppd_display', $params);
    }

    /* --------------------------------------------*
     * Render form for public interaction 
     * --------------------------------------------- */

    function wppd_render_form($atts) {

        // Array defined for containg error messages
        global $wppd_error;

        // get currency

        $easypay_options = get_option('easypay_options');
        $wppd_pay_currency = (!$easypay_options['easypay_allow_currency'])?$easypay_options['easypay_pay_currency']:'';

        // post data
        $actualamount = isset($_POST['actualamount']) ? $_POST['actualamount'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        $error_gateway = '';

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

        // start creating form
        $output_form = '';

        /**
         * 	Form Attributes
         */
        $wppd_form_start = $error_gateway . '<form action="" method="post" class="wppd-form">';

        $wppd_gateway_fields = '<fieldset>';

// add new option
        $wppd_gateway_fields .= '<div class="form-group component">
            <label class="col-lg-4 control-label">' . __('Payment Type', 'easypay') . '</label>
					<div class="input-block col-md-5">
					<label class="radio-inline" for="recurring"><input type="radio" name="paymenttype" id="paymenttype-recurring" value="recurring" maxlength="255">Recurring</label>
					<label class="radio-inline" for="normal"><input type="radio" name="paymenttype" id="paymenttype-onetime" value="onetime" maxlength="255" checked="checked">One Time Payment</label>					
            		</div>
					<input type="hidden" name="itemname" id="itemname" value="'.$_REQUEST['itemID'].'">
        </div>';
        
        $wppd_gateway_fields .= '<div class="form-group component">
            <label class="col-lg-4 control-label"></label>
					<div class="input-block col-md-5">' . __('Recurring payment Only for Paypal') . '</div>
         </div>';
        
        // creating array of static amounts
        $static_amounts = '';
        if ($easypay_options['easypay_st_amounts'] && $easypay_options['easypay_st_amount_enable']) {
            foreach ($easypay_options['easypay_st_amounts'] as $amount) {
                $static_amounts .= '<label class="radio-inline" for="amount-' . $amount . '">
      <input type="radio" name="actualamount_option" id="amount-' . $amount . '" value="' . $amount . '" maxlength="255">' . $amount . '</label>';
            }
        }

        // appending static amounts to the form

        $wppd_gateway_fields .= $static_amounts ? '<div class="form-group component"><label class="col-lg-4 control-label">' . __('Choose an amount', 'easypay') . '</label><div class="input-block ' . $easypay_options['easypay_amt_field_size'] . '">' . $static_amounts . '</div></div>' : '';

        // check if the amount text fieldf should be readonly

        $amt_readonly = $easypay_options['easypay_disable_custom_amount'] ? 'readonly="readonly"' : '';

        // appending amount text field to the form

        $wppd_gateway_fields .= '<div class="form-group component">
            <label class="col-lg-4 control-label">' . __('Amount (donation) *', 'easypay') . '</label><div class="input-block ' . $easypay_options['easypay_amt_field_size'] . '"><input type="text" class="form-control" name="actualamount" id="actualamount" placeholder="0.00" value="' . $actualamount . '" maxlength="10" ' . $amt_readonly . ' required />' . '&nbsp;' . $wppd_pay_currency . '
                    
                </div>
        </div>';

        // appending dynamically updating amount area with addition to paypal fees

        $wppd_gateway_fields .= '<div class="form-group component">
            <label class="col-lg-4 control-label">' . __('Total Amount to donate', 'easypay') . '</label>
                <div class="col-lg-8 input-block">
                    <span id="feesTotal">0.00</span>&nbsp;' . $wppd_pay_currency . '
                </div>
        </div>';

        // create custom currency select box
        // create currency options

        $currency_options = '';

        foreach ($this->wppd_currency as $key => $val) {
            $currency_options .= '<option value="' . $key . '" >' . $val . '</option>';
        }

        $currency_select = $easypay_options['easypay_allow_currency']?'<div class="form-group has-success"><label class="col-md-4 control-label" for="payment_currency">Select Currency</label>
                    <div class="col-md-6">
                        <select id="payment_currency" name="payment_currency" class="form-control" aria-invalid="false" required>' . $currency_options . '</select>
                  </div>
                </div>':'';

        // concatenate currency with gateway fields
         
        $wppd_gateway_fields .= $currency_select;
        // appending email field to the form

        $wppd_gateway_fields .= '<div class="form-group component">
            <label class="col-lg-4 control-label">' . __('Email *', 'easypay') . '</label>
                <div class="input-block ' . $easypay_options['easypay_email_field_size'] . '">
                    <input type="hidden" name="amount" id="amount" value="">
                    <input type="email" id="email" maxlength="255" class="form-control" name="email" placeholder="Email" value="' . $email . '" required />' . '</div>
        </div></fieldset>';

        // recrring fields
        global $wpdb;
        
        //sprintf("%s", $x);
        
        $choicedata = $wpdb->get_row("select * from {$wpdb->prefix}easypay_recurring ");
        //print_r($choicedata->choice2);
        $ch1 = explode(',',$choicedata->choice1);
        if($ch1[1] == 'D'){
        	$tpriod1 = 'day';
        } else{
        	$tpriod1 = 'Month';
        }
        $ch2 = explode(',',$choicedata->choice2);
        if($ch2[1] == 'D'){
        	$tpriod2 = 'day';
        } else{
        	$tpriod2 = 'Month';
        }
        $ch3 = explode(',',$choicedata->choice3);
        if($ch3[1] == 'M'){
        	$tpriod3 = 'Month';
        } else{
        	$tpriod3 = 'Year';
        }      
      $easypay_recurring_enable = $easypay_options['easypay_recurring_enable'];
        if($easypay_recurring_enable == 'true'){
        	$wppd_gateway_fields .= '<div class="form-group time-period">
		  <label class="col-md-4 control-label" for="period">Time Period </label>
		  <div class="col-md-5">
			<label class="radio-inline" for="period-0">
			  <input maxlength="255" name="period" id="period-0" value="'.$ch1[0].','.$ch1[1].'" checked="checked" type="radio">
			  '.$ch1[0].' '.$tpriod1.'
			</label>
			<label class="radio-inline" for="period-1">
			  <input maxlength="255" name="period" id="period-1" value="'.$ch2[0].','.$ch2[1].'" type="radio">
			  '.$ch2[0].' '.$tpriod2.'
			</label>
			<label class="radio-inline" for="period-2">
			  <input maxlength="255" name="period" id="period-2" value="'.$ch3[0].','.$ch3[1].'" type="radio">
			  '.$ch3[0].' '.$tpriod3.'
			</label>
		  </div>
		</div>';
        }       
        // get saved form from database

        $wppd_custom_form = trim(get_option('easypay_form'));

        /* Strip "<form>" tag from the saved optional form to append custom one */

        $wppd_custom_form = preg_replace('/<form(.*?)>/s', '', $wppd_custom_form); // Remove opening "<form>" tag
        $wppd_custom_form = preg_replace('/<\/form>/s', '', $wppd_custom_form); // Remove closing "</form>" tag
        $wppd_custom_form = '<fieldset id="builder-fields">' . $wppd_custom_form . '</fieldset>';

        $paypal_enabled = $easypay_options['easypay_paypal_enable'];
        $wppd_form_direct_submit = ($paypal_enabled) ? '<div class="radio">
  <label for="paypal"><input id="paypal" value="paypal" type="radio"  data-fee="'.$easypay_options['easypay_paypal_fee'].'" name="gateway" checked="checked" required><strong>' . __('PayPal', 'easypay') . '</strong></label></div>
      <div id="submit-paypal" class="gateway-form">
      <div class="panel panel-default">
                                <div class="panel-body">
                                 ' . __($easypay_options['easypay_paypal_description'], 'easypay') . '   </div>
                            </div>
                          </div>' : '';
        
        $easypay_stripe_setting = get_option('easypay_stripe_setting');
        $wppd_form_direct_submit1 = ($paypal_enabled) ? '<div class="radio">
  <label for="stripe"><input id="stripe" value="stripe" type="radio"  data-fee="0.0" name="gateway" required><strong>' . __('Stripe', 'easypay') . '</strong></label></div>
      <div id="submit-stripe" class="gateway-form">
      <div class="panel panel-default">
                                <div class="panel-body">
                                 ' . __($easypay_stripe_setting['easypay_strip_description'], 'easypay') . '   </div>'.$wppd_form_stripe = $this->wppd_strip_payment_form().'
                            </div>
                          </div>' : '';
        
        
        
        $pro_enabled = isset($easypay_options['pro_settings'])?$easypay_options['pro_settings']['easypay_pro_enable']:false;
        $wppd_form_credit_card = ($pro_enabled) ? $this->wppd_get_credit_card_form() : '';

        $wppd_form_stripe = $this->wppd_strip_payment_form();
        
        $payment_options = '<fieldset><legend>' . __('Choose Payment Method', 'easypay') . '</legend>' . $wppd_form_direct_submit . $wppd_form_credit_card . $wppd_form_direct_submit1 . '</fieldset>';
        
        $wppd_payment_submit = '<fieldset><legend>&nbsp;</legend><div class="form-group component">
		  		<label class="control-label col-lg-4">&nbsp;</label>
				<div class="input-block col-lg-8">
				<!--image id="loadingTxt" style="visibility: hidden;" src="http://cms.thesparxitsolutions.com/cms_charitytheme/wp-content/plugins/easypay/public/images/icons/loading.GIF"/--->
				<input type="Submit" id="payDirectSubmit" class="pull-right col-lg-4 btn btn-default" name="payDirectSubmit" value="' . __('Donate Now', 'easypay') . '" />
				</div></div></fieldset>';

        $wppd_form_close = '</form>';


        /**
         * 	Final form layout
         */
        $output_form = $wppd_form_start . $wppd_gateway_fields . $wppd_custom_form . $payment_options . $wppd_payment_submit . $wppd_form_close;

        return $output_form;
    }

    /**
     * Function to get credit cards payments form
     */
    public function wppd_get_credit_card_form() {

        $easypay_options = get_option('easypay_options');
        $exp_year_options = '';

        $i = date("Y") - 1;
        $max = $i + 16;
        while ($i <= $max) {
            $exp_year_options .= "<option value='$i'>$i</option>";
            $i++;
        }

        $fieldset = '<div class="radio">
  <label for="ccard"><input id="ccard" value="ccard" data-fee="'.$easypay_options['pro_settings']['easypay_pro_fee'].'" type="radio" name="gateway" required><strong>' . __('Credit Card', 'easypay') . '</strong></label></div>
      <div id="submit-ccard" class="gateway-form">
      <div class="panel panel-default">
                                <div class="panel-body">
                                 ' . __($easypay_options['pro_settings']['easypay_pro_description'], 'easypay') . '   </div>
                            </div>
      <div class="form-group">
        <label class="col-sm-4 control-label" for="card_holder_name">' . __('Name on Card', 'easypay') . '</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="card_holder_name" id="card_holder_name" placeholder="' . __('Card Holders Name', 'easypay') . '" required="#ccard:checked">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label" for="card_number">' . __('Card Number', 'easypay') . '</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="card_number" id="card_number" placeholder="' . __('Debit/Credit Card Number', 'easypay') . '" required="#ccard:checked" creditcard="true" value="" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label" for="expiry_month">' . __('Expiration Date', 'easypay') . '</label>
        <div class="col-sm-8">
          <div class="row">
            <div class="col-xs-3">
              <select class="form-control col-sm-2" name="expiry_month" id="expiry_month" required="#ccard:checked">
                <option value="01">' . __('Jan', 'easypay') . ' (01)</option>
                <option value="02">' . __('Feb', 'easypay') . ' (02)</option>
                <option value="03">' . __('Mar', 'easypay') . ' (03)</option>
                <option value="04">' . __('Apr', 'easypay') . ' (04)</option>
                <option value="05">' . __('May', 'easypay') . ' (05)</option>
                <option value="06">' . __('June', 'easypay') . ' (06)</option>
                <option value="07">' . __('July', 'easypay') . ' (07)</option>
                <option value="08">' . __('Aug', 'easypay') . ' (08)</option>
                <option value="09">' . __('Sep', 'easypay') . ' (09)</option>
                <option value="10">' . __('Oct', 'easypay') . ' (10)</option>
                <option value="11">' . __('Nov', 'easypay') . ' (11)</option>
                <option value="12">' . __('Dec', 'easypay') . ' (12)</option>
              </select>
            </div>
            <div class="col-xs-3">
              <select class="form-control" name="expiry_year" required="#ccard:checked">' . $exp_year_options . '</select>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label" for="cvv">' . __('Card CVV', 'easypay') . '</label>
        <div class="col-sm-3">
          <input type="password" class="form-control" name="cvv" id="cvv" placeholder="' . __('Security Code', 'easypay') . '" required="#ccard:checked">
        </div>
      </div>
    </div>';

        return $fieldset;
    }
    
    /**
     * Function to get stripe payments form
     */
    public function wppd_strip_payment_form(){
    	$exp_year_options_strip = '';
    	$exp_year_options = '';
    
    	$i = date("Y");
    	$max = $i + 16;
    	while ($i <= $max) {
    		$exp_year_options_strip .= "<option value='$i'>$i</option>";
    		$i++;
    	}
    
    	$strip_form = '
          
    
    
             <div class="form-group component">
              <label class="col-lg-4 control-label" for="card_type_stripe">'. __( 'Card Type', 'easypay') .'</label>
                <div class="col-md-5">
                    <select name="card_type_stripe" class="form-control" required >
					  <option value="Visa">Visa</option>
					  <option value="MasterCard">MasterCard</option>
					  <option value="JCB">JCB</option>
					  <option value="Discover">Discover</option>
					  <option value="Amex">Amex</option>
					  <option value="Diners Club">Diners Club / Carte Blanche</option>
                    </select>
                 </div>
                </div>
    
            <div class="form-group component">
              <label class="col-lg-4 control-label" for="cardno_stripe">'. __( 'Card No.', 'easypay') .'</label>
                <div class="col-md-5">
                    <input type="text" id="cardno_stripe" class="form-control" name="cardno_stripe" value="" required />' . '</div>
                </div>
    
             <div class="form-group component">
              <label class="col-lg-4 control-label" for="expmonth_stripe">'. __( 'Expiration date', 'easypay') .'</label>
                <div class="col-md-5">
                <select name="expmonth_stripe" data-stripe="exp-month" id="expmonth_stripe" class="form-control" required >
                    <option value="">Month</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
               </select>
                    <select name="expyear_stripe" id="expyear_stripe" class="form-control" required >
                      <option value="">Year</option>'.$exp_year_options_strip.'
    
                   </select>
                 </div>
                </div>
    <div class="form-group component">
              <label class="col-lg-4 control-label" for="cardcvv">'. __( 'Card CVV', 'easypay') .'</label>
                <div class="col-md-5">
                    <input type="text" id="cardcvv_stripe" class="form-control" name="cardcvv_stripe" value="" required />' . '</div>
                </div>';
    
    	return $strip_form;
    }
    /**
     * Initiate payment when gateway variables are set in the $_POST 
     */
    public function wppd_initiate_payment() {
        global $wppd_gateway;
        global $wppd_error;
        $gateway = isset($_POST['gateway']) ? $_POST['gateway'] : '';
        if ($gateway) {
            $wppd_gateway = $this->wppd_get_gateway_obj($gateway);

            $wppd_error = $wppd_gateway->wppd_send_to_gateway($_POST);
        }
    }

    /**
     * Paypal IPN Retry Button
     */
    public function wppd_build_retry_button() {

        global $wppd_db;

        if (isset($_GET['payment_id']) && $_GET['payment_id'] != '') {
            $payment_id = base64_decode($_GET['payment_id']);
            $wppd_payment_details = $wppd_db->wppd_get_payment_info($payment_id);
        } else {
            return;
        }

        if ($wppd_payment_details['payment_status'] == 'Completed')
            return;

        /** action page
         * @todo create handler for getting options for wppd
         */
        $easypay_options = get_option('easypay_options');
        $wppd_form_action = $easypay_options['easypay_pay_mod'];

        if ($payment_id) {

            //set form fields
            $wppd_form_attributes = '<form action="" method="post" class="wppd-form"><fieldset>';

            $wppd_form_close = '<div class="form-group component">
		  		<label class="control-label col-lg-4">&nbsp;</label>
				<div class="input-block col-lg-8">
				<input type="Submit" id="" class="col-lg-8 btn btn-default" name="payDirectSubmit" value="' . __('Retry', 'easypay') . '" />
				</div>
                            </div>';
            $wppd_form_close .= '</fieldset></form>';

            $wppd_payment_fields = '';
            $wppd_payment_fields .= '<input type="hidden" name="amount" value="' . $wppd_payment_details['payment_amount'] . '">';
            $wppd_payment_fields .= '<input type="hidden" name="actualamount" value="' . $wppd_payment_details['payment_actual_amount'] . '">';
            $wppd_payment_fields .= '<input type="hidden" name="email" value="' . $wppd_payment_details['email'] . '">';
            $wppd_payment_fields .= '<input type="hidden" name="gateway" value="paypal">';


            $output_form = $wppd_form_attributes . $wppd_payment_fields . $wppd_form_close;
        } else {
            return;
        }

        return $output_form;
    }

    public function wppd_retry() {
        if (isset($_GET['payment_id'])) {
            return $this->wppd_build_retry_button();
        }
    }

    /*
     * Register wppd shortcode to display the form
     */

    function wppd_shortcode() {
        add_shortcode('EASYPAY_FORM', array(&$this, 'wppd_render_form'));
        add_shortcode('EASYPAY_RETRY_BUTTON', array(&$this, 'wppd_retry'));
    }

    /* ---------------------------------------------*
     * 		Paypal IPN handler
     * --------------------------------------------- */

    function wppd_payment_notification_handler() {
        $gateway = $_REQUEST['ipn_for'];
        $wppd_gateway = $this->wppd_get_gateway_obj($gateway);
        $wppd_gateway->handle_ipn_req($_POST);
        
        /**
         * Used in charity theme for donation
         * @version charity 1.0 
         */
        
        do_action("charity_easy_pay_ipn", $_REQUEST);
        //$this->donationPaymentUpdate($_POST);
        die();
    }
    	


    public function wppd_get_gateway_obj($gateway) {

        switch ($gateway) {
            case 'paypal':
                // Paypal Standard
                require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-paypal-standard.php';
                $wppd_gateway = new WPPD_Paypal_Standard();
                break;
            case 'ccard':
                // Paypal Pro
                require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-paypal-pro.php';
                $wppd_gateway = new WPPD_Paypal_Pro();
                break;
            case 'stripe':
                // Strip Standard
                require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-stripe.php';
                $wppd_gateway = new WPPD_Stripe();
                break;
            default:
                $wppd_gateway = false;
                break;
        }

        return $wppd_gateway;
    }

}

<?php

require_once 'library/config.php';
require_once 'library/common.php';
require_once 'gateway-buckaroo.php';
require_once(dirname(__FILE__) . '/library/api/paymentmethods/afterpay/afterpay.php');
class WC_Gateway_Buckaroo_Afterpay extends WC_Gateway_Buckaroo {
    var $type;
    var $b2b;
    var $showpayproc;
    var $vattype;
    function __construct() {
        global $woocommerce;

        $this->id = 'buckaroo_afterpay';
        $this->title = 'AfterPay';//$this->settings['title_paypal'];
        $this->icon 		= apply_filters('woocommerce_buckaroo_paypal_icon', plugins_url('library/buckaroo_images/24x24/afterpay.jpg', __FILE__));
        $this->has_fields 	= false;
        $this->method_title = 'Buckaroo AfterPay';
        $this->description = "Betaal met AfterPay";

        parent::__construct();

        $this->supports           = array(
            'products',
            'refunds'
        );
        $this->type = $this->settings['service'];
        $this->b2b = $this->settings['enable_bb'];
        $this->vattype = $this->settings['vattype'];
        $this->notify_url = home_url('/');

        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '<' ) ) {

        } else {
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_api_wc_gateway_buckaroo_sepadirectdebit', array( $this, 'response_handler' ) );
            if ($this->showpayproc) add_action( 'woocommerce_thankyou_buckaroo_sepadirectdebit' , array( $this, 'thankyou_description' ) );
            $this->notify_url   = add_query_arg('wc-api', 'WC_Gateway_Buckaroo_SepaDirectDebit', $this->notify_url);
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
        /* @var $order WC_Order */
        $order = wc_get_order( $order_id );
        if ( ! $this->can_refund_order( $order ) ) {
            return new WP_Error('error_refund_trid', __("Refund failed: Order not in ready state, Buckaroo transaction ID do not exists."));
        }
        if ($order->get_total() != $amount) {
            return new WP_Error('error_refund_full_amount', __("Refund failed: Only full amount can be refunded for AfterPay. Partial amount can be refunded using Buckaroo Payment Plaza."));
        }
        update_post_meta($order_id, '_pushallowed', 'busy');
        $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';
        $order = wc_get_order( $order_id );
        $afterpay = new AfterPay($this->type);
        $afterpay->amountDedit = 0;
        $afterpay->amountCredit = $amount;
        $afterpay->currency = $this->currency;
        $afterpay->description = $reason;
        if ($this->mode=='test') {
            $afterpay->invoiceId = 'buckaroowootest_'.(string)$order_id;
        }
        $afterpay->orderId = $order_id;
        $afterpay->OriginalTransactionKey = $order->get_transaction_id();
        $afterpay->returnUrl = $this->notify_url;
        try {
            $response = $afterpay->Refund();
        } catch (exception $e) {
            update_post_meta($order_id, '_pushallowed', 'ok');
        }
        return fn_buckaroo_process_refund($response, $order, $amount, $this->currency);
    }

    function process_payment($order_id) {
        global $woocommerce;

        $GLOBALS['plugin_id'] = $this->plugin_id . $this->id . '_settings';

        $afterpay = new AfterPay($this->type);
        $order = new WC_Order( $order_id );
        if (method_exists($order, 'get_order_total')) {
            $afterpay->amountDedit = $order->get_order_total();
        } else {
            $afterpay->amountDedit = $order->get_total();
        }
        $afterpay->currency = $this->currency;
        $afterpay->description = $this->transactiondescription;
        $afterpay->invoiceId = (string)$order_id;
        if ($this->mode=='test') {
            $afterpay->invoiceId = 'buckaroowootest_'.(string)$order_id;
        }
        $afterpay->orderId = (string)$order_id;

        $afterpay->BillingGender = $_POST['buckaroo-afterpay-gender'];
        $afterpay->BillingInitials = $this->getInitials($order->billing_first_name);
        $afterpay->BillingLastName = !empty($order->billing_last_name) ? $order->billing_last_name : '';
        $birthdate = $_POST['buckaroo-afterpay-birthdate'];
        if (!$this->validateDate($birthdate,'Y-m-d')){
            wc_add_notice( __("Please enter correct birthdate date", 'woocommerce'), 'error' );
            return;
        }
        if (empty($_POST["buckaroo-afterpay-accept"])) {
            wc_add_notice( __("Please accept licence agreements", 'woocommerce'), 'error' );
            return;
        }
        $shippingCosts = $order->get_total_shipping();
        if (floatval($shippingCosts) > 0) {
            $afterpay->ShippingCosts = $shippingCosts;
        }
        if (!empty($_POST["buckaroo-afterpay-CompanyCOCRegistration"]))
        {
            if (empty($_POST["buckaroo-afterpay-CompanyName"])) {
                wc_add_notice( __("Company name is required", 'woocommerce'), 'error' );
                return;
            }
            if (empty($_POST["buckaroo-afterpay-CostCentre"])) {
                wc_add_notice( __("Cost center of the order is requuired", 'woocommerce'), 'error' );
                return;
            }
            if (empty($_POST["buckaroo-afterpay-VatNumber"])) {
                wc_add_notice( __("VAT (BTW) number is required", 'woocommerce'), 'error' );
                return;
            }
            $afterpay->B2B = 'TRUE';
            $afterpay->CompanyCOCRegistration = $_POST["buckaroo-afterpay-CompanyCOCRegistration"];
            $afterpay->CompanyName = $_POST["buckaroo-afterpay-CompanyName"];
            $afterpay->CostCentre = $_POST["buckaroo-afterpay-CostCentre"];
            $afterpay->VatNumber = $_POST["buckaroo-afterpay-VatNumber"];
        }

        $afterpay->BillingBirthDate = date('Y-m-d', strtotime($birthdate));
        $address_components = fn_buckaroo_get_address_components($order->billing_address_1." ".$order->billing_address_2);
        $afterpay->BillingStreet = $address_components['street'];
        $afterpay->BillingHouseNumber = $address_components['house_number'];
        $afterpay->BillingHouseNumberSuffix = $address_components['number_addition'];
        $afterpay->BillingPostalCode = $order->billing_postcode;
        $afterpay->BillingCity = $order->billing_city;
        $afterpay->BillingCountry = $order->billing_country;
        $afterpay->BillingEmail = !empty($order->billing_email) ? $order->billing_email : '';
        $afterpay->BillingLanguage = 'nl';
        $number = $this->cleanup_phone($order->billing_phone);
        $afterpay->BillingPhoneNumber = $number['phone'];


        $afterpay->AddressesDiffer = 'FALSE';
        if (!empty($_POST["buckaroo-afterpay-shipping-differ"])) {
            $afterpay->AddressesDiffer = 'TRUE';
            $afterpay->ShippingGender = $_POST['buckaroo-afterpay-shipping-gender'];
            $afterpay->ShippingInitials = $this->getInitials($order->shipping_first_name);
            $afterpay->ShippingLastName = !empty($order->shipping_last_name) ? $order->shipping_last_name : '';
            $birthdateshipping = $_POST['buckaroo-afterpay-shipping-birthdate'];
            if (!$this->validateDate($birthdateshipping,'Y-m-d')){
                wc_add_notice( __("Please enter correct shipping person birthdate date", 'woocommerce'), 'error' );
                return;
            }
            $afterpay->ShippingBirthDate = date('Y-m-d', strtotime($birthdateshipping));
            $address_components = fn_buckaroo_get_address_components($order->shipping_address_1." ".$order->shipping_address_2);
            $afterpay->ShippingStreet = $address_components['street'];
            $afterpay->ShippingHouseNumber = $address_components['house_number'];
            $afterpay->ShippingHouseNumberSuffix = $address_components['number_addition'];
            $afterpay->ShippingPostalCode = $order->shipping_postcode;
            $afterpay->ShippingCity = $order->shipping_city;
            $afterpay->ShippingCountryCode = $order->shipping_country;
            $afterpay->ShippingEmail = !empty($order->shipping_email) ? $order->shipping_email : '';
            $afterpay->ShippingLanguage = 'nl';
            $number = $this->cleanup_phone($order->shipping_phone);
            $afterpay->ShippingPhoneNumber = $number['phone'];
        }
        if ($this->type == 'afterpayacceptgiro') {

            if (empty($_POST["buckaroo-afterpay-CustomerAccountNumber"])) {
                wc_add_notice( __("IBAN is required", 'woocommerce'), 'error' );
                return;
            }
            $afterpay->CustomerAccountNumber = $_POST["buckaroo-afterpay-CustomerAccountNumber"];
        }

        $afterpay->CustomerIPAddress = $_SERVER["REMOTE_ADDR"];
        $afterpay->Accept = 'TRUE';
        $products = Array();
        $items = $order->get_items();
        foreach ( $items as $item ) {
            $product = new WC_Product($item['product_id']);
            $tax_class = $product->get_attribute("vat_category");
            if (empty($tax_class)){
                $tax_class = $this->vattype;
                //wc_add_notice( __("Vat category (vat_category) do not exist for product ", 'woocommerce').$item['name'], 'error' );
               // return;
            }
            $tmp["ArticleDescription"] = $item['name'];
            $tmp["ArticleId"] = $item['product_id'];
            $tmp["ArticleQuantity"] = $item["qty"];
            $tmp["ArticleUnitprice"] = $item["line_total"]/$item["qty"];
            $tmp["ArticleVatcategory"] = $tax_class;
            $products[] = $tmp;
        }
        $afterpay->returnUrl = $this->notify_url;
        $response = $afterpay->PayAfterpay($products);
        return fn_buckaroo_process_response($this, $response, $this->mode);
    }

    function payment_fields() {
        $accountname = get_user_meta( $GLOBALS["current_user"]->ID, 'billing_first_name', true )." ".get_user_meta( $GLOBALS["current_user"]->ID, 'billing_last_name', true );
        $post_data = Array();
        if (!empty($_POST["post_data"])) {
            parse_str($_POST["post_data"], $post_data);
        }
        ?>
        <?php if ($this->mode=='test') : ?><p><?php _e('TEST MODE', 'woocommerce'); ?></p><?php endif; ?>
        <?php if ($this->description) : ?><p><?php echo wpautop(wptexturize($this->description)); ?></p><?php endif; ?>

        <fieldset>
            <p class="form-row">
                <label for="buckaroo-afterpay-gender"><?php echo _e('Gender:', 'woocommerce')?><span class="required">*</span></label>
                <input id="buckaroo-afterpay-genderm" name="buckaroo-afterpay-gender" class="" type="radio" value="1" checked /> Male &nbsp;
                <input id="buckaroo-afterpay-genderf" name="buckaroo-afterpay-gender" class="" type="radio" value="2"/> Female
            </p>
            <p class="form-row form-row-wide validate-required">
                <label for="buckaroo-afterpay-birthdate"><?php echo _e('Birthdate:', 'woocommerce')?><span class="required">*</span></label>
                <input id="buckaroo-afterpay-birthdate" name="buckaroo-afterpay-birthdate" class="input-text" type="text" maxlength="250" autocomplete="off" value="" placeholder="YYYY-MM-DD" />
            </p>
        <?php if (!empty($post_data["ship_to_different_address"])) { ?>
            <p class="form-row">
                <label for="buckaroo-afterpay-shipping-gender"><?php echo _e('Shipping person gender:', 'woocommerce')?><span class="required">*</span></label>
                <input id="buckaroo-afterpay-shipping-genderm" name="buckaroo-afterpay-shipping-gender" class="" type="radio" value="1" checked /> Male &nbsp;
                <input id="buckaroo-afterpay-shipping-genderf" name="buckaroo-afterpay-shipping-gender" class="" type="radio" value="2"/> Female
                <input id="buckaroo-afterpay-shipping-differ" name="buckaroo-afterpay-shipping-differ" class="" type="hidden" value="1"/>
            </p>
            <p class="form-row form-row-wide validate-required">
                <label for="buckaroo-afterpay-shipping-birthdate"><?php echo _e('Shipping person birthdate:', 'woocommerce')?><span class="required">*</span></label>
                <input id="buckaroo-afterpay-shipping-birthdate" name="buckaroo-afterpay-shipping-birthdate" class="input-text" type="text" maxlength="250" autocomplete="off" value="" placeholder="YYYY-MM-DD" />
            </p>
        <?php } ?>
            <?php if ($this->type == 'afterpayacceptgiro')  { ?>
                <p class="form-row form-row-wide validate-required">
                    <label for="buckaroo-afterpay-CustomerAccountNumber"><?php echo _e('IBAN:', 'woocommerce')?><span class="required">*</span></label>
                    <input id="buckaroo-afterpay-CustomerAccountNumber" name="buckaroo-afterpay-CustomerAccountNumber" class="input-text" type="text" value="" />
                </p>
            <?php } ?>
            <?php if ($this->b2b == 'enable' && $this->type== 'afterpaydigiaccept') { ?>
            <p class="form-row form-row-wide validate-required">
                <?php echo _e('Fill required fields if bill in on the company:', 'woocommerce')?>
            </p>
            <p class="form-row form-row-wide validate-required">
                <label for="buckaroo-afterpay-CompanyCOCRegistration"><?php echo _e('COC (KvK) number:', 'woocommerce')?></label>
                <input id="buckaroo-afterpay-CompanyCOCRegistration" name="buckaroo-afterpay-CompanyCOCRegistration" class="input-text" type="text" maxlength="250" autocomplete="off" value="" />
            </p>

            <p class="form-row form-row-wide validate-required">
                <label for="buckaroo-afterpay-CompanyName"><?php echo _e('Name of the organization.:', 'woocommerce')?></label>
                <input id="buckaroo-afterpay-CompanyName" name="buckaroo-afterpay-CompanyName" class="input-text" type="text" maxlength="250" autocomplete="off" value="" />
            </p>

            <p class="form-row form-row-wide validate-required">
                <label for="buckaroo-afterpay-CostCentre"><?php echo _e('Cost center of the order (this will be visible on the invoice):', 'woocommerce')?></label>
                <input id="buckaroo-afterpay-CostCentre" name="buckaroo-afterpay-CostCentre" class="input-text" type="text" maxlength="250" autocomplete="off" value="" />
            </p>

            <p class="form-row form-row-wide validate-required">
                <label for="buckaroo-afterpay-VatNumber"><?php echo _e('VAT (BTW) number:', 'woocommerce')?></label>
                <input id="buckaroo-afterpay-VatNumber" name="buckaroo-afterpay-VatNumber" class="input-text" type="text" maxlength="250" autocomplete="off" value="" />
            </p>
            <?php } ?>
            <p class="form-row form-row-wide validate-required">
                <?php echo _e('Accept licence agreement:', 'woocommerce')?><span class="required">*</span> <input id="buckaroo-afterpay-accept" name="buckaroo-afterpay-accept" type="checkbox" value="ON" />
            </p>
        </fieldset>
    <?php
    }
    /**
     * Check response data
     */

    public function response_handler() {
        global $woocommerce;
        fn_buckaroo_process_response($this);
        exit;
    }

    function init_form_fields() {

        parent::init_form_fields();
        $this->form_fields['service'] = array(
            'title' => __( 'Select afterpay service', 'woocommerce' ),
            'type' => 'select',
            'description' => __( 'Please select the service', 'woocommerce' ),
            'options' => array('afterpayacceptgiro'=>'Offer customer to pay afterwards by SEPA Direct Debit.', 'afterpaydigiaccept'=>'Offer customer to pay afterwards by digital invoice.'),
            'default' => 'afterpaydigiaccept');

        $this->form_fields['enable_bb'] = array(
            'title' => __( 'Enable B2B option for AfterPay', 'woocommerce' ),
            'type' => 'select',
            'description' => __( 'Enables or disables possibility to pay using company credentials', 'woocommerce' ),
            'options' => array('enable'=>'Enable', 'disable'=>'Disable'),
            'default' => 'disable');

        $this->form_fields['vattype'] = array(
            'title' => __( 'Default product Vat type', 'woocommerce' ),
            'type' => 'select',
            'description' => __( 'Please select default vat type for your products', 'woocommerce' ),
            'options' => array('1'=>'1 = High rate',
                               '2'=>'2 = Low rate',
                                '3'=>'3 = Zero rate',
                                '4'=>'4 = Null rate',
                                '5'=>'5 = middle rate'),
            'default' => '1');
    }
}
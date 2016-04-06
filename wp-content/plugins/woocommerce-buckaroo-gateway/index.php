<?php
/*
Plugin Name: WC Buckaroo BPE 3.0 Gateway
Plugin URI: http://www.buckaroo.nl
Author: <plugins@buckaroo.nl>
Author URI: http://www.buckaroo.nl
Description: Buckaroo payment system plugin for WooCommerce version <strong><u>2.3.x</u></strong>.
Version: 2.0.1

*/
add_action('plugins_loaded', 'woocommerce_buckaroo_init', 0);
add_action('admin_menu', 'my_menu');
include_once( 'install/class-wcb-install.php' );
register_activation_hook( __FILE__, array( 'WCB_Install', 'install' ) );

function my_menu() {
    add_menu_page('Buckaroo plugin report', 'Buckaroo report', 'manage_options', 'buckaroo-report', 'buckaroo_reports');
}

function buckaroo_reports() {
    echo '<h1>Error report for Buckaroo WooCommerce plugin</h1>';
    echo '<table class="wp-list-table widefat fixed posts">
    <tr>
        <th width="5%"><b>Error no</b></th>
        <th width="15%"><b>Time</b></th>
        <th width="80%"><b>Error description</b></th>
    </tr>';
    $plugin_dir = plugin_dir_path(__FILE__);
    $file = $plugin_dir . 'library/api/log/report_log.txt';
    if (file_exists($file)) {
        $data = Array();
        $handle = @fopen($plugin_dir . 'library/api/log/report_log.txt', "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $data[] = $buffer;
            }
            fclose($handle);
        }
        if (!empty($data)) {
            $data = array_reverse($data);
            $i = 1;
            foreach($data as $d) {
                $tmp = explode("|||", $d);
                if (!empty($tmp[1])) {
                    list ($time, $value) = $tmp;
                } else {
                    $time = 'unknown';
                    $value = $d;
                }
                echo '<tr>
                <td>'.$i.'</td>
                <td>'.$time.'</td>
                <td>'.$value.'</td>
                </tr>';
                $i++;
            }
        } else {
            echo '<tr>
        <td colspan="3">Log file empty</td>
        </tr>';

        }
    } else {
        echo '<tr>
        <td colspan="3">No data</td>
        </tr>';
    }

    echo '</table>';
}
$buckaroo_enabled_payment_methods = array(
    //comment payment methods you do not want to use
    'PayPal' => array('filename' =>'gateway-buckaroo-paypal.php', 'classname' => 'WC_Gateway_Buckaroo_Paypal',),
    'iDeal' => array('filename' =>'gateway-buckaroo-ideal.php', 'classname' => 'WC_Gateway_Buckaroo_Ideal',),
    'Creditcards' => array('filename' =>'gateway-buckaroo-creditcard.php', 'classname' => 'WC_Gateway_Buckaroo_Creditcard',),
    'Bancontact / MisterCash' => array('filename' =>'gateway-buckaroo-mistercash.php', 'classname' => 'WC_Gateway_Buckaroo_MisterCash',),
    'Giropay' => array('filename' =>'gateway-buckaroo-giropay.php', 'classname' => 'WC_Gateway_Buckaroo_Giropay',),
    'Bank Transfer' => array('filename' =>'gateway-buckaroo-transfer.php', 'classname' => 'WC_Gateway_Buckaroo_Transfer',),
    'Giftcards' => array('filename' =>'gateway-buckaroo-giftcard.php', 'classname' => 'WC_Gateway_Buckaroo_Giftcard',),
    'eMaestro' => array('filename' =>'gateway-buckaroo-emaestro.php', 'classname' => 'WC_Gateway_Buckaroo_EMaestro',),
    'Paysafecard' => array('filename' =>'gateway-buckaroo-paysafecard.php', 'classname' => 'WC_Gateway_Buckaroo_Paysafecard',),
    'Sofortbanking' => array('filename' =>'gateway-buckaroo-sofort.php', 'classname' => 'WC_Gateway_Buckaroo_Sofortbanking',),
    'SepaDirectDebit' => array('filename' =>'gateway-buckaroo-sepadirectdebit.php', 'classname' => 'WC_Gateway_Buckaroo_SepaDirectDebit',),
    'AfterPay' => array('filename' =>'gateway-buckaroo-afterpay.php', 'classname' => 'WC_Gateway_Buckaroo_AfterPay',),
    'PaymentGuarantee' => array('filename' =>'gateway-buckaroo-paygarant.php', 'classname' => 'WC_Gateway_Buckaroo_PayGarant',),
    'PaymentGuaranteeByJuno' => array('filename' =>'gateway-buckaroo-paygarantbyjuno.php', 'classname' => 'WC_Gateway_Buckaroo_PayGarantByJuno',),
);

function woocommerce_buckaroo_init() {
        global $buckaroo_enabled_payment_methods;
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) { return; }
	$plugin_dir = plugin_dir_path(__FILE__);
	
        foreach($buckaroo_enabled_payment_methods as $method)
        {
            require_once $plugin_dir . $method['filename'];
        }
	require_once $plugin_dir . 'push-buckaroo.php';
        /**
 	* Add the Gateway to WooCommerce
 	**/
	function add_buckaroo_gateway($methods) {
            global $buckaroo_enabled_payment_methods;
            foreach($buckaroo_enabled_payment_methods as $method)
            {
		$methods[] = $method['classname'];
            }
            return $methods;
	}
	
	add_filter('woocommerce_payment_gateways', 'add_buckaroo_gateway' );

} 

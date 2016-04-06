<?php

/**
 * Fired during plugin activation
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WPPD
 * @subpackage WPPD/includes
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {


        // Create payments log table
        self::register_wppd_db_table();

        // Create uploads directory

        self::create_wppd_uploads_directory();

        /**
         * Initialize plugin options if not already set
         */
        //general settings
        if (!get_option('easypay_options')) {
            add_option('easypay_options', array(
                'easypay_paypal_enable' => 'true',
                'easypay_user_defined_name' => '',
                'easypay_business' => '',
                'easypay_pay_mod' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
                'easypay_success_url' => '',
                'easypay_fail_url' => '',
                'easypay_pay_currency' => 'USD',
                'easypay_paypal_fee' => '2.9'
                    )
            );
        }
        // Optional Form
        if (!get_option('easypay_form')) {
            add_option('easypay_form', '');
        }

        // Optional Form Builder JSON
        if (!get_option('easypay_form_builder')) {
            add_option('easypay_form_builder', '[]');
        }
    }

    public static function register_wppd_db_table() {

        global $wpdb;
        global $charset_collate;

        $sql_create_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}easypay_payment_log (
								`id` int(6) NOT NULL AUTO_INCREMENT,
  								`txnid` varchar(255) NOT NULL,
                                                                `payment_amount` decimal(7,2) NOT NULL,
                                                                `payment_actual_amount` decimal(7,2) NOT NULL,
                                                                `payment_fees_rate` decimal(7,2) NOT NULL,
                                                                `payment_currency` varchar(10) NOT NULL,
                                                                `payment_gateway` varchar(25) NOT NULL,
  								`payment_status` varchar(25) NOT NULL,
  								`itemid` text NOT NULL,
  								`itemname` varchar(255) NOT NULL,
  								`createdtime` datetime NOT NULL,
  								`email` varchar(50) NOT NULL,
  								`hash` varchar(100),
  								`custom` LONGTEXT NULL,
  								`transaction_info` LONGTEXT NULL,
  								PRIMARY KEY (`id`)
							) $charset_collate; ";
        
        $sql_create_table2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}easypay_recurring (
        `ch_id` int(11) NOT NULL AUTO_INCREMENT,
        `option_id` int(11) NOT NULL,
        `choice1` varchar(255) NOT NULL,
        `choice2` varchar(255) NOT NULL,
        `choice3` varchar(255) NOT NULL,
        PRIMARY KEY (`ch_id`)
        ) $charset_collate2; ";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql_create_table);
        dbDelta($sql_create_table2);
  //jal_install_data();
  $table_name = $wpdb->prefix . 'easypay_recurring';
	$wpdb->insert( 
		$table_name, 
		array( 
			'ch_id' => 1, 
			'option_id' => 0, 
			'choice1' => '1,D',
			'choice2' => '1,M',
			'choice3' => '1,M',
		) 
	);
    }
 /*   
Public function jal_install_data() {
	global $wpdb;	
	$table_name = $wpdb->prefix . 'easypay_recurring';
	$wpdb->insert( 
		$table_name, 
		array( 
			'ch_id' => 1, 
			'option_id' => 0, 
			'choice1' => '1,D',
			'choice2' => '1,M',
			'choice3' => '1,M',
		) 
	);
}
*/

    public static function create_wppd_uploads_directory() {
        $upload_dir = wp_upload_dir();
        $path = $upload_dir['basedir'] . '/easypay/';
        if (!file_exists($path) && !mkdir($path, 0777, true)) {
            
        }
    }

}

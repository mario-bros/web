<?php

//print_r(get_option('easypay_options'));

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://theemon.com/e/EasyPay/LivePreview
 * @since             1.0.0
 * @package           WPPD
 *
 * @wordpress-plugin
 * Plugin Name:       EasyPay
 * Plugin URI:        http://theemon.com/e/EasyPay/LivePreview
 * Description:       EasyPay is a payment solution for WordPress based web sites. It provides a direct payment interface for visitors of your  online services site through a embedded form on your site, where users will be able to enter amount and other required details and make the payment.
 * Version:           2.0.0
 * Author:            theem'on WordPress Team 
 * Author URI:        http://theemon.com/e/EasyPay/LivePreview
 * Text Domain:       easypay
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Define constants
 */
define('WPPD_INCLUDES_PATH', plugin_dir_path(__FILE__) . 'includes/');

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-wppd-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-wppd-deactivator.php';

/** This action is documented in includes/class-wppd-activator.php */
register_activation_hook(__FILE__, array('WPPD_Activator', 'activate'));

/** This action is documented in includes/class-wppd-deactivator.php */
register_deactivation_hook(__FILE__, array('WPPD_Deactivator', 'deactivate'));

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-wppd.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wppd() {

    $plugin = new WPPD();
    $plugin->run();
}

run_wppd();

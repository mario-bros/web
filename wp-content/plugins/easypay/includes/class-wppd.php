<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WPPD
 * @subpackage WPPD/includes
 * @author     theem'on <support@theemon.com>
 */
class WPPD {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WPPD_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'wppd';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - WPPD_Loader. Orchestrates the hooks of the plugin.
     * - WPPD_i18n. Defines internationalization functionality.
     * - WPPD_Admin. Defines all hooks for the dashboard.
     * - WPPD_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * Payment Gateway Interface
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateways/interface-wppd-gateway.php';
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wppd-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wppd-i18n.php';

        /**
         * This class is responsible for form validations
         */
        require_once plugin_dir_path(__FILE__) . '/class-wppd-validation.php';

        // define global validations object
        global $wppd_validation;
        $wppd_validation = new WPPD_Validations();
        /**
         * This class is responsible for all database operations of the plugin
         */
        require_once plugin_dir_path(__FILE__) . '/class-wppd-db.php';

        // define global database object
        global $wppd_db;
        $wppd_db = new WPPD_DB();

        /**
         * This class is responsible for all security operations of the plugin
         */
        require_once plugin_dir_path(__FILE__) . '/class-wppd-security.php';

        // define global security object
        global $wppd_security;
        $wppd_security = new WPPD_Security();

        /**
         * This class is responsible for all email templates & email notifications of the plugins of          * the plugin
         */
        require_once plugin_dir_path(__FILE__) . '/class-wppd-email.php';

        // define global security object
        global $wppd_email;
        $wppd_email = new WPPD_Email();

        /**
         * This class is responsible to list down the payments and their status in the Dashboard
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wppd-list.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wppd-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wppd-public.php';

        $this->loader = new WPPD_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WPPD_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new WPPD_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new WPPD_Admin($this->get_plugin_name(), $this->get_version());

        // load admin styles
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        // load admin scripts
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        // initialize form builder
        $this->loader->add_action('admin_footer', $plugin_admin, 'add_form_builder');
        // Create admin menu for the plugin
        $this->loader->add_action('admin_menu', $plugin_admin, 'wppd_admin_menu');

        $this->loader->add_action('admin_print_footer_scripts', $plugin_admin, 'wppd_add_quicktags');

        $this->loader->add_action('mce_buttons', $plugin_admin, 'wppd_register_buttons');

        $this->loader->add_action('mce_external_plugins', $plugin_admin, 'wppd_register_tinymce_button');

        foreach (array('post.php', 'post-new.php') as $hook) {
            $this->loader->add_action("admin_head-$hook", $plugin_admin, 'wppd_admin_head');
        }
        
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'wppd_admin_notices' );

        /**
         * 	Ajax request to save "form" in DB
         * 
         */
        $this->loader->add_action('wp_ajax_wppd_update_form', $plugin_admin, 'wppd_update_form');
        $this->loader->add_action('wp_ajax_nopriv_wppd_update_form', $plugin_admin, 'wppd_update_form_error');

        /**
         * 	Ajax request to load "form" from DB
         * 
         */
        $this->loader->add_action('wp_ajax_wppd_load_form', $plugin_admin, 'wppd_load_form');
        $this->loader->add_action('wp_ajax_nopriv_wppd_load_form', $plugin_admin, 'wppd_update_form_error');

        /**
         * Pre populate form on page load
         */
        $this->loader->add_action('wp_ajax_wppd_pre_populate', $plugin_admin, 'wppd_pre_populate');
        $this->loader->add_action('wp_ajax_nopriv_wppd_pre_populate', $plugin_admin, 'wppd_pre_populate');
        
        /**
         * 
         */
        add_action('wp_ajax_easyCreditSystem', array(&$this, 'easyCreditSystem'));
        add_action('wp_ajax_nopriv_easyCreditSystem', array(&$this, 'easyCreditSystem'));
        
            add_action('wp_ajax_easyStripPayment', array(&$this, 'easyStripPayment'));
        add_action('wp_ajax_nopriv_easyStripPayment', array(&$this, 'easyStripPayment'));
        
    }
    
    function easyStripPayment(){
         global $wppd_error;
                // Paypal Pro
                  require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-stripe.php';
                $wppd_gateway = new WPPD_Stripe();
                $wppd_gateway->wppd_send_to_gateway($_POST);
        
    	//print_r($wppd_error);//----------
    	die;
    }
    
    
    
    function easyCreditSystem(){
         global $wppd_error;
                // Paypal Pro
                require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-paypal-pro.php';
                $wppd_gateway = new WPPD_Paypal_Pro();
        
        //$plugin_public = new WPPD_Public($this->get_plugin_name(), $this->get_version());
        
        // initiate payment on init hooks
        
        //$this->loader->add_action('init', $plugin_public, 'wppd_initiate_payment');
       // echo "aaaaaaaaaaaaaaaaa";
    	//$this->define_public_hooks();
    	print_r($wppd_error);
    	die;
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new WPPD_Public($this->get_plugin_name(), $this->get_version());
        
        // initiate payment on init hooks
        
        $this->loader->add_action('init', $plugin_public, 'wppd_initiate_payment');
       
        // load public style
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        // load public script
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // register shortcode

        $this->loader->add_action('init', $plugin_public, 'wppd_shortcode');

        /**
         *  Handle payment notifications from different gateways
         */
        $this->loader->add_action('wp_ajax_wppd_ipnhandler', $plugin_public, 'wppd_payment_notification_handler');
        $this->loader->add_action('wp_ajax_nopriv_wppd_ipnhandler', $plugin_public, 'wppd_payment_notification_handler');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    WPPD_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}

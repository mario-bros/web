<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://www.csschopper.com
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    WPPD
 * @subpackage WPPD/admin
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Admin {

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
     * @var      string    $plugin_name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

// Admin interface style
        wp_enqueue_style($this->plugin_name . '-admin-styles', plugin_dir_url(__FILE__) . '/css/wppd-admin.css', array(), $this->version, 'all');

        if (isset($_GET['page']) && $_GET['page'] == 'wppd_form_builder') {
// Bootstrap CSS
            wp_enqueue_style($this->plugin_name . '-bootstrap-style', plugin_dir_url(__FILE__) . 'form-builder/assets/css/lib/bootstrap.min.css', array(), '3.2.0', 'all');

// Custom Stylesheet
            wp_enqueue_style($this->plugin_name . '-bfb-custom', plugin_dir_url(__FILE__) . 'form-builder/assets/css/custom.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

// Custom script file
        wp_enqueue_script($this->plugin_name . '-admin-script', plugin_dir_url(__FILE__) . 'js/wppd-admin.js', array('jquery'), $this->version, true);

// Localize 
        $params = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'template_url' => get_bloginfo('template_directory')
        );
        wp_localize_script('wppd-admin-script', 'wppd_ajax', $params);
    }

    /**
     * Add form builder scripts in footer
     * 
     */
    public function add_form_builder() {
        if (isset($_GET['page']) && $_GET['page'] == 'wppd_form_builder') {
            $saved_form = get_option('easypay_form_builder');
            echo '<script type="text/javascript"> var saved_form = ' . $saved_form . '; </script><script data-main="' . plugin_dir_url(__FILE__) . 'form-builder/assets/js/main-built.js" src="' . plugin_dir_url(__FILE__) . 'form-builder/assets/js/lib/require.js?v=3" ></script>';
        }
    }

    /* -------------------------------------------*
     * 		Page handler (menu) functions
     * ------------------------------------------- */

    function wppd_settings_page($args) {
        ?>
        <div class="wrap">

            <h2><?php _e('Gateway Settings', 'easypay'); ?></h2>
            <ul class="subsubsub">
                <li><a href="<?php echo add_query_arg(array('gateway' => 'general'), admin_url('admin.php?page=wppd_settings')); ?>"><?php _e('General Settings', 'easypay'); ?></a> |</li>
                <li><a href="<?php echo add_query_arg(array('gateway' => 'paypal-standard'), admin_url('admin.php?page=wppd_settings')); ?>"><?php _e('Paypal Standard', 'easypay'); ?></a> |</li>
                <li><a href="<?php echo add_query_arg(array('gateway' => 'paypal-pro'), admin_url('admin.php?page=wppd_settings')); ?>"><?php _e('Paypal Pro', 'easypay'); ?></a> |</li>
                <li><a href="<?php echo add_query_arg(array('gateway' => 'strip'), admin_url('admin.php?page=wppd_settings')); ?>"><?php _e('Stripe', 'easypay'); ?></a> |</li>
                <li><a href="<?php echo add_query_arg(array('gateway' => 'recrring-setting'), admin_url('admin.php?page=wppd_settings')); ?>"><?php _e('Recurring Setting', 'easypay'); ?></a></li>
            </ul>
            <div class="clear"></div>
            <?php
            $gateway = isset($_GET['gateway']) ? $_GET['gateway'] : 'general';

            switch ($gateway) {

                case 'general':
                    require_once plugin_dir_path(__FILE__) . 'partials/wppd-general-settings.php';
                    break;
                case 'paypal-standard':
                    require_once plugin_dir_path(__FILE__) . 'partials/gateway-settings/wppd-paypal-standard.php';
                    break;

                case 'paypal-pro':
                    require_once plugin_dir_path(__FILE__) . 'partials/gateway-settings/wppd-paypal-pro.php';
                    break;
                case 'strip':
                    require_once plugin_dir_path(__FILE__) . 'partials/gateway-settings/wppd-strip.php';
                    break;
                    
                case 'recrring-setting':
                    require_once plugin_dir_path(__FILE__) . 'partials/gateway-settings/recurring-settings.php';
                    break;
                    	
                default:
                    break;
            }
            ?>
        </div>
        <?php
    }

    public function wppd_get_pages_option($current) {

        $pages = get_pages();
        $options = '';
        foreach ($pages as $page) {
            $link = get_permalink($page->ID);
            $selected = ($current == $link) ? 'selected="selected"' : '';
            $options .= '<option value="' . $link . '" ' . $selected . '>' . $page->post_title . '</option>';
        }

        return $options;
    }

    public function wppd_update_gateway_settings($data) {

        if (isset($_POST['paypal'])) {

            require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-paypal-standard.php';
            $wppd_gateway = new WPPD_Paypal_Standard();

            return $wppd_gateway->wppd_update_gateway_settings($data);
        } elseif (isset($_POST['ccard'])) {
            require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-paypal-pro.php';
            $wppd_gateway = new WPPD_Paypal_Pro();

            return $wppd_gateway->wppd_update_gateway_settings($data);
        } elseif (isset($_POST['stripe'])) {
            require_once WPPD_INCLUDES_PATH . 'gateways/class-wppd-stripe.php';
            $wppd_gateway = new WPPD_Stripe();

            return $wppd_gateway->wppd_update_gateway_settings($data);
        } elseif (isset($_POST['general'])) {

            return $this->wppd_update_general_settings($data);
        }

        // add general settings
    }

    /**
     * Function to update general settings for the plugin
     */
    public function wppd_update_general_settings($data) {
        $paypal_name = isset($data['easypay_user_defined_name']) ? stripslashes($data['easypay_user_defined_name']) : '';
        $easypay_recurring_enable = isset($data['easypay_recurring_enable']) ? $data['easypay_recurring_enable'] : '';
        $paypal_sucess = isset($data['easypay_success']) ? stripslashes($data['easypay_success']) : '';
        $paypal_fail = isset($data['easypay_failed']) ? stripslashes($data['easypay_failed']) : '';
        $paypal_retry = isset($data['easypay_retry']) ? stripslashes($data['easypay_retry']) : '';
        $easypay_amt_size = isset($data['easypay_amt_size']) ? stripslashes($data['easypay_amt_size']) : '';
        $easypay_email_size = isset($data['easypay_email_size']) ? stripslashes($data['easypay_email_size']) : '';
        $easypay_allow_currency = isset($data['easypay_allow_currency']) ? stripslashes($data['easypay_allow_currency']) : '';
        $easypay_pay_currency = isset($data['easypay_pay_currency']) ? stripslashes($data['easypay_pay_currency']) : '';
        $easypay_disable_custom_amount = isset($data['easypay_disable_custom_amount']) ? $data['easypay_disable_custom_amount'] : '';
        $easypay_st_amount_enable = isset($data['easypay_st_amount_enable']) ? $data['easypay_st_amount_enable'] : '';
        $easypay_st_amounts = isset($data['easypay_st_amounts']) ? $data['easypay_st_amounts'] : '';


        $general_options = array(
            'easypay_user_defined_name' => $paypal_name,
        	'easypay_recurring_enable' => $easypay_recurring_enable,
            'easypay_success_url' => $paypal_sucess,
            'easypay_fail_url' => $paypal_fail,
            'easypay_retry_url' => $paypal_retry,
            'easypay_amt_field_size' => $easypay_amt_size,
            'easypay_email_field_size' => $easypay_email_size,
            'easypay_allow_currency' => $easypay_allow_currency,
            'easypay_pay_currency' => $easypay_pay_currency,
            'easypay_disable_custom_amount' => $easypay_disable_custom_amount,
            'easypay_st_amount_enable' => $easypay_st_amount_enable,
            'easypay_st_amounts' => $easypay_st_amounts
        );
        $get_options = get_option('easypay_options');
        $general_options = array_merge((array) $get_options, $general_options);
        return update_option('easypay_options', $general_options);
    }

    /* --------------------------------------------*
     *  EasyPay Form Builder Page
     * --------------------------------------------- */

    function wppd_form_page($args) {
// Settings page method
        $wppd_options = get_option('easypay_options');
        $wpda_pay_currency = isset($wppd_options['easypay_pay_currency']) ? $wppd_options['easypay_pay_currency'] : '';
        ?>
        <div class="wrap">

            <h2><?php _e('Optional Fields', 'easypay'); ?></h2>
            <div class="postbox">
                <div class="inside">
                    <div class="row clearfix">
                        <!-- Building Form. -->
                        <div class="col-md-6">
                            <h5><i><?php _e('Drag elements from right panel to create your form.', 'easypay'); ?></i></h5>
                            <div class="clearfix">
                                <div id="build">
                                    <form id="target" class="form-horizontal">
                                    </form>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php _e('Use [EASYPAY_FORM] shortcode to display the form.', 'easypay'); ?>
                                </div>
                            </div>
                        </div>
                        <!-- / Building Form. -->

                        <!-- Components -->
                        <div class="col-md-6">
                            <div class="tabbable">
                                <ul class="nav nav-tabs" id="formtabs">
                                    <!-- Tab nav -->
                                </ul>
                                <form class="form-horizontal" id="components" role="form">
                                    <fieldset>
                                        <div class="tab-content">
                                            <!-- Tabs of snippets go here -->
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <!-- / Components -->
                    </div>
                    <hr />
                    <div class="update-form-bottom row">
                        <div class="col-md-12">
                            <input class='button button-primary button-hero col-md-2 pay-sub' id="pay-sub" type='submit' name="update_pay_direct_form" value="<?php _e('Update Form'); ?>" />
                            <div class="wppd_message col-md-4"></div>
                        </div>
                    </div>
                </div>
            </div> <!-- /postbox -->
        </div>
        <?php
    }

    /* --------------------------------------------*
     *  EasyPay Payment List Page
     * --------------------------------------------- */

    function wppd_list_pyments($args) {
        ?>
        <div class="wrap">
            <h2><?php _e('Payment List', 'easypay'); ?></h2>
            <?php $this->wppd_generate_list(); ?>

            <div id="ajax-response"></div>
            <br class="clear">
        </div>			
        <?php
    }

    function wppd_generate_list() {

        global $wpdb, $pay_log_table;

        $pay_log_table->prepare_items();
        $pay_log_table->display();
    }

    /**
     * Settings for changing email templates
     */
    public function wppd_email_settings() {

        global $wppd_email;
        ?>
        <div class="wrap">

            <h2><?php _e('Email Templates', 'easypay'); ?></h2>

            <?php if (isset($_POST['email-type']) && $wppd_email->wppd_update_email_settings($_POST['email-type'], $_POST['template'], $_POST['subject'])):
                ?>
                <div id="message" class="updated below-h2"><p><?php _e('Settings updated successfully.', 'easypay') ?></p></div>      
            <?php elseif (isset($_POST['email-type'])): ?>
                <div id="message" class="error below-h2"><p><?php _e("There's nothing to update.", 'easypay') ?></p></div>
            <?php endif; ?>

            <ul class="subsubsub">
                <li><a href="<?php echo add_query_arg(array('tab' => 'admin-email'), admin_url('admin.php?page=wppd_email_settings')); ?>"><?php _e('Admin Email', 'easypay'); ?></a> |</li>
                <li><a href="<?php echo add_query_arg(array('tab' => 'customer-initiated'), admin_url('admin.php?page=wppd_email_settings')); ?>"><?php _e('Payment Initiated Email', 'easypay'); ?></a> |</li>
                <li><a href="<?php echo add_query_arg(array('tab' => 'customer-invoice'), admin_url('admin.php?page=wppd_email_settings')); ?>"><?php _e('Customer Invoice', 'easypay'); ?></a></li>
            </ul>

            <?php
            $tab = isset($_GET['tab']) ? $_GET['tab'] : 'admin-email';
            switch ($tab):
                case 'admin-email':
                    $wppd_email->wppd_admin_new_order();
                    break;
                case 'customer-initiated':
                    $wppd_email->wppd_customer_initiated();
                    break;
                case 'customer-invoice':
                    $wppd_email->wppd_customer_invoice();
                    break;
                default :
                    $wppd_email->wppd_admin_new_order();
                    break;
            endswitch;
            ?>
        </div>
        <?php
    }

    /**
     * Register admin menus
     * 
     * @since 1.0.0 
     */
    public function wppd_admin_menu() {
        /**
         * 	Adding 'Admin Menu' : add_menu_page
         *
         * 	For more information:
         * 	http://codex.wordpress.org/Function_Reference/add_menu_page
         * 
         * 	Menu Icon: Generated from http://iconizer.net/
         */
        add_menu_page('EasyPay', __('EasyPay', 'easypay'), 'manage_options', __FILE__, array(&$this, 'wppd_list_pyments'), plugins_url('images/icons/direct-pay-icon.png', __FILE__));


//create submenu items
        add_submenu_page(__FILE__, __('Payment List', 'easypay'), __('Payments', 'easypay'), 'manage_options', __FILE__, array(&$this, 'wppd_list_pyments'));
        add_submenu_page(__FILE__, __('Settings', 'easypay'), __('Settings', 'easypay'), 'manage_options', 'wppd_settings', array(&$this, 'wppd_settings_page'));
        add_submenu_page(__FILE__, __('Form Builder', 'easypay'), __('Optional Form Fields', 'easypay'), 'manage_options', 'wppd_form_builder', array(&$this, 'wppd_form_page'));

// Email templates settings page

        add_submenu_page(__FILE__, __('Email Templates', 'easypay'), __('Email Templates', 'easypay'), 'manage_options', 'wppd_email_settings', array(&$this, 'wppd_email_settings'));
    }

    /**
     * 	Admin 'Form Builder' update
     * 	
     * Ajax Handler function
     * action: wppd_update_form
     * 	
     * @params Request array of AJAX call
     * @returns boolean 
     * 
     */
    function wppd_update_form() {

        if (isset($_REQUEST)) {
// Form Source To Be Used On Front End
            $form_source = stripslashes($_REQUEST['form_source']);

// Updates form
            $isUpdate = update_option('easypay_form', $form_source);

//Form builder source to regenerate form fields in admin form build area
            $form_builder_source = stripslashes($_REQUEST['form_builder_source']);

// Updates form source
            $formBuildertUpdate = update_option('easypay_form_builder', $form_builder_source);
            if ($isUpdate && $formBuildertUpdate) {
                echo "true";
            } else {
                echo "false";
            }
        }
        die();
    }

    /**
     * 
     * Pre populate form when form builder loads
     */
//    function wppd_pre_populate() {
//        echo $data = get_option('easypay_form_builder');
//        die();
//    }

    /**
     * 	Loads "Form Builder" form
     * 	
     * 	@return String (HTML form)
     * 
     */
    function wppd_load_form() {

        if (isset($_REQUEST)) {
            $easypay_form = get_option('easypay_form');
            echo $easypay_form;
        }
        die();
    }

    /**
     * 	"nopriv" handler function for,
     * 	action : wppd_update_form
     * 
     * 	@returns Error for non logged in users
     * 
     */
    function wppd_update_form_error() {

        wp_die(__('You\'re attempting to get Unauthorized Access!', 'easypay'));
    }

    /**
     * Add Quicktags to insert form & retry options
     */
    function wppd_add_quicktags() {
        if (wp_script_is('quicktags')) {
            ?>
            <script type="text/javascript">
                var form_Shortcode = '[EASYPAY_FORM]';
                QTags.addButton('wppd-form', 'wppd form', form_Shortcode, '', 'wppd form', 'EasyPay');
                var retry_Shortcode = '[EASYPAY_RETRY_BUTTON]';
                QTags.addButton('wppd-retry', 'wppd retry', retry_Shortcode, '', 'wppd retry', 'EasyPay');
            </script>
            <?php
        }
    }

    // add wppd form button to visual editor



    function wppd_register_buttons($buttons) {
        array_push($buttons, 'separator', 'wppd_form');
        array_push($buttons, 'separator', 'wppd_retry');
        return $buttons;
    }

    // Load the TinyMCE plugin : editor_plugin.js (wp2.5)


    function wppd_register_tinymce_button($plugin_array) {
    	if (isset($_GET['page']) && $_GET['page'] != 'vpt_option'){
        	$plugin_array['wppd_form'] = plugin_dir_url(__FILE__) . '/js/wppd-mce-form.js';
        	$plugin_array['wppd_retry'] = plugin_dir_url(__FILE__) . '/js/wppd-mce-retry.js';
    	}
        return $plugin_array;
    }

    /**
     * Localize Script
     */
    function wppd_admin_head() {
        $layout = apply_filters("twitter_feed_layout", array());
        ?>
        <!-- TinyMCE Shortcode Plugin -->
        <script type='text/javascript'>
            var wppd_data = {'plugin_url': '<?php echo plugin_dir_url(__FILE__); ?>'};
        </script>
        <!-- TinyMCE Shortcode Plugin -->
        <?php
    }

    /**
     * add notices for admin
     */
    function wppd_admin_notices() {
        $settings = get_option('easypay_options');

        if (!$settings):
            $complete = '<a href="' . admin_url('admin.php?page=wppd_settings') . '">complete</a>';
            ?>
            <div class="error">
                <p><?php _e("Please $complete the settings to use EasyPay!", 'easypay'); ?></p>
            </div>
            <?php
        endif;

        if ($settings['easypay_paypal_enable'] && (!$settings['easypay_business'] || !$settings['easypay_pay_currency'])):
            $complete = '<a href="' . admin_url('admin.php?page=wppd_settings&gateway=paypal-standard') . '">complete</a>';
            ?>
            <div class="error">
                <p><?php _e("Please $complete the PayPal Standard settings to use it or you can disable it!", 'easypay'); ?></p>
            </div>
            <?php
        endif;

        $upload_dir = wp_upload_dir();
        $path = $upload_dir['basedir'] . '/easypay';

        if (!is_writable($path)) {
            ?>
            <div class="error">
                <p><?php _e("Please chmod 777 to " . $path, 'easypay'); ?></p>
            </div>
            <?php
        }
    }

}

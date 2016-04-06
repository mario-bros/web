<?php

/**
 * Charity - Vender for lib (TGM-Plugin-Activation) activation
 *  
 * @package     charity
 * @version     v.1.0
 */
function charity_register_required_plugins() {

    $vendorPath=get_template_directory() . '/vendor/plugins/';
    $plugins = array(
        array(
    		'name' => 'Charity Apps',
    		'slug' => 'charity-apps',
    		'source' => $vendorPath  . 'charity-apps.zip',
    		'required' => true,
        	'force_activation' => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
    	),
    	array(
    		'name' => 'EasyPay',
    		'slug' => 'easypay',
    		'source' => $vendorPath  . 'easypay.zip',
    		'required' => true,
    		'force_activation' => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
    	),array(
            'name' => 'Revolution Slider',
            'slug' => 'revslider',
            'source' => $vendorPath  . 'revslider.zip', 
            'required' => true,
    		'force_activation' => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        ),
    	
    	array(
    		'name' => 'Contact Form 7',
    		'slug' => 'contact-form-7',
    		'source' => $vendorPath  . 'contact-form-7.zip',
    		'required' => true,
    	),
        array(
            'name' => 'Mailchimp',
            'slug' => 'mailchimp-for-wp',
            'source' => $vendorPath  . 'mailchimp-for-wp.zip', 
            'required' => false,
        ),
    	array(
    		'name' => 'Woocommerce',
    		'slug' => 'woocommerce',
    		'source' => $vendorPath  . 'woocommerce.zip',
    		'required' => false,
    		'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
    	),
    	array(
    		'name' => 'Contact Form DB',
    		'slug' => 'contact-form-7-to-database-extension',
    		'source' => $vendorPath  . 'contact-form-7-to-database-extension.zip',
    		'required' => false,
    		'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
    	),
    );

    $config = array(
        'domain' => 'charity', // Text domain - likely want to be the same as your theme.
        'default_path' => '', // Default absolute path to pre-packaged plugins
        'parent_menu_slug' => 'themes.php', // Default parent menu slug
        'parent_url_slug' => 'themes.php', // Default parent URL slug
        'menu' => 'install-required-plugins', // Menu slug
        'has_notices' => true, // Show admin notices or not
        'is_automatic' => false, // Automatically activate plugins after installation or not
        'message' => '', // Message to output right before the plugins table
        'strings' => array(
            'page_title' => __('Install Required Plugins', 'b-blog'),
            'menu_title' => __('Install Plugins', 'b-blog'),
            'installing' => __('Installing Plugin: %s', 'b-blog'), // %1$s = plugin name
            'oops' => __('Something went wrong with the plugin API.', 'b-blog'),
            'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.'), // %1$s = plugin name(s)
            'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.'), // %1$s = plugin name(s)
            'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'), // %1$s = plugin name(s)
            'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'), // %1$s = plugin name(s)
            'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'), // %1$s = plugin name(s)
            'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'), // %1$s = plugin name(s)
            'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'), // %1$s = plugin name(s)
            'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins'),
            'activate_link' => _n_noop('Activate installed plugin', 'Activate installed plugins'),
            'return' => __('Return to Required Plugins Installer', 'b-blog'),
            'plugin_activated' => __('Plugin activated successfully.', 'b-blog'),
            'complete' => __('All plugins installed and activated successfully. %s', 'b-blog'), // %1$s = dashboard link
            'nag_type' => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa($plugins, $config);
}

//Include the TGM_Plugin_Activation class.
require_once dirname(__FILE__) . '/TGM-Plugin-Activation/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'charity_register_required_plugins');

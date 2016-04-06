<?php

/**
 * cherity - eneque script 
 *
 * @package     cherity
 * @version     v.1.0
 */

/**
 * Theme enequeue script
 */
function chy_enqueue_scripts() {

    $themeURL = get_stylesheet_directory_uri() . "/assets/";
    /*
     * Register style
     */
    wp_register_style('chy.fonts', 'http://fonts.googleapis.com/css?family=Lato:400,300italic,300,700%7CPlayfair+Display:400,700italic%7CRoboto:300%7CMontserrat:400,700%7COpen+Sans:400,300%7CLibre+Baskerville:400,400italic', array(), null, 'all');
    
    wp_register_style('chy.bootstrap.theme', $themeURL . 'css/bootstrap-theme.css', array(), null, 'all');
    wp_register_style('chy.font-awesome', $themeURL . 'css/font-awesome.min.css', array(), null, 'all');
    wp_register_style('chy.bootstrap', $themeURL . 'css/bootstrap.css', array(), null, 'all');
    wp_register_style('chy-fancybox-css', $themeURL . 'fancybox/css/fancybox.css', array(), null, 'all');

    //switcher
    wp_register_style('chy.switcher.theme.panel', $themeURL . 'charity-options/assets/css/theme_panel.css', array(), null, 'all');


    wp_register_style('chy.global', $themeURL . 'css/global.css', array(), null, 'all');
    wp_register_style('chy.custom.style', $themeURL . 'css/chy-style.css', array(), null, 'all');
    wp_register_style('chy.responsive', $themeURL . 'css/responsive.css', array(), null, 'all');
    wp_register_style('chy.skin', $themeURL . 'css/skin.css', array(), null, 'all');
    wp_register_style('charity-woocommerce-custom-css', $themeURL . 'css/charity-woocommerce-custom.css', array(), null, 'all');
    wp_register_style('charity-kwoocommerce', $themeURL . 'css/kwoocommerce.css', array(), null, 'all');
    
    wp_register_style('charity.owl.carousel', $themeURL . 'css/owl.carousel.css', array(), null, 'all');
    wp_register_style('charity.owl.theme', $themeURL . 'css/owl.theme.css', array(), null, 'all');
	wp_register_style('chy.style', get_stylesheet_uri(), array(), null, 'all');
    //enqueue style
    wp_enqueue_style('chy.fonts');
    

    wp_enqueue_style('chy.bootstrap');
    wp_enqueue_style('chy.bootstrap.theme');
    wp_enqueue_style('chy-fancybox-css');
    wp_enqueue_style('chy.font-awesome');


    //switcher
    wp_enqueue_style('chy.switcher.theme.panel');

    wp_enqueue_style('chy.global');
    wp_enqueue_style('chy.custom.style');

    wp_enqueue_style('chy.responsive');
	wp_enqueue_style('chy.style');
    wp_enqueue_style('chy.skin');
	
   if ( function_exists( 'is_woocommerce' ) ) {
   	switch(true){
		case is_woocommerce():
		case is_cart():
		case is_checkout():
		case is_account_page():
		case is_product():
		case is_product_category():
		case is_shop():				
		case is_page_template("charity-shop-landing.php"):
	    	wp_enqueue_style('charity-woocommerce-custom-css');
    		wp_enqueue_style('charity-kwoocommerce');
			
			break;
		
	}
		/*if ( is_woocommerce() ||  is_cart() ||  is_checkout() || is_page_template("charity-shop-landing.php") ) {
	    	wp_enqueue_style('charity-woocommerce-custom-css');
    		wp_enqueue_style('charity-kwoocommerce');
    	}*/
	}    
    
    wp_enqueue_style('charity.owl.carousel');
    wp_enqueue_style('charity.owl.theme');
    

    /**
     * Register Script
     */
    wp_register_script('chy.bootstrap', $themeURL . 'js/bootstrap.js', array('jquery'), null, true);

    //switcher
    wp_register_script('chy.switcher', $themeURL . 'charity-options/assets/js/charity-option.js', array('jquery'), null, true);
    wp_register_script('chy.switcher.cookies', $themeURL . 'charity-options/assets/js/jquery.cookie.js', array('jquery'), null, true);

    //countdown
    wp_register_script('chy.countdown.plugin', $themeURL . 'countdown/jquery.plugin.js', array('jquery',), null, true);
    wp_register_script('chy.countdown', $themeURL . 'countdown/jquery.countdown.js', array('jquery', 'chy.countdown.plugin'), null, true);


    wp_register_script('jquery.easing.min', $themeURL . 'js/jquery.easing.min.js', array('jquery'), null, true);
    wp_register_script('jquery.flexslider', $themeURL . 'js/jquery.flexslider.js', array('jquery'), null, true);
    wp_register_script('jquery-fancybox', $themeURL . 'fancybox/js/jquery.fancybox.js', array('jquery'), null, true);
    wp_register_script('jquery-fancybox-custom', $themeURL . 'fancybox/js/custom.fancybox.js', array('jquery'), null, true);

    wp_register_script('custom-woocommerce-js', $themeURL . 'js/custom-woocommerce.js', array('jquery'), null, true);
    wp_register_script('cahrity.wp.custom', $themeURL . 'js/wp.custom.js', array('jquery'), null, true);
    wp_register_script('chy-site', $themeURL . 'js/site.js', array('jquery'), null, true);
    wp_register_script('snap.svg-min', $themeURL . 'js/snap.svg-min.js', array('jquery'), null, true);
    
    wp_register_script('charity.owl.carouseljs', $themeURL . 'js/owl.carousel.js', array('jquery'), null, true);

    //enqueue script
    wp_enqueue_script('chy.bootstrap');

    //switcher
    wp_enqueue_script('chy.switcher');
    wp_enqueue_script('chy.switcher.cookies');
    //countdown
    wp_enqueue_script('chy.countdown.plugin');
    wp_enqueue_script('chy.countdown');

    wp_enqueue_script('jquery.easing.min');
    wp_enqueue_script('jquery.flexslider');
    wp_enqueue_script('jquery-fancybox');
    wp_enqueue_script('jquery-fancybox-custom');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_enqueue_script('custom-woocommerce-js');
    wp_enqueue_script('charity.owl.carouseljs');
    wp_enqueue_script('cahrity.wp.custom');
    $comingSoonDate = vp_option('vpt_option.coming_soon_date');
    $params = array(
        'countdown' => date("F d, Y h:i:s", strtotime($comingSoonDate)),
    );
    wp_localize_script('cahrity.wp.custom', 'charityCustom', $params);

    wp_enqueue_script('chy-site');
    wp_enqueue_script('snap.svg-min');
    

    $params = array(
        'ajaxURL' => admin_url('admin-ajax.php'),
        'template_url' => get_template_directory_uri(),
        'color' => vp_option('vpt_option.theme_color'), 
        'layout' => vp_option('vpt_option.page_layout'), 
        'sticky_header' => vp_option('vpt_option.sticky_header'), 
        'global_font' => vp_option('vpt_option.theme_fonts'), 
        'home_url' => get_home_url(),
    );
    wp_localize_script('chy.switcher', 'charity', $params);

}

add_action('wp_enqueue_scripts', 'chy_enqueue_scripts');

/**
 * Add Favicon
 */
function chy_favicon() {
    $favicon = (vp_option('vpt_option.favicon')) ? vp_option('vpt_option.favicon') : get_stylesheet_directory_uri() . '/favicon.ico';
    $favicon = str_replace("http","https",$favicon);
    echo '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url($favicon) . '"  />';
}

add_action("wp_head", "chy_favicon");

/**
 * Admin Eneque Script
 */
function charityAdminEnqueueScriptStyle() {
    /**
     * Register Style
     */
    wp_register_style('charity.oneclick', get_stylesheet_directory_uri() . '/assets/admin/oneclick.installation.css', array(), null, 'all');
    wp_register_style('charity.admin', get_stylesheet_directory_uri() . '/assets/admin/admin.css', array(), null, 'all');
    wp_enqueue_style('charity.oneclick');
    wp_enqueue_style('charity.admin');

    /**
     * Register Script
     */
    wp_register_script('charity.metabox', get_stylesheet_directory_uri() . '/assets/admin/metabox.js', array('jquery'), null, true);
    wp_register_script('charity.media', get_stylesheet_directory_uri() . '/assets/admin/media.js', array('jquery'), null, true);
    wp_register_script('charity.oneclick', get_stylesheet_directory_uri() . '/assets/admin/oneclick.installation.js', array('jquery', "jquery-ui-dialog"), null, true);
    wp_register_script('charity.admin', get_stylesheet_directory_uri() . '/assets/admin/admin.js', array('jquery', "thickbox"), null, true);

    wp_enqueue_script('charity.metabox');
    wp_enqueue_media(); //@define: wp.media
    wp_enqueue_script('charity.media');
    wp_enqueue_script('charity.oneclick');
    wp_enqueue_script('charity.admin');

    wp_localize_script('charity.oneclick', 'charityAdmin', array("ajaxURL" => admin_url("admin-ajax.php"), "importSucess"=> admin_url( 'themes.php?&oneclk=succes&page=vpt_option#_menu_util')));
}

add_action('admin_enqueue_scripts', 'charityAdminEnqueueScriptStyle');

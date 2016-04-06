<?php
/**
 * Charity - login form
 * @package charity
 * @version     v.1.0
 */


add_action('login_head', 'charity_login_logo');
function charity_login_logo() {
    $site_logo = (vp_option('vpt_option.site_logo')) ? vp_option('vpt_option.site_logo') : get_stylesheet_directory_uri() . "/assets/img/logo.png";
    $color = (vp_option('vpt_option.theme_color')) ? vp_option('vpt_option.theme_color') : '#ffffff';
    echo "<style type='text/css'>
       body.login div#login h1 a{ background-image: url('" . esc_url($site_logo) . "') !important; }
        body.login{ background-color: " . esc_attr($color) . "!important; }
    </style>";
}


add_action( 'login_enqueue_scripts', 'charity_login_stylesheet' );
function charity_login_stylesheet() {
    wp_enqueue_style( 'charity-login', get_template_directory_uri() . '/assets/admin/login-style.css', array(), null, 'all' );
    
}

add_filter('login_headerurl', 'charity_wp_login_url');
function charity_wp_login_url() {
    return site_url('/');
}

add_filter('login_headertitle', 'charity_wp_login_title');
function charity_wp_login_title() {
    return get_option('blogname');
}


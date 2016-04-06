<?php

/**
 * Create instance of Options
 */
$tmpl_opt = array(
    'title' => __(' ', 'charity'),
    'logo' => CHY_THEME_URL . '/assets/img/logo.png',
    'menus' => array(
        array(
            'title' => __('General', 'charity'),
            'name' => 'general-menu',
            'icon' => 'font-awesome:fa-cog',
            'menus' => array(
                array(
                    'title' => __('Logos', 'charity'),
                    'name' => 'logos-submenu',
                    'icon' => 'font-awesome:fa-bookmark',
                    'controls' => include 'logos-settings.php'
                ),
                array(
                    'title' => __('Typography', 'charity'),
                    'name' => 'typography-submenu',
                    'icon' => 'font-awesome:fa-book',
                    'controls' => include 'typography-settings.php'
                ),
                array(
                    'title' => __('Other option', 'charity'),
                    'name' => 'other-option-submenu',
                    'icon' => 'font-awesome:fa-asterisk',
                    'controls' => include 'general-other-settings.php'
                )
            )
        ),
        array(
            'title' => __('Layout', 'charity'),
            'name' => 'layout-menu',
            'icon' => 'font-awesome:fa-star',
            'menus' => array(
                array(
                    'title' => __('Header', 'charity'),
                    'name' => 'header-submenu',
                    'icon' => 'font-awesome:fa-sun-o',
                    'controls' => include 'settings-header.php'
                ),
                array(
                    'title' => __('Footer', 'charity'),
                    'name' => 'footer-submenu',
                    'icon' => 'font-awesome:fa-exchange',
                    'controls' => include 'footer-settings.php'
                ),
                array(
                    'title' => __('Home', 'charity'),
                    'name' => 'home-submenu',
                    'icon' => 'font-awesome:fa-certificate',
                    'controls' => include 'home-layout.php'
                ),
                array(
                    'title' => __('Gallery', 'charity'),
                    'name' => 'gallery-submenu',
                    'icon' => 'font-awesome:fa-folder-open',
                    'controls' => include 'gallery-settings.php'
                ),
                array(
                    'title' => __('Causes', 'charity'),
                    'name' => 'causes-submenu',
                    'icon' => 'font-awesome:fa-bolt',
                    'controls' => include 'causes-settings.php'
                )
            )
        ),
        array(
            'title' => __('Advance', 'charity'),
            'name' => 'advance-menu',
            'icon' => 'font-awesome:fa-cogs',
            'menus' => array(
                array(
                    'title' => __('Causes Settings', 'charity'),
                    'name' => 'cuases-settings-submenu',
                    'icon' => 'font-awesome:fa-bookmark-o',
                    'controls' => include 'advanced-causes-settings.php'
                ),
                array(
                    'title' => __('Social', 'charity'),
                    'name' => 'social-submenu',
                    'icon' => 'font-awesome:fa-th-list',
                    'controls' => include 'social-media-settings.php'
                ),
                array(
                    'title' => __('Other Option', 'charity'),
                    'name' => 'advance-other-submenu',
                    'icon' => 'font-awesome:fa-leaf',
                    'controls' => include 'advanced-other-settings.php'
                )
            )
        ),
        /*
          array (
          'title' => __ ( 'Page Layout Settings', 'charity' ),
          'name' => 'pagelayout_settings',
          'icon' => 'font-awesome:fa-gift',
          'controls' => include 'page-layout-settings.php'
          ),
          array (
          'title' => __ ( 'Home Layout', 'charity' ),
          'name' => 'home_pagelayout_settings',
          'icon' => 'font-awesome:fa-gift',
          //'controls' => include 'home-layout.php'
          ),
          array (
          'title' => __ ( 'Social Media Settings', 'charity' ),
          'name' => 'socialmedia_settings',
          'icon' => 'font-awesome:fa-th-list',
          //'controls' => include 'social-media-settings.php'
          ),
          // 				array (
          // 						'title' => __ ( 'Footer Settings', 'charity' ),
          // 						'name' => 'footer_settings',
          // 						'icon' => 'font-awesome:fa-exchange',
          // 						//'controls' => include 'footer-settings.php'
          // 				),
          array (
          'title' => __ ( 'Advanced Settings', 'charity' ),
          'name' => 'advanced_settings',
          'icon' => 'font-awesome:fa-cogs',
          'controls' => include 'advanced-settings.php'
          ),
          array (
          'title' => __ ( 'Causes Settings', 'charity' ),
          'name' => 'causes_settings',
          'icon' => 'font-awesome:fa-cogs',
          'controls' => include 'causes-settings.php'
          ), */
        array(
            'title' => __('Coming Soon', 'charity'),
            'name' => 'coming_soon_settings',
            'icon' => 'font-awesome:fa-bell-o',
            'controls' => include 'coming-soon.php'
        ),
         array(
            'title' => __('Static Content', 'charity'),
            'name' => 'static-content-menu',
            'icon' => 'font-awesome:fa-dashboard',
            'menus' => array(
                array(
                    'title' => __('Home Page', 'charity'),
                    'name' => 'home-page-submenu',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => include 'home-static-content-settings.php'
                ),
                array(
                    'title' => __('Other Page', 'charity'),
                    'name' => 'other-page-submenu',
                    'icon' => 'font-awesome:fa-globe',
                    'controls' => include 'other-static-content-settings.php'
                ),
            )
        )
    )
);

$theme_options = new VP_Option(array(
            'is_dev_mode' => false, // dev mode, default to false
            'option_key' => 'vpt_option', // options key in db, required
            'page_slug' => 'vpt_option', // options page slug, required
            'template' => $tmpl_opt, // template file path or array, required
            'menu_page' => 'themes.php', // parent menu slug or supply `array` (can contains 'icon_url' & 'position') for top level menu
            'use_auto_group_naming' => true, // default to true
            'use_util_menu' => true, // default to true, shows utility menu
            'minimum_role' => 'edit_theme_options', // default to 'edit_theme_options'
            'layout' => 'fixed', // fluid or fixed, default to fixed
            'page_title' => __('Theme Options', 'charity'), // page title
            'menu_label' => __('Theme Options', 'charity')  // menu label
                ));

function charity_get_social_medias() {
    $socmeds = array(
        array(
            'value' => 'facebook',
            'label' => 'Facebook'
        ),
        array(
            'value' => 'twitter',
            'label' => 'Twitter'
        ),
        array(
            'value' => 'dribbble',
            'label' => 'Dribbble'
        ),
        array(
            'value' => 'pinterest',
            'label' => 'Pinterest'
        ),
        array(
            'value' => 'google-plus',
            'label' => 'Google+'
        ),
        array(
            'value' => 'instagram',
            'label' => 'Instagram'
        )
    );

    return $socmeds;
}
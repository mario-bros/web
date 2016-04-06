<?php

/**
 * Charity - Register Sidebar 
 * @package charity
 * @version     v.1.0
 * 
 */
function charity_widgets_init() {

    /**
     * Default Sidebar
     */
    register_sidebar(
            array(
                'name' => __('Default Charity widget', 'charity'),
                'id' => 'default-charity-section',
                'description' => 'Default Charity widgets Section',
                'class' => '',
                'before_widget' => '<div id="%1$s" class="default-widgets-section col-xs-12 col-sm-12 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	/**
	 * Sidebar Footer One
	 */
	 
	register_sidebar(
            array(
                'name' => __('Footer One Sidebar:One', 'charity'),
                'id' => 'footer-one-sidebar-one',
                'description' => 'Footer One Sidebar One',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '',
                'after_title' => ''
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer One Sidebar:Two', 'charity'),
                'id' => 'footer-one-sidebar-two',
                'description' => 'Footer One Sidebar Two',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer One Sidebar:Three', 'charity'),
                'id' => 'footer-one-sidebar-three',
                'description' => 'Footer One Sidebar Three',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	
	/**
	 * Sidebar Footer Two
	 */
	 
	register_sidebar(
            array(
                'name' => __('Footer Two Sidebar:One', 'charity'),
                'id' => 'footer-two-sidebar-one',
                'description' => 'Footer Two Sidebar One',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '',
                'after_title' => ''
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer Two Sidebar:Two', 'charity'),
                'id' => 'footer-two-sidebar-two',
                'description' => 'Footer Two Sidebar Two',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer Two Sidebar:Three', 'charity'),
                'id' => 'footer-two-sidebar-three',
                'description' => 'Footer Two Sidebar Three',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer Two Sidebar:Four', 'charity'),
                'id' => 'footer-two-sidebar-four',
                'description' => 'Footer Two Sidebar Four',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	/**
	 * Sidebar Footer Three
	 */
	 
	register_sidebar(
            array(
                'name' => __('Footer Three Sidebar:One', 'charity'),
                'id' => 'footer-three-sidebar-one',
                'description' => 'Footer Three Sidebar One',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '',
                'after_title' => ''
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer Three Sidebar:Two', 'charity'),
                'id' => 'footer-three-sidebar-two',
                'description' => 'Footer Three Sidebar Two',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	register_sidebar(
            array(
                'name' => __('Footer Three Sidebar:Three', 'charity'),
                'id' => 'footer-three-sidebar-three',
                'description' => 'Footer Three Sidebar Three',
                'class' => '',
                'before_widget' => '',
                'after_widget' => '',
                'before_title' => '<h6>',
                'after_title' => '</h6>'
            )
    );
	/**
     * Right Sidebar
     */
    register_sidebar(
            array(
                'name' => __('Causes Widget Section', 'charity'),
                'id' => 'causes-wisget-section',
                'description' => 'Causes widgets Section',
                'class' => '',
                'before_widget' => '<div class="text-widget">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            )
    );
	
    
    register_sidebar(
            array(
                'name' => __('Charity shop', 'charity'),
                'id' => 'charity-shop',
                'description' => 'Charity shop for woocommerce',
                'class' => '',
                'before_widget' => '<div class="shoping-filter clearfix">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="space-top">',
                'after_title' => '</h3>'
            )
    );
	
	
}

add_action('widgets_init', 'charity_widgets_init');

function hasCharityDefault_Widgets() {
    ob_start();
    dynamic_sidebar("default-charity-section");
    $default = ob_get_clean();

    if (!empty($default)) {
        return true;
    } else {
        return false;
    }
}

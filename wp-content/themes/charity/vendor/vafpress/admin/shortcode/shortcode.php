<?php
/**
 * Charity - Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */
 
 
// shortocode generators
$shortcode_vp_dir = CHY_THEME_DIR . '/vendor/vafpress/admin/shortcode/';
$general_shortcode = $shortcode_vp_dir.'general_shortcode.php';





/**
 * Create instances of Shortcode Generator
 */
$general_shortcode = array(
	'name'           => 'general_shortcodes',                                        // unique name, required
	'template'       => $general_shortcode,                                     // template file or array, required
	'modal_title'    => __( 'Charity Shortcode', 'charity'), // modal title, default to empty string
	'button_title'   => __( 'Charity Shortcode', 'charity'),              // button title, default to empty string
	'types'          => array( 'post', 'page','charity-portfolio','charity-causes' ),                       // at what post types the generator should works, default to post and page
	'included_pages' => array( 'appearance_page_vpt_option' ),         // or to what other admin pages it should appears
);


$charity_general_shortcode = new VP_ShortcodeGenerator($general_shortcode);

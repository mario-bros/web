<?php
/**
 * Include Vafpress Framework
 */
require_once 'framework/bootstrap.php';

/**
 * Include Custom Data Sources
 */
require_once 'admin/data_sources.php';

// options
require_once 'admin/option/option.php';

// MetaBox
require_once 'admin/metabox/metabox.php';

// Shortcode
require_once 'admin/shortcode/shortcode.php';



//$tmpl_opt  = CHY_THEME_DIR . '/vendor/vafpress/admin/option/option.php';

// metaboxes
/*$tmpl_mb1  = get_template_directory() . '/admin/metabox/sample_1.php';
$tmpl_mb2  = get_template_directory() . '/admin/metabox/sample_2.php';
$tmpl_mb3  = get_template_directory() . '/admin/metabox/sample_3.php';
$tmpl_mb4  = get_template_directory() . '/admin/metabox/sample_4.php';
$tmpl_mb5  = get_template_directory() . '/admin/metabox/sample_5.php';
$tmpl_mb6  = get_template_directory() . '/admin/metabox/sample_6.php';
$tmpl_mb6  = get_template_directory() . '/admin/metabox/sample_6.php';
$tmpl_mb7  = get_template_directory() . '/admin/metabox/sample_7.php';
$tmpl_mb8  = get_template_directory() . '/admin/metabox/sample_8.php';

// shortocode generators
$tmpl_sg1  = get_template_directory() . '/admin/shortcode_generator/shortcodes1.php';
$tmpl_sg2  = get_template_directory() . '/admin/shortcode_generator/shortcodes2.php';*/


/**
 * Create instances of Metaboxes
 */
/*$mb1 = new VP_Metabox($tmpl_mb1);
$mb2 = new VP_Metabox($tmpl_mb2);
$mb3 = new VP_Metabox($tmpl_mb3);
$mb4 = new VP_Metabox($tmpl_mb4);
$mb5 = new VP_Metabox($tmpl_mb5);
$mb6 = new VP_Metabox($tmpl_mb6);
$mb7 = new VP_Metabox($tmpl_mb7);
$mb8 = new VP_Metabox($tmpl_mb8);

/**
 * Create instances of Shortcode Generator
 */
/*/*$tmpl_sg1 = array(
	'name'           => 'sg_1',                                        // unique name, required
	'template'       => $tmpl_sg1,                                     // template file or array, required
	'modal_title'    => __( 'Vafpress Shortcodes 1', 'vp_textdomain'), // modal title, default to empty string
	'button_title'   => __( 'Vafpress', 'vp_textdomain'),              // button title, default to empty string
	'types'          => array( 'post', 'page' ),                       // at what post types the generator should works, default to post and page
	'included_pages' => array( 'appearance_page_vpt_option' ),         // or to what other admin pages it should appears
);
$tmpl_sg2 = array(
	'name'           => 'sg_2',
	'template'       => $tmpl_sg2,
	'modal_title'    => __( 'Vafpress Shortcodes 2', 'vp_textdomain'),
	'button_title'   => __( 'Vafpress', 'vp_textdomain'),
	'types'          => array( 'post', 'page' ),
	'main_image'     => get_template_directory_uri() . '/public/img/vp_shortcode_icon.png',
	'sprite_image'   => get_template_directory_uri() . '/public/img/vp_shortcode_icon_sprite.png',
);

$sg1 = new VP_ShortcodeGenerator($tmpl_sg1);
$sg2 = new VP_ShortcodeGenerator($tmpl_sg2);
*/
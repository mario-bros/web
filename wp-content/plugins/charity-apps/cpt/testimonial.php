<?php
/**
 * Charity - Custom Post Type
 *
 * Generator using
 * @link: http://generatewp.com/post-type/ 
 *   
 * @package     charity
 * @version     v.1.0
 */

// Register Custom Post Type
function charity_testimonial_post_type() {

	$labels = array(
		'name'                => _x( 'Testimonials', 'Post Type General Name', 'charity' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'charity' ),
		'menu_name'           => __( 'Testimonial', 'charity' ),
		'name_admin_bar'      => __( 'Testimonial', 'charity' ),
		'parent_item_colon'   => __( 'Parent Item:', 'charity' ),
		'all_items'           => __( 'All Items', 'charity' ),
		'add_new_item'        => __( 'Add New Item', 'charity' ),
		'add_new'             => __( 'Add New', 'charity' ),
		'new_item'            => __( 'New Item', 'charity' ),
		'edit_item'           => __( 'Edit Item', 'charity' ),
		'update_item'         => __( 'Update Item', 'charity' ),
		'view_item'           => __( 'View Item', 'charity' ),
		'search_items'        => __( 'Search Item', 'charity' ),
		'not_found'           => __( 'Not found', 'charity' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'charity' ),
	);
	$args = array(
		'label'               => __( 'testimonial', 'charity' ),
		'description'         => __( 'Post Type Description', 'charity' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		//'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-quote',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'testimonial', $args );

}

// Hook into the 'init' action
add_action( 'init', 'charity_testimonial_post_type', 0 );


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
function charity_portfolio_post_type() {

	$labels = array(
		'name'                => _x( 'Testimonials', 'Post Type General Name', 'charity' ),
		'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'charity' ),
		'menu_name'           => __( 'Testimonials', 'charity' ),
		'name_admin_bar'      => __( 'Testimonials', 'charity' ),
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
		'label'               => __( 'Testimonials', 'charity' ),
		'description'         => __( 'Post Type Description', 'charity' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'comments', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		//'menu_position'       => 5,
		'menu_icon'           => 'dashicons-groups',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'rewrite'            => array( 'slug' => 'testimonials' ),
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'charity-portfolio', $args );
	flush_rewrite_rules();
}

// Hook into the 'init' action
add_action( 'init', 'charity_portfolio_post_type', 0 );



// Register Custom Taxonomy
function charity_portfolio_type_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'charity' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'charity' ),
		'menu_name'                  => __( 'Type', 'charity' ),
		'all_items'                  => __( 'All Items', 'charity' ),
		'parent_item'                => __( 'Parent Item', 'charity' ),
		'parent_item_colon'          => __( 'Parent Item:', 'charity' ),
		'new_item_name'              => __( 'New Item Name', 'charity' ),
		'add_new_item'               => __( 'Add New Item', 'charity' ),
		'edit_item'                  => __( 'Edit Item', 'charity' ),
		'update_item'                => __( 'Update Item', 'charity' ),
		'view_item'                  => __( 'View Item', 'charity' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'charity' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'charity' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'charity' ),
		'popular_items'              => __( 'Popular Items', 'charity' ),
		'search_items'               => __( 'Search Items', 'charity' ),
		'not_found'                  => __( 'Not Found', 'charity' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'portfolio-type', array( 'charity-portfolio' ), $args );
	flush_rewrite_rules();
}

// Hook into the 'init' action
add_action( 'init', 'charity_portfolio_type_taxonomy', 0 );

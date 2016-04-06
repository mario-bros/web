<?php
// Register Custom Post Type For Charity Causes
function charity_causes_post_type() {

	$labels = array(
		'name'                => _x( 'Projects', 'Post Type General Name', 'charity' ),
		'singular_name'       => _x( 'Projects', 'Post Type Singular Name', 'charity' ),
		'menu_name'           => __( 'Projects', 'charity' ),
		'parent_item_colon'   => __( 'Parent Item:', 'charity' ),
		'all_items'           => __( 'All Items', 'charity' ),
		'view_item'           => __( 'View Item', 'charity' ),
		'add_new_item'        => __( 'Add New Item', 'charity' ),
		'add_new'             => __( 'Add New', 'charity' ),
		'edit_item'           => __( 'Edit Item', 'charity' ),
		'update_item'         => __( 'Update Item', 'charity' ),
		'search_items'        => __( 'Search Item', 'charity' ),
		'not_found'           => __( 'Not found', 'charity' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'charity' ),
	);
	$args = array(
		'label'               => __( 'charity_causes', 'charity' ),
		'description'         => __( 'Causes for donationation', 'charity' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		//'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		//'menu_position'       => 5,
		'menu_icon'           => 'dashicons-menu',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'rewrite'            => array( 'slug' => 'projects' ),
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	flush_rewrite_rules();
	register_post_type( 'charity-causes', $args );

}

// Hook into the 'init' action
add_action( 'init', 'charity_causes_post_type', 0 );


// Register Custom Taxonomy
function causes_category_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Category', 'Taxonomy General Name', 'charity' ),
		'singular_name'              => _x( 'Categories', 'Taxonomy Singular Name', 'charity' ),
		'menu_name'                  => __( 'Categories', 'charity' ),
		'all_items'                  => __( 'All Items', 'charity' ),
		'parent_item'                => __( 'Parent Item', 'charity' ),
		'parent_item_colon'          => __( 'Parent Item:', 'charity' ),
		'new_item_name'              => __( 'New Item Name', 'charity' ),
		'add_new_item'               => __( 'Add New Item', 'charity' ),
		'edit_item'                  => __( 'Edit Item', 'charity' ),
		'update_item'                => __( 'Update Item', 'charity' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'charity' ),
		'search_items'               => __( 'Search Items', 'charity' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'charity' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'charity' ),
		'not_found'                  => __( 'Not Found', 'charity' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
		'rewrite' => array( 'slug' => 'projects-type' ),
	);
	flush_rewrite_rules();
	register_taxonomy( 'causes-category', array( 'charity-causes' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'causes_category_taxonomy', 0 );

// Register Custom Taxonomy
function causes_location_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Locations', 'Taxonomy General Name', 'charity' ),
		'singular_name'              => _x( 'Location', 'Taxonomy Singular Name', 'charity' ),
		'menu_name'                  => __( 'Locations', 'charity' ),
		'all_items'                  => __( 'All Items', 'charity' ),
		'parent_item'                => __( 'Parent Item', 'charity' ),
		'parent_item_colon'          => __( 'Parent Item:', 'charity' ),
		'new_item_name'              => __( 'New Item Name', 'charity' ),
		'add_new_item'               => __( 'Add New Item', 'charity' ),
		'edit_item'                  => __( 'Edit Item', 'charity' ),
		'update_item'                => __( 'Update Item', 'charity' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'charity' ),
		'search_items'               => __( 'Search Items', 'charity' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'charity' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'charity' ),
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
		'rewrite'               	 => array( 'slug' => 'projects-location' ),
	);
	flush_rewrite_rules();
	register_taxonomy( 'causes-location', array( 'charity-causes' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'causes_location_taxonomy', 0 );


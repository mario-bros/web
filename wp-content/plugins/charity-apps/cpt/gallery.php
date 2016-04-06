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
function gallery_post_type() {

    $labels = array(
        'name' => _x('Galleries', 'Post Type General Name', 'charity'),
        'singular_name' => _x('Gallery', 'Post Type Singular Name', 'charity'),
        'menu_name' => __('Gallery', 'charity'),
        'name_admin_bar' => __('Gallery', 'charity'),
        'parent_item_colon' => __('Parent Item:', 'charity'),
        'all_items' => __('All Items', 'charity'),
        'add_new_item' => __('Add New Item', 'charity'),
        'add_new' => __('Add New', 'charity'),
        'new_item' => __('New Item', 'charity'),
        'edit_item' => __('Edit Item', 'charity'),
        'update_item' => __('Update Item', 'charity'),
        'view_item' => __('View Item', 'charity'),
        'search_items' => __('Search Item', 'charity'),
        'not_found' => __('Not found', 'charity'),
        'not_found_in_trash' => __('Not found in Trash', 'charity'),
    );
    $args = array(
        'label' => __('Gallery', 'charity'),
        'description' => __('Post Type Description', 'charity'),
        'labels' => $labels,
        'supports' => array('title',  'excerpt'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
       // 'menu_position' => 5,
        'menu_icon' => 'dashicons-images-alt',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => true,
		//'rewrite' => array( 'slug' => 'gallery' ),
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );
   // flush_rewrite_rules();
    register_post_type('charity-gallery', $args);
}

// Hook into the 'init' action
add_action('init', 'gallery_post_type', 0);

// Register Custom Taxonomy
function gallery_type_taxonomy() {

    $labels = array(
        'name' => _x('Type', 'Taxonomy General Name', 'charity'),
        'singular_name' => _x('Types', 'Taxonomy Singular Name', 'charity'),
        'menu_name' => __('Types', 'charity'),
        'all_items' => __('All Items', 'charity'),
        'parent_item' => __('Parent Item', 'charity'),
        'parent_item_colon' => __('Parent Item:', 'charity'),
        'new_item_name' => __('New Item Name', 'charity'),
        'add_new_item' => __('Add New Item', 'charity'),
        'edit_item' => __('Edit Item', 'charity'),
        'update_item' => __('Update Item', 'charity'),
        'view_item' => __('View Item', 'charity'),
        'separate_items_with_commas' => __('Separate items with commas', 'charity'),
        'add_or_remove_items' => __('Add or remove items', 'charity'),
        'choose_from_most_used' => __('Choose from the most used', 'charity'),
        'popular_items' => __('Popular Items', 'charity'),
        'search_items' => __('Search Items', 'charity'),
        'not_found' => __('Not Found', 'charity'),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
		'rewrite' => array( 'slug' => 'gallery-type' )
    );
    
    register_taxonomy('gallery-type', array('charity-gallery'), $args);
    flush_rewrite_rules();
}

// Hook into the 'init' action
add_action('init', 'gallery_type_taxonomy', 0);

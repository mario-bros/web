<?php

/**
 * Charity functions 
 * @package charity
 * @version     v.1.0
 * 
 */
define('CHY_THEME_DIR', get_template_directory());
define('CHY_THEME_URL', get_template_directory_uri());


require_once CHY_THEME_DIR . "/inc/theme-setup.php";
require_once CHY_THEME_DIR . "/inc/enqueue-scripts.php";
require_once CHY_THEME_DIR . "/inc/login-form.php";
require_once CHY_THEME_DIR . "/inc/helper-functions.php";
require_once CHY_THEME_DIR . "/inc/comment-list-callback.php";
require_once CHY_THEME_DIR . "/inc/causes-helper.php";
require_once CHY_THEME_DIR . "/inc/breadcrumb-hooks.php";
require_once CHY_THEME_DIR . "/inc/causes-hooks.php";
require_once CHY_THEME_DIR . "/inc/post-format-hooks.php";
require_once CHY_THEME_DIR . "/inc/portfolio-hooks.php";
require_once CHY_THEME_DIR . "/inc/gallery-hooks.php";
require_once CHY_THEME_DIR . "/inc/page-layout.php";

require_once CHY_THEME_DIR . "/inc/lib/pagination.php";
require_once CHY_THEME_DIR . "/inc/lib/breadcrumb.php";
require_once CHY_THEME_DIR . "/inc/lib/nav-walker.php";
require_once CHY_THEME_DIR . "/inc/lib/image-resize.php";
require_once CHY_THEME_DIR . "/inc/lib/social-links.php";
require_once CHY_THEME_DIR . "/inc/lib/donation-payment.php";

require_once CHY_THEME_DIR . "/inc/user/meta.php";

require_once CHY_THEME_DIR . "/inc/widgets/register-sidebar.php";
require_once CHY_THEME_DIR . "/inc/widgets/widgets.php";

require_once CHY_THEME_DIR . "/inc/woocommerce/woocommerce.php";


require_once CHY_THEME_DIR . '/vendor/vafpress/vafpress.php';
require_once CHY_THEME_DIR . '/vendor/import/charity-import.php';


add_action( 'wp_enqueue_scripts', 'mytheme_scripts' );
/**
 * Enqueue Dashicons style for frontend use
 */
function mytheme_scripts() {
	wp_enqueue_style( 'dashicons' );
}


add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

// Our hooked in function - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
     $address_fields['city']['class'] = array('form-row-first');
     $address_fields['postcode']['class'] = array('form-row-last');
	 
	
     $address_fields['country']['clear'] = 0;
     $address_fields['country']['required'] = false;
	 $address_fields['region']['required'] = false;
	 $address_fields['state']['required'] = false;	 
     $address_fields['account_password']['required'] = false;	 
	 $address_fields['address_1']['type'] = 'textarea';
	 $address_fields['postcode']['clear'] = 0;
	 $address_fields['city']['clear'] = 0;

     return $address_fields;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_wc_checkout_fields_placeholders' );
// Our hooked in function - $fields is passed via the filter!
// Action: add placeholder for $fields

function custom_wc_checkout_fields_placeholders($fields) {
    // loop by category
    foreach ($fields as $category => $value) {
        // loop by fields
        foreach ($fields[$category] as $field => $property) {
            // remove label property
			$fields[$category][$field]['placeholder'] = $fields[$category][$field]['label'];

        }
    }
     return $fields;
}


// WooCommerce Checkout Fields Hook
add_filter('woocommerce_checkout_fields','custom_wc_checkout_fields_no_label');

// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function custom_wc_checkout_fields_no_label($fields) {
    // loop by category
    foreach ($fields as $category => $value) {
        // loop by fields
        foreach ($fields[$category] as $field => $property) {
            // remove label property
            unset($fields[$category][$field]['label']);
        }
    }
     return $fields;
}

 
function test_modify_post_table($columns){
    $new_columns = (is_array($columns)) ? $columns : array();
    unset( $new_columns['order_actions'] );

    //edit this for you column(s)
    //all of your columns will be added before the actions column
    $new_columns['recurring'] = 'Recurring';
    $new_columns['donation'] = 'Donation Type';
	$new_columns['paid'] = 'Last Payment';
    //stop editing

    $new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
}


//function test_modify_post_table($columns){
//    $new_columns = (is_array($columns)) ? $columns : array();
//    unset( $new_columns['order_actions'] );
//
//    //edit this for you column(s)
//    //all of your columns will be added before the actions column
//    $new_columns['recurring'] = 'Recurring';
//    $new_columns['donation'] = 'Donation Type';
//	$new_columns['paid'] = 'Last Payment';
//    //stop editing
//
//    $new_columns['order_actions'] = $columns['order_actions'];
//    return $new_columns;
//}
//
//add_filter( 'manage_shop_order_posts_columns', 'test_modify_post_table' );
//
//
//function test_modify_post_table_row( $column_name, $post_id ) {
// 
//    $custom_fields = get_post_custom( $post_id );
//    global $wpdb;
//	//print_r($orderfields);
//	
//	
// 
//	
// 
//    switch ($column_name) {
//        case 'recurring' :
//            echo $custom_fields['_recurring_profile'][0] . '';
//            break;
// 
//        case 'paid' :
//        echo $custom_fields['_paid_date'][0] . '';
//            break;
//        case 'donation' :
// $qrytwee = "SELECT meta_value FROM wp_woocommerce_order_itemmeta where meta_key = 'order_item_name' and order_item_id = (select order_item_id from wp_woocommerce_order_items where order_id = '$post_id')";
//  //echo $qrytwee;
//  $statestwee = $wpdb->get_results( $qrytwee );
//	foreach( $statestwee as $rowtwee ) {
//		$donationfor = "Donation for " . $rowtwee->meta_value;
//	}			
//		
//        echo $donationfor;
//            break;			
// 
//        default:
//    }
//}
// 
//add_action( 'manage_shop_order_posts_custom_column', 'test_modify_post_table_row', 10, 2 );


add_filter( 'manage_edit-shop_order_columns', 'MY_COLUMNS_FUNCTION' );
function MY_COLUMNS_FUNCTION($columns){
	
	
	print_r($columns);
	
    $new_columns = (is_array($columns)) ? $columns : array();
    unset( $new_columns['order_actions'] );
	unset ($new_columns['order_items']);
	unset ($new_columns['customer_message']);
	unset ($new_columns['order_notes']);
	$new_columns['order_title'] = "Transactions";
	$new_columns['shipping_address'] = "Contributor";
	
 
  
	

    //edit this for you column(s)
    //all of your columns will be added before the actions column
    $new_columns['recurring'] = 'Recurring';
    $new_columns['donation'] = 'Donation Type';
	$new_columns['paid'] = 'Last Transaction';
	$new_columns['next'] = 'Next Transaction';
    //stop editing

    $new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'MY_COLUMNS_VALUES_FUNCTION', 2 );
function MY_COLUMNS_VALUES_FUNCTION($column){
	
	
	
	
    global $post;
	global $wpdb;
    $data = get_post_custom( $post->ID );
	$post_id = $post->ID;

    //start editing, I was saving my fields for the orders as custom post meta
    //if you did the same, follow this code
    if ( $column == 'recurring' ) {    
        echo (isset($data['_recurring_profile'][0]) ? $data['_recurring_profile'][0] : '-');
    }
    if ( $column == 'paid' ) {    
        echo (isset($data['_trans_date'][0]) ? $data['_trans_date'][0] : '-');
    }
	
    if ( $column == 'next' ) {
		$final = "-";
		$lastdate = $data['_paid_date'][0];
		$lastdatecheck = explode(" ", $lastdate);
		$period = $data['_recurring_profile'][0];
		if ($period == "monthly") {
		$nextdate = $lastdatecheck[0];
		$time = strtotime($nextdate);
		$final = date("Y-m-d", strtotime("+1 month", $time));	
		$final = $final . " " . $lastdatecheck[1];	 	
		}
		if ($period == "yearly") {
		$nextdate = $lastdatecheck[0];
		$time = strtotime($nextdate);
		$final = date("Y-m-d", strtotime("+1 year", $time));	
		$final = $final . " " . $lastdatecheck[1];	 	
		}		
        echo $final;
    }	
	
    if ( $column == 'donation' ) {   
	
 $qrytwee = "SELECT meta_value FROM wp_woocommerce_order_itemmeta where meta_key = 'order_item_name' and order_item_id = (select order_item_id from wp_woocommerce_order_items where order_id = '$post_id')";
//  //echo $qrytwee;
  $statestwee = $wpdb->get_results( $qrytwee );
  $donationfor = "";
	foreach( $statestwee as $rowtwee ) {
		$donationfor = "Donation for " . $rowtwee->meta_value;
	}	
	 
        echo (isset($donationfor) ? $donationfor : '-');
    }	
    //stop editing

	if ($column == "order_actions") {
		if (get_post_status( $post_id ) == "wc-failed")
		{
			?>
			<div class="spinnerpush_<?php echo $post_id; ?>"></div>
			<style>
.spinnerpush_<?php echo $post_id; ?> {
background: url('http://www.pedulianak.org/wp-admin/images/wpspin_light.gif') no-repeat;
background-size: 16px 16px;
display: none;
float: right;
opacity: .7;
filter: alpha(opacity=70);
width: 16px;
height: 16px;
margin: 5px 5px 0;
}			
			</style>
			<a href="#" onclick="manualpushbuckaroo(<?php echo $post_id; ?>); return false;" id="pushbuckaroo_<?php echo $post_id; ?>">Push</a>	
			<?php
		}
	}
	
}



// Register New Order Statuses
function wpex_wc_register_post_statuses() {
	register_post_status( 'wc-recurring', array(
		'label'						=> _x( 'Recurring Payments', 'WooCommerce Order status', 'text_domain' ),
		'public'					=> true,
		'exclude_from_search'		=> false,
		'show_in_admin_all_list'	=> true,
		'show_in_admin_status_list'	=> true,
		'label_count'               => _n_noop( 'Recurring Payments', 'Recurring Payments', 'woocommerce' )
	) );
}
add_filter( 'init', 'wpex_wc_register_post_statuses' );

// Add New Order Statuses to WooCommerce
function wpex_wc_add_order_statuses( $order_statuses ) {
	$order_statuses['wc-recurring'] = _x( 'Recurring Payments', 'WooCommerce Order status', 'text_domain' );
	return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wpex_wc_add_order_statuses' );

//restrict the posts by an additional author filter
function add_author_filter_to_posts_query($query){

    global $post_type, $pagenow; 

    //if we are currently on the edit screen of the post type listings
	
	
	
	
    if($pagenow == 'edit.php' && $post_type == 'shop_order'){

		$args = array(
           
           
            'meta_key' => '_trans_date',
            'orderby' => 'meta_value',
            'order' => 'DESC'
        );

		


        if(isset($_GET['post_status'])){

            //set the query variable for 'author' to the desired value
            $status = sanitize_text_field($_GET['post_status']);

            //if the author is not 0 (meaning all)
            if($status == "wc-recurring"){
               $query->set('post_status', array( 'wc-completed', 'wc-recurring' ) );
			   $query->set('meta_query', array(array('key' => '_recurring_profile','value'   => array('yearly', 'monthly'),'compare' => 'IN',)));

            }

        }
    }
}

add_action('pre_get_posts','add_author_filter_to_posts_query');


add_action('my_hourly_event', 'do_this_hourly');

function my_activation() {
	if ( !wp_next_scheduled( 'my_hourly_event' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'my_hourly_event');
	}
}
add_action('wp', 'my_activation');

function do_this_hourly() {

	
	
}

function be_listing_query( $query ) {
	global $post_type;
	
	if ($post_type == "shop_order") {
		
		// Sort by Featured, then Most Recent
		
			$query->set( 'orderby', array( 'meta_value' => 'DESC' ) );
			$query->set( 'meta_key', '_trans_date' );
		
		
//		// Sort by Featured, then Price (orderby filter toggles low/high)
//		if( in_array( $sort, array( 'price_low', 'price_high' ) ) ) {
//			$query->set( 'orderby', 'meta_value_num' );
//			$query->set( 'order', 'DESC' );
//			$query->set( 'meta_key', 'be_listing_featured' );
//			$query->set( 'meta_query', array(
//				'relation' => 'AND', 
//					array( 'key' => 'be_listing_featured',     'compare' => 'EXISTS' ), 
//					array( 'key' => 'be_listing_asking_price', 'compare' => 'EXISTS' ) 
//				) 
//			);
//		}
	}
}
add_action( 'pre_get_posts', 'be_listing_query' );
<?php
/*  Copyright 2014  Varun Sridharan  (email : varunsridharan23@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
 
    Plugin Name: Woocommerce Quick Donation
    Plugin URI: http://varunsridharan.in/
    Description: Woocommerce Quick Donation
    Version: 1.2
    Author: Varun Sridharan
    Author URI: http://varunsridharan.in/
    License: GPL2
*/
defined('ABSPATH') or die("No script kiddies please!"); 
define( 'wc_qd_u', plugin_dir_url( __FILE__ ) );
define( 'wc_qd_p', plugin_dir_path( __FILE__ ) );

class wc_quick_donation{
	
	public $donation_id;
    private $plugin_v;
	
	/**
	 * Setup The Plugin Class
	 */
	function __construct() {
		$this->donation_id = get_option('wc_quick_donation_product_id');
        $this->plugin_v = '1.2';
		add_shortcode( 'wc_quick_donation', array($this,'shortcode_handler' ));
        
        add_action( 'post_row_actions', array($this,'protect_donation_product'),99,2);
        add_action( 'get_the_generator_html',  array($this,'generate_meta_tags'), 15, 2 );
        add_action( 'get_the_generator_xhtml', array($this,'generate_meta_tags'), 15, 2 );        
		add_action( 'wp_loaded',array($this,'process_donation'),20);  
		add_action( 'wc_qd_show_projects_list',array($this,'get_projects_list'));	
    
		add_action( 'woocommerce_checkout_update_order_meta',  array($this,'save_order_id_db'));
        add_action( 'woocommerce_add_order_item_meta',  array($this,'add_order_meta'),99,3);
        
		
		add_action( 'woocommerce_admin_order_data_after_billing_address', array($this,'custom_order_details_page_info'), 10, 1 ); 
        add_action( 'woocommerce_available_payment_gateways',array($this,'remove_gateway'));
        
        add_filter('woocommerce_hidden_order_itemmeta',array($this,'hide_core_fields'));
        add_filter( 'woocommerce_get_price', array($this,'get_price'),10,2);
		add_filter( 'woocommerce_get_settings_pages',  array($this,'settings_page') );
		add_filter( 'woocommerce_email_classes',  array($this,'email_classes'));
		add_filter('woocommerce_enable_order_notes_field',array($this,'order_notes'));
		
		
		
		
	}
 
	/**
	 * Hides Order Notes
	 * @return boolean [[Description]]
	 * @since 1.2
	 */
	public function order_notes(){ 
		if($this->donation_exsits() &&  $this->only_donation_exsits()){ 
			if(get_option('wc_quick_donation_hide_order_notes') === true){
				return false;
			}
		}
		return true;
	}
	
    /**
     * Adds Donation Meta Tag
     * @param   String $gen  Refer WP.ORG
     * @param   String $type Refer WP.ORG 
     * @returns String
     * @since 0.4
     */
    public function generate_meta_tags( $gen, $type ) {
        switch ( $type ) {
            case 'html':
                $gen .= "\n" . '<meta name="generator" content="WooCommerce Quick Donation '.$this->plugin_v.'">';
                break;
            case 'xhtml':
                $gen .= "\n" . '<meta name="generator" content="WooCommerce Quick Donation '.$this->plugin_v.'" />';
                break;
        }
        return $gen;
    }    
    
    /**
     * Hides Some Important Fields 
     * @since 1.0
     * @param   [[Type]] $fields [[Description]]
     * @returns [[Type]] [[Description]]
     */
    public function hide_core_fields($fields){
        $fields[] = '_is_donation';
        $fields[] = '_project_details';
        return $fields;
    }
                   
	/**
	 * Adds Settings Page
	 */
 	public function settings_page( $settings ) {
		$settings[] = include( wc_qd_p.'wc_qd_settings.php' );  
		return $settings;
	}
	 
	/**
	 * Adds Email Classes
	 */
	public function email_classes($email_classes){
		require_once( wc_qd_p.'wc_qd_email_processing.php' );
		require_once( wc_qd_p.'wc_qd_email_completed.php' );
		$email_classes['wc_quick_donation_processing_donation_email'] = new wc_quick_donation_processing_donation_email();
		$email_classes['wc_quick_donation_completed_donation_email'] = new wc_quick_donation_completed_donation_email();
		return $email_classes;		
	}	
	
	/**
	 * Adds Donation Order Meta. [Project Name]
	 * @param [[Type]] $order_id [[Description]]
	 * @since 0.2
     * @updated 1.0
	 */
	public function add_order_meta( $item_id, $values, $cart_item_key) { 
        if($this->donation_id == $values['product_id']){
            global $woocommerce;
            wc_add_order_item_meta( $item_id, "_project_details",$woocommerce->session->projects);	
            wc_add_order_item_meta( $item_id, "_is_donation",'yes');
        }
	} 
    
    /**
     * Saves Donation Order ID and adds order notes...
     * @param [[Type]] $order_id [[Description]]
     * @since 1.0
     * @adopedFrom add_order_meta
     */
    public function save_order_id_db($order_id){
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        foreach($items as $item){
            $order_product_id = floatval($item['product_id']);
            if($this->donation_id == $order_product_id){
                $this->update_order_id($order_id);
                $format = sprintf(get_option('wc_quick_donation_order_notes_title'), $woocommerce->session->projects);
                $order->add_order_note($format);
                update_post_meta($order_id,'_is_donation',true);
            }
        }
        unset($order);
        return $order_id;
    }
 

    /**
     * Updates Order ID to [wc_quick_donation_ids] when donation is ordered
     * @param [int] $order_id [Donation Order ID]
     * @since 1.0
     */
    private function update_order_id($order_id){
        $ordersID = get_option('wc_quick_donation_ids');
        $save_order_id = array();
        if(empty($ordersID)){
            $save_order_id[] = $order_id;
        } else {
            $save_order_id = json_decode($ordersID,true);
            $save_order_id[] = $order_id;
        }
        update_option('wc_quick_donation_ids',json_encode($save_order_id));
        
    }
	
	/**
	 * Custom Title In Order View Page
	 * @since 1.0
	 */
	public function custom_order_details_page_info($order){
        $is_donation = wc_get_order_item_meta($order->id, '_is_donation');
        if($is_donation === true){
            echo '<p><strong>'.get_option('wc_quick_donation_project_section_title').' :</strong>'.wc_get_order_item_meta($order->id, '_project_details') . '</p>';
        }
        
		
	} 	
	
	
	 
    
	/**
	 * Get All Enabled And Avaiable Payment Gateway To List In Settings Page
	 * @returns array [Aviable Gateways]
	 */
	public function get_payments_gateway(){
		$payment = WC()->payment_gateways->payment_gateways();
		$gateways = array();
		foreach($payment as $gateway){
			if ( $gateway->enabled == 'yes' ){
				$gateways[$gateway->id] = $gateway->title;
			}
		}
		return $gateways;
	}
	
	
	/**
	 * Check's For Donation Product Exist In Cart
	 * @returns Boolean True|False
	 */
	public function donation_exsits(){
		global $woocommerce; 
		if( sizeof($woocommerce->cart->get_cart()) > 0){
			foreach($woocommerce->cart->get_cart() as $cart_item_key => $values){
				$_product = $values['data'];
				if($_product->id == $this->donation_id){
					return true;	
				}				
			}
		}
		return false;
	}	
	
	
	
	/**
	 * Check's For Donation Product Exist In Cart
	 * @returns Boolean True|False
	 */
	public function only_donation_exsits(){
		global $woocommerce;
        if( sizeof($woocommerce->cart->get_cart()) == 1 && $this->donation_exsits()){
			return true;	
		}
		return false;
	}		
	/**
	 * Gets Donation Current Price
	 * @param  $price 
	 * @param  $product 
	 * @returns 0 | price
	 */
	public function get_price($price, $product){
		global $woocommerce;
		if($product->id == $this->donation_id){ 
			return isset($woocommerce->session->jc_donation) ? floatval($woocommerce->session->jc_donation) : 0; 
		}
		return $price;
	}	
 
	
	/**
	 * Process The Given Donation
	 */
	public function process_donation(){
		global $woocommerce;
		//echo "TEST";
		//print_r($woocommerce);
		//echo $this->donation_id;
		//exit();
		//global $purchase_data;
		//print_r($_POST);
		
//Array ( [donation_add] => 1 [give-form-id] => 834 [give-form-title] => Donate now and help Peduli Anak [give-current-url] => http://peduli.laucaconsultancy.nl/donations/donate-now-and-help-peduli-anak/ [give-form-url] => http://peduli.laucaconsultancy.nl/donations/donate-now-and-help-peduli-anak/ [give-amount] => 50.00 [give-user-id] => 1 [give_action] => purchase [give-gateway] => buckaroo ) 		
		
		if(isset($_POST['donation_add'])){
			$error = 0;
			$found = false;
			$donation = isset($_POST['give-amount']) ? $_POST['give-amount'] : false;
			$recur_period = $_POST['recur_period'];
			
			$livplan = $_POST['give-liv-plan'];
			$eduplan = $_POST['give-edu-plan'];			
			
			//echo "liv " . $livplan;
			//echo "edu " . $eduplan;
			//exit();
			
			
			$projects = isset($_POST['give-form-title']) && !empty($_POST['give-form-title']) ? $_POST['give-form-title'] : false;
			$_SESSION['wc_qd_projects'] = $projects;  
			$_SESSION['recur_period'] = $_POST['recur_period'];
			$_SESSION['edu_plan'] = $eduplan;  
			$_SESSION['liv_plan'] = $livplan; 
			if ($projects == "Donate Now") {
				$name = "Peduli Anak";
			} else { 
			$name = $projects;
			}
            
			
            if(! empty($donation)){
                $donate_price = floatval($donation); 
     
            } 
			if($error ==0 ) {
                if($donation >= 0){
                    $woocommerce->session->jc_donation = $donation;
                    $woocommerce->session->projects = $projects; 
					
   $items = $woocommerce->cart->get_cart();


					
					
                    if( sizeof($woocommerce->cart->get_cart()) > 0){
                        foreach($woocommerce->cart->get_cart() as $cart_item_key=>$values){
                            $_product = $values['data'];
							//print_r($values);
							//exit();
                            if($_product->id == $this->donation_id)
                                $found = true;
                        }

                        if(! $found){ 
							$this->add_donation_cart($name);
						 	
						} else {
							$this->add_donation_cart($name);
						}
							
                            
                    }else{
                        $this->add_donation_cart($name);
                    }
                }			
            }
		}
		
 
	}	
	
	/**
	 * Adds Donation Product To Cart
	 */
	private function add_donation_cart($name){
		global $woocommerce; 
		$this->remove_cart_items();
		$woocommerce->cart->add_to_cart($this->donation_id,'1','',array('order_item_name' => $name));
        $this->redirectCART();
		
	}

	/**
	 * Redirect To Checkout Page After Donation is Added
	 */
	public function redirectCART(){
		global $woocommerce; 
        $redirect_op = get_option('wc_quick_donation_redirect');
        if($redirect_op == 'cart'){
           wp_safe_redirect(WC()->cart->get_checkout_url() );exit; 
        } else if($redirect_op == 'checkout'){
            wp_safe_redirect(WC()->cart->get_checkout_url() );exit; 
        }
		 
	}
	
	
	/**
	 * Allowes only selected payment gateway for donation product.
	 * @since 0.2
	 * @access public
	 */
	public function remove_gateway($gateway){ 
		if($this->donation_exsits() &&  $this->only_donation_exsits()){
			$payments = get_option('wc_quick_donation_payment_gateway'); 
			
			if(!empty($payments)){
				foreach($gateway as $val){
					if(! in_array($val->id,$payments)){
						unset($gateway[$val->id]);
					}  
				}
			}
			return $gateway;	
		} 
		return $gateway;	
	}
	
	/**
	 * Removes Cart ITEM if donation is added
	 * @returns Boolean [[Description]]
	 */
	private function remove_cart_items(){
		$cart_remove = get_option('wc_quick_donation_cart_remove');
		if(isset($cart_remove) && $cart_remove == 'true'){
			global $woocommerce;
			$woocommerce->cart->empty_cart();
			return true;
		}
		return true;
	}


	/**
	 * Gets Donation Form.
	 * @updated 1.2
	 */
	public function wc_qd_form(){
		global $woocommerce; 
        
		$donate = isset($woocommerce->session->jc_donation) ? floatval($woocommerce->session->jc_donation) : 0;
		if(!$this->donation_exsits()){  
			unset($woocommerce->session->jc_donation); 
			unset($woocommerce->session->projects); 
		}
		
		// $donate = jc_round_donation($woocommerce->cart->total ); 
		$show_form_donatio_exist = get_option('wc_quick_donation_hide_form');
		if($this->donation_exsits() && $show_form_donatio_exist){
			$this->_load_donation_form();
		}else if(! $this->donation_exsits()){
			$this->_load_donation_form();
			
		}
		
		 
	}	
	 
 	/**
 	 * Requires Donation Form
 	 * Actions 1 wc_quick_donation_before_form
 	 * Actions 2 wc_quick_donation_after_form
 	 * @since 1.2
 	 */
 	private function _load_donation_form(){
		do_action('wc_quick_donation_before_form');
		$wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';
		$wc_get_template( 'donation_form.php', array(), '', wc_qd_p . 'template/' ); 
		do_action('wc_quick_donation_after_form');
	}
	
	public function donation_projects(){
		$projects_db = get_option('wc_quick_donation_projects');
		if(!empty($projects_db) && $projects_db != null){
			$project = explode(',',$projects_db);
			return $project;
		}
		return false;
	}
	
	
	/**
	 * Generates Select Box For Projects List
	 * @updated 1.2
	 */
	public function get_projects_list(){
			$projects_db = $this->donation_projects();
			if($projects_db){
				$project_list = '';
				$project_list .= '<option value="" > Select A Project </option>';
				foreach($projects_db as $proj){
					$project_list .= '<option value="'.$proj.'" > '.$proj.'</option>';
				}
				echo '<select name="projects">'.$project_list.'</select>';
			}
	}
	
	
	/**
	 * wc_quick_donation shortcode Handler
	 */
	public function shortcode_handler(){
		return $this->wc_qd_form();
	}
	
	
	/**
	 * Install The Plugin
	 */
	public static function install() {
		$exist = get_option('wc_quick_donation_product_id');

		if($exist){
            if ( get_post_status ( $exist ) ) {
                return true;
            } else {
                $post_id = create_donation();
                add_option('wc_quick_donation_product_id',$post_id); 
                add_site_option( 'wc_quick_donation_product_id', $post_id) ;
            }
			
		} else { 
            $post_id = create_donation();
			add_option('wc_quick_donation_product_id',$post_id); 
            add_option('wc_quick_donation_ids','');
		    add_site_option( 'wc_quick_donation_product_id', $post_id) ;
		}
	}
	
	/**
	* Protects Donation Product By 
	* @filter_user post_row_actions
	* @param  Array $actions Refer WP.org
	* @param  Array $post    Refer WP.org
	* @return Array Refer WP.org
	* @since 1.0
	*/
	public function protect_donation_product($actions,$post) {
		if('product' == $post->post_type) {  
			if($post->ID == $this->donation_id){
				unset($actions['inline hide-if-no-js']);
				unset($actions['trash']);
				unset($actions['duplicate']);
				$actions['trash'] = '<a href="javascript:alert(\'Remove Woocommerce Quick Donation Plugin To Remove This Product \');"> Trash </a>';
				
			}
		}
        return $actions;
	}
     
}


function create_donation(){
    //$userID = 1;
    //if(get_current_user_id()){
    //    $userID = get_current_user_id();
    //}
$current_user = wp_get_current_user();
$userID = $current_user->ID;

    $post = array(
        'post_author' => $userID,
        'post_content' => 'Used For Donation',
        'post_status' => 'publish',
        'post_title' => 'Donation',
        'post_type' => 'product',
    );

    $post_id = wp_insert_post($post);  
    update_post_meta($post_id, '_stock_status', 'instock');
    update_post_meta($post_id, '_tax_status', 'none');
    update_post_meta($post_id, '_tax_class',  'zero-rate');
    update_post_meta($post_id, '_visibility', 'hidden');
    update_post_meta($post_id, '_stock', '');
    update_post_meta($post_id, '_virtual', 'yes');
    update_post_meta($post_id, '_featured', 'no');
    update_post_meta($post_id, '_manage_stock', "no" );
    update_post_meta($post_id, '_sold_individually', "yes" );
    update_post_meta($post_id, '_sku', 'checkout-donation');   			
    return $post_id;
}

/**
 * Check if WooCommerce is active 
 * if yes then call the class
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	register_activation_hook( __FILE__, array( 'wc_quick_donation', 'install' ) );
	$wc_quick_donation = new wc_quick_donation;
    require(wc_qd_p.'wc_qd_donation_orders.php');
    require(wc_qd_p.'wc_qd_widget.php');
    
    function register_foo_widget() {
        register_widget( 'woocommerce_quick_donation_widget' );
    }
    add_action( 'widgets_init', 'register_foo_widget' );
    
} else {
	add_action( 'admin_notices', 'wc_quick_donation_notice' );
}

function wc_quick_donation_notice() {
	echo '<div class="error"><p><strong> <i> Woocommerce Quick Donation </i> </strong> Requires <a href="'.admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce').'"> <strong> <u>Woocommerce</u></strong>  </a> To Be Installed And Activated </p></div>';
}
?>

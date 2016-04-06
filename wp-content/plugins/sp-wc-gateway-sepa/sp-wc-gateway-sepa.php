<?php


	if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


	/**
 	 Plugin Name: SP WC PaymentGateway SEPA
	 Plugin URI: http://specialpress.de/plugins/spwcps
	 Description: Add a SEPA payment gateway to WooCommerce
	 Shortname : spwcsp
	 Version: 2.5.0
	 Date : 2016-03-04
	 Author: Ralf Fuhrmann
	 Author URI: http://naranili.de
	*/


	/**
	 * ToDo
	 *
	 * - Bankleitzahl / Konto-Nummer Konverter, auch Ausland
	 * - Checkbox Auswahl der Bestellungen für die eine SEPA XML erstellt werden soll
	 * - IBAN und BIC validation via SOAP
	 * - COR1 Express Support
	 * - SEPA AVIS an Kunden senden wenn SEPA-XML erzeugt wird
	 * - SEPA Email an Admin senden, mit allen Daten der SEPA-Datei und Datei als Anhang
	 * - RCUR setzen, sofern Kunde schon einmal gezahlt hat
	 * - Anzeige aller Order einer SEPA-Transaktion
	 * - Subscription Emails anpassen
	 * - digitip lib einbinden
	 */


	error_reporting( E_ERROR );



	require_once( plugin_dir_path( __FILE__ ) . 'php-iban-master/oophp-iban.php' );
	




	/**
	 * Init Database
	 */
	register_activation_hook( __FILE__ , 'spwcsp_activation_hook' );



	/**
	 * Load the Plugin
	 */
	add_action( 'plugins_loaded', 'init_SPWC_gateway_class' );
	


	/**
	 * Init the Plugin
	 */
	function init_SPWC_gateway_class() 
	{


		/**
		 * Add the payment class to the WooCommerce backend
		 */
		add_filter( 'woocommerce_payment_gateways', 'add_SPWC_gateway_class' );
		function add_SPWC_gateway_class( $methods ) 
		{
	
			$methods[] = 'SPWC_Payment_Gateway_SEPA'; 
			return $methods;

		}


		/**
		 * Actions
		 */
		add_action( 'admin_menu', 'spwcsp_menu_sepa' );
		add_action( 'manage_shop_order_posts_custom_column', 'spwcsp_manage_shop_order_posts_custom_column', 99, 2 );
		add_action( 'show_user_profile', 'spwcsp_show_custom_user_profile_fields' );
		add_action( 'edit_user_profile', 'spwcsp_show_custom_user_profile_fields' );
		add_action( 'personal_options_update', 'spwcsp_save_custom_user_profile_fields' );
		add_action( 'edit_user_profile_update', 'spwcsp_save_custom_user_profile_fields' );
		add_action( 'woocommerce_edit_account_form', 'spwcsp_woocommerce_edit_account_form' );
		add_action( 'woocommerce_save_account_details', 'spwcsp_woocommerce_save_account_details' );
		add_action( 'woocommerce_save_account_details_errors' , 'spwcsp_woocommerce_save_account_details_errors' );


		/**
		 * Filters
		 */
		add_filter( 'user_profile_update_errors', 'spwcsp_user_profile_update_errors', 10, 3);
		add_filter( 'woocommerce_process_myaccount_field_sepa_iban' , 'spwcsp_woocommerce_process_myaccount_field_sepa_iban' );

		/**
		 * Load the Textdomain
		 */
		if( function_exists( 'load_plugin_textdomain' ) )
			load_plugin_textdomain( 'spwcsp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


		
		/**
		 * Based on the WooCommerce BACS and PayPal payment gateway
		 */
		class SPWC_Payment_Gateway_SEPA extends WC_Payment_Gateway 
		{


			const GATEWAY_ID = 'sepa';
			
    
			/**
			 * Constructor for the gateway.
			 */
			function __construct() 
			{
		
		
				$this->id                 = self::GATEWAY_ID;
				$this->icon               = apply_filters( 'woocommerce_sepa_icon', '' );
				$this->has_fields         = true;
				$this->method_title       = __( 'SEPA', 'spwcsp' );
				$this->method_description = __( 'Allows payments by SEPA, more commonly known as direct debit.', 'spwcsp' );


				/**
				 * Add support for products and subcriptions
				 */
				$this->supports = array( 
					'products', 
					'subscriptions',
					'subscription_cancellation',
					'subscription_suspension',
					'subscription_reactivation',
					'subscription_amount_changes',
					'subscription_date_changes',
					'subscription_payment_method_change',
					'multiple_subscriptions'					
					);
				
				
				/**
				 * Load the settings
				 */
				$this->init_form_fields();
				$this->init_settings();

				
				/** 
				 * Define user set variables
				 */
				$this->title = $this->get_option( 'title' );
				$this->description = $this->get_option( 'description' );
				$this->instructions = $this->get_option( 'instructions', $this->description );
				$this->sepaAccountOwner = $this->get_option( 'sepaAccountOwner' );
				$this->sepaIBAN = $this->get_option( 'sepaIBAN' );
				$this->sepaBIC = $this->get_option( 'sepaBIC' );
				$this->sepaCreditorId = $this->get_option( 'sepaCreditorId' );
				$this->sepaMandateId = $this->get_option( 'sepaMandateId' );


				/**
				 * WooCommerce Actions
				 */
				add_action( 'woocommerce_thankyou_' . self::GATEWAY_ID, array( $this, 'thankyou_page' ) );
				add_action( 'woocommerce_update_options_payment_gateways_' . self::GATEWAY_ID, array( $this, 'process_admin_options' ) );
				add_action( 'woocommerce_after_my_account', array( $this, 'show_sepa_account') );
				
				
				/**
				 * If WooCommerce Subscription is installed, add
				 * some special actions and filters
				 */
				if ( class_exists( 'WC_Subscriptions_Order' ) ) 
				{
				
				
					add_action( 'woocommerce_scheduled_subscription_payment_' . self::GATEWAY_ID,  array( $this, 'scheduled_subscription_payment' ), 10, 2 );
					add_filter( 'wcs_renewal_order_meta', array( $this, 'add_renewal_order_meta' ), 10, 3 );
					
				}
				

				/**
				 * Custom Emails
				 */
				add_action( 'woocommerce_email_before_order_table', array( $this, 'add_order_email_instructions' ), 10, 3 );				


			}


			
			/**
			 * Initialise Gateway Settings Form Fields
			 */
			public function init_form_fields() 
			{


				$this->form_fields = array(
					'enabled' => array(
						'title'   => __( 'Enable/Disable', 'woocommerce' ),
						'type'    => 'checkbox',
						'label'   => __( 'Enable SEPA Payment', 'spwcsp' ),
						'default' => 'yes',
						'description' => __( 'SEPA Payment only works with EUR as currency.', 'spwcsp' )
						),
					'title' => array(
						'title'       => __( 'Title', 'woocommerce' ),
						'type'        => 'text',
						'description' => __( 'This controls the title which the user will see during checkout.', 'woocommerce' ),
						'default'     => __( 'SEPA Direct Debit', 'spwcsp' ),
						'desc_tip'    => true,
						),
					'description' => array(
						'title'       => __( 'Description', 'woocommerce' ),
						'type'        => 'textarea',
						'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
						'default'     => __( 'We will debit the amount directly from your account via SEPA direct debit. Your order won\'t be shipped until the funds have cleared in our account.', 'spwcsp' ),
						'desc_tip'    => true,
						),
					'instructions' => array(
						'title'       => __( 'Instructions', 'woocommerce' ),
						'type'        => 'textarea',
						'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woocommerce' ),
						'default'     => '',
						'desc_tip'    => true,
						),
					'sepaAccountOwner' => array(
						'title'       => __( 'Account Owner', 'spwcsp' ),
						'type'        => 'text',
						'description' => __( 'The Owner of your bank account.', 'spwcsp' ),
						'default'     => '',
						'desc_tip'    => true,
						),
					'sepaIBAN' => array(
						'title'       => __( 'Account IBAN', 'spwcsp' ),
						'type'        => 'text',
						'description' => __( 'Your IBAN.', 'spwcsp' ),
						'default'     => '',
						'desc_tip'    => true,
						),
					'sepaBIC' => array(
						'title'       => __( 'Account BIC', 'spwcsp' ),
						'type'        => 'text',
						'description' => __( 'Your BIC.', 'spwcsp' ),
						'default'     => '',
						'desc_tip'    => true,
						),
					'sepaCreditorId' => array(
						'title'       => __( 'Creditor Id', 'spwcsp' ),
						'type'        => 'text',
						'description' => __( 'Your Creditor ID.', 'spwcsp' ),
						'default'     => '',
						'desc_tip'    => true,
						),
					'sepaMandateId' => array(
						'title'       => __( 'Mandate Id', 'spwcsp' ),
						'type'        => 'text',
						'description' => __( 'The default mandate id. This will be extended by the order-id.', 'spwcsp' ),
						'default'     => '',
						'desc_tip'    => true,
						),
					);
    

			}

    

			/**
			 * Output for the order received page.
			 */
			public function thankyou_page( $order_id ) 
			{
		

				if( $this->instructions )
					echo wpautop( wptexturize( wp_kses_post( $this->instructions ) ) );

				$this->bank_details( $order_id );

    
			}



			/**
			 * Add content to the WC emails.
			 */
			public function add_order_email_instructions( $order, $sent_to_admin, $plain_text = false ) 
			{

    	
				if( ! $sent_to_admin && 'sepa' === $order->payment_method && 'on-hold' === $order->status ) 
				{
					
					if( $this->instructions )
						echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;

					$this->bank_details( $order->id );

				}

    
			}



			/**
			 * Get bank details and place into a list format
			 */
			private function bank_details( $order_id = '' ) 
			{


				$sepaAccountOwner = $_POST[ 'sepa_account_owner' ];
				if( empty( $sepaAccountOwner ) )
					$sepaAccountOwner = get_post_meta( $order_id, 'sepaAccountOwner', true );
				$sepaIBAN = $_POST[ 'sepa_iban' ];
				if( empty( $sepaIBAN ) )
					$sepaIBAN = get_post_meta( $order_id, 'sepaIBAN', true );
				$sepaBIC = $_POST[ 'sepa_bic' ];
				if( empty( $sepaBIC ) )
					$sepaBIC = get_post_meta( $order_id, 'sepaBIC', true );

				echo "<strong>" . __("SEPA Bank Details", 'spwcsp') . "</strong><br />";
				echo __("Account Owner", 'spwcsp' ) . ": {$sepaAccountOwner} <br />";
				echo __("Account IBAN", 'spwcsp' ) . ": {$sepaIBAN} <br />";
				echo __("Account BIC", 'spwcsp' ) . ": {$sepaBIC} <br />";
				echo "<br />&nbsp;<br />";
				
    	
			}

    

			/**
			 * Process the payment and return the result
			 */
			public function process_payment( $order_id ) 
			{

		
				$order = new WC_Order( $order_id );

				
				/**
				 * Mark as on-hold (we're awaiting the payment)
				 */
				$order->update_status( 'on-hold', __( 'Awaiting SEPA payment', 'spwcsp' ) );

				
				/**
				 * Reduce stock levels
				 */
				$order->reduce_order_stock();

				
				/**
				 * Get the SEPA payment data
				 */
				$sepaAccountOwner = $_POST[ 'sepa_account_owner' ];
				$sepaIBAN = $_POST[ 'sepa_iban' ];
				$sepaBIC = $_POST[ 'sepa_bic' ];



				/**
				 * Save the SEPA data as post meta
				 */
				update_post_meta( $order_id, 'sepaAccountOwner', $sepaAccountOwner );
				update_post_meta( $order_id, 'sepaIBAN', $sepaIBAN );
				update_post_meta( $order_id, 'sepaBIC', $sepaBIC );


				/**
				 * If there are any subscriptions copy the data to the
				 * subscription posts
				 */
				if( function_exists( 'wcs_get_subscriptions_for_order' ) ) 
				{
				
					foreach( wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'any' ) ) as $subscription ) 
					{
					
						update_post_meta( $subscription->id, 'sepaAccountOwner', $sepaAccountOwner );
						update_post_meta( $subscription->id, 'sepaIBAN', $sepaIBAN );
						update_post_meta( $subscription->id, 'sepaBIC', $sepaBIC );
						delete_post_meta( $order_id, 'sepaTransactionId' );
						delete_post_meta( $order_id, 'sepaTransactionDate' );
					
					}
				
				}
				
				update_post_meta( $order_id, 'sepaTransactionId', '0' );
				update_post_meta( $order_id, 'sepaTransactionDate', '0000-00-00' );

				
				
				/**
				 * If the user is logged-in update the
				 * user meta with the SEPA payment data
				 */
				if( is_user_logged_in() )
				{
				
					$userID = get_current_user_id(); 
					update_user_meta( $userID, 'sepaAccountOwner', $sepaAccountOwner );
					update_user_meta( $userID, 'sepaIBAN', $sepaIBAN );
					update_user_meta( $userID, 'sepaBIC', $sepaBIC );
				
				}

				
				/**
				 * Remove cart
				 */
				WC()->cart->empty_cart();

				
				/**
				 * Return thankyou redirect
				 */
				return array(
					'result' 	=> 'success',
					'redirect'	=> $this->get_return_url( $order )
				);

    
			}



			/**
			 * Schedule a subsciption payment
			 */
			public static function scheduled_subscription_payment( $amount_to_charge, $order ) 
			{
        
        
				$order->payment_complete('');
    
			
			}



			/**
			 * Add the SEPA fields to the renewal order
			 */
			public function add_renewal_order_meta( $order_meta, $renewal_order, $original_order ) 
			{
		
		
				update_post_meta( $renewal_order->id, 'sepaTransactionId', '0' );
				update_post_meta( $renewal_order->id, 'sepaTransactionDate', '0000-00-00' );
				
				return( $order_meta );

	
			}



			/**
			 * UI - Payment page fields
			 */
			public function payment_fields() 
			{
          	
          	
				/**
				 * If the user is logged-in retrieve the
				 * SEPA payment data from the user meta
				 */
				if( is_user_logged_in() )
				{
				
					$userID = get_current_user_id(); 
					$sepaAccountOwner = get_user_meta( $userID, 'sepaAccountOwner', true );
					$sepaIBAN = get_user_meta( $userID, 'sepaIBAN', true );
					$sepaBIC = get_user_meta( $userID, 'sepaBIC', true );
				
				}
          	

				if( $this->description )
            		echo '<p>' . $this->description . '</p>';

				?>
       			<fieldset>
					<div id="sepa-account">
               			<p class="form-row form-row-first">
							<label for="sepa-account-owner"><?php echo __( 'Account Owner', 'spwcsp' ) ?> :<span class="required">*</span></label>
							<input type="text" class="input-text" id="sepa_account_owner" name="sepa_account_owner" maxlength="32" value="<?php echo $sepaAccountOwner; ?>" />
                    	</p>
               			<p class="form-row form-row-first">
							<label for="sepa-iban"><?php echo __( 'Account IBAN', 'spwcsp' ) ?> :<span class="required">*</span></label>
							<input type="text" class="input-text" id="sepa_iban" name="sepa_iban" maxlength="32" value="<?php echo $sepaIBAN; ?>" />
                    	</p>
               			<p class="form-row form-row-first">
							<label for="sepa-bic"><?php echo __( 'Account BIC', 'spwcsp' ) ?> :<span class="required">*</span></label>
							<input type="text" class="input-text" id="sepa_bic" name="sepa_bic" maxlength="32" value="<?php echo $sepaBIC; ?>" />
                    	</p>
				</fieldset>
				<?php
    

			}



			/**
			 * Show the SEPA fields at the WooCommerce-MyAccount
			 */
			function show_sepa_account()
			{
			
			
				$userID = get_current_user_id(); 
				$sepaAccountOwner = get_user_meta( $userID, 'sepaAccountOwner', true );
				$sepaIBAN = get_user_meta( $userID, 'sepaIBAN', true );
				$sepaBIC = get_user_meta( $userID, 'sepaBIC', true );
				
				?>
				<div class="col-1 sepa">
					<header class="title">
						<h3><?php _e("SEPA Bank Details", 'spwcsp'); ?></h3>
						<?php /* <a href="http://localhost/woocommerce/mein-konto/edit-address/shipping" class="edit">Bearbeiten</a> */ ?>
					</header>
					<address>
						<?php 
						echo __("Account Owner", 'spwcsp') . ': ' . $sepaAccountOwner . '<br />';
						echo __("Account IBAN", 'spwcsp') . ': ' . $sepaIBAN . '<br />';
						echo __("Account BIC", 'spwcsp') . ': ' . $sepaBIC . '<br  />';
						?>
					</address>
				</div>			
				<?php
			
			
			}
			

			/**
			 * Check payment details for valid format
			 */
			public function validate_fields() 
			{


				/**
				 * We need EUR as Checkout Currency
				 */
				$currency = get_woocommerce_currency();
				if( $currency != 'EUR' )
				{

					wc_add_notice( __( 'SEPA Payment only works with EUR as currency.', 'spwcsp' ), 'error' );
					return( false );

				}


				/**
				 * Check the Account Owner
				 */
				$sepaAccountOwner = $_POST[ 'sepa_account_owner' ];
				if( empty( $sepaAccountOwner ) ) 
				{

					wc_add_notice( __( 'Account-Owner must be filled.', 'spwcsp' ), 'error' );
					return( false );

				}


				/**
				 * Check the IBAN
				 */
				$sepaIBAN = $_POST[ 'sepa_iban' ];
				$check = spwcsp_checkSepaIBAN( $sepaIBAN, $sepaBIC, true );
				if( $check )
				{
				
					wc_add_notice( $check, 'error' );
					return( false );
					
				}


				/**
				 * Check the BIC
				 */
				$sepaBIC = $_POST[ 'sepa_bic' ];
				$check = spwcsp_checkSepaBIC( $sepaBIC, $sepaIBAN, true );
				if( $check )
				{

					wc_add_notice( $check, 'error' );
					return( false );

				}

				
				return( true );


			}

			
		}


	}



	/**
	 * WooCommerce Functions
	 * ---------------------
	 */



	/**
	 * Show the SEPA fields at the WooCommerce user profile
	 */
	function spwcsp_woocommerce_edit_account_form() 
	{
 
  
		$user_id = get_current_user_id();
 
		if ( !$user_id )
			return;
 
		$sepaAccountOwner = $_POST[ 'sepa_account_owner' ];
		if( empty( $sepaAccountOwner ) )
			$sepaAccountOwner = get_the_author_meta( 'sepaAccountOwner', $user->ID );

		$sepaIBAN = $_POST[ 'sepa_iban' ];
		if( empty( $sepaIBAN ) )
		$sepaIBAN = get_the_author_meta( 'sepaIBAN', $user->ID );

		$sepaBIC = $_POST[ 'sepa_bic' ];
		if( empty( $sepaBIC ) )
		$sepaBIC = get_the_author_meta( 'sepaBIC', $user->ID );
  
		?>
 
		<fieldset>
		
			<legend><?php _e( 'SEPA Bank Details', 'spwcsp'); ?></legend>
		
			<p class="form-row form-row-thirds">
				<label for="twitter"><?php _e('Account Owner', 'spwcsp'); ?></label>
				<input type="text" name="sepa_account_owner" value="<?php echo esc_attr( $sepaAccountOwner ); ?>" class="input-text" />
				<p><?php _e('Please enter the account owner.', 'spwcsp'); ?></p>
			</p>
		
			<p class="form-row form-row-thirds">
				<label for="twitter"><?php _e('Account IBAN', 'spwcsp'); ?></label>
				<input type="text" name="sepa_iban" value="<?php echo esc_attr( $sepaIBAN ); ?>" class="input-text" />
				<p><?php _e('Please enter the IBAN.', 'spwcsp'); ?></p>
			</p>
		
			<p class="form-row form-row-thirds">
				<label for="twitter"><?php _e('Account BIC', 'spwcsp'); ?></label>
				<input type="text" name="sepa_bic" value="<?php echo esc_attr( $sepaBIC ); ?>" class="input-text" />
				<p><?php _e('Please enter the BIC.', 'spwcsp'); ?></p>
			</p>
		
		</fieldset>
 
		<?php
 

	}
	
	

	/**
	 * validate the IBAN 
	 */
	function spwcsp_woocommerce_save_account_details_errors( $errors, $user ) 
	{



		/**
		 * Check the IBAN
		 */
		$sepaIBAN = $_POST[ 'sepa_iban' ];
		$check = spwcsp_checkSepaIBAN( $sepaIBAN, $sepaBIC, true );
		if( $check )
		{
				
			wc_add_notice( $check, 'error' );
			return( false );
			
		}


		/**
		 * Check the BIC
		 */
		$sepaBIC = $_POST[ 'sepa_bic' ];
		$check = spwcsp_checkSepaBIC( $sepaBIC, $sepaIBAN, true );
		if( $check )
		{

			wc_add_notice( $check, 'error' );
			return( false );

		}

	
	} 
	
	
	
	/**
	 * Save the SEPA fields at the WooCommerce user profile
	 */ 
	function spwcsp_woocommerce_save_account_details( $user_id ) 
	{
 

		if( !current_user_can( 'edit_user', $user_id) )
			return( FALSE );
	
		update_usermeta( $user_id, 'sepaAccountOwner', $_POST[ 'sepa_account_owner' ] );
		update_usermeta( $user_id, 'sepaIBAN', $_POST[ 'sepa_iban' ] );
		update_usermeta( $user_id, 'sepaBIC', $_POST[ 'sepa_bic' ] );

 
	}



	/**
	 * Show the SEPA payment status at the order list
	 */
	function spwcsp_manage_shop_order_posts_custom_column( $column )
	{


		global $post, $woocommerce, $the_order;

		if( empty( $the_order ) || $the_order->id != $post->ID ) 
			$the_order = new WC_Order( $post->ID );

		if( $column == 'order_total' )
		{

			$sepaTransactionDate = get_post_meta( $post->ID, 'sepaTransactionDate', true );
			if( !empty( $sepaTransactionDate ) )
			{

				if( $sepaTransactionDate != '0000-00-00' )
					echo date_i18n( get_option( 'date_format' ), strtotime( $sepaTransactionDate ) );
				else
					_e( 'Unpayed', 'spwcsp');

			}

		}


	}





	/**
	 * Wordpress Functions
	 * -------------------
	 */



	/**
	 * Do some stuff at the activation
	 * to make the plugin work
	 */
	function spwcsp_activation_hook() 
	{
	

		/**
		 * Create a new table to store the 
		 * SEPA-transaction data
		 */
		global $wpdb;

		$wpdb->hide_errors();

		$collate = '';
		if( $wpdb->has_cap( 'collation' ) ) 
		{

			if( ! empty($wpdb->charset ) )
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty($wpdb->collate ) )
				$collate .= " COLLATE $wpdb->collate";

		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$spwcsp_tables = "
			CREATE TABLE {$wpdb->prefix}woocommerce_sepa_payment (
				id int(11) NOT NULL auto_increment,
				class varchar(255) NOT NULL,
				transactionDate date NOT NULL,
				transactionAmount float NOT NULL,
				transactionRecords int(11) NOT NULL,
				fileNameXML varchar(255) NOT NULL,
				PRIMARY KEY  (id)
			) $collate;
			";

		dbDelta( $spwcsp_tables );


	}



	/**
	 * Add two submenus to the WooCommerce menu
	 */
	function spwcsp_menu_sepa()
	{


		add_submenu_page( 'woocommerce', __( 'SEPA Create XML', 'spwcsp' ),  __( 'SEPA Create XML', 'spwcsp' ) , 'manage_woocommerce', 'spwc-orders',  'spwcsp_process_sepa'  );
		add_submenu_page( 'woocommerce', __( 'SEPA Transactions', 'spwcsp' ),  __( 'SEPA Transactions', 'spwcsp' ) , 'manage_woocommerce', 'spwc-transactions',  'spwcsp_transaction_sepa'  );


	}



	/**
	 * Show the SEPA fields at the user profile
	 */
	function spwcsp_show_custom_user_profile_fields( $user ) 
	{


		?>
		<h3><?php _e( 'SEPA Bank Details', 'spwcsp'); ?></h3>
	
		<table class="form-table">
			<tr>
				<th>
					<label for="sepaAccountOwner">
						<?php _e('Account Owner', 'spwcsp'); ?>
					</label>
				</th>
				<td>
					<input type="text" name="sepa_account_owner" id="sepa_account_owner" value="<?php echo esc_attr( get_the_author_meta( 'sepaAccountOwner', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description"><?php _e('Please enter the account owner.', 'spwcsp'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="sepaIBAN">
						<?php _e('Account IBAN', 'spwcsp'); ?>
					</label>
				</th>
				<td>
					<input type="text" name="sepa_iban" id="sepa_iban" value="<?php echo esc_attr( get_the_author_meta( 'sepaIBAN', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description"><?php _e('Please enter the IBAN.', 'spwcsp'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="sepaBIC">
						<?php _e('Account BIC', 'spwcsp'); ?>
					</label>
				</th>
				<td>
					<input type="text" name="sepa_bic" id="sepa_bic" value="<?php echo esc_attr( get_the_author_meta( 'sepaBIC', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description"><?php _e('Please enter the BIC.', 'spwcsp'); ?></span>
				</td>
			</tr>
		</table>
		
		<?php
 
	}



	/**
	 * Validate the SEPA fields at the user profile
	 */
	function spwcsp_user_profile_update_errors( $errors, $update, $user )
	{
	
	
		/**
		 * Check the IBAN
		 */
		$sepaIBAN = $_POST[ 'sepa_iban' ];
		$check = spwcsp_checkSepaIBAN( $sepaIBAN, $sepaBIC, true );
		if( $check )
			$errors->add( 'iban_not_valid', $check );


		/**
		 * Check the BIC
		 */
		$sepaBIC = $_POST[ 'sepa_bic' ];
		$check = spwcsp_checkSepaBIC( $sepaBIC, $sepaIBAN, true );
		if( $check )
			$errors->add( 'bic_not_valid', $check );

	
	}



	/**
	 * Save the SEPA fields at the user profile
	 */
	function spwcsp_save_custom_user_profile_fields( $userID ) 
	{
	
	
		if( !current_user_can( 'edit_user', $userID ) )
			return( FALSE );
	
		update_usermeta( $userID, 'sepaAccountOwner', $_POST[ 'sepa_account_owner' ] );
		update_usermeta( $userID, 'sepaIBAN', $_POST[ 'sepa_iban' ] );
		update_usermeta( $userID, 'sepaBIC', $_POST[ 'sepa_bic' ] );
	
	
	}	 
	 
	 
	 
	/**
	 * SEPA Transaction Functions
	 */
	 
	 
	 
	/**
	 * Display all transactions
	 */
	function spwcsp_transaction_sepa()
	{

		
		echo "<div class=\"wrap\">\n";
		echo "<h2>" . __( "SEPA Transactions", 'spwcsp' ) . "</h2>\n";
		echo "<br>\n";


		/**
		 * Display the header
		 */
		echo "<table class=\"wp-list-table widefat fixed posts\">\n";
		echo "	<thead>\n";
		echo "		<tr>\n";
		echo "			<th scope=\"col\" id=\"transaction_id\" class=\"manage-column column-transaction_id\" style=\"\">" . __( "Transaction-ID", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_date\" class=\"manage-column column-transaction_date\" style=\"\">" . __( "Transaction-Date", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_amount\" class=\"manage-column column-transaction_amount\" style=\"\">" . __( "Transaction-Amount", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_records\" class=\"manage-column column-transaction_records\" style=\"\">" . __( "Transaction-Records", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_filenamexml\" class=\"manage-column column-transaction_filenamexml\" style=\"\">" . __( "File-Name-XML", 'spwcsp' ) . "</th>\n";
		echo "		</tr>\n";
		echo "	</thead>\n";


		global $wpdb;


		/**
		 * Select all stored transactions
		 */
		$transactions = $wpdb->get_results( "	SELECT		* 
												FROM 		{$wpdb->prefix}woocommerce_sepa_payment
											", 
											ARRAY_A );

		if( $transactions )
		{

			echo "	<tbody>\n";

			foreach( $transactions as $transaction )
			{

				$transaction[ 'transactionDate' ] = date_i18n( get_option( 'date_format' ), strtotime( $transaction[ 'transactionDate' ] ) );
				$transaction[ 'transactionAmount' ] = wc_price( $transaction[ 'transactionAmount' ], '' );
				$transaction[ 'fileName' ] = substr( $transaction[ 'fileNameXML' ], strlen( plugin_dir_path( __FILE__ ) ) );
				$transaction[ 'fileNameURL' ] = plugin_dir_url( __FILE__ ) . $transaction[ 'fileName' ];
				$transaction[ 'fileNameDisplay' ] = substr( $transaction[ 'fileName' ], 5 );

				echo "		<tr>\n";
				echo "			<td>{$transaction[ 'id' ]}</td>\n";
				echo "			<td>{$transaction[ 'transactionDate' ]}</td>\n";
				echo "			<td>{$transaction[ 'transactionAmount' ]}</td>\n";
				echo "			<td>{$transaction[ 'transactionRecords' ]}</td>\n";
				echo "			<td><a href=\"{$transaction[ 'fileNameURL' ]}\" target=\"_new\">{$transaction[ 'fileNameDisplay' ]}</a></td>\n";
				echo "		</tr>\n";


			}

			echo "	</tbody>\n";

		}


		/**
		 * Display the Footer
		 */
		echo "	<tfoot>\n";
		echo "		<tr>\n";
		echo "			<th scope=\"col\" id=\"transaction_id\" class=\"manage-column column-transaction_id\" style=\"\">" . __( "Transaction-ID", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_date\" class=\"manage-column column-transaction_date\" style=\"\">" . __( "Transaction-Date", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_amount\" class=\"manage-column column-transaction_amount\" style=\"\">" . __( "Transaction-Amount", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_records\" class=\"manage-column column-transaction_records\" style=\"\">" . __( "Transaction-Records", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"transaction_filenamexml\" class=\"manage-column column-transaction_filenamexml\" style=\"\">" . __( "File-Name-XML", 'spwcsp' ) . "</th>\n";
		echo "		</tr>\n";
		echo "	</tfoot>\n";
		echo "</table>\n";

		echo "</div>\n";


	}



	/**
	 * Process the SEPA payment, create the XML file
	 * and show the records
	 */
	function spwcsp_process_sepa()
	{


		echo "<div class=\"wrap\">\n";
		echo "<h2>" . __( "Create SEPA XML File", 'spwcsp' ) . "</h2>\n";
		echo "<br>\n";


		/**
		 * Display the header
		 */
		echo "<table class=\"wp-list-table widefat fixed posts\">\n";
		echo "	<thead>\n";
		echo "		<tr>\n";
		echo "			<th scope=\"col\" id=\"order_id\" class=\"manage-column column-order_id\" style=\"\">" . __( "Order-ID", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"order_date\" class=\"manage-column column-order_date\" style=\"\">" . __( "Order-Date", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"order_amount\" class=\"manage-column column-order_amount\" style=\"\">" . __( "Order-Amount", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"sepa_iban\" class=\"manage-column column-sepa_iban\" style=\"\">" . __( "IBAN", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"sepa_bic\" class=\"manage-column column-sepa_bic\" style=\"\">" . __( "BIC", 'spwcsp' ) . "</th>\n";
		echo "		</tr>\n";
		echo "	</thead>\n";


		global $wpdb;


		/**
		 * Get the SEPA settings
		 */
		$sepaSettings = get_option( 'woocommerce_sepa_settings' );


		/**
		 * Select all unpayed orders with a sepa payment
		 */
		$records = $wpdb->get_results( "	SELECT		* 
											FROM 		{$wpdb->prefix}postmeta 
											WHERE 		meta_key = 'sepaTransactionId' 
											AND 		meta_value = '0'
											", 
											ARRAY_A );

		if( $records)
		{


			/**
			 * We create a new transaction record
			 */
			$transaction[ 'id' ] = NULL;
			$transaction[ 'class' ] = 'sepaDirectDebit';
			$transaction[ 'transactionDate' ] = date( 'Y-m-d' );

								
			/**
			 * Now we insert the record into the database table
			 */
			$wpdb->insert(	
				"{$wpdb->prefix}woocommerce_sepa_payment",
				array (
					'id' => $transaction[ 'id' ],
					'class' => $transaction[ 'class' ],
					'transactionDate' => $transaction[ 'transactionDate' ]
				),
				array ( '%d', '%s', '%s' )
			);

			$transaction[ 'id' ] = $wpdb->insert_id;


			/** 
			 * Loop thru the recordset and add the 
			 * information from orders and postmeta
			 * wich is currently not included
			 */
			foreach( $records as $key => $record )
			{


				/**
				 * get the post
				 */
				$post = get_post( $record[ 'post_id' ] );
				$records[ $key ][ 'orderDate' ] = substr( $post->post_date, 0, 10 );


				/**
				 * get the total amount
				 */
				$order = new WC_Order( $record[ 'post_id' ] );
				$records[ $key ][ 'totalAmount' ] = $order->get_total();


				/**
				 * get BIC, IBAN and AccountOwner
				 */
				$records[ $key ][ 'sepaAccountOwner' ] = get_post_meta( $record[ 'post_id' ], 'sepaAccountOwner', true );
				$records[ $key ][ 'sepaIBAN' ] = get_post_meta( $record[ 'post_id' ], 'sepaIBAN', true );
				$records[ $key ][ 'sepaBIC' ] = get_post_meta( $record[ 'post_id' ], 'sepaBIC', true );
				$records[ $key ][ 'lastTransactionDate' ] = '0000-00-00';	
					
			}


			/**
			 * Create the XML
			 */
			$xmlString = '<?xml version="1.0" encoding="UTF-8"?><Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.008.003.02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:iso:std:iso:20022:tech:xsd:pain.008.003.02 pain.008.003.02.xsd"></Document>';
			$xmlRoot = simplexml_load_string( $xmlString );
			$xml = $xmlRoot->addChild( 'CstmrDrctDbtInitn' );

	
			/**
			 * At first we need to calculate the number of transactions 
			 * and the control sum of all transactions
			 */
			unset( $NbOfTxs, $CtrlSum );
			foreach( $records as $record )
			{

				$CtrlSum[ 0 ] = $CtrlSum[ 0 ] + round( $record[ 'totalAmount' ], 2 );
				$NbOfTxs[ 0 ]++;

			}

					
			/**
			 * Create the XML header
			 */
			$GrpHdr = $xml->addChild( 'GrpHdr' );
			$GrpHdr->addChild( 'MsgId', $transaction[ 'id' ] );
			$GrpHdr->addChild( 'CreDtTm', date( 'Y-m-d' ) . 'T' . date( 'H:i:s' ) );
			$GrpHdr->addChild( 'NbOfTxs', $NbOfTxs[ 0 ] );
			$GrpHdr->addChild( 'CtrlSum', $CtrlSum[ 0 ] );
			$Nm = $GrpHdr->addChild( 'InitgPty' );
				$Nm->addChild( 'Nm', substr( spwcsp_makeSepaString( $sepaSettings[ 'sepaAccountOwner' ] ), 0, 70 ) );
				
				
			/**
			 * Now we work on each record
			 */
			foreach( $records as $record )
			{


				/**
				 * Create the XML payment info
				 */
				$PmtInf = $xml->addChild( 'PmtInf' );
				$PmtInf->addChild( 'PmtInfId', $record[ 'post_id' ] );
				$PmtInf->addChild( 'PmtMtd', 'DD' );
				$PmtInf->addChild( 'NbOfTxs', '1' );
				$PmtInf->addChild( 'CtrlSum', round( $record[ 'totalAmount' ], 2 ) );
				$PmtTpInf = $PmtInf->addChild( 'PmtTpInf' );
					$SvcLvl = $PmtTpInf->addChild( 'SvcLvl' );
					$SvcLvl->addChild( 'Cd', 'SEPA' );
					$LclInstrm = $PmtTpInf->addChild( 'LclInstrm' );
					$LclInstrm->addChild( 'Cd', 'COR1' );
					
					
				/**
				 * Check if we are the first transaction
				 * or a recurring transaction
				 */
				if( $record[ 'lastTransactionDate' ] == '0000-00-00' )
					$PmtTpInf->addChild( 'SeqTp', 'FRST' );
				else
					$PmtTpInf->addChild( 'SeqTp', 'RCUR' );

				$PmtInf->addChild( 'ReqdColltnDt', date( 'Y-m-d', mktime( 0, 0, 0, intval( date( 'm' ) ), intval( date( 'd' ) ) + 2, intval( date( 'Y' ) ) ) ) );
					$Cdtr = $PmtInf->addChild( 'Cdtr' );
				$Cdtr->addChild( 'Nm', $sepaSettings[ 'sepaAccountOwner' ] );
				$CdtrAcct = $PmtInf->addChild( 'CdtrAcct' );
					$id = $CdtrAcct->addChild( 'Id' );
					$id->addChild( 'IBAN', trim( $sepaSettings[ 'sepaIBAN' ] ) );
				$CdtrAgt = $PmtInf->addChild( 'CdtrAgt' );
					$FinInstnId = $CdtrAgt->addChild( 'FinInstnId' );
					$FinInstnId ->addChild( 'BIC', trim( $sepaSettings[ 'sepaBIC' ]) );	
				$PmtInf->addChild( 'ChrgBr', 'SLEV' );
				$CdtrSchmeId = $PmtInf->addChild( 'CdtrSchmeId' );
					$Id = $CdtrSchmeId->addChild( 'Id' );
						$PrvtId = $Id->addChild( 'PrvtId' );
							$Othr = $PrvtId->addChild( 'Othr' );
								$Othr->addChild( 'Id', trim( $sepaSettings[ 'sepaCreditorId' ] ) );
								$SchmeNm = $Othr->addChild( 'SchmeNm' );
								$SchmeNm->addChild( 'Prtry', 'SEPA' );
									
				$record[ 'mandateId' ] = $sepaSettings[ 'sepaMandateId' ] . sprintf( "%06d", $record[ 'post_id' ] );
				$record[ 'mandateDate' ] = $record[ 'orderDate' ];

				$DrctDbtTxInf = $PmtInf->addChild( 'DrctDbtTxInf' );
					$PmtId = $DrctDbtTxInf->addChild( 'PmtId' );
					$PmtId->addChild( 'EndToEndId',  __( 'Your Order', 'spwcsp') . ': ' . $record[ 'post_id' ] . ' ' . __( 'from Date', 'spwcsp') . ': ' . $record[ 'orderDate' ] );
					$InstdAmt = $DrctDbtTxInf->addChild( 'InstdAmt', round( $record[ 'totalAmount' ], 2 ) );
					$InstdAmt->addAttribute( 'Ccy', 'EUR' );
					$DrctDbtTx = $DrctDbtTxInf->addChild( 'DrctDbtTx' );
						$MndtRltdInf = $DrctDbtTx->addChild( 'MndtRltdInf' );
							$MndtRltdInf->addChild( 'MndtId', trim( $record[ 'mandateId' ] ) );
							$MndtRltdInf->addChild( 'DtOfSgntr', $record[ 'mandateDate' ] );
					$DbtrAgt = $DrctDbtTxInf->addChild( 'DbtrAgt' );
						$FinInstnId = $DbtrAgt->addChild( 'FinInstnId' );
						$FinInstnId->addChild( 'BIC', trim( $record[ 'sepaBIC' ] ) );					
					$Dbtr= $DrctDbtTxInf->addChild( 'Dbtr' );
						$Dbtr->addChild( 'Nm', substr( spwcsp_makeSepaString( $record[ 'sepaAccountOwner' ] ), 0, 70 ) );
					$DbtrAcct = $DrctDbtTxInf->addChild( 'DbtrAcct' );
						$id = $DbtrAcct->addChild( 'Id' );
						$id->addChild( 'IBAN', trim( $record[ 'sepaIBAN' ] ) );
					$RmtInf = $DrctDbtTxInf->addChild( 'RmtInf' );
						$RmtInf->addChild('Ustrd', __( 'Your Order', 'spwcsp') . ': ' . $record[ 'post_id' ] . ' ' . __( 'from Date', 'spwcsp') . ': ' . $record[ 'orderDate' ] );
							

			}


			/**
			 * Save the SEPA XML file
			 */

			$fileNameXML = plugin_dir_path( __FILE__ ) . '/xml/sepa_dd-' . sprintf( "%06d", $transaction[ 'id' ] ) . '.xml';
			$xmlRoot->asXML( $fileNameXML );
				
				
			/**
			 * Update the TRANSACTION record
			 */
			$wpdb->update( 	
				"{$wpdb->prefix}woocommerce_sepa_payment",
				array (
					'transactionAmount' => $CtrlSum[ 0 ],
					'transactionRecords' => $NbOfTxs[ 0 ],
					'fileNameXML' => $fileNameXML
				),
				array (
					'id' => $transaction[ 'id' ]
				),
				array ( '%f', '%d', '%s' ),
				array ( '%d' )
			);


			echo "	<tbody>\n";


			/** 
			 * Loop thru the recordset and store the  
			 * transation-information at the record,
			 * update the order-status and display
			 * a list of all records
			 */
			foreach( $records as $key => $record )
			{


				/**
				 * Update the order-status
				 */
				$order = new WC_Order( $record[ 'post_id' ] );
				$order->update_status( 'processing', __( 'SEPA payment processed', 'spwcsp' ) );


				/**
				 * Update the transaction info
				 */
				update_post_meta( $record[ 'post_id' ], 'sepaTransactionId', $transaction[ 'id' ] );
				update_post_meta( $record[ 'post_id' ], 'sepaTransactionDate', $transaction[ 'transactionDate' ] );


				/**
				 * Display the table with all order infos
				 */
				echo "		<tr>\n";
				echo "			<td>" . $record[ 'post_id' ] . "</td>\n";
				echo "			<td>" . date_i18n( get_option( 'date_format' ), strtotime( $record[ 'orderDate' ] ) ) . "</td>\n";
				echo "			<td>" . wc_price( $record[ 'totalAmount' ], '' ) . "</td>\n";
				echo "			<td>" . $record[ 'sepaIBAN' ] . "</td>\n";
				echo "			<td>" . $record[ 'sepaBIC' ] . "</td>\n";
				echo "		</tr>\n";
				
			}


			echo "	</tbody>\n";

				
		}


		/**
		 * Display the Footer
		 */
		echo "	<tfoot>\n";
		echo "		<tr>\n";
		echo "			<th scope=\"col\" id=\"order_id\" class=\"manage-column column-order_id\" style=\"\">" . __( "Order-ID", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"order_date\" class=\"manage-column column-order_date\" style=\"\">" . __( "Order-Date", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"order_amount\" class=\"manage-column column-order_amount\" style=\"\">" . __( "Order-Amount", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"sepa_iban\" class=\"manage-column column-sepa_iban\" style=\"\">" . __( "IBAN", 'spwcsp' ) . "</th>\n";
		echo "			<th scope=\"col\" id=\"sepa_bic\" class=\"manage-column column-sepa_bic\" style=\"\">" . __( "BIC", 'spwcsp' ) . "</th>\n";
		echo "		</tr>\n";
		echo "	</tfoot>\n";
		echo "</table>\n";


	}



	/**
	 * Return an array with all SEPA countries
	 * and the country specific IBAN length
	 */
	function spwcsp_getSepaCountries()
	{
	
	
		$sepaCountries = array(
			'AT' => '20',
			'BE' => '16',
			'BG' => '22',
			'CH' => '21',
			'CY' => '28',
			'CZ' => '24',
			'DE' => '22',
			'DK' => '18',
			'EE' => '20',
			'ES' => '24',
			'FI' => '18',
			'FR' => '27',
			'GB' => '22',
			'GR' => '27',
			'HU' => '28',
			'IE' => '22',
			'IS' => '26',
			'IT' => '27',
			'HR' => '21',
			'LI' => '21',
			'LT' => '20',
			'LU' => '20',
			'LV' => '21',
			'MC' => '27',
			'MT' => '31',
			'NL' => '18',
			'NO' => '15',
			'PL' => '28',
			'PT' => '25',
			'RO' => '24',
			'SE' => '24',
			'SI' => '19',
			'SK' => '24',
			'SM' => '27'
			);


		return( $sepaCountries);
		
	
	}



	/**
	 * Sanitize a string to use with SEPA
	 */
	function spwcsp_makeSepaString( $string )
	{


		if( strlen( $string ) == 0 )
			return "";

		$special_chars = array(
			'á' => 'a',
			'à' => 'a',
			'ä' => 'ae',
			'â' => 'a',
			'ã' => 'a',
			'å' => 'a',
			'æ' => 'ae',
			'a' => 'a',
			'a' => 'a',
			'a' => 'a',
			'?' => 'a',
			'?' => 'a',
			'Á' => 'A',
			'À' => 'A',
			'Ä' => 'Ae',
			'Â' => 'A',
			'Ã' => 'A',
			'Å' => 'A',
			'Æ' => 'AE',
			'A' => 'A',
			'A' => 'A',
			'A' => 'A',
			'?' => 'A',
			'?' => 'A',
			'ç' => 'c',
			'c' => 'c',
			'c' => 'c',
			'c' => 'c',
			'c' => 'c',
			'Ç' => 'C',
			'C' => 'C',
			'C' => 'C',
			'C' => 'C',
			'C' => 'C',
			'd' => 'd',
			'd' => 'd',
			'D' => 'D',
			'Ð' => 'D',
			'é' => 'e',
			'è' => 'e',
			'ê' => 'e',
			'ë' => 'e',
			'e' => 'e',
			'e' => 'e',
			'e' => 'e',
			'e' => 'e',
			'e' => 'e',
			'?' => 'e',
			'?' => 'e',
			'É' => 'E',
			'È' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'E' => 'E',
			'E' => 'E',
			'E' => 'E',
			'E' => 'E',
			'E' => 'E',
			'?' => 'E',
			'?' => 'E',
			'g' => 'g',
			'g' => 'g',
			'g' => 'g',
			'g' => 'g',
			'G' => 'G',
			'G' => 'G',
			'G' => 'G',
			'G' => 'G',
			'h' => 'h',
			'h' => 'h',
			'H' => 'H',
			'H' => 'H',
			'ì' => 'i',
			'ì' => 'i',
			'î' => 'i',
			'ï' => 'i',
			'i' => 'i',
			'i' => 'i',
			'i' => 'i',
			'i' => 'i',
			'i' => 'i',
			'?' => 'ij',
			'?' => 'i',
			'?' => 'i',
			'Í' => 'I',
			'Ì' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'I' => 'I',
			'I' => 'I',
			'I' => 'I',
			'I' => 'I',
			'I' => 'I',
			'?' => 'IJ',
			'?' => 'I',
			'?' => 'I',
			'j' => 'j',
			'J' => 'J',
			'k' => 'k',
			'K' => 'K',
			'l' => 'l',
			'l' => 'l',
			'l' => 'l',
			'?' => 'l',
			'l' => 'l',
			'L' => 'L',
			'L' => 'L',
			'L' => 'L',
			'?' => 'L',
			'L' => 'L',
			'ñ' => 'n',
			'n' => 'n',
			'n' => 'n',
			'n' => 'n',
			'?' => 'n',
			'Ñ' => 'N',
			'N' => 'N',
			'N' => 'N',
			'N' => 'N',
			'ó' => 'o',
			'ò' => 'o',
			'ö' => 'oe',
			'ô' => 'o',
			'õ' => 'o',
			'ø' => 'o',
			'o' => 'o',
			'o' => 'o',
			'o' => 'o',
			'œ' => 'oe',
			'?' => 'o',
			'?' => 'o',
			'Ó' => 'O',
			'Ò' => 'O',
			'Ö' => 'Oe',
			'Ô' => 'O',
			'Õ' => 'O',
			'Ø' => 'O',
			'O' => 'O',
			'O' => 'O',
			'O' => 'O',
			'Œ' => 'OE',
			'?' => 'O',
			'?' => 'O',
			'r' => 'r',
			'r' => 'r',
			'?' => 'r',
			'?' => 'r',
			'R' => 'R',
			'R' => 'R',
			'?' => 'R',
			'?' => 'R',
			'ß' => 'ss',
			's' => 's',
			's' => 's',
			's' => 's',
			'š' => 's',
			'?' => 's',
			'S' => 'S',
			'S' => 'S',
			'S' => 'S',
			'Š' => 'S',
			'?' => 'S',
			't' => 't',
			't' => 't',
			't' => 't',
			'?' => 't',
			'T' => 'T',
			'T' => 'T',
			'T' => 'T',
			'?' => 'T',
			'ú' => 'u',
			'ù' => 'u',
			'ü' => 'ue',
			'û' => 'u',
			'u' => 'u',
			'u' => 'u',
			'u' => 'u',
			'u' => 'u',
			'u' => 'u',
			'u' => 'u',
			'?' => 'u',
			'?' => 'u',
			'Ú' => 'U',
			'Ù' => 'U',
			'Ü' => 'Ue',
			'Û' => 'U',
			'U' => 'U',
			'U' => 'U',
			'U' => 'U',
			'U' => 'U',
			'U' => 'U',
			'U' => 'U',
			'?' => 'U',
			'?' => 'U',
			'w' => 'w',
			'W' => 'W',
			'ý' => 'y',
			'ÿ' => 'y',
			'y' => 'y',
			'Ý' => 'Y',
			'Ÿ' => 'Y',
			'Y' => 'Y',
			'z' => 'z',
			'z' => 'z',
			'ž' => 'z',
			'Z' => 'Z',
			'Z' => 'Z',
			'Ž' => 'Z',
			
            '&' => '&amp;',
            '<' => '&lt;',
			'>' => '&gt;',
						
		);

		/**
		 * ensure UTF-8, for single-byte-encodings use either
		 * the internal encoding or assume ISO-8859-1
		 */
		$utf8string = mb_convert_encoding ( $string, "UTF-8", array( "UTF-8", mb_internal_encoding(), "ISO-8859-1" ) );

		/**
		 * replace known special chars
		 * and make sure every special char is replaced by one space, not two or three
		 */
		$result = strtr( $utf8string, $special_chars );
		$result = mb_convert_encoding( $result, "ASCII", "UTF-8" );

		$result = trim( $result );
		return( $result );

			
	}
	
	
	
	/**
	 * perfrom a IBAN check
	 */
	function spwcsp_checkSepaIBAN( &$sepaIBAN, $sepaBIC = '', $empty = true )
	{
	
	
		/**
		 * If the IBAN is empty and $empty is set to false
		 * we returne an error
		 */
		if( empty( $sepaIBAN ) && $empty === false ) 
			return( __( 'IBAN must be filled.', 'spwcsp' ) );
			
			
		if( !empty( $sepaIBAN ) )
		{
	
	
			/**
			 * Retrieve an array with all SEPA countries
			 */
			$sepaCountries = spwcsp_getSepaCountries();
	
	
			/**
			 * Check if this is a SEPA country
			 */
			$ibanCountry = substr( $sepaIBAN, 0, 2 );
			if( !$sepaCountries[ $ibanCountry ] )
				return( __( 'This is not an IBAN from a SEPA country.', 'spwcsp' ) );
				
				
			/**
			 * Check if the IBAN has the right lenght
			 * for this country
			 */
			$ibanLen = strlen( $sepaIBAN );
			if( $ibanLen != $sepaCountries[ $ibanCountry ] )
				return( __( 'IBAN has not the right length.', 'spwcsp' ) );
			 
			 

			/**
			 * Validate the IBAN the the php-iban lib
			 */
			if( class_exists( 'IBAN' ) )
			{

			
				$iban = NEW IBAN( $sepaIBAN );
				if( !$iban->Verify() ) 
					return( __( 'IBAN is not valid.', 'spwcsp' ) );



			}
			
		}
	
	
	}
	 
	 
	/**
	 * perform a BIX check
	 */
	function spwcsp_checkSepaBIC( &$sepaBIC, $sepaIBAN = '', $empty = true )
	{
	 
	 
		/**
		 * If the BIC is empty and $empty is set to false
		 * we returne an error
		 */
		if( empty( $sepaBIC ) && $empty === false ) 
			return( __( 'BIC must be filled.', 'spwcsp' ) );
			
			
		if( !empty( $sepaBIC ) )
		{


			$sepaBIC = strtoupper( $sepaBIC );
		
	 
			/**
			 * Check the length of the BIC
			 */
			$bicLen = strlen( $sepaBIC );
			if( $bicLen == 8 )
			{
					
				$bicLen = 11;
				$sepaBiC = $sepaBic + 'XXX';
				$_POST[ 'sepa_bic' ] = $sepaBIC;
						
			}
			if( $bicLen != 11 )
				return( __( 'BIC has not the right len.', 'spwcsp' ) );


			/**
			 * Check if all chars are valid
			 */
			if( !eregi( "^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$", $sepaBIC ) ) 
				return( __( 'BIC is not valid.', 'spwcsp' ) );


			/**
			 * Retrieve an array with all SEPA countries
			 */			
			$sepaCountries = spwcsp_getSepaCountries();

			/**
			 * Check if this is a SEPA country
			 */
			$bicCountry = substr( $sepaBIC, 4, 2 );
			if( !$sepaCountries[ $bicCountry ] )
				return( __( 'This is not a BIC from a SEPA country.', 'spwcsp' ) );
				
			
			if( !empty( $sepaIBAN ) )
			{
			
				$ibanCountry = substr( $sepaIBAN, 0, 2);
				if( $ibanCountry != $bicCountry )
					return( __( 'IBAN and BIC country must be equal.', 'spwcsp' ) );
				
			
			
			}


		}	 
		
		
	 }
	 
	 
?>
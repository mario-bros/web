<?php
/**
 * buckaroo Donations Gateway
 *
 * @package     Give
 * @subpackage  Gateways
 * @copyright   Copyright (c) 2015, WordImpress
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


/**
 * Register the payment gateway
 *
 * @since  1.0
 *
 * @param array $gateways
 *
 * @return array
 */
function give_buckaroo_register_gateway( $gateways ) {
	// Format: ID => Name
	$gateways['buckaroo'] = array(
		'admin_label'    => 'buckaroo Donation',
		'checkout_label' => __( 'buckaroo Donation', 'give' )
	);

	return $gateways;
}

add_filter( 'give_payment_gateways', 'give_buckaroo_register_gateway', 1 );


/**
 * Disables the automatic marking of abandoned orders
 * Marking pending payments as abandoned could break manual check payments
 *
 * @since  1.0
 * @return void
 */
function give_buckaroo_disable_abandoned_orders() {
	remove_action( 'give_weekly_scheduled_events', 'give_mark_abandoned_orders' );
}

add_action( 'plugins_loaded', 'give_buckaroo_disable_abandoned_orders' );


/**
 * Add our payment instructions to the checkout
 *
 * @since  1.0
 *
 * @param int $form_id
 *
 * @return void
 */
function give_buckaroo_payment_cc_form( $form_id ) {

	$post_buckaroo_customization_option = get_post_meta( $form_id, '_give_customize_buckaroo_donations', true );
	$post_buckaroo_instructions         = get_post_meta( $form_id, '_give_buckaroo_checkout_notes', true );
	$global_buckaroo_instruction        = give_get_option( 'global_buckaroo_donation_content' );
	$buckaroo_instructions              = $global_buckaroo_instruction;

	if ( $post_buckaroo_customization_option == 'yes' ) {
		$buckaroo_instructions = $post_buckaroo_instructions;
	}

	//Enable Default CC fields (billing info)
	$post_buckaroo_cc_fields   = get_post_meta( $form_id, '_give_buckaroo_donation_enable_billing_fields_single', true );
	$global_buckaroo_cc_fields = give_get_option( 'give_buckaroo_donation_enable_billing_fields' );

	if ( $global_buckaroo_cc_fields == 'on' || $post_buckaroo_cc_fields == 'on' ) {
		add_action( 'give_before_buckaroo_info_fields', 'give_default_cc_address_fields' );
	}

	ob_start(); ?>
	<?php do_action( 'give_before_buckaroo_info_fields' ); ?>
	<fieldset id="give_buckaroo_payment_info">
		<?php
		$settings_url         = admin_url( 'post.php?post=' . $form_id . '&action=edit&message=1' );
		$buckaroo_instructions = ! empty( $buckaroo_instructions ) ? $buckaroo_instructions : sprintf( __( 'Please enter buckaroo donation instructions in the %s.', 'give' ), '<a href="' . $settings_url . '">' . __( 'this form\'s settings', 'give' ) . '</a>' );
		echo wpautop( stripslashes( $buckaroo_instructions ) );
		?>
	</fieldset>
	<?php do_action( 'give_after_buckaroo_info_fields' ); ?>
	<?php
	echo ob_get_clean();
}

add_action( 'give_buckaroo_cc_form', 'give_buckaroo_payment_cc_form' );



/**
 * Process PayPal Purchase
 *
 * @since 1.0
 *
 * @param array $purchase_data Purchase Data
 *
 * @return void
 */
function give_process_buckaroo_purchase( $purchase_data ) {
	if ( ! wp_verify_nonce( $purchase_data['gateway_nonce'], 'give-gateway' ) ) {
		wp_die( __( 'Nonce verification has failed', 'give' ), __( 'Error', 'give' ), array( 'response' => 403 ) );
	}

	// Collect payment data
	$payment_data = array(
		'price'           => $purchase_data['price'],
		'give_form_title' => $purchase_data['post_data']['give-form-title'],
		'give_form_id'    => intval( $purchase_data['post_data']['give-form-id'] ),
		'date'            => $purchase_data['date'],
		'user_email'      => $purchase_data['user_email'],
		'purchase_key'    => $purchase_data['purchase_key'],
		'currency'        => give_get_currency(),
		'user_info'       => $purchase_data['user_info'],
		'status'          => 'pending',
		'gateway'         => 'buckaroo'
	);
		global $donate;
		do_action('wp_loaded');
		do_action('give_process_buckaroo_purchase');
	

	// Record the pending payment
	$payment = give_insert_payment( $payment_data );

	// Check payment
	if ( ! $payment ) {
		// Record the error
		give_record_gateway_error( __( 'Payment Error', 'give' ), sprintf( __( 'Payment creation failed before sending buyer to PayPal. Payment data: %s', 'give' ), json_encode( $payment_data ) ), $payment );
		// Problems? send back
		give_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['give-gateway'] );
	} else {
		// Only send to PayPal if the pending payment is created successfully
		$listener_url = add_query_arg( 'give-listener', 'IPN', home_url( 'index.php' ) );

		// Get the success url
		$return_url = add_query_arg( array(
			'payment-confirmation' => 'buckaroo',
			'payment-id'           => $payment

		), get_permalink( give_get_option( 'success_page' ) ) );

		// Get the PayPal redirect uri
		$paypal_redirect = trailingslashit( give_get_paypal_redirect() ) . '?';

		//Item name - pass level name if variable priced
		$item_name = $purchase_data['post_data']['give-form-title'];
		if ( give_has_variable_prices( $purchase_data['post_data']['give-form-id'] ) && isset( $purchase_data['post_data']['give-price-id'] ) ) {

			$item_price_level_text = give_get_price_option_name( $purchase_data['post_data']['give-form-id'], $purchase_data['post_data']['give-price-id'] );

			//Is there any donation level text?
			if(!empty($item_price_level_text)) {
				$item_name = ' - ' . $item_price_level_text;
			}

		}

		// Setup Buckaroo arguments
		$paypal_args = array(
			'business'      => give_get_option( 'paypal_email', false ),
			'email'         => $purchase_data['user_email'],
			'invoice'       => $purchase_data['purchase_key'],
			'amount'        => $purchase_data['price'],
			// The all important donation amount
			'item_name'     => $item_name,
			// "Purpose" field pre-populated with Form Title
			'no_shipping'   => '1',
			'shipping'      => '0',
			'no_note'       => '1',
			'currency_code' => give_get_currency(),
			'charset'       => get_bloginfo( 'charset' ),
			'custom'        => $payment,
			'rm'            => '2',
			'return'        => $return_url,
			'cancel_return' => give_get_failed_transaction_uri( '?payment-id=' . $payment ),
			'notify_url'    => $listener_url,
			'page_style'    => give_get_paypal_page_style(),
			'cbt'           => get_bloginfo( 'name' ),
			'bn'            => 'WordImpress_Donate_Give_US'
		);

		if ( ! empty( $purchase_data['user_info']['address'] ) ) {
			$paypal_args['address1'] = $purchase_data['user_info']['address']['line1'];
			$paypal_args['address2'] = $purchase_data['user_info']['address']['line2'];
			$paypal_args['city']     = $purchase_data['user_info']['address']['city'];
			$paypal_args['country']  = $purchase_data['user_info']['address']['country'];
		}

		if ( give_get_option( 'paypal_button_type' ) === 'standard' ) {
			$paypal_extra_args = array(
				'cmd' => '_xclick',
			);
		} else {
			$paypal_extra_args = array(
				'cmd' => '_donations',
			);
		}

		$paypal_args = array_merge( $paypal_extra_args, $paypal_args );


		$paypal_args = apply_filters( 'give_paypal_redirect_args', $paypal_args, $purchase_data );

		// Build query
		$paypal_redirect .= http_build_query( $paypal_args );

		// Fix for some sites that encode the entities
		$paypal_redirect = str_replace( '&amp;', '&', $paypal_redirect );

	$paypal_redirect = "/checkout/";
		wp_redirect( $paypal_redirect );
		exit;	
	}

}

add_action( 'give_gateway_buckaroo', 'give_process_buckaroo_purchase' );



/**
 * Process the payment
 *
 * @since  1.0
 * @return void
 */
function give_buckaroo_process_payment( $purchase_data ) {

	$purchase_summary = give_get_purchase_summary( $purchase_data );

	// setup the payment details
	$payment_data = array(
		'price'           => $purchase_data['price'],
		'give_form_title' => $purchase_data['post_data']['give-form-title'],
		'give_form_id'    => intval( $purchase_data['post_data']['give-form-id'] ),
		'date'            => $purchase_data['date'],
		'user_email'      => $purchase_data['user_email'],
		'purchase_key'    => $purchase_data['purchase_key'],
		'currency'        => give_get_currency(),
		'user_info'       => $purchase_data['user_info'],
		'status'          => 'pending',
		'gateway'         => 'buckaroo'
	);


	// record the pending payment
	$payment = give_insert_payment( $payment_data );

	if ( $payment ) {
		give_buckaroo_send_admin_notice( $payment );
		give_buckaroo_send_donor_instructions( $payment );
		give_send_to_success_page();
	} else {
		// if errors are present, send the user back to the donation form so they can be corrected
		give_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['give-gateway'] );
	}

}

add_action( 'give_gateway_buckaroo', 'give_buckaroo_process_payment' );


/**
 * Send buckaroo Donation Instructions
 *
 * @description Sends a notice to the donor with buckaroo instructions; can be customized per form
 *
 * @param int $payment_id
 *
 * @since       1.0
 * @return void
 */
function give_buckaroo_send_donor_instructions( $payment_id = 0 ) {

	$payment_data                      = give_get_payment_meta( $payment_id );
	$post_buckaroo_customization_option = get_post_meta( $payment_data['form_id'], '_give_customize_buckaroo_donations', true );

	//Customize email content depending on whether the single form has been customized
	$email_content = give_get_option( 'global_buckaroo_donation_email' );

	if ( $post_buckaroo_customization_option === 'yes' ) {
		$email_content = get_post_meta( $payment_data['form_id'], '_give_buckaroo_donation_email', true );
	}

	$from_name = give_get_option( 'from_name', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) );
	$from_name = apply_filters( 'give_purchase_from_name', $from_name, $payment_id, $payment_data );

	$from_email = give_get_option( 'from_email', get_bloginfo( 'admin_email' ) );
	$from_email = apply_filters( 'give_purchase_from_address', $from_email, $payment_id, $payment_data );

	$to_email = give_get_payment_user_email( $payment_id );

	$subject = give_get_option( 'buckaroo_donation_subject', __( 'buckaroo Donation Instructions', 'give' ) );
	if ( $post_buckaroo_customization_option === 'yes' ) {
		$subject = get_post_meta( $payment_data['form_id'], '_give_buckaroo_donation_subject', true );
	}

	$subject = apply_filters( 'give_buckaroo_donation_subject', wp_strip_all_tags( $subject ), $payment_id );
	$subject = give_do_email_tags( $subject, $payment_id );

	$attachments = apply_filters( 'give_buckaroo_donation_attachments', array(), $payment_id, $payment_data );
	$message     = give_do_email_tags( $email_content, $payment_id );

	$emails = Give()->emails;

	$emails->__set( 'from_name', $from_name );
	$emails->__set( 'from_email', $from_email );
	$emails->__set( 'heading', __( 'buckaroo Donation Instructions', 'give' ) );

	$headers = apply_filters( 'give_receipt_headers', $emails->get_headers(), $payment_id, $payment_data );
	$emails->__set( 'headers', $headers );

	$emails->send( $to_email, $subject, $message, $attachments );

}


/**
 * Send buckaroo Donation Admin Notice
 *
 * @description Sends a notice to site admins about the pending donation
 *
 * @since       1.0
 *
 * @param int $payment_id
 *
 * @return void
 *
 */
function give_buckaroo_send_admin_notice( $payment_id = 0 ) {

	/* Send an email notification to the admin */
	$admin_email = give_get_admin_notice_emails();
	$user_info   = give_get_payment_meta_user_info( $payment_id );

	if ( isset( $user_info['id'] ) && $user_info['id'] > 0 ) {
		$user_data = get_userdata( $user_info['id'] );
		$name      = $user_data->display_name;
	} elseif ( isset( $user_info['first_name'] ) && isset( $user_info['last_name'] ) ) {
		$name = $user_info['first_name'] . ' ' . $user_info['last_name'];
	} else {
		$name = $user_info['email'];
	}

	$amount = give_currency_filter( give_format_amount( give_get_payment_amount( $payment_id ) ) );

	$admin_subject = apply_filters( 'give_buckaroo_admin_donation_notification_subject', __( 'New Pending Donation', 'give' ), $payment_id );

	$admin_message = __( 'Dear Admin,', 'give' ) . "\n\n" . __( 'An buckaroo donation has been made', 'give' ) . ".\n\n";

	$order_url = admin_url( 'edit.php?post_type=give_forms&page=give-payment-history&view=view-order-details&id=' . $payment_id );
	$admin_message .= __( 'Donor: ', 'give' ) . " " . html_entity_decode( $name, ENT_COMPAT, 'UTF-8' ) . "\n";
	$admin_message .= __( 'Amount: ', 'give' ) . " " . html_entity_decode( $amount, ENT_COMPAT, 'UTF-8' ) . "\n\n";
	$admin_message .= __( 'This is a pending donation awaiting payment. Donation instructions have been emailed to the donor. Once you receive payment, be sure to mark the donation as complete using the link below.', 'give' ) . "\n\n";
	$admin_message .= sprintf( __( 'View Donation Details: %s.', 'give' ), $order_url ) . "\n\n";
	$admin_message = apply_filters( 'give_buckaroo_admin_donation_notification', $admin_message, $payment_id );
	$admin_headers = apply_filters( 'give_buckaroo_admin_donation_notification_headers', array(), $payment_id );
	$attachments   = apply_filters( 'give_buckaroo_admin_donation_notification_attachments', array(), $payment_id );

	wp_mail( $admin_email, $admin_subject, $admin_message, $admin_headers, $attachments );

}


/**
 * Register gateway settings
 *
 * @since  1.0
 * @return array
 */
function give_buckaroo_add_settings( $settings ) {

	//Vars
	$prefix = '_give_';

	$is_gateway_active = give_is_gateway_active( 'buckaroo' );

	//this gateway isn't active
	if ( ! $is_gateway_active ) {
		//return settings and bounce
		return $settings;
	}

	//Fields
	$check_settings = array(

		array(
			'name'    => __( 'Customize buckaroo Donations', 'give' ),
			'desc'    => __( 'If you would like to customize the donation instructions for this specific forms check this option.', 'give' ),
			'id'      => $prefix . 'customize_buckaroo_donations',
			'type'    => 'radio_inline',
			'default' => 'no',
			'options' => array(
				'yes' => __( 'Yes', 'give' ),
				'no'  => __( 'No', 'give' ),
			),
		),
		array(
			'name'        => __( 'Request Billing Information', 'give' ),
			'desc'        => __( 'This option will enable the billing details section for this form\'s buckaroo donation payment gateway. The fieldset will appear above the buckaroo donation instructions.', 'give' ),
			'id'          => $prefix . 'buckaroo_donation_enable_billing_fields_single',
			'row_classes' => 'give-subfield',
			'type'        => 'checkbox'
		),
		array(
			'id'          => $prefix . 'buckaroo_checkout_notes',
			'name'        => __( 'buckaroo Donation Instructions', 'give' ),
			'desc'        => __( 'Enter the instructions you want to display to the donor during the donation process. Most likely this would include important information like mailing address and who to make the check out to.', 'give' ),
			'default'     => give_get_default_buckaroo_donation_content(),
			'type'        => 'wysiwyg',
			'row_classes' => 'give-subfield',
			'options'     => array(
				'textarea_rows' => 6,
			)
		),
		array(
			'id'          => $prefix . 'buckaroo_donation_subject',
			'name'        => __( 'buckaroo Donation Email Instructions Subject', 'give' ),
			'desc'        => __( 'Enter the subject line for the donation receipt email.', 'give' ),
			'default'     => __( '{donation} - buckaroo Donation Instructions', 'give' ),
			'row_classes' => 'give-subfield',
			'type'        => 'text'
		),
		array(
			'id'          => $prefix . 'buckaroo_donation_email',
			'name'        => __( 'buckaroo Donation Email Instructions', 'give' ),
			'desc'        => __( 'Enter the instructions you want emailed to the donor after they have submitted the donation form. Most likely this would include important information like mailing address and who to make the check out to.', 'give' ),
			'default'     => give_get_default_buckaroo_donation_email_content(),
			'type'        => 'wysiwyg',
			'row_classes' => 'give-subfield',
			'options'     => array(
				'textarea_rows' => 6,
			)
		)
	);

	return array_merge( $settings, $check_settings );
}

add_filter( 'give_forms_display_options_metabox_fields', 'give_buckaroo_add_settings' );


/**
 * buckaroo Donation Content
 *
 * @description Get default buckaroo donation text
 *
 * @return mixed|void
 */
function give_get_default_buckaroo_donation_content() {

	$sitename = get_bloginfo( 'sitename' );

	$default_text = '<p>' . __( 'In order to make a donation we ask that you please follow these instructions', 'give' ) . ': </p>';
	$default_text .= '<ol>';
	$default_text .= '<li>' . _x( 'Make a check payable to ', 'Step 1 for donating buckaroo by check', 'give' ) . '"' . $sitename . '"' . '</li>';
	$default_text .= '<li>' . _x( 'On the memo line of the check, please indicate that the donation is for ', 'Step 2 for donating by check; this explains who the check should be written to', 'give' ) . '"' . $sitename . '"' . '</li>';
	$default_text .= '<li>' . _x( 'Please mail your check to:', 'Step 3; where to mail the check', 'give' ) . '</li>';
	$default_text .= '</ol>';
	$default_text .= '&nbsp;&nbsp;&nbsp;&nbsp;<em>' . $sitename . '</em><br>';
	$default_text .= '&nbsp;&nbsp;&nbsp;&nbsp;<em>123 G Street </em><br>';
	$default_text .= '&nbsp;&nbsp;&nbsp;&nbsp;<em>San Diego, CA 92101 </em><br>';
	$default_text .= '<p>' . __( 'All contributions will be gratefully acknowledged and are tax deductible.', 'give' ) . '</p>';

	return apply_filters( 'give_default_buckaroo_donation_content', $default_text );

}

/**
 * buckaroo Donation Email Content
 *
 * @description Gets the default buckaroo donation email content
 *
 * @return mixed|void
 */
function give_get_default_buckaroo_donation_email_content() {

	$sitename     = get_bloginfo( 'sitename' );
	$default_text = '<p>' . __( 'Dear {name},', 'give' ) . '</p>';
	$default_text .= '<p>' . __( 'Thank you for your buckaroo donation request! Your generosity is greatly appreciated. In order to make an buckaroo donation we ask that you please follow these instructions', 'give' ) . ': </p>';
	$default_text .= '<ol>';
	$default_text .= '<li>' . _x( 'Make a check payable to ', 'Step 1 for donating buckaroo by check', 'give' ) . '"' . $sitename . '"' . '</li>';
	$default_text .= '<li>' . _x( 'On the memo line of the check, please indicate that the donation is for ', 'Step 2 for donating by check; this explains who the check should be written to', 'give' ) . '"' . $sitename . '"' . '</li>';
	$default_text .= '<li>' . _x( 'Please mail your check to:', 'Step 3; where to mail the check', 'give' ) . '</li>';
	$default_text .= '</ol>';
	$default_text .= '&nbsp;&nbsp;&nbsp;&nbsp;<em>' . $sitename . '</em><br>';
	$default_text .= '&nbsp;&nbsp;&nbsp;&nbsp;<em>123 G Street </em><br>';
	$default_text .= '&nbsp;&nbsp;&nbsp;&nbsp;<em>San Diego, CA 92101 </em><br>';
	$default_text .= '<p>' . __( 'Once your donation has been received we will mark it as complete and you will receive an email receipt for your records. Please contact us with any questions you may have!', 'give' ) . '</p>';
	$default_text .= '<p>' . __( 'Sincerely,', 'give' ) . '</p>';
	$default_text .= '<p>' . $sitename . '</p>';

	return apply_filters( 'give_default_buckaroo_donation_content', $default_text );

}
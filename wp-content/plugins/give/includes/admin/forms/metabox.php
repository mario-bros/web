<?php
/**
 * Metabox Functions
 *
 * @package     Give
 * @subpackage  Admin/Forms
 * @copyright   Copyright (c) 2015, WordImpress
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'cmb2_meta_boxes', 'give_single_forms_cmb2_metaboxes' );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 *
 * @return array
 */
function give_single_forms_cmb2_metaboxes( array $meta_boxes ) {

	$post_id          = give_get_admin_post_id();
	$price            = give_get_form_price( $post_id );
	$goal             = give_get_form_goal( $post_id );
	$variable_pricing = give_has_variable_prices( $post_id );
	$prices           = give_get_variable_prices( $post_id );

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_give_';

	/**
	 * Repeatable Field Groups
	 */
	 
    $sponsors = array();
	$sponsors[''] = "Choose a sponsor";
global $wpdb;	
$table_name = $wpdb->prefix . "users";
$sql = "SELECT ID, display_name, user_email FROM " . $table_name . " WHERE ID in (select user_id from wp_usermeta where meta_key = 'role' and meta_value = 'member') OR ID = '1'";	
	
$pageposts = $wpdb->get_results($sql);

foreach ( $pageposts as $sponsor ) 
{
	$ID = $sponsor->ID;
	$display_name = $sponsor->display_name . " (".$sponsor->user_email.")";
	$sponsors[$ID] = $display_name;
}


//echo $sql; exit();
//print_r($choices); exit();

	$meta_boxes['form_field_options'] = apply_filters( 'give_forms_field_options', array(
		'id'           => 'form_field_options',
		'title'        => __( 'Donation Options', 'give' ),
		'object_types' => array( 'give_forms' ),
		'context'      => 'normal',
		'priority'     => 'high', //Show above Content WYSIWYG
		'fields'       => apply_filters( 'give_forms_donation_form_metabox_fields', array(
				
				
				array(
					'name'        => __( 'Name', 'give' ),
					'description' => __( 'Name of the Child.', 'give' ),
					'id'          => $prefix . 'childname',
					'type'        => 'text',
					'row_classes' => 'give-subfield',
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( '', 'give' ),
					),
				),				
				
				array(
					'name'        => __( 'Date of Birth', 'give' ),
					'description' => __( 'Data of Birth of the Child.', 'give' ),
					'id'          => $prefix . 'date_of_birth',
					'type'        => 'text',
					
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( '01-01-1970', 'give' ),
					),
				),	
				
				array(
					'name'        => __( 'Gender', 'give' ),
					'description' => __( 'Gender of the Child.', 'give' ),
					'id'          => $prefix . 'gender',
					'type'        => 'text',
					'row_classes' => 'give-subfield',
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( 'Male/Female', 'give' ),
					),
				),		
				
				array(
					'name'        => __( 'Introduction', 'give' ),
					'description' => __( 'Small introduction.', 'give' ),
					'id'          => $prefix . 'intro',
					'type'        => 'textarea',
					
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( 'introduction text about the child', 'give' ),
					),
				),
				
				array(
					'name'        => __( 'Full information', 'give' ),
					'description' => __( 'Full information.', 'give' ),
					'id'          => $prefix . 'fullinfo',
					'type'        => 'wysiwyg',
					
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( 'Information text about the child', 'give' ),
					),
				),	
				
				array(
					'name'        => __( 'Youtube video', 'give' ),
					'description' => __( 'Youtube video.', 'give' ),
					'id'          => $prefix . 'youtubelink',
					'type'        => 'text',
					'row_classes' => 'give-subfield',
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( 'enter the youtube url', 'give' ),
					),
				),							
				
				
				
				
														
				
				
				//Donation Option
				array(
					'name'        => __( 'Donation Option', 'give' ),
					'description' => __( 'Would you like this form to have one set donation price or multiple levels (for example, $10 silver, $20 gold, $50 platinum)?', 'give' ),
					'id'          => $prefix . 'price_option',
					'type'        => 'radio_inline',
					'default'     => 'set',
					'options'     => apply_filters( 'give_forms_price_options', array(
						'set'   => __( 'Set Donation', 'give' ),
						'multi' => __( 'Multi-level Donation', 'give' ),
					) ),
				),
				array(
					'name'         => __( 'Set Donation', 'give' ),
					'description'  => __( 'This is the set donation amount for this form.', 'give' ),
					'id'           => $prefix . 'set_price',
					'type'         => 'text_small',
					'row_classes'  => 'give-subfield',
					'before_field' => give_get_option( 'currency_position' ) == 'before' ? '<span class="give-money-symbol give-money-symbol-before">' . give_currency_symbol() . '</span>' : '',
					'after_field'  => give_get_option( 'currency_position' ) == 'after' ? '<span class="give-money-symbol give-money-symbol-after">' . give_currency_symbol() . '</span>' : '',
					'attributes'   => array(
						'placeholder' => give_format_amount( '0.00' ),
						'value'       => isset( $price ) ? esc_attr( give_format_amount( $price ) ) : '',
						'class'       => 'cmb-type-text-small give-money-field',
					),
				),
				//Donation levels: Header
				array(
					'id'   => $prefix . 'levels_header',
					'type' => 'levels_repeater_header',
				),
				//Donation Levels: Repeatable CMB2 Group
				array(
					'id'          => $prefix . 'donation_levels',
					'type'        => 'group',
					'row_classes' => 'give-subfield',
					'options'     => array(
						'add_button'    => __( 'Add Level', 'give' ),
						'remove_button' => __( '<span class="dashicons dashicons-no"></span>', 'give' ),
						'sortable'      => true, // beta
					),
					// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
					'fields'      => apply_filters( 'give_donation_levels_table_row', array(
						array(
							'name' => __( 'ID', 'give' ),
							'id'   => $prefix . 'id',
							'type' => 'levels_id',
						),
						array(
							'name'         => __( 'Amount', 'give' ),
							'id'           => $prefix . 'amount',
							'type'         => 'text_small',
							'before_field' => give_get_option( 'currency_position' ) == 'before' ? '<span class="give-money-symbol  give-money-symbol-before">' . give_currency_symbol() . '</span>' : '',
							'after_field'  => give_get_option( 'currency_position' ) == 'after' ? '<span class="give-money-symbol  give-money-symbol-after">' . give_currency_symbol() . '</span>' : '',
							'attributes'   => array(
								'placeholder' => give_format_amount( '0.00' ),
								'class'       => 'cmb-type-text-small give-money-field',
							),
							'before'       => 'give_format_admin_multilevel_amount',
						),
						array(
							'name'       => __( 'Text', 'give' ),
							'id'         => $prefix . 'text',
							'type'       => 'text',
							'attributes' => array(
								'placeholder' => __( 'Donation Level', 'give' ),
								'rows'        => 3,
							),
						),
						array(
							'name' => __( 'Default', 'give' ),
							'id'   => $prefix . 'default',
							'type' => 'give_default_radio_inline'
						),
					) ),
				),
				
			
				//Sponsors 
				array(
					'name'        => __( 'Livelihood Sponsor', 'give' ),
					'description' => __( 'Set the Livelihood Sponsor', 'give' ),
					'id'          => $prefix . 'liv_sponsor',
					'type'        => 'select',
					'options'     => $sponsors,
					'default'     => 'none',
				),	
				
				array(
					'name'        => __( 'Education Sponsor', 'give' ),
					'description' => __( 'Set the Education Sponsor', 'give' ),
					'id'          => $prefix . 'edu_sponsor',
					'type'        => 'select',
					'options'     => $sponsors,
					'default'     => 'none',
				),
				
				array(
					'name'        => __( 'Monthly Report', 'give' ),
					'description' => __( 'Upload the monthly report', 'give' ),
					'id'          => $prefix . 'monthly_report',
					'type'        => 'file',
					'default'     => 'none',
				),											
				
				
				//Display Style
				array(
					'name'        => __( 'Display Style', 'give' ),
					'description' => __( 'Set how the donations levels will display on the form.', 'give' ),
					'id'          => $prefix . 'display_style',
					'type'        => 'radio_inline',
					'default'     => 'buttons',
					'options'     => array(
						'buttons'  => __( 'Buttons', 'give' ),
						'radios'   => __( 'Radios', 'give' ),
						'dropdown' => __( 'Dropdown', 'give' ),
					),
				),
				//Custom Amount
				array(
					'name'        => __( 'Custom Amount', 'give' ),
					'description' => __( 'Do you want the user to be able to input their own donation amount?', 'give' ),
					'id'          => $prefix . 'custom_amount',
					'type'        => 'radio_inline',
					'default'     => 'no',
					'options'     => array(
						'yes' => __( 'Yes', 'give' ),
						'no'  => __( 'No', 'give' ),
					),
				),
				array(
					'name'        => __( 'Custom Amount Text', 'give' ),
					'description' => __( 'This text appears as a label next to the custom amount field for single level forms. For multi-level forms the text will appear as it\'s own level (ie button, radio, or select option). Add your own message or leave this field blank to prevent it from displaying within your form.', 'give' ),
					'id'          => $prefix . 'custom_amount_text',
					'type'        => 'text',
					'row_classes' => 'give-subfield',
					'attributes'  => array(
						'rows'        => 3,
						'placeholder' => __( 'Give a Custom Amount', 'give' ),
					),
				),
				//Goals
				array(
					'name'        => __( 'Set Goal?', 'give' ),
					'description' => __( 'Do you want to set a donation goal for this form?', 'give' ),
					'id'          => $prefix . 'goal_option',
					'type'        => 'radio_inline',
					'default'     => 'no',
					'options'     => array(
						'yes' => __( 'Yes', 'give' ),
						'no'  => __( 'No', 'give' ),
					),
				),
				array(
					'name'         => __( 'Set Goal', 'give' ),
					'description'  => __( 'This is the goal you want to achieve for this form.', 'give' ),
					'id'           => $prefix . 'set_goal',
					'type'         => 'text_money',
					'row_classes'  => 'give-subfield',
					'before_field' => give_currency_symbol(), // Replaces default '$'
					'attributes'   => array(
						'placeholder' => give_format_amount( '0.00' ),
						'value'       => isset( $goal ) ? esc_attr( give_format_amount( $goal ) ) : '',
					),
				),
				array(
					'name'        => __( 'Goal Progress Bar Color', 'give' ),
					'id'          => $prefix . 'goal_color',
					'type'        => 'colorpicker',
					'row_classes' => 'give-subfield',
					'default'     => '#2bc253',
				),
			)
		)
	) );
	
	




	return $meta_boxes;

}

/**
 * Repeatable Levels Custom Field
 */
add_action( 'cmb2_render_levels_repeater_header', 'give_cmb_render_levels_repeater_header', 10 );
function give_cmb_render_levels_repeater_header() {
	?>

	<div class="table-container">
		<div class="table-row">
			<div class="table-cell col-id"><?php _e( 'ID', 'give' ); ?></div>
			<div class="table-cell col-amount"><?php _e( 'Amount', 'give' ); ?></div>
			<div class="table-cell col-text"><?php _e( 'Text', 'give' ); ?></div>
			<div class="table-cell col-default"><?php _e( 'Default', 'give' ); ?></div>
			<?php do_action( 'give_donation_levels_table_head' ); ?>
			<div class="table-cell col-sort"><?php _e( 'Sort', 'give' ); ?></div>

		</div>
	</div>

	<?php
}


/**
 * CMB2 Repeatable ID Field
 */
add_action( 'cmb2_render_levels_id', 'give_cmb_render_levels_id', 10, 5 );
function give_cmb_render_levels_id( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {

	$escaped_value = ( isset( $escaped_value['level_id'] ) ? $escaped_value['level_id'] : '' );

	$field_options_array = array(
		'class' => 'give-hidden give-level-id-input',
		'name'  => $field_type_object->_name( '[level_id]' ),
		'id'    => $field_type_object->_id( '_level_id' ),
		'value' => $escaped_value,
		'type'  => 'number',
		'desc'  => '',
	);

	echo '<p class="give-level-id">' . $escaped_value . '</p>';
	echo $field_type_object->input( $field_options_array );

}

/**
 * CMB2 Repeatable Default ID Field
 */
add_action( 'cmb2_render_give_default_radio_inline', 'give_cmb_give_default_radio_inline', 10, 5 );
function give_cmb_give_default_radio_inline( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {
	echo '<input type="radio" class="cmb2-option donation-level-radio" name="' . $field_object->args['_name'] . '" id="' . $field_object->args['id'] . '" value="default" ' . checked( 'default', $escaped_value, false ) . '>';
	echo '<label for="' . $field_object->args['id'] . '">Default</label>';

}


/**
 * Add Shortcode Copy Field to Publish Metabox
 *
 * @since: 1.0
 */
function give_add_shortcode_to_publish_metabox() {

	if ( 'give_forms' !== get_post_type() ) {
		return false;
	}

	global $post;

	//Only enqueue scripts for CPT on post type screen
	if ( 'give_forms' === $post->post_type ) {
		//Shortcode column with select all input
		$shortcode = htmlentities( '[give_form id="' . $post->ID . '"]' );
		echo '<div class="shortcode-wrap box-sizing"><label>' . __( 'Give Form Shortcode:', 'give' ) . '</label><input onClick="this.setSelectionRange(0, this.value.length)" type="text" class="shortcode-input" readonly value="' . $shortcode . '"></div>';

	}

}

add_action( 'post_submitbox_misc_actions', 'give_add_shortcode_to_publish_metabox' );


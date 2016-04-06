<?php
/**
 * Charity - Flickr Widget
 * @package charity
 * @version     v.1.0
 * 
 */

class Charity_Flickr_Widget extends WP_Widget {

	var $prefix;
	var $textdomain;


	/**
	 * Set up the widget's unique name, ID, class, description, and other options
	 * @since 1.0
	 **/
	function __construct() {
		
		// Set default variable for the widget instances
		$this->prefix = 'charity_flickr';
		$this->textdomain = 'charity';

		// Set up the widget control options
		$control_options = array(
				'width' => 444,
				'height' => 350,
				'id_base' => $this->prefix
		);

		// Add some informations to the widget
		$widget_options = array('classname' => 'charity_flickr_widget', 'description' => __( 'Flickr photo stream from ID', "charity" ) );

		// Create the widget
		$this->WP_Widget($this->prefix, __('Charity Flickr Badge', "charity"), $widget_options, $control_options );
		
	}

	/**
	 * Push the widget 
	 * @since 1.0
	 **/
	function widget( $args, $instance ) {
		extract( $args );

		// Set up the arguments for wp_list_categories().
		$cur_arg = array(
				'title'			=> $instance['title'],
				'type'			=> empty( $instance['type'] ) ? 'user' : $instance['type'],
				'flickr_id'		=> $instance['flickr_id'],
				'count'			=> (int) $instance['count'],
				'display'		=> empty( $instance['display'] ) ? 'latest' : $instance['display'],
				'size'			=> isset( $instance['size'] ) ? $instance['size'] : 't',
		);

		extract( $cur_arg );

		// print the before widget
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Get the user direction, rtl or ltr
		if ( function_exists( 'is_rtl' ) )
			$dir = is_rtl() ? 'rtl' : 'ltr';


		echo "<div class='charity-flickr-badge-wrapper charity-flicker-wrap-$dir'>";

		$protocol = is_ssl() ? 'https' : 'http';

		// If the widget have an ID, we can continue
		if ( ! empty( $instance['flickr_id'] ) )
			echo "<script type='text/javascript' src='$protocol://www.flickr.com/badge_code_v2.gne?count=$count&amp;display=$display&amp;size=$size&amp;layout=x&amp;source=$type&amp;$type=$flickr_id'></script>";
		else
			echo '<p>' . __('Please provide an Flickr ID', "charity") . '</p>';

		echo '</div>';

		// Print the after widget
		echo $after_widget;
	}



	/**
	 * Widget update functino
	 * @since 1.0
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['type'] 			= strip_tags($new_instance['type']);
		$instance['flickr_id'] 		= strip_tags($new_instance['flickr_id']);
		$instance['count'] 			= (int) $new_instance['count'];
		$instance['display'] 		= strip_tags($new_instance['display']);
		$instance['size']			= strip_tags($new_instance['size']);
		$instance['title']			= strip_tags($new_instance['title']);

		return $instance;
	}



	/**
	 * Widget form function
	 * @since 1.0
	 **/
	function form( $instance ) {
		// Set up the default form values.
		$defaults = array(
				'title'			=> esc_attr__( 'Flickr Widget', "charity" ),
				'type'			=> 'user',
				'flickr_id'		=> '', // 71865026@N00
				'count'			=> 9,
				'display'		=> 'display',
				'size'			=> 't',
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

		$types = array(
				'user'  => esc_attr__( 'user', "charity" ),
				'group' => esc_attr__( 'group', "charity" )
		);
		$sizes = array(
				's' => esc_attr__( 'Standard', "charity" ),
				't' => esc_attr__( 'Thumbnail', "charity" ),
				'm' => esc_attr__( 'Medium', "charity" )
		);
		$displays = array(
				'latest' => esc_attr__( 'latest', "charity" ),
				'random' => esc_attr__( 'random', "charity" )
		);
                
                
                $flickrLookup="http://goo.gl/PM6rZ";
		?>
		
		<div id="fbw-<?php echo $this->id ; ?>" class="totalControls charity-flicker-form">
					<ul>
						<li>
							<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', "charity"); ?></label>
							
							<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( '', "charity" ); ?></label>
							<span class="controlDesc"><?php _e( 'The type of images from user or group.', "charity" ); ?></span>
							<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
								<?php foreach ( $types as $k => $v ) { ?>
									<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $instance['type'], $k ); ?>><?php echo esc_html( $v ); ?></option>
								<?php } ?>
							</select>				
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID', "charity"); ?></label>							
							<input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
							<p><span class="controlDesc"><?php  _e( 'Put the flickr ID here, go to', "charity"); ?> <a href="<?php echo esc_url($flickrLookup); ?>" target="_blank"><?php _e("Flickr NSID Lookup", "charity"); ?></a><?php _e('if you don\'t know your ID. Example: 71865026@N00', "charity" ); ?></span></p>
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of images shown from 1 to 10', "charity"); ?></label>
							
							<input class="column-last" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr( $instance['count'] ); ?>" size="3" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('display'); ?>"><?php _e('Get the image from recent or use random function.', "charity"); ?></label>
							
							<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
								<?php foreach ( $displays as $k => $v ) { ?>
									<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $instance['display'], $k ); ?>><?php echo esc_html( $v ); ?></option>
								<?php } ?>
							</select>	
						</li>
						<li>
							<label for="<?php echo $this->get_field_id(''); ?>"><?php _e( 'Sizes', "charity" ); ?></label>
							<span class="controlDesc"><?php _e( 'Represents the size of the image', "charity" ); ?></span>
							<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
								<?php foreach ( $sizes as $k => $v ) { ?>
									<option value="<?php echo $k; ?>" <?php selected( $instance['size'], $k ); ?>><?php echo $v; ?></option>
								<?php } ?>
							</select>				
						</li>
						</ul>
				</div>			
		<?php
	}
}
function flickr_badges_widget_init() {
	register_widget( 'Charity_Flickr_Widget' );
}

add_action( 'widgets_init', 'flickr_badges_widget_init' );
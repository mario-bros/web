<?php
class WP_Widget_Address extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_adress', 'description' => __('Address Widget.', "charity"));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('address-widget', __('Charity Address Widget', "charity"), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$instance = array(
				'title' 		=> $instance['title'],
				'text'			=> $instance['text'],
				'address'		=> $instance['address'],
				'phoneNo'		=> $instance['phoneNo'],
				'phoneNoTwo'		=> $instance['phoneNoTwo'],
				'mailId'		=> $instance['mailId'],
				
		);

		/** This filter is documented in wp-includes/default-widgets.php */
		

		/**
		 * Filter the content of the Text widget.
		 *
		 * @since 2.3.0
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		 ?>
		 <div class="footer-logo">
								<a href="<?php esc_url(site_url()) ?>" title="Welcome to Charity"><?php print($instance['title']); ?></a>
							</div>
							<p>
								<?php print($instance['text']); ?>
							</p>
							<address>
								<span> <i class="fa fa-home"></i> <span><?php print($instance['address']); ?></span> </span>
								<span> <i class="fa fa-phone-square"></i> <span><?php print($instance['phoneNo']); ?></span> </span>
								<span> <i class="fa fa-phone-square"></i> <span><?php print($instance['phoneNoTwo']); ?></span> </span>
								<span> <i class="fa fa-envelope"></i> <span><a href="mailto:<?php print($instance['mailId']); ?>"><?php print($instance['mailId']); ?></a></span> </span>
							</address>

		
	<?php	
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'text' => '', 'address' => '','phoneNo'=>'','phoneNoTwo'=>'','mailId'=>''));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['phoneNo'] = strip_tags($new_instance['phoneNo']);
		$instance['phoneNoTwo'] = strip_tags($new_instance['phoneNoTwo']);
		$instance['mailId'] = strip_tags($new_instance['mailId']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'address' => '','phoneNo'=>'','phoneNoTwo'=>'','mailId'=>'' ) );
		$title = strip_tags($instance['title']);
		$text = strip_tags($instance['text']);
		$address = strip_tags($instance['address']);
		$phoneNo = strip_tags($instance['phoneNo']);
		$mailId = strip_tags($instance['mailId']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		
		<p><label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo esc_attr($address); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('phoneNo'); ?>"><?php _e('Phone No:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('phoneNo'); ?>" name="<?php echo $this->get_field_name('phoneNo'); ?>" type="text" value="<?php echo esc_attr($phoneNo); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('phoneNoTwo'); ?>"><?php _e('Phone No:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('phoneNoTwo'); ?>" name="<?php echo $this->get_field_name('phoneNoTwo'); ?>" type="text" value="<?php echo esc_attr($phoneNoTwo); ?>" /></p>		
		
		<p><label for="<?php echo $this->get_field_id('mailId'); ?>"><?php _e('Mail Id:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('mailId'); ?>" name="<?php echo $this->get_field_name('mailId'); ?>" type="text" value="<?php echo esc_attr($mailId); ?>" /></p>

<?php
	}
}
function address_widget_init() {
	register_widget( 'WP_Widget_Address' );
}

add_action( 'widgets_init', 'address_widget_init' );
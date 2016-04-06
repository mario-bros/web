<?php
class Charity_about_widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'about-widget', 'description' => __('Description with Buttons.', "charity"));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('about-widget', __('Charity Description With Button', "charity"), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$instance = array(
				'title' 		=> $instance['title'],
				'text'			=> $instance['text'],
				'buttontext1'		=> $instance['buttontext1'],
				'buttonlink1'		=> $instance['buttonlink1'],
				'buttontext2'		=> $instance['buttontext2'],
				'buttonlink2'		=> $instance['buttonlink2'],
				
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
								<a title="Welcome to Charity" href="<?php esc_url(site_url()) ?>"><?php print($instance['title']); ?></a>
							</div>
							<p><?php print($instance['text']); ?></p>
							<div class="btn-wrapper">
								<a class="btn btn-default" href="<?php esc_url($instance['buttonlink1']); ?>"><?php print($instance['buttontext1']); ?></a>
								<a class="btn btn-default" href="<?php esc_url($instance['buttonlink2']); ?>"><?php print($instance['buttontext2']); ?></a>
							</div>
		
	<?php	
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'text' => '', 'buttontext1' => '','buttonlink1'=>'','buttontext2'=>'','buttonlink2'=>''));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['buttontext1'] = strip_tags($new_instance['buttontext1']);
		$instance['buttonlink1'] = strip_tags($new_instance['buttonlink1']);
		$instance['buttontext2'] = strip_tags($new_instance['buttontext2']);
		$instance['buttonlink2'] = strip_tags($new_instance['buttonlink2']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'buttontext1' => '','buttonlink1'=>'','buttontext2'=>'','buttonlink2'=>'' ) );
		$title = strip_tags($instance['title']);
		$text = strip_tags($instance['text']);
		$buttontext1 = strip_tags($instance['buttontext1']);
		$buttonlink1 = strip_tags($instance['buttonlink1']);
		$buttontext2 = strip_tags($instance['buttontext2']);
		$buttonlink2 = strip_tags($instance['buttonlink2']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		
		<p><label for="<?php echo $this->get_field_id('buttontext1'); ?>"><?php _e('Button Text 1:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('buttontext1'); ?>" name="<?php echo $this->get_field_name('buttontext1'); ?>" type="text" value="<?php echo esc_attr($buttontext1); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('buttonlink1'); ?>"><?php _e('Button Link 1:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('buttonlink1'); ?>" name="<?php echo $this->get_field_name('buttonlink1'); ?>" type="text" value="<?php echo esc_attr($buttonlink1); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('buttontext2'); ?>"><?php _e('Button Text 2:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('buttontext2'); ?>" name="<?php echo $this->get_field_name('buttontext2'); ?>" type="text" value="<?php echo esc_attr($buttontext2); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('buttonlink2'); ?>"><?php _e('Button Link 2:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('buttonlink2'); ?>" name="<?php echo $this->get_field_name('buttonlink2'); ?>" type="text" value="<?php echo esc_attr($buttonlink2); ?>" /></p>

<?php
	}
}
function about_widget_init() {
	register_widget( 'Charity_about_widget' );
}

add_action( 'widgets_init', 'about_widget_init' );
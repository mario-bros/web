<?php
class Charity_social_widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'social-widget', 'description' => __('Social Media Widget.', "charity"));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('social-widget', __('Charity Social Media Widget', "charity"), $widget_ops, $control_ops);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$instance = array(
				'title' 		=> $instance['title'],
				'facebook'			=> $instance['facebook'],
				'twitter'		=> $instance['twitter'],
				'dribble'		=> $instance['dribble'],
				'pinterest'		=> $instance['pinterest'],
				'gplus'		=> $instance['gplus'],
				'instagram'		=> $instance['instagram'],
				
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
		<div class="social-media-widget">
			<h6><?php echo $instance['title']; ?></h6>
			<ul class="social-icons">
				<?php 
				$facebook_link=$instance['facebook'];
				$twitter_link=$instance['twitter'];
				$dribble_link=$instance['dribble'];
				$pinterest_link=$instance['pinterest'];
				$gplus_link=$instance['gplus'];
				$instagram_link=$instance['instagram'];
				
				?>         <?php if(!empty($facebook_link)){ ?>
								<li>
									<a target="_blank" href="<?php echo $facebook_link; ?>"><i class="fa fa-facebook"></i></a>
								</li>
					
			<?php	}?>
			
			<?php if(!empty($twitter_link)){ ?>
								<li>
									<a target="_blank" href="<?php echo $twitter_link; ?>"><i class="fa fa-twitter"></i></a>
								</li>
								<?php	}?>
								<?php if(!empty($dribble_link)){ ?>
								<li>
									<a target="_blank" href="<?php echo $dribble_link; ?>"><i class="fa fa-dribbble"></i></a>
								</li>
								<?php	}?>
								<?php if(!empty($pinterest_link)){ ?>
								<li>
									<a target="_blank" href="<?php echo $pinterest_link; ?>"><i class="fa fa-pinterest"></i></a>
								</li>
								<?php	}?>
								<?php if(!empty($gplus_link)){ ?>
								<li>
									<a target="_blank" href="<?php echo $gplus_link; ?>"><i class="fa fa-google-plus"></i></a>
								</li>
								<?php	}?>
								<?php if(!empty($instagram_link)){ ?>
								<li>
									<a target="_blank" href="<?php echo $instagram_link; ?>"><i class="fa fa-instagram"></i></a>
								</li>
							
								<?php	}?>
								<li><a href="https://www.linkedin.com/company/peduli-anak-foundation" target="blank"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="https://www.youtube.com/user/pedulianak" target="blank"><i class="fa fa-youtube"></i></a></li>	
							</ul>				
</div>
		
	<?php	
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'facebook' => '', 'twitter' => '','dribble'=>'','pinterest'=>'','gplus'=>'','instagram'=>''));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['facebook'] = strip_tags($new_instance['facebook']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['dribble'] = strip_tags($new_instance['dribble']);
		$instance['pinterest'] = strip_tags($new_instance['pinterest']);
		$instance['gplus'] = strip_tags($new_instance['gplus']);
		$instance['instagram'] = strip_tags($new_instance['instagram']);
		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'facebook' => '', 'twitter' => '','dribble'=>'','pinterest'=>'','gplus'=>'','instagram'=>'' ) );
		$title = strip_tags($instance['title']);
		$facebook = strip_tags($instance['facebook']);
		$twitter = strip_tags($instance['twitter']);
		$dribble = strip_tags($instance['dribble']);
		$pinterest = strip_tags($instance['pinterest']);
		$gplus = strip_tags($instance['gplus']);
		$instagram = strip_tags($instance['instagram']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('dribble'); ?>"><?php _e('Dribble:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('dribble'); ?>" name="<?php echo $this->get_field_name('dribble'); ?>" type="text" value="<?php echo esc_attr($dribble); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" name="<?php echo $this->get_field_name('pinterest'); ?>" type="text" value="<?php echo esc_attr($pinterest); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('gplus'); ?>"><?php _e('GooglePlus:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('gplus'); ?>" name="<?php echo $this->get_field_name('gplus'); ?>" type="text" value="<?php echo esc_attr($gplus); ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e('Instagram:', "charity"); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" type="text" value="<?php echo esc_attr($instagram); ?>" /></p>

<?php
	}
}
function Charity_social_widget_init() {
	register_widget( 'Charity_social_widget' );
}

add_action( 'widgets_init', 'Charity_social_widget_init' );
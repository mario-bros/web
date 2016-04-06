<?php
/*
 * 
 * Charity Custom Widget For Category
 * 
 * */
add_action('widgets_init', 'charity_category');

function charity_category() {
    register_widget('Charity_Category');
}

/**
 * Adds Foo_Widget widget.
 */
class Charity_Category extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
                'custom_cat', // Base ID
                __('Charity Category', "charity"), // Name
                array('classname' => 'charity_category', 'description' => __('Charity Category. ', 'charity'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);

        $title = $instance['title'];
        ?>
       
       <aside class="media">
		<h3><?php echo esc_html($title); ?></h3>
		<ul class="archives">
			
			<?php $cat_args['title_li'] = '';
					$cat_args['exclude'] = '1';
					$cat_args['show_count'] ='1';

					/**
					 * Filter the arguments for the Categories widget.
					 *
					 * @since 2.8.0
					 *
					 * @param array $cat_args An array of Categories widget options.
					 */
					wp_list_categories(apply_filters('widget_categories_args', $cat_args));
                    ?>

		</ul>
	</aside>

        <?php }

		/**
		* Back-end widget form.
		*
		* @see WP_Widget::form()
		*
		* @param array $instance Previously saved values from database.
		*/
		public function form($instance) {
		//Defaults
		$instance = wp_parse_args((array) $instance, array('title' => ''));
		$title = esc_attr($instance['title']);
        ?>
        <p><label for="<?php echo $this -> get_field_id('title'); ?>"><?php esc_html_e('Title:', "charity"); ?></label>
            <input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php print($title); ?>" /></p>
        <?php
		}

		/**
		* Sanitize widget form values as they are saved.
		*
		* @see WP_Widget::update()
		*
		* @param array $new_instance Values just sent to be saved.
		* @param array $old_instance Previously saved values from database.
		*
		* @return array Updated safe values to be saved.
		*/
		public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
		}

		}
	
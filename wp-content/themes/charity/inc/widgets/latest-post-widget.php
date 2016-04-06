<?php
/**
 * Recent Post Widgets 
 * @package     charity.inc.widgets
 * @version     v.1.0
 */

class Charity_recent_post_widget extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'charity_recent_post', 'description' => __("Charity Recent Posts.", "charity"));
        parent::__construct('recent-posts', __('Charity Recent Posts', "charity"), $widget_ops);
        $this->alt_option_name = 'charity_recent_post';

        add_action('save_post', array($this, 'flush_widget_cache'));
        add_action('deleted_post', array($this, 'flush_widget_cache'));
        add_action('switch_theme', array($this, 'flush_widget_cache'));
    }

    public function widget($args, $instance) {
        $cache = array();
        if (!$this->is_preview()) {
            $cache = wp_cache_get('widget_recent_posts', 'widget');
        }

        if (!is_array($cache)) {
            $cache = array();
        }

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        if (isset($cache[$args['widget_id']])) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();

        $title = (!empty($instance['title']) ) ? $instance['title'] : __('Recent', "charity");

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number']) ) ? absint($instance['number']) : 5;
        if (!$number)
            $number = 5;

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */
        $latestQuery = new WP_Query(apply_filters('widget_posts_args', array(
                    'posts_per_page' => $number,
                    'no_found_rows' => true,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true
        )));

        if ($latestQuery->have_posts()) :
            ?>

			<aside class="media">
		<h3 class="space-top"><?php printf("%s", $title);  ?></h3>
		<ul>
			
			<?php while ($latestQuery->have_posts()) : $latestQuery->the_post(); ?>
			
			<?php $format=(get_post_format()) ? get_post_format() : "standard"; ?>
			
			<li><a href="<?php the_permalink(); ?>" class="pull-left">
					<figure>
						<?php 
						if($format=="image"){
							if (has_post_thumbnail()) {
                         the_post_thumbnail('charity-recentpost-thumb');
                                    }
						}
						if($format=="gallery"){
							$metaValueGallery = vp_metabox('gallery_meta.gallery_group');
							$gallery_img=$metaValueGallery[0]['gallery_image']; ?>
							<img src="<?php echo esc_url(charity_resize($gallery_img,98,98));?>" alt="" />		
						<?php }
						if($format=="video"){
							$metaValueVideoImage = vp_metabox('video_meta.video_image'); ?>
							<img src="<?php echo esc_url(charity_resize($metaValueVideoImage,98,98));?>" alt="" />	
					 <?php	}
						
						?>
						
					</figure>
			</a>
				<div class="media-body">
					<p>
						<a href="<?php the_permalink(); ?>"><?php echo esc_html(charity_truncate_content(get_the_title(), 29)); ?></a>
					</p>
					<span><?php printf("%s", get_the_date("d, F'y")); ?></span>
				</div></li>
				
				<?php endwhile; ?>
			
		</ul>

	</aside>




            <?php
// Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        if (!$this->is_preview()) {
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set('widget_recent_posts', $cache, 'widget');
        } else {
            ob_end_flush();
        }
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $this->flush_widget_cache();

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['charity_recent_post']))
            delete_option('charity_recent_post');

        return $instance;
    }

    public function flush_widget_cache() {
        wp_cache_delete('widget_recent_posts', 'widget');
    }

    public function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php printf("%s", $this->get_field_id('title')); ?>"><?php esc_html_e('Title:', "charity"); ?></label>
            <input class="widefat" id="<?php printf("%s", $this->get_field_id('title')); ?>" name="<?php printf("%s", $this->get_field_name('title')); ?>" type="text" value="<?php  printf("%s",  $title); ?>" /></p>

        <p><label for="<?php printf("%s",  $this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', "charity"); ?></label>
            <input id="<?php printf("%s", $this->get_field_id('number')); ?>" name="<?php printf("%s", $this->get_field_name('number')); ?>" type="text" value="<?php printf("%s", $number); ?>" size="3" /></p>

        <?php
    }

}
function charity_recent_post_widget_init() {
	register_widget( 'Charity_recent_post_widget' );
}

add_action( 'widgets_init', 'charity_recent_post_widget_init' );
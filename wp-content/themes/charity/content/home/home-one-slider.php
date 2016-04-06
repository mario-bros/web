<?php
/**
 * Charity -  Home One Slider
 *
 * @package     charity
 * @version     v.1.0
 */
if (have_posts()) : while (have_posts()) : the_post();
        $slidershortcode = vp_metabox('homeslider.slidershortcode');
        ?>
        <section class="rev_slider_wrapper banner-section">
            <div class="rev_slider banner-slider">
                <?php if (!empty($slidershortcode)): ?>
                    <?php echo do_shortcode($slidershortcode); ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
    endwhile;
endif;

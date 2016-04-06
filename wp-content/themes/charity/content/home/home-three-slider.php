<?php
/**
 * Charity -  Home Three Slider
 *
 * @package     charity
 * @version     v.1.0
 */
if (have_posts()) :
    while (have_posts()) :
        the_post();
        $slidershortcode = vp_metabox('homeslider.slidershortcode');
        ?>
        <section class="rev_slider_wrapper slider-third">
            <div class="rev_slider banner-slider">
                <?php
                if (!empty($slidershortcode)):
                    echo do_shortcode($slidershortcode);
                endif;
                ?>
            </div>
        </section>
        <?php
    endwhile;
endif;

<?php
/**
 * Charity -  Home One Testimonial
 *
 * @package     charity
 * @version     v.1.0
 */
$args = array('post_type' => 'testimonial', 'post_status' => 'publish');
query_posts($args);
if (have_posts()) :
    ?>		
    <section class="testimonial parallax">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="testimonial-slider flexslider">
                        <ul class="slides">
                            <?php
                            while (have_posts()) : the_post();
                                $company = vp_metabox('testimonial.companey_name');
                                ?>									
                                <li>
                                    <div class="slide">
                                        <h2><?php echo vp_option('vpt_option.ch_shop_landing_testimonial'); ?></h2>
                                        <blockquote>
                                                <?php the_content(); ?>
                                            <footer>
                                                <span><?php the_title(); ?></span>
        <?php if (!empty($company)): ?><cite>(<?php echo esc_html($company); ?>)</cite><?php endif; ?>
                                            </footer>
                                        </blockquote>
                                    </div>
                                </li>
    <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endif;
wp_reset_query();
<?php
/**
 * Charity -  Home Three Testimonial
 *
 * @package     charity
 * @version     v.1.0
 */
$args = array(
    'post_type' => 'testimonial',
    'post_status' => 'publish'
);
query_posts($args);
if (have_posts()) :
    ?>
    <section class="donation-holder holders">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2><?php echo vp_option('vpt_option.ch_hThree_testimonial_title'); ?> </h2>
                    <div class="col-xs-12 col-md-12">
                        <div class="flexslider">
                            <ul class="slides">
                                <?php
                                while (have_posts()) :
                                    the_post();
                                    $company = vp_metabox('testimonial.companey_name');
                                    ?>	
                                    <li>
                                        <div class="testimonial-content">
                                            <?php
                                            if (has_post_thumbnail()) :
                                                $url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array(
                                                    108,
                                                    129
                                                        ));
                                                ?>
                                                <div class="img-circle photo-frame">
                                                    <img src="<?php echo esc_url($url[0]); ?>" alt="circle" class="img-circle" width="150" height="150">
                                                </div>
                                            <?php endif; ?>
                                            <blockquote>

                                                <i class="fa fa-quote-left quote-mark"></i>
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
                        <!-- End Flex -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;
wp_reset_query();
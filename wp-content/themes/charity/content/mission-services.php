<?php
/**
 * Charity -  Mission Services
 *
 * @package     charity
 * @version     v.1.0
 */
$args = array('post_type' => 'charity_our_mission', 'post_status' => 'publish', 'posts_per_page' => 6);
query_posts($args);
if (have_posts()) :
    ?><section class="container services text-center">
        <div class="row">
            <div class="col-xs-12">
                <header class="service-header section-header">
                    <h2><?php echo vp_option('vpt_option.ch_mission_services_title'); ?></h2>
                </header>              
                    <div class="row">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-xs-12 col-sm-4 zoom">
                            <?php
                            if (has_post_thumbnail()):
                                the_post_thumbnail("charity_mission");
                            endif;
                            ?>
                            <h3 class="h3"><?php the_title(); ?></h3>
                            <?php the_content(); ?>
                        </div>
                        <?php endwhile; ?>
                    </div>                
            </div>
        </div>
    </section>
    <?php
endif;
wp_reset_query();
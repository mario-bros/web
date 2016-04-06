<?php
/**
 * Charity -  Home Three Causes
 *
 * @package     charity
 * @version     v.1.0
 */
$args = array('post_type' => 'charity-causes', "post_status" => "publish", "posts_per_page" => 3);
global $more;
$more = 0;
$shortdescription = vp_metabox('helpicon.shortdescription');

query_posts($args);
if (have_posts()) :
    ?>
    <section class="our-causes our-causes-section our-causes3">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <header class="page-header section-header">
                        <h2><?php echo vp_option('vpt_option.ch_hThree_causes_title'); ?></h2>
                        <span><?php print($shortdescription); ?></span>
                    </header>
                    <div class="row">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="col-xs-12 col-md-4 item-wrapper">
                                <div class="items zoom">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="img-thumb">
                                            <figure>
                                                <?php the_post_thumbnail("charity_causes_thumb"); ?>
                                            </figure>
                                        </a>
                                    <?php endif; ?>
                                    <div class="item-content">
                                        <h3><?php the_title(); ?></h3>
                                        <?php do_action("charity_cauese_donation_details_sidebar"); ?>
                                        <?php do_action("charity_cauese_donation_details_single"); ?>
                                        <?php the_content(""); ?>
                                        <div class="buttonmiddle"><?php do_action("charity_causes_donation_button"); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
endif;
wp_reset_query();
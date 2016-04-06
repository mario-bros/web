<?php
/**
 * Charity -  Home Two Causes Listing
 *
 * @package     charity
 * @version     v.1.0
 */
?>
<section class="our-causes our-causes-section ">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <?php
                    $urgentCauseId = vp_option('vpt_option.charity_urgent_cause_id');

                    $args = array(
                        'p' => $urgentCauseId,
                        'post_type' => 'charity-causes',
                        "post_status" => "publish",
                        "posts_per_page" => 1
                    );
                    global $more;
                    $more = 0;
                    query_posts($args);
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            ?>
                            <div class="col-xs-12 col-md-6">
                                <h2><?php echo vp_option('vpt_option.ch_htwo_urgentcauses_title'); ?></h2>
                                <div class="items zoom">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="img-thumb">
                                            <figure><?php the_post_thumbnail("charity-urgent-causes"); ?></figure>
                                        </a>                                
                                    <?php endif; ?>

                                    <h3 class="h4"><?php the_title(); ?></h3>							
                                    <?php
                                    do_action("charity_cauese_donation_details_sidebar");
                                    do_action("charity_cauese_donation_details_single");
                                    ?> <p><?php echo esc_html(charity_truncate_content(get_the_content(), 200)); ?></p><?php
                                    //the_content("");
                                    do_action("charity_causes_donation_button");
                                    ?>
                                </div>

                            </div>
                            <?php
                        endwhile;
                    endif;
                    wp_reset_query();
                    ?>


                    <?php
                    $args = array(
                        'post_type' => 'charity-causes',
                        "post_status" => "publish",
                        "posts_per_page" => 2
                    );
                    global $more;
                    query_posts($args);
                    if (have_posts()) :
                        ?>				
                        <div class="col-xs-12 col-md-6 cause-summary">

                            <div class="row">
                                <div class="col-xs-12">
                                    <h2><?php echo vp_option('vpt_option.ch_htwo_allcauses_title'); ?></h2>
                                </div>
                                <?php while (have_posts()) : the_post(); ?>
                                    <div class="col-xs-12 col-sm-6 col-md-6 one-block">
                                        <div class="items zoom">
                                            <?php if (has_post_thumbnail()): ?>
                                                <a
                                                    href="<?php the_permalink(); ?>" class="img-thumb">
                                                    <figure>
                                                        <?php the_post_thumbnail("charity_causes_thumb"); ?>
                                                    </figure>
                                                </a>                                
                                            <?php endif; ?>
                                            <div class="heading-block">

                                                <h3 class="h4"><?php the_title(); ?></h3>
                                            </div>
                                            <?php
                                            do_action("charity_cauese_donation_details_sidebar");
                                            do_action("charity_cauese_donation_details_single");

                                            the_content("");
                                            do_action("charity_causes_donation_button");
                                            ?>								
                                        </div>
                                    </div>
                                <?php endwhile; ?>										
                            </div>
                        </div>

                        <?php
                    endif;
                    wp_reset_query();
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php 
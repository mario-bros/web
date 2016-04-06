<?php
/**
 * Charity -  Home Two News
 *
 * @package     charity
 * @version     v.1.0
 */
?>
<section class="latest-news news-section">
    <div class="container anim-section">
        <div class="row">
            <!-- become volunteer start here -->
            <?php
            $pageid = vp_metabox('charity-sub-page.charity_ask_us');
            query_posts(array("page_id" => $pageid));
            if (have_posts()): the_post();
                $usericon = vp_metabox('usericon.usericon');
                ?>
                <div class="col-xs-12 col-md-5">
                    <div class="volunteer-reward">
                        <div class="reward-apply">
                            <header class="page-header">
                                <strong class="get-involved"><?php echo vp_option('vpt_option.ch_hTwo_get_involved_text'); ?></span> </strong>
                                <span class="svg-shape user-svg-shape"> 
                                    <?php if (!empty($usericon)): ?>
                                        <img src="<?php echo esc_url($usericon); ?>" class="svg" alt="<?php the_title(); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/user.svg" class="svg" alt="<?php the_title(); ?>" />
                                    <?php endif; ?> </span>	
                                <h2><?php the_title(); ?></h2>
                            </header>
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
                <?php
            endif;
            wp_reset_query();
            ?>
            <!-- become volunteer end here -->			
            <?php
            global $more, $charityHomeNews;
            $more = 0;
            $charityHomeNews = "twoSection";
            query_posts(array("post_type" => "post", "post_status" => "publish", "posts_per_page" => 2));
            if (have_posts()) :
                ?>
                <div class="col-xs-12 col-md-7">
                    <div class="row">
                        <header class="col-xs-12 page-header section-header clearfix">
                            <h2><?php echo vp_option('vpt_option.ch_hTwo_latest_news_title'); ?></h2>
                        </header>
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="items zoom col-xs-12 col-md-12">
                                <div class="row charity-latest-news-home-two">
                                    <?php
                                    $format = (get_post_format()) ? get_post_format() : "standard";
                                    get_template_part('content/format/' . $format);
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
</section>
<?php


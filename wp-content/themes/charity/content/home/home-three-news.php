<?php
/**
 * Charity -  Home Three Latest News
 *
 * @package     charity
 * @version     v.1.0
 */
?>
<section class="latest-news news-section news-section3">
    <div class="container anim-section">
        <div class="row">
            <?php
            global $more, $charityHomeNews, $charityHome3;
            $more = 0;
            $charityHomeNews = "twoSection";
            $charityHome3="attribute";
            query_posts(array("post_type" => "post", "post_status" => "publish", "posts_per_page" => 2));
            if (have_posts()) :
                ?>
                <div class="col-xs-12 col-md-7">
                    <div class="row">
                        <header class="page-header section-header col-md-12 clearfix">
                            <h2><?php echo vp_option('vpt_option.ch_hThree_latest_news_title'); ?></h2>
                        </header>
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="items zoom col-xs-12 col-md-12">
                                <div class="row charity-latest-news-home-two charity-latest-news-home-three"> 
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

            $pageid = vp_metabox('charity-sub-page.charity_ask_us');
            query_posts(array("page_id" => $pageid));
            if (have_posts()) : the_post();
                $url = wp_get_attachment_image_src(get_post_thumbnail_id($pageid), array(360, 447));
                $usericon = vp_metabox('usericon.usericon');
                ?>		
                <div class="col-xs-12 col-md-5">

                    <div class="volunteer-reward">
                        <?php if (!empty($url[0])): ?><img src="<?php echo esc_url($url[0]); ?>"
                                 alt="<?php the_title(); ?>" class="get-involved-pic"><?php endif; ?>
                        <div class="reward-apply">
                            <header class="page-header">
                                <span class="svg-shape user-svg-shape"> 
                                	<?php 
                                	if(!empty($usericon)): ?>
                                	<img src="<?php echo esc_url($usericon); ?>" class="svg" alt="Get Involved">
                                	<?php else: ?>
                                	<img src="<?php echo get_stylesheet_directory_uri();?>/assets/svg/user.svg" class="svg">
                                	<?php endif; ?>
                                	</span> 
                                <strong class="get-involved"><?php echo vp_option('vpt_option.ch_hThree_get_involved_text'); ?></strong>
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
        </div>
    </div>
</section>
<?php 
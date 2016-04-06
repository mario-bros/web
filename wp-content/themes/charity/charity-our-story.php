<?php
/**
 * Template Name: Charity Our Story
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<div class="content-wrapper" id="page-info">
    <div class="container">
        <!-- Our Story Section Start Here -->
        <section class="our-story row">
            <div class="col-xs-12">
                <header class="story-heading section-header">
                    <h2><?php echo vp_option('vpt_option.ch_our_story_title'); ?></h2>
                </header>
                <?php get_template_part("content/our", "story"); ?>
            </div>
        </section>
        <!-- Our Story Section Start Here -->
        <!-- What We Do Section Start Here-->
        <?php get_template_part("content/our", "works"); ?>
        <!-- What We Do Section Start Here-->
    </div>

    <?php
    $pageid = vp_metabox('charity-sub-page.charity_ask_us');
    $storyQuery=new WP_Query(array("page_id" => $pageid));
    if ($storyQuery->have_posts()): $storyQuery->the_post();
       $url = wp_get_attachment_image_src(get_post_thumbnail_id($pageid), array(1143, 479));
        ?>
        <!-- Helping Section Start here-->
        <section class="helping-child parallax" style="background-image: url('<?php echo esc_url($url[0]); ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7 col-md-5">
                        <h2 class="h1"> <?php the_title(); ?> </h2>
                        <?php the_content(); ?>        
                    </div>
                </div>
            </div>
        </section>
        <!-- Helping Section Start here-->
    <?php  endif;  wp_reset_postdata(); ?>
    <!-- Our Team Section Start Here -->
<?php get_template_part("content/our", "team"); ?>
    <!-- Our Team Section Start Here -->
</div>
<?php
get_footer();
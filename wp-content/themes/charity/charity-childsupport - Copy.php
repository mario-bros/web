<?php
/**
 * Template Name: Charity ChildSupport
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
//breadcrumb
    
    $current = $post->ID;
    $parent = $post->post_parent;
    $grandparent_get = get_post($parent);
    $grandparent = $grandparent_get->post_parent;
    if ($root_parent = get_the_title($grandparent) !== $root_parent = get_the_title($current)) {$titel = get_the_title($grandparent); }else {$titel = get_the_title($parent); }


do_action("charity_breadcrumb", array("title" => $titel));
?>
<div class="content-wrapper" id="page-info">
    <div class="container childsupportcontainer">
        <!-- Our Story Section Start Here -->
        <section class="our-story row">
            <div class="col-xs-12">
                <header class="story-heading section-header">
                    <h2><?php echo get_the_title(); ?></h2>
                </header>
                
            </div>
        </section>
        <!-- Our Story Section Start Here -->
        <!-- What We Do Section Start Here-->
        <?php get_template_part("content/child", "support"); ?>
        <!-- What We Do Section Start Here-->
    </div>

    <?php
    $pageid = vp_metabox('charity-sub-page.charity_ask_us');
    $storyQuery=new WP_Query(array("page_id" => $pageid));
    if ($storyQuery->have_posts()): $storyQuery->the_post();
       $url = wp_get_attachment_image_src(get_post_thumbnail_id($pageid), array(1143, 479));
        ?>
  
    <?php  endif;  wp_reset_postdata(); ?>

</div>
<?php
get_footer();
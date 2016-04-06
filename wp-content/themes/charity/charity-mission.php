<?php
/**
 * Template Name: Charity Mission
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
get_the_content();
?>


<div class="mission-page" id="page-info">
    <!-- Services Section Start Here-->
    <?php get_template_part("content/mission", "services"); ?>
    <!-- Services Section End Here-->
    <?php
    $pageid = vp_metabox('charity-sub-page.charity_ask_us');
     $missionQuery=new WP_Query(array("page_id" => $pageid));
    if ($missionQuery->have_posts()):
        $missionQuery->the_post();
        $url = wp_get_attachment_image_src(get_post_thumbnail_id($pageid), array(1143, 479));
        ?>
        <!-- Save Lives Section Start Here-->
        <section class="save-lives text-center parallax" style="background-image: url('<?php echo esc_url($url[0]); ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <header class="page-header">
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                        </header>
                    </div>
                </div>
            </div>
        </section>
        <!-- Save Lives Section Start Here-->
    <?php
    endif;
    wp_reset_postdata();
    ?>
    <!-- We Help Section Start Here -->
<?php get_template_part("content/mission", "help"); ?>
    <!-- We Help Section Start Here -->

</div>
<?php
get_footer();
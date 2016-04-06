<?php
/*
 * Charity - taxonomy causes category 
 *
 * @package     charity
 * @version     v.1.0
 * */

get_header();

$titel = "Projects";
do_action("charity_breadcrumb", array("title" => $titel));

//echo single_term_title("Projects ", false);

?>
<!-- cause page Start Here-->
<div class="content-wrapper cause-page-section" id="page-info">
    <div class="container">
        <section class="our-story row ">
            <div class="col-xs-12">
                <?php get_template_part('content/causes', 'header'); ?>
            </div>
        </section>

        <!-- Our Causes Section-->
        <section class="our-causes our-causes-section our-causes3">
            <!-- div class="anim-section" -->
            <div class="row">													
                <!-- Article Section Srart Here -->
                <div class="article-list progressbar">						
                    <?php
                    //$args = array('post_type' => 'charity-causes',);
                    global $more;
                    $more = 0;
                    // query_posts($args);
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            ?>
                              <div <?php post_class("cols-xs-12 col-sm-4 "); ?> >
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
                                        <?php do_action("charity_causes_donation_button"); ?> 
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        //do_action("charity_causes_donation_form");
                        chy_pagenavi();
                    else :
                        get_template_part('content/none');
                    endif;
                    ?>
                </div>
                <!-- Article Section Srart Here -->

            </div>
            <!--/div-->
        </section>
        <!-- Our Causes Section End-->

    </div>

</div>
<!-- cause page Start End-->
<?php
get_footer();

<?php
/**
 * The Template for displaying portfolio detail page
 * 
 * @package     charity
 * @version     v.1.0
 */
get_header();
// breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<div class="content-wrapper" id="page-info">

    <!-- portfolio detail sections -->
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $metaType = vp_metabox('cahrity-meta-type-settings.choose-meta-type');
                        ?>

                        <header class="page-header section-header text-left">
                            <h2><?php the_title(); ?></h2>
                        </header>
                        <div class="portfolio-detail-description">
                            <?php
                            if ($metaType == "video"):
                                do_action("charity_portfolio_video");
                            else:
                                do_action("charity_portfolio_image");
                            endif;
                            ?>          
                            <div class="row portfolio-details">
                                <div class="col-xs-12">
                                    <div class="detail-description">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                else :
                    get_template_part('content/none');
                endif;
                ?>
                <div class="portfolio-detail-description">
                    <div class="row portfolio-details">
                        <div class="col-xs-12">
                            <?php get_template_part("content/related", "portfolio"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--  sections end -->
        </div>
    </div>
</div>

<!-- site content ends -->
<?php
get_footer();

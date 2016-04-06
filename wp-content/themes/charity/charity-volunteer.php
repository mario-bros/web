<?php
/**
 * Template Name: Charity Volunteer
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?><div class="container mission-page" id="page-info">
    <div class="row">
        <div class="col-xs-12">
            <!-- Help Us Section Start Here-->
            <section class="help-us">
                <header class="page-header section-header clearfix">
                    <h2><?php echo vp_option('vpt_option.ch_volunteer_title'); ?></h2>
                </header>
                <?php if (has_post_thumbnail()): ?>
                    <figure><?php the_post_thumbnail(); ?></figure>
                <?php endif; ?>
            </section>
            <!-- Help Us Section End Here-->
            <!-- Process Step Section Start Here-->
            <section class="process-section anim-section text-center">
                <header class="page-header section-header clearfix">
                    <h2><?php echo vp_option('vpt_option.ch_volunteer_process_title'); ?></h2>
                </header>
                <div class="row processes">
                    <?php
                    if (have_posts()) : while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                    endif;
                    ?>						
                </div>
            </section>
            <!-- Process Step Section End Here-->
        </div>
    </div>
</div>
<?php
get_footer();

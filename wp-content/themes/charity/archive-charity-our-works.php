<?php
/**
 * Charity - our works
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
        <!-- What We Do Section Start Here-->

        <?php  if (have_posts()) :  ?>
            <section class="our-works row anim-section">
                <div class="col-xs-12">
                    <header class="work-block-heading section-header">
                        <h2><?php echo vp_option('vpt_option.ch_work_section'); ?></h2>
                    </header>
                    <div class="row">
                        <?php while (have_posts()) : the_post(); ?>
                            <div <?php post_class("col-xs-12 col-sm-6 col-md-3"); ?>>
                                <div class="thumbnail zoom">
                                    <h3><?php the_title(); ?></h3>
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="img-thumb">
                                            <figure>
                                                <?php the_post_thumbnail('charity_our_work'); ?>
                                            </figure>
                                        </a>
                                    <?php endif; ?>
                                    <?php the_content('Read More'); ?>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
        ?>
        <!-- What We Do Section Start Here-->
    </div>
</div>
<?php
get_footer();
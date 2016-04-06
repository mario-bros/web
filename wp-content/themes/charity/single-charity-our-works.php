<?php
/**
 * The template for displaying all single work post.
 *
 * @package Charity
 */
get_header();

//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<div class="content-wrapper container" id="page-info">
    <div class="row">
        <div class="col-xs-12">
            <?php
            while (have_posts()) : the_post();
                ?>
                <article <?php post_class("blog"); ?>>
                    <div class="row">
                        <?php if (has_post_thumbnail()): ?>
                            <figure><?php the_post_thumbnail('charity_causes_full'); ?></figure>
                        <?php endif; ?>
                        <div class="col-xs-12 col-sm-10 col-sm-offset-1 caption">
                            <h2 class="h1"><a href="<?php the_permalink(); ?> "><?php the_title() ?></a></h2>
                            <?php the_content("READ MORE"); ?>
                        </div>
                    </div>
                </article>

            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php
get_footer();

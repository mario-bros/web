<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Charity
 * @version 1.0
 */
get_header();
?>
<div class="content-wrapper container" id="page-info">
    <div class="row">
        <div class="col-sm-8 col-md-9">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    $format = (get_post_format()) ? get_post_format() : "standard";
                    get_template_part('content/format/' . $format);
                endwhile;
                chy_pagenavi();
            else :
                get_template_part('content/none');
            endif;
            ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php
get_footer();


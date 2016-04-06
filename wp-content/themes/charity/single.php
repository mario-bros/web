<?php
/**
 * The template for displaying all single posts.
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
                $format = (get_post_format()) ? get_post_format() : "standard";
                get_template_part('content/format/' . $format);
                ?>
                <div class="row">
                    <div class="col-xs-12">

                    </div>
                </div>
            <?php endwhile; // end of the loop.  ?>
        </div>
    </div>
</div>
<?php
get_footer();

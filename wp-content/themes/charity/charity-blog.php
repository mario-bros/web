<?php
/**
 * Template Name: Charity Blog
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();

//breadcrumb
do_action("charity_breadcrumb", array("title" => get_the_title()));
?>
<div class="content-wrapper container" id="page-info">
    <div class="row">
        <div class="col-xs-12">
            <?php
            query_posts(array("post_type" => "post", "post_status" => "publish"));
            if (have_posts()) :
                while (have_posts()) : the_post();
                    $format = (get_post_format()) ? get_post_format() : "standard";
					//echo $format . "<Br/>";
                    get_template_part('content/format/' . $format);
                endwhile;
                chy_pagenavi();
            else :
                get_template_part('content/none');
            endif;
            wp_reset_query();
            ?>
        </div>
    </div>
</div>
<?php
get_footer();

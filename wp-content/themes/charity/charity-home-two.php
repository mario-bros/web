<?php
/**
 * Template Name: Charity Home Two
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
?>
<!-- banner slider Start Here -->
<?php get_template_part("content/home/home", "two-slider"); ?>
<!-- banner slider End Here -->
<!-- Our Causes Section Start Here-->
<?php get_template_part("content/home/home", "two-causes"); ?>
<!-- Our Causes Section End Here-->
<!-- How To Help Section Start Here -->
<?php get_template_part("content/home/home", "two-help"); ?>
<!-- How To Help Section End Here-->
<!-- Latest News Section Start Here -->
<?php get_template_part("content/home/home", "two-news"); ?>
<!-- Latest News Section End Here -->
<!--Testimonial Section Start Here -->
<?php get_template_part("content/home/home", "two-testimonial"); ?>
<!--Testimonial Section End Here -->
<?php
get_footer();

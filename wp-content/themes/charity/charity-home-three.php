<?php
/**
 * Template Name: Charity Home Three
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
?>
<!-- banner slider Start Here -->
<?php get_template_part("content/home/home", "three-slider"); ?>
<!-- banner slider End Here -->
<!-- Our Causes Section Start Here-->
<?php get_template_part("content/home/home", "three-causes"); ?>
<!-- Our Causes Section End Here-->
<!-- How To Help Section Start Here -->
<?php get_template_part("content/home/home", "three-help"); ?>
<!-- How To Help Section End Here-->
<!-- Latest News Section Start Here -->
<?php get_template_part("content/home/home", "three-news"); ?>
<!-- Latest News Section End Here -->
<!--Testimonial Section Start Here -->
<?php get_template_part("content/home/home", "three-testimonial"); ?>
<!--Testimonial Section End Here -->
<?php
get_footer();

<?php
/**
 * Template Name: Charity Home One
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
 ?>
<!-- banner slider Start Here -->
 <?php get_template_part("content/home/home", "one-slider"); ?>
<!-- banner slider End Here -->

<?php get_template_part("content/child", "support"); ?>

<!-- Our Causes Section Start Here-->
  <?php get_template_part("content/home/home", "three-causes"); ?>
<!-- Our Causes Section End Here-->
<!-- How To Help Section Start Here -->
  <?php get_template_part("content/home/home", "one-help"); ?>
<!-- How To Help Section End Here-->
<!-- Become Volunteer Section Start Here -->
  <?php get_template_part("content/home/home", "one-volunteer"); ?>
<!-- Become Volunteer Section End Here -->
<!-- Latest News Section Start Here -->
<?php get_template_part("content/home/home", "one-news"); ?>
<!-- Latest News Section End Here -->
<!--Testimonial Section Start Here -->	
<?php get_template_part("content/home/home", "three-testimonial"); ?>
<!--Testimonial Section End Here -->
<?php get_footer();

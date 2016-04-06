<?php
/*
 * Template Name: Charity My Sponsor Area
 *
 * @package     charity
 * @version     v.1.0
 */

?>
<?php
/**
 * Search page 
 *
 * @package     charity
 * @version     v.1.0
 */
get_header(); 
?>
<article <?php post_class("blog"); ?>>
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 caption">
           <h2><?php the_title(); ?></h2>
           <?php the_content(); ?>
        </div>
    </div>
</article>
<?php get_footer();
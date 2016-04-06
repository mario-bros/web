<?php
/**
 * Search page 
 *
 * @package     charity
 * @version     v.1.0
 */
?>
<?php $postid = get_the_ID();  // 1454 ?> 
<article <?php post_class("blog"); ?>>
<?php if ($postid == "1454" || $postid == "1753" || $postid == "1766" || $postid == "1789") { ?>
<?php // sponsor area ?>
    <div class="row">
	
        <div class="col-xs-12 caption">
           <?php if ($postid == "1753") { ?>
		   <?php get_template_part("content/sponsor/sponsor", "mysponsor"); ?>		   
		   <?php } elseif ($postid == "1766") { ?>
		   <?php get_template_part("content/sponsor/sponsor", "edit"); ?>
		   <?php } elseif ($postid == "1789") { ?>
		   <?php get_template_part("content/sponsor/sponsor", "transactions"); ?>		   	
		   <?php } else { ?>
           <?php the_content(); ?>
		   <?php } ?>
        </div>
    </div>


<?php } else { ?>
    <div class="row">
	
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 caption">
           <h2><?php the_title(); ?></h2>
           <?php the_content(); ?>
        </div>
    </div>
<?php } ?>	
</article>
<?php 
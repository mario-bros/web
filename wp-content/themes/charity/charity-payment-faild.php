<?php
/**
 * Template Name: Charity payment Fail
 *
 * @package     charity
 * @version     v.1.0
 */
	get_header(); 
	?>
	<div class="container sorry-page">
	<div class="row">
	<div class="col-xs-12">	
	<?php	
	if (have_posts()) : while (have_posts()) : the_post();
the_title('<h1>','</h1>');
if (has_post_thumbnail()):
the_post_thumbnail();
endif;

the_content();

endwhile;
endif;
?>
</div>
</div>
</div>
<?php
get_footer();


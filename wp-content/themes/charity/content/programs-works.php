<?php
/**
 * Charity - Our works in programs story template 
 * 
 * @package     charity
 * @version     v.1.0
 */
global $more;
$args = array ('post_type' => 'page', "post_status"=> "publish", "post_parent" => 585, "posts_per_page" => 4);
query_posts ( $args );
if (have_posts ()) :

?>
<section class="our-works row anim-section">
	<div class="col-xs-12">
		<header class="work-block-heading section-header">
			<h2  style="margin-top: -40px !important;">See what we do! <strong>Watch our video</strong></h2>
		</header>
		<div class="row youtubevideo">
		
		<center><iframe src="https://www.youtube.com/embed/JaFmATgwIew" class="youtubemovie" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe></center>
		
	<!--	<?php while ( have_posts () ) : the_post ();?>
		<div <?php post_class("col-xs-12 col-sm-6 col-md-3"); ?>>
						<div class="thumbnail zoom">
							<h3><?php the_title();?></h3>
							<?php if(has_post_thumbnail()): ?>
							<a href="<?php the_permalink();?>" class="img-thumb">
								<figure>
						   <?php the_post_thumbnail('charity_our_work'); ?>
						        </figure>
						    </a>
						 <?php endif; ?>
							<?php the_content('Read More');?>
						</div>
					</div>
		<?php
		endwhile;
		?>
		--></div>
	</div>
</section>
<?php 
endif;
wp_reset_query();

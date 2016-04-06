<?php
/**
 * Charity -  Mission Help
 *
 * @package     charity
 * @version     v.1.0
 */
$args = array ('post_type' => 'charity_help','post_status' => 'publish','posts_per_page' => 4);
query_posts ( $args );
if (have_posts ()) :
 ?><section class="we-help text-center">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<header class="page-header">
					<h2><?php echo vp_option('vpt_option.ch_mission_help_title'); ?></h2>
				</header>
				<div class="row">
				<?php while ( have_posts () ) : the_post ();?>
					<div class="cols-xs-12 col-sm-6 anim-section">
						<div class="thumbnail zoom">
						<?php if(has_post_thumbnail()): ?>
						<a href="<?php the_permalink(); ?>" class="img-thumb"><?php the_post_thumbnail("charity_causes_thumb"); ?></a>
						<?php endif; ?>
												
							<div class="caption">
								<h3 class="h3"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
								<?php the_content();?>
							</div>
						</div>
					</div>
					<?php endwhile;	?>					
				</div>
			</div>
		</div>
	</div>
</section>
<?php 
endif;
wp_reset_query();
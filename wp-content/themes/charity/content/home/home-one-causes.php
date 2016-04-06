<?php
/**
 * Charity -  Home One Causes Listing
 *
 * @package     charity
 * @version     v.1.0
 */
  $args = array('post_type' => 'charity-causes', "post_status" => "publish");
global $more;
$more=0;
query_posts($args);
if (have_posts()) :
  ?>
<section class="our-causes">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="page-header section-header clearfix">
					<h2><?php echo vp_option('vpt_option.ch_hOne_causes_title');?></h2>
				</div>

				<div class="article-list flexslider article-slider progressbar">
					<ul class="slides">
					<?php  while (have_posts()) : the_post();?>
					<li>		
					<div class="items zoom">
						<h3 class="h6"><?php the_title(); ?></h3>
						<?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="img-thumb">
                                            <figure> <?php the_post_thumbnail("charity_causes_thumb"); ?> </figure>
                                        </a>
                                        <?php endif; ?>					
						<?php
						do_action("charity_cauese_donation_details");
						the_content("");
						do_action("charity_causes_donation_button");
						?>
					</div>
					</li>
					
				<?php  endwhile;?>
				</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<?php                 
     endif;
	wp_reset_query();
                 
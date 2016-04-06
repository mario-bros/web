<?php
/**
 * Charity - Our works in programs story template 
 * 
 * @package     charity
 * @version     v.1.0
 */
global $more;
$args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 48);
query_posts ( $args );
if (have_posts ()) :

?>
<section class="our-works row anim-section">
	<div class="col-xs-12">
		<header class="work-block-heading section-header">
			<h2  style="margin-top: -40px !important;">Our children are in need of support, please sponsor a child below</h2>
		</header>
		<div class="row">
		
		<?php while ( have_posts () ) : the_post ();?>
		<div <?php post_class("col-xs-12 col-sm-6 col-md-3"); ?>>
						<div class="thumbnail zoom">
							<h3 style="margin-bottom:5px;"><?php the_title();?></h3>
							<?php $dob = get_post_meta( get_the_ID(), '_give_date_of_birth', true ); ?>
							<?php $gender = get_post_meta( get_the_ID(), '_give_gender', true ); ?>
							<?php $intro = get_post_meta( get_the_ID(), '_give_intro', true ); ?>
							<?php $name = get_post_meta( get_the_ID(), '_give_childname', true ); ?>							
	
							
							<ul>
							<li>Gender: <span><strong><?php echo $gender; ?></strong></span></li>
							<li>Date of Birth: <span><strong><?php echo $dob; ?></strong></span></li>							
							</ul>
							<?php if(has_post_thumbnail()): ?>
							<a href="<?php the_permalink();?>" class="img-thumb">
								<figure>
						   <?php the_post_thumbnail('charity_our_work'); ?>
						        </figure>
						    </a>
							
						 <?php endif; ?>
						 <p><?php echo $intro; ?></p>					
						 <a class="more-link btn btn-default" role="button" href="<?php the_permalink();?>">Sponsor now</a>
							<?php the_content('Read More');?>
						</div>
					</div>
		<?php
		endwhile;
		?>
		</div>
	</div>
</section>
<?php 
endif;
wp_reset_query();

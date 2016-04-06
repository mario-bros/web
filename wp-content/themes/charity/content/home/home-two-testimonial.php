<?php 
/**
 * Charity -  Home Two Testimonial
 *
 * @package     charity
 * @version     v.1.0
 */
$args = array ('post_type' => 'testimonial','post_status' => 'publish', 'posts_per_page' => 3);
						query_posts ( $args );
						if (have_posts ()) : ?>
<section class="donation-holder">

	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h2><?php echo vp_option('vpt_option.ch_hTwo_testimonial_title'); ?></h2>
				<div class="row">
				<?php while (have_posts()) : the_post(); 
	$companyUrlLink = vp_metabox('testimonial.urllink');
	?>	
				<div class="col-xs-12 col-sm-4 col-md-4 quote-block equal-box">
					<blockquote class="equal-box">
							<i class="fa fa-quote-left quote-mark"></i>
							<?php the_content(); ?>
						<footer>
							<?php the_title(); ?>
							<?php if (!empty($companyUrlLink)): ?><cite title="<?php echo esc_html($companyUrlLink); ?>"><?php echo esc_html($companyUrlLink); ?></cite><?php endif;?>
						</footer>
					</blockquote>
				</div>
			<?php endwhile;?>	


			</div>
			</div>
		</div>
	</div>
</section>
<?php endif; 
wp_reset_query();
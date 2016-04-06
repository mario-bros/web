<?php
/**
 * Charity - causes single sidebar
 * 
 * @package     charity
 * @version     v.1.0
 */

if (have_posts()) :
while (have_posts()) : the_post();
$shortCodeMeta = vp_metabox('causes-short-code.causesshortcode');
?>
<div class="col-xs-12 col-sm-9 left-block">
	<div class="article-list-large causes-description progressbar">
		<div class="anim-section">											
             <?php if (has_post_thumbnail()): ?>
                     <figure><?php the_post_thumbnail("full"); ?></figure>
             <?php endif; ?>
			<?php do_action("charity_cauese_donation_details_single_page"); ?>

			<div class="heading-sec text-left">
				<h3 class="h4"><?php the_title(); ?></h3>
				<?php do_action("charity_causes_details_attribute"); ?>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<?php do_action("charity_cauese_donation_details_sidebar"); ?>
								<?php do_action("charity_causes_donation_button", "pull-right"); ?>
				</div>
			</div>

			<div class="detail-description">
          <?php the_content();?>
		<?php do_action("charity_causes_donation_button"); ?>
			</div>						
			<!--step donation-->
			<div class="step-donation sec-step-med causes-details-step anim-section">
				<header class="page-header section-header"><h2><?php echo vp_option('vpt_option.ch_donation_steps_section_title'); ?></h2>
				</header>
				<?php if(!empty($shortCodeMeta)): ?>
			<div class="col-xs-12 text-center">
				<?php print($shortCodeMeta); ?>
			</div>
			<?php endif;?>
				<!--step donation-->
			</div>
		</div>
	</div>						
		<?php get_template_part("content/related", "causes"); ?>
	</div>
<?php
endwhile;
endif;
get_sidebar ();
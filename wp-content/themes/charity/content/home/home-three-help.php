<?php
/**
 * Charity -  Home Three help section
 *
 * @package     charity
 * @version     v.1.0
 */

if ( have_posts() ) : while ( have_posts() ) : the_post();
$help = vp_metabox('helpicon.helpicon_group');
$helpVideoImage = vp_metabox('helpicon.help-video-image');
$helpVideo = vp_metabox('helpicon.help-video');
?>
<section class="help-people help-people-three">
	<div class="container">
		<div class="row">
				
			<div class="col-md-8">

				<h2><?php echo vp_option('vpt_option.ch_hThree_help_one_title'); ?><span><?php echo vp_option('vpt_option.ch_hThree_help_one_sub_title'); ?></span></h2>
				<div class="row">
				<?php the_content(); ?>				
				</div>
 				
			<?php if (!empty($helpVideoImage)): ?>	
				<div class="video-section">
					<img src="<?php echo esc_url($helpVideoImage);?>" <?php if (!empty($helpVideo)):?>data-video="<?php echo esc_html($helpVideo);?>" <?php endif;?>>
					<div class="control">
						<h2><?php echo vp_option('vpt_option.ch_hThree_help_video_text'); ?></h2>
						<a href="#" class="play-btn"> <i class="fa fa-play-circle-o"></i>
						</a>
					</div>
				</div>
			<?php endif; ?>
			
			</div>
			<div class="col-md-4">
				<h2><?php echo vp_option('vpt_option.ch_hThree_help_second_title'); ?> <span><?php echo vp_option('vpt_option.ch_hThree_help_second_sub_title'); ?> </span></h2>
				<?php if(!empty($help[0])): ?>
					<?php foreach ($help as $helpkey => $helpval): ?>
				<div class="help-block">
					<h3>
						<a href="javascript:;"><?php if (!empty($helpval['helpicon'])): ?><img src="<?php echo esc_attr($helpval['helpicon']);?>"><?php endif;?> <?php echo esc_html($helpval['helptitle']);?></a>
					</h3>
					<p><?php echo esc_html($helpval['helpcontent']);?></p>
				</div>
				<?php endforeach; endif;?>													
				
			</div>
		</div>
	</div>
</section>
<?php 
endwhile; 
endif;
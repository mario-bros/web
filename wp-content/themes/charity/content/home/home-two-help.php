<?php 
/**
 * Charity -  Home Two Help Section
 *
 * @package     charity
 * @version     v.1.0
 */

if ( have_posts() ) : while ( have_posts() ) : the_post(); 
        $help = vp_metabox('helpicon.helpicon_group');
        $helpVideoImage = vp_metabox('helpicon.help-video-image');
        $helpVideo = vp_metabox('helpicon.help-video');
 ?>
<section class="how-to-help help-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 ">
				<header class="page-header section-header">
					<h2><?php echo vp_option('vpt_option.ch_hTwo_how_help_title'); ?></h2>
				</header>
				<div class="row help-list">
					<div class="col-xs-12 col-sm-12 col-lg-12">
						<div class="row">
						
					<?php if(!empty($help[0])): ?>
					<?php foreach ($help as $helpkey => $helpval): ?>
							<div class="media col-xs-12 col-md-4">
								<div class="media-content equal-block">
									<span class="svg-shape megaphone"> <?php if (!empty($helpval['helpicon'])): ?><img class="svg" alt="<?php echo esc_attr($helpval['helptitle']);?>" src="<?php echo esc_attr($helpval['helpicon']);?>"><?php endif;?> </span>
									<div class="media-body less-width">
										<h3 class="media-heading"><a href="javascript:;"><?php echo esc_html($helpval['helptitle']);?></a> </h3>
										<p><?php echo esc_html($helpval['helpcontent']);?></p>
									</div>
								</div>
							</div>
						<?php endforeach; endif;?>								
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Become Volunteer Section Start Here -->
<?php if (!empty($helpVideoImage)): ?>
<div class="video-section">
					<img  src="<?php echo esc_url($helpVideoImage);  ?>" <?php if (!empty($helpVideo)):?>data-video="<?php echo esc_html($helpVideo);?>" alt="Watch our media video" <?php endif;?> > 
					<div class="control">
						<h2><?php echo vp_option('vpt_option.ch_hTwo_help_video_text'); ?></h2>
						<a href="javascript:;" class="play-btn"> <i class="fa fa-play-circle-o"></i> </a>
					</div>
				</div>
				<?php endif; ?>
<!-- Become Volunteer Section End Here -->				
<?php endwhile; 
endif; 

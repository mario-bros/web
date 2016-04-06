<?php 
/**
 * Charity -  Home One Help Section
 *
 * @package     charity
 * @version     v.1.0
 */
  if ( have_posts() ) : while ( have_posts() ) : the_post(); 
     $help = vp_metabox('helpicon.helpicon_group');
     $helpVideoImage = vp_metabox('helpicon.help-video-image');
     $helpVideo = vp_metabox('helpicon.help-video');
?>
<section class="how-to-help header-one-help">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 ">
				<header class="page-header section-header">
					<h2><?php echo vp_option('vpt_option.ch_hOne_how_help_title');?></h2>
				</header>
				<div class="row help-list">
					<div class="col-xs-12 col-sm-6 col-lg-5">
					<?php if(!empty($help[0])): ?>
					<?php $count = 0; ?>
					<?php foreach ($help as $helpkey => $helpval): ?>
					<?php $count++; ?>
						<article class="media">
							<?php if ($count == 1) { ?>
							<a class="pull-left warning-icon-box" href="javascript:;">
							<?php } ?>
							<?php if ($count == 2) { ?>
							<a class="pull-left warning-icon-box" href="/get-involved/volunteer/">
							<?php } ?>
							<?php if ($count == 3) { ?>
							<a class="pull-left warning-icon-box charity-global-causes-301 charity-donation-button" data-target=".donate-form" data-id="301" data-toggle="modal" href="javascript:;">
							<?php } ?>														
							<?php if (!empty($helpval['helpicon'])): ?><img src="<?php echo esc_attr($helpval['helpicon']);?>" alt="<?php echo esc_attr($helpval['helptitle']);?>"><?php endif;?> </a>
							<div class="media-body less-width">
								<h3 class="media-heading"><?php echo esc_html($helpval['helptitle']);?></h3>
								<p><?php echo esc_html($helpval['helpcontent']);?></p>
							</div>
						</article>
						<?php endforeach; endif;?>						
					</div>
					
					<div class="col-xs-12 col-sm-6 col-lg-6 col-lg-offset-1">
						<div class="embed-responsive embed-responsive-16by9 video-section">
							<?php echo $helpVideo; ?>
											
							</div>
					</div>
					
					
				
					
				</div>

			</div>
		</div>
	</div>
</section>
<?php endwhile; 
endif;


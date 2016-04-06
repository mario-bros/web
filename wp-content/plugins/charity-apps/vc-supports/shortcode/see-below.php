<?php 
function charity_vc_see_below( $atts, $content = null ) {
	extract( shortcode_atts( array( 
		'help'  => '',
		), $atts ) );
	ob_start();
	switch ($help) {
	   case "home1" :
	    $id=charity_get_page_template("charity-home-one.php", $by="ID");
	      if(isset($id)){
				$charityQuery=new WP_Query(array("page_id"=> $id));
				charity_vc_see_home1_template($charityQuery);
				wp_reset_postdata($charityQuery);
				
			}
	   break;
	   case "home2" :
	    $id=charity_get_page_template("charity-home-two.php", $by="ID");
	    if(isset($id)){
				$charityQuery=new WP_Query(array("page_id"=> $id));
				charity_vc_see_home2_template($charityQuery);
				wp_reset_postdata($charityQuery);
				
			}
	   
	   break;
	   case "home3" :
	    $id=charity_get_page_template("charity-home-three.php", $by="ID");
	    if(isset($id)){
				$charityQuery=new WP_Query(array("page_id"=> $id));
				charity_vc_see_home3_template($charityQuery);
				wp_reset_postdata($charityQuery);
				
			}
	   break;
	  
	  }
           
	return ob_get_clean(); 
}
//home1
function charity_vc_see_home1_template($seeBelow){
  if ( $seeBelow->have_posts() ) : while ( $seeBelow->have_posts() ) : $seeBelow->the_post(); 
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
					<?php foreach ($help as $helpkey => $helpval): ?>
						<article class="media">
							<a class="pull-left warning-icon-box" href="javascript:;"><?php if (!empty($helpval['helpicon'])): ?><img src="<?php echo esc_attr($helpval['helpicon']);?>" alt="<?php echo esc_attr($helpval['helptitle']);?>"><?php endif;?> </a>
							<div class="media-body less-width">
								<h3 class="media-heading"><?php echo esc_html($helpval['helptitle']);?></h3>
								<p><?php echo esc_html($helpval['helpcontent']);?></p>
							</div>
						</article>
						<?php endforeach; endif;?>						
					</div>
					<?php if (!empty($helpVideoImage)): ?>
					<div class="col-xs-12 col-sm-6 col-lg-6 col-lg-offset-1">
						<div class="embed-responsive embed-responsive-16by9">
							<img  src="<?php echo esc_url($helpVideoImage);  ?>" alt="Click to play" <?php if (!empty($helpVideo)):?>data-video="<?php echo esc_attr($helpVideo);?>" <?php endif;?>/>
							</div>
					</div>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</div>
</section>
<?php endwhile; 
endif;

	
}
//home2
function charity_vc_see_home2_template($seeBelow){
  if ( $seeBelow->have_posts() ) : while ( $seeBelow->have_posts() ) : $seeBelow->the_post(); 
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

<?php
 endwhile; 
endif; 

}
// home3
function charity_vc_see_home3_template($seeBelow) {
  if ( $seeBelow->have_posts() ) : while ( $seeBelow->have_posts() ) : $seeBelow->the_post();
$help = vp_metabox('helpicon.helpicon_group');
$helpVideoImage = vp_metabox('helpicon.help-video-image');
$helpVideo = vp_metabox('helpicon.help-video');
?>
<section class="help-people">
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
}


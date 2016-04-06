<?php
/**
 * Charity - our story template 
 * 
 * @package     charity
 * @version     v.1.0
 */
global $post;
$storyImage = vp_metabox('our_story.our_story_group');
$summaryText = vp_metabox('our_story_text.our_story_text_sammury');
$text = vp_metabox('our_story_text.our_story_text');
?>
<div class="row">
<?php if(!empty($storyImage[0]['our_story_image'])):?>
					<div class="col-xs-12 col-sm-5">
						<section class="slider-wrap flex-slide flexslider">
							<ul class="slides">
							<?php foreach($storyImage as $image):?>
								<li><img src="<?php echo esc_url($image['our_story_image']);?>" alt="<?php the_title();?>"></li>
								<?php endforeach;?>
							</ul>
						</section>
					</div>
	<?php endif; ?>				
					<div class="col-xs-12 col-sm-7">
					<?php if(!empty($summaryText)):?>
						<strong class="article-sammury"><?php echo esc_html($summaryText);?></strong>
							<?php endif;
							if(!empty($text)):?>
						<p><?php echo esc_html($text);?></p>
						<?php endif;?>
					</div>
				</div>
<?php 


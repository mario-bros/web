<?php
/**
 * Charity portfolio hooks
 *
 * @package  charity
 * @version  v.1.0
 */
class CharityPortfolioHooks {

    public function __construct() {
        $this->action();
    }

    public function action() {
		
    	add_action("charity_portfolio_types_breadcrumb", array(&$this, "portfolioTypes"));
        add_action("charity_portfolio_image", array(&$this, 'image'));
        add_action("charity_portfolio_video", array(&$this, 'video'));
    }
    function portfolioTypes(){
    	$portfolioTypes = get_terms('portfolio-type', 'orderby=id&order=ASC');
    	if(count($portfolioTypes)> 0):
    	//current term
    	$currentTermId =(is_tax('portfolio-type')) ? get_queried_object()->term_id: "";
    
    	?><ol class="breadcrumb">
                  <?php foreach ($portfolioTypes as $types): 
                  	$activeClass=($currentTermId == $types->term_id) ? " active " :"";
                  ?>
                     <li class="gallery-type term-<?php echo esc_attr($types->slug); echo esc_attr($activeClass); ?> term-id-<?php echo esc_attr($types->term_id); ?>">
                     <a href="<?php echo esc_url(get_term_link($types)); ?>"><?php echo esc_html($types->name); ?></a>
                     </li>
                 <?php endforeach;  ?>
                </ol><?php 
        	endif;
        }
        
    function image() {
        global $post;
		
		$portfolioImage = vp_metabox('cahrity-meta-type-settings.charity-meta-images');								
		if (count($portfolioImage) == 1):
		foreach($portfolioImage as $imgkey => $imgval):
		?>
		<a class="fancybox-effects-a img-thumb" href="<?php the_permalink();?>">
		<?php if ( is_single() ){
			$img=$imgval['upload-gallery-or-image'];
       }
		else{
			$img=charity_resize($imgval['upload-gallery-or-image'],600,400);
			
		} ?>
		
		<img src="<?php echo esc_url($img);?>" alt="<?php the_title();?>">
		</a>
		<?php endforeach;
		      else:
		   ?>
		<section class="flex-slide flexslider">
		<ul class="slides">
		<?php if(!empty($portfolioImage)):
		foreach($portfolioImage as $imgkey => $imgval): ?>
		<?php if ( is_single() ){
			$img=$imgval['upload-gallery-or-image'];
       }
		else{
			$img=charity_resize($imgval['upload-gallery-or-image'],600,400);
			
		} ?>
		<li>
		<a class="img-thumb" href="javascript:;"><img src="<?php echo esc_url($img);?>" alt="<?php the_title();?>"></a>
		</li>													
		<?php endforeach; endif;?>
		</ul>
		</section>
		<?php endif; 
        
	}
	

	function video(){
		 global $post;
		  $videoMeta = vp_metabox('cahrity-meta-type-settings.charity-meta-video');
		  if(!empty($videoMeta[0])):
			  $src=(!empty($videoMeta[0]['upload-video-image'])) ? $videoMeta[0]['upload-video-image'] : false;
			  
			  if($src):
				  $videoURL="";
				  if(!empty($videoMeta[0]['txt-youtube-video-url'])){
				  	$videoURL=$videoMeta[0]['txt-youtube-video-url'];	
				  }
				  elseif(!empty($videoMeta[0]['txt-vimeo-video-url'])){
				  	$videoURL=$videoMeta[0]['txt-vimeo-video-url'];
				  }
				  ?>
					<div class="embed-responsive embed-responsive-16by9">
					<?php if ( is_single() ){
			$src_video_img=$src;
       }
		else{
			$src_video_img=charity_resize($src,600,400);
			
		} ?>
						<img  src="<?php echo esc_url($src_video_img);?>" alt="<?php esc_attr_e("Click to play", "charity"); ?>" data-video='<?php print($videoURL);?>'/>
					</div>
				  <?php
			  endif;		   
		endif;
	}



}

new CharityPortfolioHooks();

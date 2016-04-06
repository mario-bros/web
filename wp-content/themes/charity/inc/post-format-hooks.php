<?php

/**
 * Charity post format hooks
 *
 * @package  charity
 * @version  v.1.0
 */
class CharityPostFormatHooks {

    public function __construct() {
        $this->action();
    }

    public function action() {
        add_action("charity_post_format_audio", array(&$this, 'audio'));
        add_action("charity_post_format_gallery", array(&$this, 'gallery'));
        add_action("charity_post_format_video", array(&$this, 'video'));
        add_action("charity_post_format_quote", array(&$this, 'quote'));
		//add_action("charity_breadcrumb", array(&$this, "layout"));
        add_action("charity_post_format_attribute", array(&$this, 'formatAttribute'));
        add_action("charity_post_latest_format_attribute", array(&$this, 'latestFormatAttribute'));
    }

    function latestFormatAttribute() {
        global $post, $charityHome3;
        ?>

        <?php
    }

    function formatAttribute() {
        global $post;
        ?>
      
        <?php
    }

    /*
     * Audio Format
     * 
     * This is post format tempale support
     * 
     * @global $post
     * @param String
     * @retrun HTMl
     * 
     * @version charity 1.0
     * 
     */

    function audio() {
        global $post;

        $ogg = vp_metabox('audio_meta.audio_ogg');
        $mp3 = vp_metabox('audio_meta.audio_mp3');
        $wav = vp_metabox('audio_meta.audio_wav');
		if(!empty($ogg) || !empty($mp3) || !empty($wav)) {
        ?><audio controls>
            <?php
            //print_r($ogg);
            if (!empty($ogg)):
                ?>
                <source src="<?php echo esc_url($ogg); ?>" type="audio/ogg">
                <?php
            endif;
            if (!empty($mp3)):
                ?>
                <source src="<?php echo esc_url($mp3); ?>" type="audio/mpeg">
                <?php
            endif;
            if (!empty($wav)):
                ?>
                <source src="<?php echo esc_url($wav); ?>" type="audio/wav">
            <?php endif ?>
            <?php esc_html_e("Your browser does not support the audio tag.", "charity"); ?>
        </audio>
        <?php
    }
	}

    /*
     * Gallery Format
     * 
     * This is post format tempale support
     * 
     * @global $post
     * @param String
     * @retrun HTMl
     * 
     * @version charity 1.0
     * 
     */

    function gallery() {
        global $post, $charityHomeNews;
if ($charityHomeNews == "latestNewsSection"){ $width=600; $height=400; }
elseif ($charityHomeNews == "twoSection"){ $width=600; $height=400; }
else{ $width=1140; $height=458; }

			
        $metaValueGallery = vp_metabox('gallery_meta.gallery_group');
        if (!empty($metaValueGallery) && is_array($metaValueGallery)):
            ?>
            <section class="img-slider flex-slide flexslider"> 
                <ul class="slides">
                    <?php
                    // print_r($metaValueGallery);
                    foreach ($metaValueGallery as $gallerykey => $galleryval):
                        if (!empty($galleryval['gallery_image'])):
                            ?> 
                            <li>
                            <?php  ?>	
                            	<img src="<?php echo esc_url(charity_resize($galleryval['gallery_image'], $width, $height)); ?>" alt="<?php echo esc_attr($gallerykey); ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" >
                            </li>        
                            <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </section>
            <?php
        endif;
    }

    /*
     * Video Format
     * 
     * This is post format tempale support
     * 
     * @global $post
     * @param String
     * @retrun HTMl
     * 
     * @version charity 1.0
     * 
     */

    function video() {
        global $post,$charityHomeNews;

        $metaValueVideoImage = vp_metabox('video_meta.video_image');
        $charity_you_tube_url = vp_metabox('video_meta.charity_you_tube_url');
        $charity_viemo_url = vp_metabox('video_meta.charity_viemo_url');
		
		if ($charityHomeNews == "latestNewsSection"){ $width=600; $height=400; }
		elseif ($charityHomeNews == "twoSection"){ $width=600; $height=400; }
		else{ $width=1140; $height=458; }
		$metaValueVideoImage = 1;
        if (!empty($metaValueVideoImage)):
            ?>
            <div class="embed-responsive embed-responsive-16by9">
                <?php
                $videoUrl = "";
                if (!empty($charity_you_tube_url)) {
                    $videoUrl = vp_metabox('video_meta.charity_you_tube_url');
                }
                if (!empty($charity_viemo_url)) {
                    $videoUrl = vp_metabox('video_meta.charity_viemo_url');
                }
                ?>
                <!--<img  src="<?php echo esc_url(charity_resize($metaValueVideoImage, $width, $height)); ?>" alt="<?php the_title(); ?>" data-video='<?php echo esc_html($videoUrl); ?>'/>-->
				<?php echo $videoUrl; ?>
            </div>
            <?php
        endif;
    }

    /*
     * Quote Format
     * 
     * This is post format tempale support
     * 
     * @global $post
     * @param String
     * @retrun HTMl
     * 
     * @version charity 1.0
     * 
     */

    function quote() {
        global $post;

        $metaValueQuotedesc = vp_metabox('quote_meta.quote_text');
        $metaValueQuotefooterText = vp_metabox('quote_meta.quote_footer_text');
        if (!empty($metaValueQuotedesc)) {
            ?>

            <blockquote class="blog-callout">
                <p>
                    "<?php echo $metaValueQuotedesc; ?>"
                </p>
                <footer>
                    <?php echo $metaValueQuotefooterText; ?>
                </footer>
            </blockquote>


            <?php
        }
    }

}

new CharityPostFormatHooks();

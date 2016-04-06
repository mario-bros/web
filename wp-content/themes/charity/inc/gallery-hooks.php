<?php

/**
 * Charity gallery hooks
 *
 * @package  charity
 * @version  v.1.0
 */
class CharityGalleryHooks {

    public function __construct() {
        $this->action();
    }

    public function action() {
        add_action("charity_gallery_types_breadcrumb", array(&$this, "galleryTypes"));
        add_action("charity_gallery_image", array(&$this, 'image'));
        add_action("charity_gallery_video", array(&$this, 'video'));
    }

    function galleryTypes() {
        $galleryTypes = get_terms('gallery-type', 'orderby=id&order=ASC');
        if (count($galleryTypes) > 0):
            //current term
            $currentTermId = (is_tax('gallery-type')) ? get_queried_object()->term_id : "";
            ?><ol class="breadcrumb">
            <?php
            foreach ($galleryTypes as $types):
                $activeClass = ($currentTermId == $types->term_id) ? " active " : "";
                ?>
                    <li class="gallery-type term-<?php echo esc_attr($types->slug);
                echo esc_attr($activeClass);
                ?> term-id-<?php echo esc_attr($types->term_id); ?>">
                        <a href="<?php echo esc_url(get_term_link($types)); ?>"><?php echo esc_html($types->name); ?></a>
                    </li>
            <?php endforeach; ?>
            </ol><?php
        endif;
    }

    function image() {
        global $post;

        $galleryImage = vp_metabox('cahrity-meta-type-settings.charity-meta-images');
        if (count($galleryImage) == 1):
            foreach ($galleryImage as $imgkey => $imgval):
                ?>
                <a class="fancybox-effects-a img-thumb" href="<?php echo esc_url($imgval['upload-gallery-or-image']); ?>">
                    <img src="<?php echo esc_url(charity_resize($imgval['upload-gallery-or-image'], 600, 400)); ?>" alt="<?php the_title(); ?>" width="600" height="400">
                </a>
                <?php
            endforeach;
        else:
            ?>
            <section class="flex-slide flexslider">
                <ul class="slides">
                    <?php
                    if (!empty($galleryImage)):
                        foreach ($galleryImage as $imgkey => $imgval):
                            ?>

                            <li>
                                <a class="img-thumb" href="javascript:;"><img src="<?php echo esc_url(charity_resize($imgval['upload-gallery-or-image'], 600, 400)); ?>" alt="" width="600" height="400"></a>
                            </li>													
                <?php endforeach;
            endif;
            ?>
                </ul>
            </section>
        <?php
        endif;
    }

    function video() {
        global $post;
        $videoMeta = vp_metabox('cahrity-meta-type-settings.charity-meta-video');
        if (!empty($videoMeta[0])):
            $src = (!empty($videoMeta[0]['upload-video-image'])) ? $videoMeta[0]['upload-video-image'] : false;

            if ($src):
                $videoURL = "";
                if (!empty($videoMeta[0]['txt-youtube-video-url'])) {
                    $videoURL = $videoMeta[0]['txt-youtube-video-url'];
                } elseif (!empty($videoMeta[0]['txt-vimeo-video-url'])) {
                    $videoURL = $videoMeta[0]['txt-vimeo-video-url'];
                }
                ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <img  src="<?php echo esc_url(charity_resize($src, 600, 400)); ?>" alt="<?php esc_attr_e("Click to play", "charity"); ?>" data-video='<?php print($videoURL); ?>' width="600" height="400"/>
                </div>
                <?php
            endif;
        endif;
    }

}

new CharityGalleryHooks();

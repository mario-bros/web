<?php
/**
 * Charity - metaboxs
 *
 * @package  charity
 * @version  v.1.0
 */
$meta_vp_dir = CHY_THEME_DIR . '/vendor/vafpress/admin/metabox/';

function cuasesListTable() {
    if (!function_exists('is_plugin_active')) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    if (!is_plugin_active('easypay/easypay.php')) {
        return "";
    }

    if (empty($_REQUEST['post'])) {
        return "";
    }
    require_once 'donation-list.php';
    $charityDonationList = new CharityDonationList();
    $charityDonationList->prepare_items();
    ob_start();
    $charityDonationList->display();
    return ob_get_clean();
}

$gallery_meta = new VP_Metabox($meta_vp_dir . 'gallery-post.php');
$video_meta = new VP_Metabox($meta_vp_dir . 'video-post.php');
$audio_meta = new VP_Metabox($meta_vp_dir . 'audio-post.php');
$quote_meta = new VP_Metabox($meta_vp_dir . 'quote-post.php');
$causes_post_meta = new VP_Metabox($meta_vp_dir . 'causes-donation.php');
$causes_post_meta = new VP_Metabox($meta_vp_dir . 'breadcrumb.php');

$charity_our_story = new VP_Metabox($meta_vp_dir . 'charity-our-story.php');
$charity_our_story_text = new VP_Metabox($meta_vp_dir . 'charity-our-story-text.php');

$charity_programmes_story = new VP_Metabox($meta_vp_dir . 'charity-programmes-story.php');
$charity_programmes_story_text = new VP_Metabox($meta_vp_dir . 'charity-programmes-story-text.php');


$charity_faq = new VP_Metabox($meta_vp_dir . 'general-question.php');
$charity_sub = new VP_Metabox($meta_vp_dir . 'charity-sub-page.php');

$gallery_types_meta = new VP_Metabox($meta_vp_dir . 'charity-gallery.php');
$contact_page_meta = new VP_Metabox($meta_vp_dir . 'contact-page.php');

$testimonial_meta = new VP_Metabox($meta_vp_dir . 'testimonial.php');
$help_home1_meta = new VP_Metabox($meta_vp_dir . 'help-home1.php');
$home_slider_meta = new VP_Metabox($meta_vp_dir . 'home-slider.php');

$product_breadcrumb_meta = new VP_Metabox($meta_vp_dir . 'product-breadcrumb.php');
$product_shop_banner_meta = new VP_Metabox($meta_vp_dir . 'product-shop-banner.php');

$voulunteer_meta = new VP_Metabox($meta_vp_dir . 'voulunteer.php');
$get_involve_meta = new VP_Metabox($meta_vp_dir . 'user-get-involve.php');

$causes_step_meta = new VP_Metabox($meta_vp_dir . 'causes-shortcode-text.php');

$shop_landing_meta = new VP_Metabox($meta_vp_dir . 'shop-landing-data.php');

$charity_testimonial_one_meta = new VP_Metabox($meta_vp_dir . 'charity-testimonial-one.php');


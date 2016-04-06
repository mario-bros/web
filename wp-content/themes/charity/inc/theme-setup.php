<?php
/**
 * Charity theme setup functionality
 *
 * @package  charity.inc
 * @version  v.1.0
 */
if (version_compare($GLOBALS['wp_version'], '3.9', '<=')) {
    require CHY_THEME_DIR . '/inc/back-compat.php';
}

/**
 * chy_setup
 * 
 */
function charitySetup() {
    
    //Make theme available for translation.
    load_theme_textdomain( 'charity', get_template_directory() . '/languages' );
    
    // Add RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');

    add_theme_support("title-tag");

    // Enable support for Post Thumbnails, and declare two sizes.
    add_theme_support('post-thumbnails');
    add_theme_support('post-formats', array('image', 'video', 'quote', 'audio', 'gallery', 'link'));

    add_image_size("charity_mission", 64, 65, true);
    add_image_size("chy_index", 360, 240, true);
    add_image_size("chy_thmb", 360, 240, true);
    add_image_size("chy_details", 850, 626, true);
    add_image_size("charity_causes_thumb", 600, 400, true);
    add_image_size("charity_causes_medium", 849, 421, true);
    add_image_size("charity_causes_full", 1140, 421, true);
    add_image_size("charity-recentpost-thumb", 98, 98, true);
    add_image_size("charity-urgent-causes", 553, 188, true);
    add_image_size("charity_our_work", 263, 175, true);

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
        'header_menu' => __('Header Menu', "charity"),
    ));
}

add_action('after_setup_theme', 'charitySetup');


if (!function_exists('_wp_render_title_tag')) :

    function charity_render_title() {
        ?>
        <title><?php wp_title('|', true, 'right'); ?></title>
        <?php
    }

    add_action('wp_head', 'charity_render_title');
endif;

/**
 * chy_wp_title
 * 
 * @global type $paged
 * @global type $page
 * @param type $title
 * @param type $sep
 * @return string
 */
function charityWPTitle($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name', 'display');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() )) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if (( $paged >= 2 || $page >= 2 ) && !is_404()) {
        $title = "$title $sep " . sprintf(__('Page %s', 'charity'), max($paged, $page));
    }

    return $title;
}

//add_filter('wp_title', 'charityWPTitle', 10, 2);


add_action("charity_nav_menu", "charityNavMenu");

function charityNavMenu() {
    $locations = get_theme_mod('nav_menu_locations');

    $withLocation = array(
        'theme_location' => 'header_menu',
        'menu' => '',
        'container' => 'nav',
        'container_class' => '',
        'container_id' => '',
        'menu_class' => 'nav navbar-nav',
        'menu_id' => '',
        'before' => '',
        'after' => '',
        'items_wrap' => '<ul id="%1$s" class="nav navbar-nav">%3$s</ul>',
        'link_before' => '',
        'link_after' => '',
        'walker' => new Charity_Nav_Walker
    );
    $withOutLoaction = array(
        'theme_location' => '',
        'menu' => '',
        'container' => 'nav',
        'container_class' => '',
        'container_id' => '',
        'menu_class' => 'nav navbar-nav',
        'menu_id' => '',
        'before' => '',
        'after' => '',
        'items_wrap' => '<ul id="%1$s" class="nav navbar-nav">%3$s</ul>',
        'link_before' => '',
        'link_after' => '',
        'walker' => new Charity_Nav_Walker
    );

    if (empty($locations)) {
        wp_nav_menu($withOutLoaction);
    } else {
        wp_nav_menu($withLocation);
    }
}

/**
 * Coming Soon Mode
 */
add_action('admin_bar_menu', 'charity_coming_soon_mode', 1000);

//add_action('init', 'charity_coming_soon_mode', 1000);

function charity_coming_soon_mode() {
    global $wp_admin_bar;
    //$front_page_id=get_option('page_on_front');
    $comingSoonMode = vp_option("vpt_option.coming_soon_mode");
    if (!empty($comingSoonMode)) {
        //$templatePage=get_post_meta($front_page_id , "_wp_page_template", true);
        $front_page_id = vp_option("vpt_option.coming_soon_page");
        $templatePage = get_post_meta($front_page_id, "_wp_page_template", true);

        if ($templatePage == "charity-coming-soon.php") {
            update_option("show_on_front", "page");
            update_option('page_on_front', $front_page_id);

            // Add Parent Menu
            $argsParent = array(
                'id' => 'chrityComingSoonMode',
                'title' => 'Coming Soon Mode',
                'href' => 'options-reading.php',
                'meta' => array('class' => 'charity-coming-soon-mode'),
            );
            $wp_admin_bar->add_menu($argsParent);
        }
    } else {
        $front_page_id = get_option('page_on_front');
        $templatePage = get_post_meta($front_page_id, "_wp_page_template", true);
        if ($templatePage == "charity-coming-soon.php") {
            update_option("show_on_front", "posts");
            update_option('page_on_front', "");
        } else {
            charityHomeLayout();
        }
    }
}

/**
 * Home page layout
 */
//add_action("init", "charityHomeLayout");

function charityHomeLayout() {
    $home_1_select = vp_option("vpt_option.charity_home_1_select");
    $home_2_select = vp_option("vpt_option.charity_home_2_select");
    $home_3_select = vp_option("vpt_option.charity_home_3_select");
    $home_shoplanding = vp_option("vpt_option.charity_home_shoplanding");
    $home_layout = vp_option("vpt_option.home_layout");

    switch ($home_layout) {
        case "one":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_1_select);
            break;
        case "two":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_2_select);
            break;
        case "three":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_3_select);
            break;
        case "shoplanding":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_shoplanding);
            break;
        default :
            $front_page_id = get_option('page_on_front');
            $templatePage = get_post_meta($front_page_id, "_wp_page_template", true);

            if ($templatePage == "charity-home-one.php" || $templatePage == "charity-home-two.php" || $templatePage == "charity-home-three.php" || $templatePage == "charity-shop-landing.php") {
                update_option("show_on_front", "posts");
                update_option('page_on_front', "");
            }
            break;
    }
}

add_filter('upload_mimes', 'charityUploadMimes');

function charityUploadMimes($files) {
    $files["svg"] = "image/svg+xml";
    return $files;
}

/**
 * Avoid Theme Support feature
 * We are using Option
 */
add_theme_support('custom-header');
remove_theme_support('custom-header');

add_theme_support('custom-background');
remove_theme_support('custom-background');


add_editor_style();
remove_editor_styles();

if (!isset($content_width)) {
    $content_width = 474;
}

add_action("init", "charity_rewrite_rules");

function charity_rewrite_rules() {
    flush_rewrite_rules();
}

/*
 *  Woocommerce Theme Support
 */
add_action('after_setup_theme', 'charity_woocommerce_support');

function charity_woocommerce_support() {
    add_theme_support('woocommerce');
}


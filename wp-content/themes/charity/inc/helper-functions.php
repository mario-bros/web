<?php
/**
 * Charity helper function
 *
 * @package  charity
 * @version  v.1.0
 */
add_filter('the_content_more_link', 'charityModifyReadMoreLink');

function charityModifyReadMoreLink() {
    return '<a class="more-link btn btn-default" role="button" href="' . get_permalink() . '">' . esc_html__('READ MORE', 'charity') . '</a>';
}

if (!function_exists('the_posts_navigation')) :

    /**
     * Posts Naviagtions
     * @return type
     */
    function the_posts_navigation() {
        // Don't print empty markup if there's only one page.
        if ($GLOBALS['wp_query']->max_num_pages < 2) {
            return;
        }
        ?>
        <nav class="navigation posts-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e('Posts navigation', 'airdev'); ?></h2>
            <div class="nav-links">

                <?php if (get_next_posts_link()) : ?>
                    <div class="nav-previous"><?php next_posts_link(__('Older posts', 'airdev')); ?></div>
                <?php endif; ?>

                <?php if (get_previous_posts_link()) : ?>
                    <div class="nav-next"><?php previous_posts_link(__('Newer posts', 'airdev')); ?></div>
                <?php endif; ?>

            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        <?php
    }

endif;

/**
 * Post navigation
 * @return type
 */
if (!function_exists('the_post_navigation')) :

    function the_post_navigation() {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = ( is_attachment() ) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);

        if (!$next && !$previous) {
            return;
        }
        ?>
        <nav class="navigation post-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e('Post navigation', 'airdev'); ?></h2>
            <div class="nav-links">
                <?php
                previous_post_link('<div class="nav-previous">%link</div>', '%title');
                next_post_link('<div class="nav-next">%link</div>', '%title');
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        <?php
    }

endif;

/**
 * Charity - get_page_template
 * @param type $file_name
 * @param type $by parameter
 * @return type post_object, parameter, void
 * 
 */
function charity_get_page_template($file_name, $by = "") {
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => 1,
        'meta_key' => '_wp_page_template',
        'meta_value' => $file_name,
    );

    $charity_pages = get_posts($args);

    if (!empty($charity_pages[0])) {
        return (!empty($charity_pages[0]->$by)) ? $charity_pages[0]->$by : $charity_pages[0];
    }
}

function charity_author_social_link($user_id = false) {

    $socials = array("facebook", "twitter", "pinterest", "google", "dribbble");

    if (!empty($user_id)):
        ?>
        <ul class="social-icons">
            <?php
            foreach ($socials as $social):
                $link = get_the_author_meta($social, $user_id)
                ?>
                <li class="social-<?php echo esc_attr($social); ?>"><a href="<?php echo esc_url($link); ?>" target="blank"><i class="fa fa-<?php echo esc_attr($social); ?>"></i></a></li>
            <?php endforeach; ?>
        </ul>
        <?php
    endif;
}

add_action("charity-site-social-link", "charity_site_social_link");

function charity_site_social_link($class = "") {
    $socials = array("facebook", "google-plus", "twitter", "linkedin", "vimeo-square");
    ?>
    <ul class="social-icons <?php echo esc_attr($class); ?>">
        <?php
        foreach ($socials as $social):
            $link = vp_option("vpt_option.charity-" . $social);
            if (empty($link)) {
                continue;
            }
            ?>
            <li class="social-<?php echo esc_attr($social); ?>"><a href="<?php echo esc_url($link); ?>" target="blank"><i class="fa fa-<?php echo esc_attr($social); ?>"></i></a></li>
        <?php endforeach; ?>
		<li class="social-youtube"><a href="https://www.youtube.com/user/pedulianak" target="blank"><i class="fa fa-youtube"></i></a></li>		
    </ul>
    <?php
}

add_filter("post_class", 'charityPostClass');

function charityPostClass($class) {
    global $charityPostFlag;
    if (is_single()) {
        $class[] = 'blog-details anim-section animate';
    } else {
        $charityPostFlag++;
        if ($charityPostFlag > 1) {
            $class[] = "anim-section animate";
        }
    }
    return $class;
}

add_action("wp_head", "charityCustomCss", 99999);

function charityCustomCss() {
    $ch_css = vp_option("vpt_option.ch_css");

    if (!empty($ch_css)) {
        ?><style type="text/css"> <?php print($ch_css); ?></style><?php
    }
}

add_action("wp_footer", "charityCustomJS", 99999);

function charityCustomJS() {
    $ch_js = vp_option("vpt_option.ch_js");

    if (!empty($ch_js)) {
        ?><script type="text/javascript">
          /* <![CDATA[ */
                <?php print($ch_js); ?> 
            /* ]]> */    
        </script><?php
    }
}

add_action('wp_footer', 'charityCustomGoogalAnalytics', 99999);
function charityCustomGoogalAnalytics() {
    $ch_goo = vp_option("vpt_option.google_analytics");

    if (!empty($ch_goo)) {
        ?><script type="text/javascript">
          /* <![CDATA[ */
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php print($ch_goo); ?> ']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
            /* ]]> */    
        </script><?php
    }
}


/**
 * Get Currency symbol.
 * @param string $currency (default: '')
 * @return string
 */
function get_charity_currency_symbol( $currency = '' ) {

	switch ( $currency ) {
	/*	case 'AED' :
			$currency_s ymbol = ' د .إ';
			break;*/
		case 'AUD' :
		case 'CAD' :
		case 'CLP' :
		case 'COP' :
		case 'HKD' :
		case 'MXN' :
		case 'NZD' :
		case 'SGD' :
		case 'USD' :
			$currency_symbol = '&#36;';
			break;
		case 'BDT':
			$currency_symbol = '&#2547;&nbsp;';
			break;
		case 'BGN' :
			$currency_symbol = '&#1083;&#1074;.';
			break;
		case 'BRL' :
			$currency_symbol = '&#82;&#36;';
			break;
		case 'CHF' :
			$currency_symbol = '&#67;&#72;&#70;';
			break;
		case 'CNY' :
		case 'JPY' :
		case 'RMB' :
			$currency_symbol = '&yen;';
			break;
		case 'CZK' :
			$currency_symbol = '&#75;&#269;';
			break;
		case 'DKK' :
			$currency_symbol = 'kr.';
			break;
		case 'DOP' :
			$currency_symbol = 'RD&#36;';
			break;
		case 'EGP' :
			$currency_symbol = 'EGP';
			break;
		case 'EUR' :
			$currency_symbol = '&euro;';
			break;
		case 'GBP' :
			$currency_symbol = '&pound;';
			break;
		case 'HRK' :
			$currency_symbol = 'Kn';
			break;
		case 'HUF' :
			$currency_symbol = '&#70;&#116;';
			break;
		case 'IDR' :
			$currency_symbol = 'Rp';
			break;
		case 'ILS' :
			$currency_symbol = '&#8362;';
			break;
		case 'INR' :
			$currency_symbol = 'Rs.';
			break;
		case 'ISK' :
			$currency_symbol = 'Kr.';
			break;
		case 'KIP' :
			$currency_symbol = '&#8365;';
			break;
		case 'KRW' :
			$currency_symbol = '&#8361;';
			break;
		case 'MYR' :
			$currency_symbol = '&#82;&#77;';
			break;
		case 'NGN' :
			$currency_symbol = '&#8358;';
			break;
		case 'NOK' :
			$currency_symbol = '&#107;&#114;';
			break;
		case 'NPR' :
			$currency_symbol = 'Rs.';
			break;
		case 'PHP' :
			$currency_symbol = '&#8369;';
			break;
		case 'PLN' :
			$currency_symbol = '&#122;&#322;';
			break;
		case 'PYG' :
			$currency_symbol = '&#8370;';
			break;
		case 'RON' :
			$currency_symbol = 'lei';
			break;
		case 'RUB' :
			$currency_symbol = '&#1088;&#1091;&#1073;.';
			break;
		case 'SEK' :
			$currency_symbol = '&#107;&#114;';
			break;
		case 'THB' :
			$currency_symbol = '&#3647;';
			break;
		case 'TRY' :
			$currency_symbol = '&#8378;';
			break;
		case 'TWD' :
			$currency_symbol = '&#78;&#84;&#36;';
			break;
		case 'UAH' :
			$currency_symbol = '&#8372;';
			break;
		case 'VND' :
			$currency_symbol = '&#8363;';
			break;
		case 'ZAR' :
			$currency_symbol = '&#82;';
			break;
		default :
			$currency_symbol = '';
			break;
	}

	return $currency_symbol;
}


function charity_truncate_content($string, $chars=50) {
   
    // ----- remove HTML TAGs -----
    $string = preg_replace ('/<[^>]*>/', ' ', $string);
   
    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
   
    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
   
    $len=  strlen($string);
    
    if($len > $chars){
        $string = substr($string,0,$chars);
        //$string = substr($string,0,strrpos($string,' '));
        //$string = $string."...";
    }
    
    return $string;
}
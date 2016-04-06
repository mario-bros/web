<?php
/**
 * Charity - Charity tabs Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */
 

function charity_tabs_shortcode($atts,$content = null) {
   $content='<ul class="list-trangled">'.$content.'</ul>';
    return $content;
}
add_shortcode( 'charity_tabs', 'charity_tabs_shortcode' );

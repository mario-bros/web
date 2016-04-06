<?php
/**
 * Charity - Charity General Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */
 
//Shortcode For donation summary text [causesDonationSummary][/causesDonationSummary]
function charity_typography_shortcode($atts,$content = null) {
   $content='<div class="heading-group">'.$content.'</div>';
    return $content;
}
add_shortcode( 'charity_typography', 'charity_typography_shortcode' );

<?php
/**
 * Charity - Charity General Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */
 
//Shortcode For donation summary text [causesDonationSummary][/causesDonationSummary]
function causesDonationSummary_shortcode($atts,$content = null) {
   $content='<span class="donation-summary">'.$content.'</span>';
    return $content;
}
add_shortcode( 'donationsummary', 'causesDonationSummary_shortcode' );

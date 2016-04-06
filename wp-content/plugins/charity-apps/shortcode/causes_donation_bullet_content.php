<?php
/**
 * Charity - Charity General Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */
 
//Shortcode For donation summary text [donationbulletcontent][/donationbulletcontent]
function causesDonation_bullet_content_shortcode($atts,$content = null) {
   $content='<ul class="list-trangled">'.$content.'</ul>';
    return $content;
}
add_shortcode( 'donationbulletcontent', 'causesDonation_bullet_content_shortcode' );


//Shortcode For donation summary text [causes_donation_easy_step][/causes_donation_easy_step]
function causes_donation_easy_step_shortcode($atts,$content = null) {
	shortcode_atts( array(
			'style_type' => '',
			'donation_step_title' => '',
	), $atts );
	$content='<div class="cols-xs-12 col-sm-4">
			       <div class="sec-step-desc">
			            <span class="number-count">'.$atts['style_type'].'</span>
							<h4 class="normal-text">'.$atts['donation_step_title'].'</h4>
							
						</div>
					</div>';
	return $content;
}
add_shortcode( 'causes_donation_easy_step', 'causes_donation_easy_step_shortcode' );
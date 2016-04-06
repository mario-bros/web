<?php 
function charity_vc_donate_now( $atts, $content = null ) {
	$return  = do_shortcode("[charity_global_donation_button][/charity_global_donation_button]");
	return $return;
}

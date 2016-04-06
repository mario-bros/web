<?php 
function charity_vc_donation_holder_say( $atts, $content = null ) {
	extract( shortcode_atts( array( 
		'testimonial'  => '',
		), $atts ) );
		
	  switch ($testimonial) {
	   case "home1" : get_template_part("content/home/home", "one-testimonial");
	   break;
	   case "home2" : get_template_part("content/home/home", "two-testimonial");
	   break;
	   case "home3" : get_template_part("content/home/home", "three-testimonial");
	   break;
	  
	  }
	  
}

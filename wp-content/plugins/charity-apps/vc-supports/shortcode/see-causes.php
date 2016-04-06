<?php 
function charity_vc_see_causes( $atts, $content = null ) { 
	extract( shortcode_atts( array( 
		'causes'  => '',
		), $atts ) );
	  switch ($causes) {
	   case "home1" : get_template_part("content/home/home", "one-causes");
	   break;
	   case "home2" : get_template_part("content/home/home", "two-causes");
	   break;
	   case "home3" : get_template_part("content/home/home", "three-causes");
	   break;
	  
	  }
}

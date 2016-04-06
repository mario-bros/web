<?php 
function charity_vc_latest_news( $atts, $content = null ) {
	extract( shortcode_atts( array( 
		'news'  => '',
		), $atts ) );
	  switch ($news) {
	   case "home1" : get_template_part("content/home/home", "one-news");
	   break;
	   case "home2" : get_template_part("content/home/home", "two-news");
	   break;
	   case "home3" : get_template_part("content/home/home", "three-news");
	   break;
	  
	  }
	
}

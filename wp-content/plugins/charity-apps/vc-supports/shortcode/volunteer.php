<?php 
function charity_vc_become_volunteer( $atts, $content = null ) {
	extract( shortcode_atts( array( 
		'step_1'  => '',
		'title_1' => '',
		'content_1' => '',
		'step_2'  => '',
		'title_2' => '',
		'content_2' => '',
		'step_3'  => '',
		'title_3' => '',
		'content_3' => '',
		'step_4'  => '',
		'title_4' => '',
		'content_4' => '',
		
	), $atts, 'volunteer' ) );
	
	$return="";
	$return .="<section class='process-section text-center'>";
    $return .="<header class='page-header section-header clearfix'>";
    $return .="<h2>".vp_option('vpt_option.ch_volunteer_process_title')."</h2>";
    $return .="</header>";
    $return .="<div class='row processes'>";
    $return .= do_shortcode("[volunteer step='".$step_1."' title='".$title_1."']".$content_1."[/volunteer]");
    $return .= do_shortcode("[volunteer step='".$step_2."' title='".$title_2."']".$content_2."[/volunteer]");
    $return .= do_shortcode("[volunteer step='".$step_3."' title='".$title_3."']".$content_3."[/volunteer]");
    $return .= do_shortcode("[volunteer step='".$step_4."' title='".$title_4."']".$content_4."[/volunteer]");
    $return .= do_shortcode("[charity_join_today_button][/charity_join_today_button]");
    $return .= do_shortcode("[join_today_form][contact-form-7 id='356' title='Join Today'][/join_today_form]");
    $return .="</div>";
    $return .="</section>";
	return $return;
}

<?php
/**
 * Charity - Charity General Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */



//Shortcode For BlockQuote [charity_global_donation_button][/charity_global_donation_button]
function charity_global_donation_button_shortcode($atts,$content = null) {
	$causesId=vp_option("vpt_option.charity_global_donation_cause_id");
	if(!empty($causesId)):
	
	
	        $content='<a href="/general-donation/" data-id="'.$causesId.'" data-target=".donate-form" class="btn btn-default charity-global-causes-'.$causesId.' charity-donation-button">'.__("DONATE NOW", "charity") .'</a>';
	        
	        else:
	        	$content=__("Select causes for global donation", "charity");
	        endif;
	return $content;
}
add_shortcode( 'charity_global_donation_button', 'charity_global_donation_button_shortcode' );


//Shortcode For BlockQuote [blockquote][/blockquote]
function blockquote_shortcode($atts,$content = null) {
   $content='<blockquote class="callout"><p>'.$content.'</p></blockquote>';
    return $content;
}
add_shortcode( 'blockquote', 'blockquote_shortcode' );

//Shortcode For Row [blogrow][/blogrow]
function charity_blog_row($atts,$content = null) {
   $content='<div class="row block-content">'.do_shortcode($content).'</div>';
    return $content;
}
add_shortcode( 'blogrow', 'charity_blog_row' );

//Shortcode For Column [blogTwocol][/blogTwocol]
function charity_blog_col($atts,$content = null) {
	shortcode_atts( array(
        'blog_two_column_heading' => '',
    ), $atts );
	$content='<div class="col-xs-12 col-sm-6"><h3>'.$atts['blog_two_column_heading'].'</h3><p>'.$content.'</p></div>';
    return $content;
}
add_shortcode( 'blogTwocol', 'charity_blog_col' );

//Shortcode For Portfolio Summary text [portfolio-strong-text][/portfolio-strong-text]
function portfolio_shortcode($atts,$content = null) {
   $content='<strong class="detail-summary">'.$content.'</strong>';
    return $content;
}
add_shortcode( 'portfolio-strong-text', 'portfolio_shortcode' );

//Shortcode For List UL [list-style-yello-icon][/list-style-yello-icon]
function list_style_yello_icon($atts,$content = null) {
   $content='<ul class="list-unstyled yello-icon">'.do_shortcode($content).'</ul>';
    return $content;
}
add_shortcode( 'list-style-yello-icon', 'list_style_yello_icon' );

//Shortcode For List Item [list-item][/list-item]
function portfolio_list_item($atts,$content = null) {
   $content='<li>'.$content.'</li>';
    return $content;
}
add_shortcode( 'list-item', 'portfolio_list_item' );

//Shortcode For Default Button Style [default-button-style][/default-button-style]
function default_button_style($atts,$content = null) {
	shortcode_atts( array(
        'default_button_text' => '',
        'default_button_link' => '',
    ), $atts );
   $content='<a class="btn btn-default" href="'.$atts['default_button_link'].'">'.$atts['default_button_text'].'</a>';
    return $content;
}
add_shortcode( 'default-button-style', 'default_button_style' );

//Shortcode For Contact Info [contact-info][/contact-info]
function contact_info($atts,$content = null) {
	shortcode_atts( array(
        'address_first_line' => '',
        'address_second_line' => '',
        'email_id' => '',
        'telephone_no' => '',
        'telephone_no_link' => '',
		'telephone_no_two' => '',
	    'telephone_no_two_link' => '',		
        
    ), $atts );
   $content='<address>
				<span> <strong>Address :</strong> <span>'.$atts['address_first_line'].'
						<br>
						'.$atts['address_second_line'].'</span> </span>
				<span> <strong>E-Mail :</strong> <span><a href="mailto:'.$atts['email_id'].'">'.$atts['email_id'].'</a></span> </span>
				<span> <strong>Tel :</strong> <span><a href="tel:'.$atts['telephone_no_link'].'">'.$atts['telephone_no'].'</a></span> </span>
				<span> <strong>Tel :</strong> <span><a href="tel:'.$atts['telephone_no_two_link'].'">'.$atts['telephone_no_two'].'</a></span> </span>
			</address>';
    return $content;
}
add_shortcode( 'contact-info', 'contact_info' );


//Shortcode For Become Volunteer [volunteer][/volunteer]
function become_volunteer($atts,$content = null) {
	shortcode_atts( array(
			'step' => '',
			'title' => '',
			'content' => '',
	), $atts );
	$content='<div class="col-xs-12 col-sm-3">
						<div class="process-step">'.$atts['step'].'</div>
						<h3 class="h4">'.$atts['title'].'</h3>
						<p>'.$content.'</p>
					</div>';
	return $content;
}
add_shortcode( 'volunteer', 'become_volunteer' );


//Shortcode For Message [message][/message]
function message($atts,$content = null) {
	shortcode_atts( array(
			'step' => '',
			'title' => '',
			'content' => '',
	), $atts );
	$content='<p class="message">'.$content.'</p>';
	return $content;
}
add_shortcode( 'message', 'message' );


//Shortcode For list_style [list_style][/list_style]
function list_style($atts,$content = null) {
	shortcode_atts( array(
			'style_type' => '',
	), $atts );

	$content='<ul class="'.$atts['style_type'].'">'.do_shortcode($content).'</ul>';
	return $content;
}
add_shortcode( 'list_style', 'list_style' );



//Shortcode For Notification[notification][/notification]
function notification($atts,$content = null) {
	shortcode_atts( array(
			'class' => '',
			'content' => '',
	), $atts );
	$content='<div class="alert '.$atts['class'].'"><p>'.$content.'</p></div>';
	return $content;
}
add_shortcode( 'notification', 'notification' );



//Shortcode For sliderrange [sliderrange][/sliderrange]
function sliderrange($atts,$content = null) {
	shortcode_atts( array(
			'type' => '',
			'slider_range' => '',
			'content' => '',
	), $atts );
	$content='<div class="progress">
    <div class="progress-bar '.$atts['type'].'" role="progressbar" aria-valuenow="'.$atts['slider_range'].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$atts['slider_range'].'%">
       <span class="progress-value">'.$atts['slider_range'].'% </span>
    </div>
      <span class="progress-value-number">'.$atts['slider_range'].'%</span>
 </div>';
	return $content;
}
add_shortcode( 'sliderrange', 'sliderrange' );


//Shortcode For Tags [tabs][/tabs]
/*function tabs($atts,$content = null) {
	$content='<ul class="nav nav-tabs" role="tablist"><li><a href="#'.str_replace(' ','_',$atts['tabs_heading']).'" role="tab" data-toggle="tab">'.$atts['tabs_heading'].'</a></li></ul>
			<div class="tab-content">
  <div class="tab-pane active" id="'.str_replace(' ','_',$atts['tabs_heading']).'">
  	<P>'.$content.'</P>
  </div>
</div>';
	return $content;
}
add_shortcode( 'tabs', 'tabs' );
			*/

//Shortcode For Table [table][/table]
function table($atts,$content = null) {
	shortcode_atts( array(
			'class' => '',
	), $atts );
	$content='<table class="table '.$atts['class'].'">'.do_shortcode($content).'</table>';
	return $content;
}
add_shortcode( 'table', 'table' );

// for sub body content of table
function table_data($atts,$content = null) {
	shortcode_atts( array(
			'class' => '',
	), $atts );
	$content='<td>'.$content.'</td>';
	return $content;
}
add_shortcode( 'table-data', 'table_data' );

// for Table title content of table
function table_heading($atts,$content = null) {
	shortcode_atts( array(
			'class' => '',
	), $atts );
	$content='<th>'.$content.'</th>';
	return $content;
}
add_shortcode( 'table-heading', 'table_heading' );

// for Table title content of table
function table_row($atts,$content = null) {
	shortcode_atts( array(
			'class' => '',
	), $atts );
	$content='<tr>'.do_shortcode($content).'</tr>';
	return $content;
}
add_shortcode( 'table-row', 'table_row' );



//Shortcode For BlockQuote [charity_global_donation_button][/charity_global_donation_button]
function charity_join_today_button_shortcode($atts,$content = null) {
	$content='<a data-toggle="modal" href="javascript:;" data-target=".join-now-form" class="btn btn-default btn-lg join-today">JOIN TODAY</a>';
	$content = '<a class="btn btn-default btn-lg join-today"  href="/get-involved/volunteer/">JOIN TODAY</a>';
	return $content;
}
add_shortcode( 'charity_join_today_button', 'charity_join_today_button_shortcode' );


// for join today contact form
function join_today_form($atts,$content = null) {
	shortcode_atts( array(
			'class' => '',
	), $atts );
	$content='<div class="modal join-now-form"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						x</button><header class="page-header"><h2>Become a <strong>Volunteer</strong></h2></header></div><div class="modal-body">'.do_shortcode($content).'</div></div></div></div>';
	return $content;
}
add_shortcode( 'join_today_form', 'join_today_form' );

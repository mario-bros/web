<?php
/**
 * Charity - Charity General Shortcode
 *
 * @package  charity
 * @version  v.1.0
 */
 
//Shortcode For donation summary text [causesDonationSummary][/causesDonationSummary]
function charity_toogle_shortcode($atts,$content = null) {
   $content='<div class="panel-group" id="accordion">
									<div class="panel panel-default">  		
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse_'.str_replace(' ','_',$atts['charity_toggle_heading']).'" class="collapsed"> <i class="fa fa-plus-circle  toggel-icon"></i>'.$atts['charity_toggle_heading'].'</a></h4>
										</div>		
										<div id="collapse_'.str_replace(' ','_',$atts['charity_toggle_heading']).'" class="panel-collapse collapse" style="height: 1px;">
											<div class="panel-body">'.$content.'</div>
										</div>
									</div>
								</div>';
    return $content;
}
add_shortcode( 'charity_toggle', 'charity_toogle_shortcode' );

<?php
/**
 * Charity -  Home One Become Volunteer
 *
 * @package     charity
 * @version     v.1.0
 */
 
$pageid = vp_metabox('charity-sub-page.charity_ask_us' );
//echo $pageid;
query_posts(array("page_id"=> $pageid));
if(have_posts()) : the_post(); 
$title = vp_metabox('volunteer.volunteer-title');
$image = vp_metabox('volunteer.volunteer-image');
$content = vp_metabox('volunteer.volunteer-content');
//$url = wp_get_attachment_image_src( get_post_thumbnail_id($pageid), array( 1143,479 ) );
 ?>
<section class="parallax-section parallax" style="background-image: url('<?php echo esc_url($image);?>')">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-5 volunteertje">
			<div class="volunteercontainer">
				<h2><?php print($title); ?></h2>
				<p><?php print($content); ?></p>
			</div>	
			</div>
		</div>
	</div>
</section>
<?php endif; 
wp_reset_query();
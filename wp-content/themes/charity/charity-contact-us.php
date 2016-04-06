<?php
/**
 * Template Name: Charity Contact Us
 *
 * @package     charity
 * @version     v.1.0
 */
	get_header(); 
	//breadcrumb
	do_action("charity_breadcrumb", array("title"=> get_the_title()));
	
?>
<div class="content-wrapper container" id="page-info">
	<div class="row">
	
		<div class="col-xs-12 col-sm-6 contact-form">
			<div class="col-xs-12" id="success"></div>
			<h2><?php print(vp_metabox('contact_page.contact_page_form_title')); ?></h2>
			<?php print(vp_metabox('contact_page.contact-page-form')); ?>
		</div>
		
		
		<div class="col-xs-12 col-sm-5 col-sm-offset-1 contact-address">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			the_content();
			endwhile; endif;
			?>
			<!--<h2>Get in touch</h2>
			<address>
				<span> <strong>Address :</strong> <span>A-2, Sector-63,
						<br>
						Noida, 201301, India</span> </span>
				<span> <strong>E-Mail :</strong> <span><a href="mailto:contact@charity.com">contact@charity.com</a></span> </span>
				<span> <strong>Tel :</strong> <span><a href="tel:+17079217269">+1 707 921 7269</a></span> </span>
				<span> <strong>Fax :</strong> <span>+1 206 973 7944</span> </span>
			</address>-->
		</div>
	</div>
</div>
<?php 

get_footer();


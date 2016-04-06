<?php
/**
 * The template for displaying product content in the single-give-form.php template
 *
 * Override this template by copying it to yourtheme/give/content-single-give-form.php
 *
 * @package       Give/Templates
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
/**
 * give_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'give_before_single_form' );

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
?>

<script type="text/javascript">

function seteduplan() {
var value = jQuery('#give-donation-level-<?php the_ID(); ?> option:selected').val();
if (value != '0.00') {
	value = value + '*' + <?php the_ID(); ?>;	
jQuery('#give-edu-plan').val(value);	
if (value == "408.00") {
jQuery('#recur_period').val('yearly');	
} else { 
jQuery('#recur_period').val('monthly');
}
}

}

function setlivplan() {
var value = jQuery('#give-donation-level-twee-<?php the_ID(); ?> option:selected').val()
if (value != '0.00') {
	value = value + '*' + <?php the_ID(); ?>;
jQuery('#give-liv-plan').val(value);	
if (value == "576.00") {
jQuery('#recur_period').val('yearly');	
} else { 
jQuery('#recur_period').val('monthly');
}

}

}

</script>

	<div id="give-form-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="yoursponsoring">
	<h2><span class="grijs">You're sponsoring </span><strong><span class="zwart"><?php $name = get_post_meta( get_the_ID(), '_give_childname', true ); echo $name; ?></span></strong></h2>
	</div>
		<?php
		/**
		 * give_before_single_product_summary hook
		 *
		 * @hooked give_show_product_images - 10
		 */
		//do_action( 'give_before_single_form_summary' );
		?>
		<div class="containerchild">
		<div class="leftchild">
		<div class="img-thumb">
		<?php echo get_the_post_thumbnail( get_the_ID(), 'medium'); ?>
		</div>
		</div>
		<div class="middlechild">
							<h3 style="margin-bottom:5px;"><?php the_title();?></h3>
							<?php $dob = get_post_meta( get_the_ID(), '_give_date_of_birth', true ); ?>
							<?php $gender = get_post_meta( get_the_ID(), '_give_gender', true ); ?>
							<?php $intro = get_post_meta( get_the_ID(), '_give_intro', true ); ?>
							<?php $fullinfo = get_post_meta( get_the_ID(), '_give_fullinfo', true ); ?>
							<?php $liv_sponsor = get_post_meta( get_the_ID(), '_give_liv_sponsor', true ); ?>
							<?php $edu_sponsor = get_post_meta( get_the_ID(), '_give_edu_sponsor', true ); ?>														
							
							
							<?php $fullinfo = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<p class=\"youtube\"><object width=\"560%\" height=\"315\"><param name=\"movie\" value=\"http://www.youtube.com/v/$1&hl=en&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/$1&hl=en&fs=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"560\" height=\"315\"></embed></object></p>",$fullinfo); ?>
														
							<?php $name = get_post_meta( get_the_ID(), '_give_childname', true ); ?>							
	
							
							<ul>
							<li>Gender: <span><strong><?php echo $gender; ?></strong></span></li>
							<li>Date of Birth: <span><strong><?php echo $dob; ?></strong></span></li>							
							</ul>	
							
							<p><?php echo $fullinfo; ?></p>	
		</div>
		<div class="rightchild">
		<div class="donation_title"><span>Donation Amount Test</span></div>
			<?php
			/**
			 * give_single_form_summary hook
			 *
			 * @hooked give_template_single_title - 5
			 * @hooked give_get_donation_form - 10
			 */
			//do_action( 'give_single_form_summary' );
			
			?>
			<?php 
			$livstatus = "active";
			if ($liv_sponsor == "") {
				$livstatus = "inactive";
			}
			$edustatus = "active";
			if ($edu_sponsor == "") {
				$edustatus = "inactive";
			}			
			?>
			
<div id="give-form-<?php the_ID(); ?>-wrap" class="give-form-wrap give-display-onpage">
<form id="give-form-834" class="give-form give-form_<?php the_ID(); ?>" action="#" method="post">
			<input type="hidden" name="donation_add" value="1" />
			<input type="hidden" name="give-form-id" value="<?php the_ID(); ?>" />
			<input type="hidden" name="give-form-title" value="<?php the_title();?>" />
			<input type="hidden" name="give-current-url" value="<?php the_permalink(); ?>" />
			<input type="hidden" name="give-form-url" value="<?php the_permalink(); ?>" />
							<input type="hidden" name="give-price-id" value="1" />
		<p class="sponsoredicon"><span class="dashicons sponsor-<?php echo $edustatus; ?>-education"></span><span class="sponsor-item">Education Sponsorship</span></p>
		
		<?php if ($edustatus == "active") { ?>
		<p class="sponsored">Sponsored</p>
		
		<select id="give-donation-level-<?php the_ID(); ?>" class="give-select give-select-level" style="display:none;">
		<option data-price-id="0" class="give-donation-level-<?php the_ID(); ?>" value="0.00">Choose Education Plan</option>
		
		</select>		
		
		<?php } else { ?>
		
		<select id="give-donation-level-<?php the_ID(); ?>" class="give-select give-select-level" onchange="seteduplan();">
		<option data-price-id="0" class="give-donation-level-<?php the_ID(); ?>" value="0.00">Choose Education Plan</option>
		<option data-price-id="1" class="give-donation-level-<?php the_ID(); ?>" value="34.00">Monthly Child Education Plan (&euro;34.00)</option>
		<option data-price-id="2" class="give-donation-level-<?php the_ID(); ?>"  value="408.00">Yearly Child Education Plan (&euro;408.00)</option>
		</select>		
		<input type="hidden" id="give-edu-plan" name="give-edu-plan" value="" />
		
		<?php } ?>
		<p class="break">&nbsp;</p>
		<p class="sponsoredicon"><span class="dashicons sponsor-<?php echo $livstatus; ?>-hearth"></span><span class="sponsor-item">Livelihood Sponsorship</span></p>
		
		<?php if ($livstatus == "active") { ?>
		<p class="sponsored">Sponsored</p>
			
		<select id="give-donation-level-twee-<?php the_ID(); ?>" class="give-select-twee give-select-level-twee" style="display:none;">
		<option data-price-id="0" class="give-donation-level-<?php the_ID(); ?>" value="0.00">Choose Livelihood Plan</option>
		</select>		
		<?php } else { ?>
		
		<select id="give-donation-level-twee-<?php the_ID(); ?>" class="give-select-twee give-select-level-twee" onchange="setlivplan();">
		<option data-price-id="0" class="give-donation-level-<?php the_ID(); ?>" value="0.00">Choose Livelihood Plan</option>

		<option data-price-id="1" class="give-donation-level-twee-<?php the_ID(); ?>"  value="48.00">Monthly Child Support Plan (&euro;48.00)</option>
		
		<option data-price-id="2" class="give-donation-level=twee-<?php the_ID(); ?>"  value="576.00">Yearly Child Support Plan (&euro;576.00)</option>
		</select>	
		<input type="hidden" id="give-liv-plan" name="give-liv-plan" value="" />	
		
		<?php } ?>
		<p class="break">&nbsp;</p>	
		<?php if ($livstatus == "active" && $edustatus == "active") { ?>
		
		<?php } else { ?>

					<div class="give-total-wrap">
			<div class="give-donation-amount form-row-wide">
				<span class="give-currency-symbol give-currency-position-before" style="display:none;">&euro;</span>
				<input class="give-text-input" id="give-amount" name="give-amount" type="hidden" placeholder="" value="0.00" required autocomplete="off">
				<input type="hidden" id="recur_period" name="recur_period" value="" />	
				
				<p class="give-loading-text give-updating-price-loader" style="display: none;">
					<span class="give-loading-animation"></span> Updating Donation<span class="elipsis">.</span><span class="elipsis">.</span><span class="elipsis">.</span></p>
			</div>
		
		</div>
	


	<div id="give_purchase_form_wrap">

		
			<fieldset id="give_purchase_submit">
			<p id="give-final-total-wrap" class="form-wrap ">
		<span class="give-donation-total-label">Donation Total:</span>
		<span class="give-final-total-amount" data-total="0">&euro;0.00</span>
	</p>
	<p class="break">&nbsp;</p>
	
					<input type="hidden" name="give-user-id" value="6" />
		<input type="hidden" name="give_action" value="purchase" />
	<input type="hidden" name="give-gateway" value="buckaroo" />
	
	<div style="text-align:center; width:100%; float:left; margin-left:42%; margin-bottom:5px !important; display:none;" class="give-loading-animation spinner"><span class="give-loading-animation"></span></div>	
	<div class="give-submit-button-wrap give-clearfix ">
	
		<input type="submit" class="btn btn-donatie" id="give-purchase-button" name="give-purchase" value="Donate Now" />
		
	</div>
	
		
	</fieldset>
	<?php } ?>
	
	</div><!-- the checkout fields are loaded into this-->

	
		</form>

		
		<!--end #give-form-834--></div>
		</div>

		<?php
		/**
		 * give_after_single_form_summary hook
		 */
		do_action( 'give_after_single_form_summary' );
		?>


	</div><!-- #give-form-<?php the_ID(); ?> -->

<?php do_action( 'give_after_single_form' ); ?>
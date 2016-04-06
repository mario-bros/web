<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package     charity
 * @version     v.1.0
 */
?>
</div>
<section class="how-to-help header-one-help partnerscontainer" style="margin-bottom:-20px !important; background-color:#dee0dc !important;">
        <div class="container">
            <div class="row">
			<header class="page-header section-header">
					<h2 class="sponsorheader">Our partners &amp; <strong>sponsors</strong></h2>
				</header>
<!--<div class="col-md-3">
<?php
$string_with_shortcodes = "[carousel_slider id='1' category_slug='partners-and-sponsors' items_desktop='1' items='1' single_item='true']";
echo do_shortcode($string_with_shortcodes);
?>
</div>-->
<div class="col-md-12">
<?php
$string_with_shortcodes = "[carousel_slider id='2' category_slug='partners-and-sponsors-random' items_desktop='6' items='6'] ";
echo do_shortcode($string_with_shortcodes);
?>
</div>
			</div>
		</div>
</section>					
<!--Footer Section Start Here -->
<?php do_action("charity_footer_layout"); ?>
<!--Footer Section End Here -->
</div>

<?php wp_footer(); ?>	


<script type="text/javascript">
  var hash = window.location.hash.slice(1);
  var $ = jQuery.noConflict();
  
  open_donate_by_url(hash);
  function open_donate_by_url(hash){if(hash == 'donate'){ jQuery('[data-target=".donate-form"]').first().trigger('click'); }}

  $(function(){
    jQuery(window).on('hashchange',function(){ 
      hash = location.hash.slice(1);
      open_donate_by_url(hash); 
    });
  })

</script>

</body>
</html>

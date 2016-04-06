<?php /**
 * Template Name: Charity Shop Landing
 *
 * @package     charity
 * @version     v.1.0
 */
get_header();
$imgOne = vp_metabox('shoplanding.imgOne');
$imgOneText = vp_metabox('shoplanding.imgOneText');
$imgOneText2 = vp_metabox('shoplanding.imgOneTextsecond');

$imgtwo = vp_metabox('shoplanding.imgtwo');
$imgtwo2 = vp_metabox('shoplanding.imgtwo2');
$imgtwoText = vp_metabox('shoplanding.imgtwoText');

$producttitle = vp_metabox('shoplanding.productTitle');
$productImage = vp_metabox('shoplanding.product-image');
$productContent = vp_metabox('shoplanding.product-content');

get_template_part("content/shop", "landing-slider");
?>


	<section class="special-offer-section">
		<div class="container">

			<div class="row">
				<div class="col-sm-6 shopping-offer-list">

					<img src="<?php echo $imgOne; ?>" alt="imgOne" title="imgOne" />

					<div class="special-offer-text">
						<div class="offer-percent">
							<?php print($imgOneText); ?>
						</div>
						<div class="offer-product-type">
							<?php print($imgOneText2); ?>
						</div>
					</div>
				</div>

				<div class="col-sm-6 shopping-offer-list">
					<img src="<?php echo $imgtwo; ?>" alt="imgtwo" title="imgtwo" />
					<div class="special-offer-text">
						<div class="better-text">
							<?php print($imgtwoText); ?>
							<img src="<?php echo $imgtwo2; ?>" alt="imgtwo2" title="imgtwo2" />
						</div>
					</div>
				</div>
							
				
			</div>
		</div>
	</section>
<!-- crousel -->

<header class="shoping-list section-header our-products clearfix">
                    <h2><?php echo vp_option('vpt_option.ch_shop_landing_product_title'); ?></h2>  
                </header>
	<div class="our-product-section shoping">
		<div class="container">
			<div class="row">			
				
				<?php
				global $charity_product_list_open;
				global $charity_product_list_close;
				$charity_product_list_open="<li class='item'>"; 
				$charity_product_list_close="</li>"; 
				echo do_shortcode('[recent_products per_page="12" columns="1"]'); ?>
							
			</div>
		</div>
	</div>


<div class="upcomming-product">
<div class="upcomming-product-image" style="background-image: url(<?php echo $productImage; ?>); ">
				<!-- <?php echo '<img src="' . $productImage . '">';?> -->
				</div>
					<!-- <div class="upcomming-product-data">
						
					<div class="upcomming-product-content"> -->
					<div class="container">
			<div class="row">	
				<div class="col-sm-4 upcomming-product-data">
				<h2><?php print($producttitle);?></h2>
				<p><?php print($productContent);?></p>
				</div><!-- </div></div> -->
				</div></div>
				<!-- <div class="upcomming-product-image">
				<?php echo '<img src="' . $productImage . '">';?>
				</div> -->
				 
				</div>



<?php
get_template_part("content/shop", "news");
get_template_part("content/shop", "testimonial");

get_footer();


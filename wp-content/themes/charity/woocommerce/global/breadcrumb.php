<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php

	$shop_single_bg=vp_option('vpt_option.img_404');
	$charityShopBreadCrumbImagedefault = 'style="background-image: url('.$shop_single_bg.')"';
	//global $charityShopBreadCrumbImage_product;
	if ( is_single() ):
		$charityProductId=get_the_ID();
		$shopbgImage = vp_metabox('product_breadcrumb.single_shopimage',$charityProductId);
		$charityShopBreadCrumbImage_product = 'style="background-image: url('.$shopbgImage.')"';
		$breadcrumbImg =(!empty($shopbgImage) && $shopbgImage !="") ? $charityShopBreadCrumbImage_product : $charityShopBreadCrumbImagedefault;
		
	//endif;
	else :
	$charity_id=woocommerce_get_page_id( 'shop' );
	$shopbgImage = vp_metabox('breadcrumb.image', '', $charity_id);
	$charityShopBreadCrumbImage = 'style="background-image: url('.$shopbgImage.')"';
	$breadcrumbImg =(!empty($shopbgImage) && $shopbgImage !="") ? $charityShopBreadCrumbImage : $charityShopBreadCrumbImagedefault;
	endif;
	?>
<div class="breadcrumb-section" <?php echo $breadcrumbImg;?>>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
							<h1><?php woocommerce_page_title(); ?></h1>

<?php 
if ( $breadcrumb ) {

	echo $wrap_before;

	foreach ( $breadcrumb as $key => $crumb ) {

		echo $before;

		if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
			echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
		} else {
			echo esc_html( $crumb[0] );
		}

		echo $after;

		if ( sizeof( $breadcrumb ) !== $key + 1 ) {
			echo $delimiter;
		}

	}

	echo $wrap_after; ?>
	
	</div>
						</div>
					</div>
				</div>
	

<?php }
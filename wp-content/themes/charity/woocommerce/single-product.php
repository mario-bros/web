<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
    //global $charityShopBreadCrumbImage;
  //  global $charityShopBreadCrumbImage_product;
    
    
   
			/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
	
		<div class="container mission-page single-product-page" id="page-info">
					<div class="row">
						<div class="col-xs-12">
							<!-- Shoping Section Start Here-->
							<section class="our-works shoping shoping-detail-block">
								<header class="page-header section-header clearfix">
									<h2><?php _e( 'Shop our products &amp; <strong>Help us.</strong>','charity');?></h2>
								</header>
								<div class="row">
								
								<div class="col-md-9 product-detail">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	</div>
<div class="col-md-3">
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>
	</div>
	</div>
	</section>
	</div>
	</div>
	</div>
	
	
	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
	
	<?php $title=vp_metabox('product_shop_banner.banner_title','',$id);
							$sub_title=vp_metabox('product_shop_banner.banner_sub_title','',$id);
							$desc=vp_metabox('product_shop_banner.banner_desc', '' ,$id);
							
							if(!empty($title)):
							?>
	<section class="shop-today">
					<div class="container">
						<div class="row">
							<div class="col-sm-12">
								<span class="glyphicon glyphicon-shopping-cart"></span>
								<h2><?php echo esc_attr($title);?><strong><?php echo esc_attr($sub_title);?></strong></h2>
								<p><?php echo esc_attr($desc);?></p>
								<a class="btn btn-default" href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) );?>"><?php _e( 'add to cart','charity');?></a>
							</div>
						</div>
					</div>	
				</section>
				<?php endif; ?>
	
	

<?php get_footer( 'shop' ); ?>

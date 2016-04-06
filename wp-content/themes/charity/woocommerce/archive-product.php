<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');
?>
<?php
/*
  global $charityShopBreadCrumbImage;
  $charity_id=woocommerce_get_page_id( 'shop' );
  $shopbgImage = vp_metabox('breadcrumb.image', '', $charity_id);
  $charityShopBreadCrumbImage = 'style="background-image: url('.$shopbgImage.')"'; */


/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<div id="page-info" class="container mission-page">
    <div class="row">
        <div class="col-xs-12">
            <!-- Shoping Section Start Here-->
            <section class="our-works shoping">

                <header class="shoping-list section-header clearfix">
                    <h2>
                        <?php
                        if (!empty($_REQUEST['s'])):
                            _e('Search', 'charity'); 
                            printf('<strong>"%s"</strong>', $_REQUEST['s']);
                        else:
                            _e('Shop our products &amp; <strong>Help us.</strong>', 'charity');
                        endif;
                        ?>
                    </h2>  
                </header>

                <?php /* if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

                  <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

                  <?php endif;*/  ?>

                <div class="row">
                	    <div class="col-sm-12">
                	    	<?php do_action("charity_shop_count"); ?>
                	    </div>
                	<div class="col-md-9">
                        <div class="row shoping-row">

                            <?php do_action('woocommerce_archive_description'); ?>

                            <?php if (have_posts()) : ?>

                                <?php
                                /**
                                 * woocommerce_before_shop_loop hook
                                 *
                                 * @hooked woocommerce_result_count - 20
                                 * @hooked woocommerce_catalog_ordering - 30
                                 */
                                do_action('woocommerce_before_shop_loop');
                                ?>

                                <?php //woocommerce_product_loop_start();  ?>

                                <?php //woocommerce_product_subcategories();  ?>

                                <?php while (have_posts()) : the_post(); ?>

                                    <div class="col-sm-4">


                                        <?php wc_get_template_part('content', 'product'); ?>

                                    </div>

                                <?php endwhile; // end of the loop.  ?>

                                <?php //woocommerce_product_loop_end();  ?>

                                <?php
                                /**
                                 * woocommerce_after_shop_loop hook
                                 *
                                 * @hooked woocommerce_pagination - 10
                                 */
                                do_action('woocommerce_after_shop_loop');
                                ?>

                            <?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>

                                <?php wc_get_template('loop/no-products-found.php'); ?>

                            <?php endif; ?>



                        </div></div>
                    <div class="col-md-3">
                        <?php
                        /**
                         * woocommerce_sidebar hook
                         *
                         * @hooked woocommerce_get_sidebar - 10
                         */
                        do_action('woocommerce_sidebar');
                        ?>
                    </div></div>
            </section>
            <!-- Shoping Section End Here-->
        </div>
    </div>
</div>
<?php
/**
 * woocommerce_after_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
?>

<?php get_footer('shop'); ?>

<?php

/**
 * Charity - woocommerce  product
 * @package charity
 * @version     v.1.0
 * 
 */

//remove_action("woocommerce_before_shop_loop_item_title","woocommerce_show_product_loop_sale_flash");

remove_action("woocommerce_after_shop_loop_item_title","woocommerce_template_loop_rating", 5);
add_action("woocommerce_after_shop_loop_item","woocommerce_template_loop_rating", 11);

remove_action("woocommerce_single_product_summary","woocommerce_template_single_add_to_cart", 30);
add_action("woocommerce_single_product_summary","woocommerce_template_single_add_to_cart", 41);

remove_action("woocommerce_before_shop_loop","woocommerce_result_count", 20);
add_action("charity_shop_count","woocommerce_result_count");


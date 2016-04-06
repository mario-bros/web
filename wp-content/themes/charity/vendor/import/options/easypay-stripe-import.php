<?php
/**
 * Charity- Vender for easypay option import
 *  
 * @package     charity
 * @version     v.1.0
 */

easyPayStripeImport();

function easyPayStripeImport() {

	$oldoption=get_option("easypay_stripe_setting");
	
	if(empty($oldoption)){
		$data_file = CHY_THEME_URL.'/vendor/data/easypay-stripe.json';
		$get_data = wp_remote_get($data_file);

		if (is_wp_error($get_data))
			return false;

		$rawdata = isset($get_data['body']) ? $get_data['body'] : '';
		$options = json_decode($rawdata, true);

		if(!empty($options[0])){
                        $newOption=str_replace('http://theemon.com/c/charity-wp/PlaceHolder', site_url(), $options[0]);
			update_option("easypay_stripe_setting", $newOption);
		}
	}
}

//Import User Meta
//[easyPayOptionExport()]; //json value

function easyPayStripeExport() {

	$options=get_option("easypay_stripe_setting");

    return json_encode($options);
}


<?php
/**
 * Charity- Vender for easypay option import
 *  
 * @package     charity
 * @version     v.1.0
 */

easyPayFormImport();

function easyPayFormImport() {

	$oldoption=get_option("easypay_form");
	
	if(empty($oldoption)){
		$data_file = CHY_THEME_URL.'/vendor/data/easypay-form-options.json';
		$get_data = wp_remote_get($data_file);

		if (is_wp_error($get_data))
			return false;

		$rawdata = isset($get_data['body']) ? $get_data['body'] : '';
		$options = json_decode($rawdata, true);

		if(!empty($options[0])){
                       $optionsCurrent=  str_replace("http://theemon.com/c/charity-wp/PlaceHolder", site_url(), $options[0]);
			update_option("easypay_form", $optionsCurrent);
		}
	}
}

//Import User Meta
//[easyPayFormExport()]; //json value

function easyPayFormExport() {

	$options=get_option("easypay_form");

    return json_encode($options);
}


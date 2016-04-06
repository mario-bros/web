<?php
/**
 * Charity- Vender for easypay builder import import
 *  
 * @package     charity
 * @version     v.1.0
 */

easyPayBuilderImport();

function easyPayBuilderImport() {

	$oldoption=get_option("easypay_form_builder");
	
	if(empty($oldoption)){
		$data_file = CHY_THEME_URL.'/vendor/data/easypay-builder-options.json';
		$get_data = wp_remote_get($data_file);

		if (is_wp_error($get_data))
			return false;

		$rawdata = isset($get_data['body']) ? $get_data['body'] : '';
		$options = json_decode($rawdata, true);

		if(!empty($options[0])){
                        $optionsCurrent=  str_replace("http://theemon.com/c/charity-wp/PlaceHolder", site_url(), $options[0]);
			update_option("easypay_form_builder", $optionsCurrent);
		}
	}
}

//Import User Meta
//[easyPayBuilderExport()]; //json value

function easyPayBuilderExport() {

	$options=get_option("easypay_form_builder");

    return json_encode($options);
}


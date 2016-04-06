<?php
/**
 * Charity- Vender for lib (One Click Install) activation
 *  
 * @package     charity
 * @version     v.1.0
 */

charityOptionImport();

function charityOptionImport() {

	$oldoption=get_option("vpt_option");
	
	if(empty($oldoption)){
		$data_file = CHY_THEME_URL.'/vendor/data/charity-options.json';
		$get_data = wp_remote_get($data_file);

		if (is_wp_error($get_data))
			return false;

		$rawdata = isset($get_data['body']) ? $get_data['body'] : '';
		$options = json_decode($rawdata, true);

		if(!empty($options[0])){
			update_option("vpt_option", $options[0]);
		}
	}
}

//Import User Meta
//[charityOptionExport()]; //json value

function charityOptionExport() {

	$options=get_option("vpt_option");

    return json_encode($options);
}


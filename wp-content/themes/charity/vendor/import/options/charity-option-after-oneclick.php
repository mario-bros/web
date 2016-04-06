<?php
/**
 * Charity- Vender for lib (One Click Install oneclick) activation
 *  
 * @package     charity
 * @version     v.1.0
 */

charityOptionImportOneClick();

function charityOptionImportOneClick() {

	//$oldoption=get_option("vpt_option");
	
	//if(empty($oldoption)){
		$data_file = CHY_THEME_URL.'/vendor/data/charity-options-oneclick.json';
		$get_data = wp_remote_get($data_file);

		if (is_wp_error($get_data))
			return false;

		$rawdata = isset($get_data['body']) ? $get_data['body'] : '';
		$options = json_decode($rawdata, true);

		if(!empty($options[0])){
                        $optionsCurrent=  str_replace("http://theemon.com/c/charity-wp/PlaceHolder", site_url(), $options[0]);
			update_option("vpt_option", $optionsCurrent);
		}
	//}
                charityHomeLayoutSetUp();
}

function charityHomeLayoutSetUp() {
    $home_1_select = vp_option("vpt_option.charity_home_1_select");
    $home_2_select = vp_option("vpt_option.charity_home_2_select");
    $home_3_select = vp_option("vpt_option.charity_home_3_select");
    $home_shoplanding = vp_option("vpt_option.charity_home_shoplanding");
    $home_layout = vp_option("vpt_option.home_layout");

    switch ($home_layout) {
        case "one":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_1_select);
            break;
        case "two":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_2_select);
            break;
        case "three":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_3_select);
            break;
        case "shoplanding":
            update_option("show_on_front", "page");
            update_option('page_on_front', $home_shoplanding);
            break;
        default :
            $front_page_id = get_option('page_on_front');
            $templatePage = get_post_meta($front_page_id, "_wp_page_template", true);

            if ($templatePage == "charity-home-one.php" || $templatePage == "charity-home-two.php" || $templatePage == "charity-home-three.php" || $templatePage == "charity-shop-landing.php") {
                update_option("show_on_front", "posts");
                update_option('page_on_front', "");
            }
            break;
    }
}

//Import User Meta
//[charityOptionExport()]; //json value

/*function charityOptionExport() {

	$options=get_option("vpt_option");

    return json_encode($options);
}
*/

<?php

/**
 * Charity- Vender for lib (One Click Install) activation
 *  
 * @package     charity
 * @version     v.1.0
 */

charityWooCommerceImportOption();

function charityWooCommerceImportOption() {

    $data_file = CHY_THEME_URL.'/vendor/data/charity-woocommerce-option.json';
    $get_data = wp_remote_get($data_file);

    if (is_wp_error($get_data))
        return false;

    $rawdata = isset($get_data['body']) ? $get_data['body'] : '';
    $options = json_decode($rawdata, true);

        if (isset($options[0])):
            foreach ($options[0] as $key => $val):
                update_option($key, $val);
            endforeach;
        endif;
}


/*settings import*/
/*global $wpdb;
 $woooption=$wpdb->get_results( $wpdb->prepare("SELECT * FROM  wp_options WHERE option_name LIKE %s "), "'%woocommerce%'" );
 
 $myargs=array();
 if(count($woooption)> 0){
     foreach($woooption as $woo ){
         $data=@unserialize($woo->option_value);
         $myargs[$woo->option_name]= ($data !== false)? $data : $woo->option_value ;
     }
 }
 
$woojson=  json_encode($myargs); 
print_r( $woojson);*/
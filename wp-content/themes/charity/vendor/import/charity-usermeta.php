<?php

/**
 * Charity- Vender for lib (One Click Install) activation
 *  
 * @package     charity
 * @version     v.1.0
 */

charityUserImportMeta();

function charityUserImportMeta() {

    $data_file = CHY_THEME_URL.'/vendor/data/charity-usermeta.json';
    $get_data = wp_remote_get($data_file);

    if (is_wp_error($get_data))
        return false;

    $rawdata = isset($get_data['body']) ? $get_data['body'] : '';
    $options = json_decode($rawdata, true);

    $args = array(
        'orderby' => 'ID',
        'order' => 'ASC',
        'count_total' => false,
        'fields' => 'all',
        'who' => '',
    );

    $teams = get_users($args);
    $authors = array();
    $flag=true;
    foreach ($teams as $team):
        
        if($flag){
            userFistLastName($team->ID);
            $flag=false;
        }
        
        if (isset($options[0][$team->user_login])):
            foreach ($options[0][$team->user_login] as $key => $val):
                update_user_meta($team->ID, $key, $val);
            endforeach;
        endif;
    endforeach;
}

function userFistLastName($user_id){
    $firstname=get_user_meta($user_id, 'first_name', true);
    if($firstname == ""){
        update_user_meta($user_id, 'first_name', 'Jhony');
        update_user_meta($user_id, 'last_name', 'Waker');
    }
}
//Import User Meta
//[charityUserExportMeta()]; //json value

function charityUserExportMeta() {
    $args = array(
        'orderby' => 'ID',
        'order' => 'ASC',
        'count_total' => false,
        'fields' => 'all',
        'who' => '',
    );


    $teams = get_users($args);
    $authors = array();
    foreach ($teams as $team):
        $authors[$team->user_login] = charityUserExportMetaVal($team->ID);
    endforeach;

    return json_encode($authors);
}

function charityUserExportMetaVal($team_id) {
    $args = array(
        'image' => '',
        'image_thumb' => '',
        'image_medium' => '',
        'image' => '',
        'facebook' => '',
        'twitter' => '',
        'dribbble' => '',
        'pinterest' => '',
        'instagram' => '',
        'google_plus' => ''
    );

    $metas = array();

    foreach ($args as $key => $val):
        $metas[$key] = get_the_author_meta($key, $team_id);
    endforeach;

    return $metas;
}

<?php

/**
 * Charity- One Click Install
 * using wordpress importer plugin
 *  
 * @package     charity
 * @version     v.1.0
 */
class CharityOneclickImportStart extends WP_Import {

    function check() {
        $locations = array(); //get_theme_mod('nav_menu_locations'); 
        $menus = wp_get_nav_menus();
        if ($menus) {
            foreach ($menus as $menu) {
                $locations[str_replace("-", "_", $menu->slug)] = $menu->term_id;
            }
        }

        set_theme_mod('nav_menu_locations', $locations); // set menus to locations    
        
    }

    function deleteOldData() {
        global $wpdb;
        wp_delete_post(1);
        wp_delete_post(2);

        $wpdb->delete($wpdb->posts, array('post_type' => 'nav_menu_item'));
    }
    
}

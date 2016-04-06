<?php

/**
 * Charity - OneClick Install
 *  
 * @package     charity
 * @version     v.1.0
 */


function charityStartOneClick(){
    $start=new CharityOneclickInstall();
}
add_action('wp_ajax_charity_oneclick', 'charityStartOneClick');

class CharityOneclickInstall {
    public function __construct() {
        $this->importInit();
    }
    
    public function importInit(){
        $this->loadImporter();
       if (class_exists('WP_Import')) {
            $this->importStart();
        }
        die();
    }
    
    public function importStart(){
        
        require_once get_template_directory()."/vendor/import/oneclick/import-start.php";
        $themeXML=get_template_directory()."/vendor/data/charity-theme.xml";
        
        $importSatrt = new CharityOneclickImportStart();
        $importSatrt->fetch_attachments = true;
        $importSatrt->deleteOldData();
        $importSatrt->import($themeXML);
        
        $importSatrt->check();
        
        // theme option after oneclick 
        $this->optionReady();
        
        //woocommerce
        $this->woocommerceOptionReady();
        
        //widgets Ready
        $this->widgetsReady();
        
        //import user
        $this->importUserMeta();

        //revolution slider
        $this->revSliderCharityDataImport();
               
    }
    public function optionReady() {
        require_once CHY_THEME_DIR . "/vendor/import/options/charity-option-after-oneclick.php";
    }
    public function woocommerceOptionReady() {
        require_once CHY_THEME_DIR . "/vendor/import/options/charity-woocommerce-option.php";
    }
    
   public function widgetsReady(){
        require_once CHY_THEME_DIR."/vendor/import/widgets-import/widgets-import.php";
    }
    
    public function importUserMeta(){
        require_once  CHY_THEME_DIR."/vendor/import/charity-usermeta.php";
    }
    
    public function revSliderCharityDataImport() {
    	if (class_exists('RevSlider')) {
    		$slider = new RevSlider();
    		$response = $slider->importSliderFromPost(true, true, CHY_THEME_DIR . '/vendor/data/home_slder2.zip');
    		$response = $slider->importSliderFromPost(true, true, CHY_THEME_DIR . '/vendor/data/home_slder3.zip');
    		$response = $slider->importSliderFromPost(true, true, CHY_THEME_DIR . '/vendor/data/home_slder1.zip');
    		$response = $slider->importSliderFromPost(true, true, CHY_THEME_DIR . '/vendor/data/ShopLandingSlider.zip');
    	}
    }
    
    public function loadImporter() {
        //define 
        if (!defined('WP_LOAD_IMPORTERS')){
            define('WP_LOAD_IMPORTERS', true);
        }    
        
        //init wp importer
        require_once ABSPATH . 'wp-admin/includes/import.php';
        if (!class_exists('WP_Importer')) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if (file_exists($class_wp_importer)) {
                require $class_wp_importer;
            }
        }

        //wp import class
        if (!class_exists('WP_Import')) {
            $class_wp_importer = get_template_directory()."/vendor/import/oneclick/wordpress-importer.php";
            
            if (file_exists($class_wp_importer)){
                require $class_wp_importer;
            }
        }

    }

}


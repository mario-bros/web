<?php

/**
 * Charity - Vender for lib (Widgets on ready) activation
 *  
 * @package     charity
 * @version     v.1.0
 */
 if (!class_exists('Widget_Data')) {
    require_once 'class-widget-data.php';
 }
class CharityWidgetsImport extends Widget_Data {
    /**
     * initialize
     */
    public static function charityinit() {
        self::charity_ajax_import_widget_data();
    }

    /**
     * Parse JSON import file and load
     */
    public static function charity_ajax_import_widget_data() {
        $response = array(
            'what' => 'widget_import_export',
            'action' => 'import_submit'
        );

        $widgets = isset($_POST['widgets']) ? $_POST['widgets'] : false;
        $import_file = get_template_directory() . '/vendor/data/charity-widgets.json';
        
      //  global  $wp_filesystem;
       // $fileGetContent=$wp_filesystem->get_contents($import_file);
        //$json_data=$fileGetContent;
        $json_data = file_get_contents($import_file);
        $json_data = json_decode($json_data, true);
        

        $sidebar_data = $json_data[0];
        $widgets = self::select_all_widgets($sidebar_data);
        
        $widget_data = $json_data[1];
        foreach ($sidebar_data as $title => $sidebar) {
            $count = count($sidebar);
            for ($i = 0; $i < $count; $i++) {
                $widget = array();
                $widget['type'] = trim(substr($sidebar[$i], 0, strrpos($sidebar[$i], '-')));
                $widget['type-index'] = trim(substr($sidebar[$i], strrpos($sidebar[$i], '-') + 1));
                if (!isset($widgets[$widget['type']][$widget['type-index']])) {
                    unset($sidebar_data[$title][$i]);
                }
            }
            
            $sidebar_data[$title] = array_values($sidebar_data[$title]);
        }

        foreach ($widgets as $widget_title => $widget_value) {
            foreach ($widget_value as $widget_key => $widget_value) {
                $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
            }
        }

        $sidebar_data = array(array_filter($sidebar_data), $widgets);
        
        if (!empty($sidebar_data[0])):
        
            /**
             * Widgets Ids
             */
            $old_sidebar = array(
                'footer-one-sidebar-one' => array(), 
                'footer-one-sidebar-two' => array(), 
                'footer-one-sidebar-three' => array(), 
                'footer-two-sidebar-one' => array(), 
                'footer-two-sidebar-two' => array(),
                'footer-two-sidebar-three' => array(),
                'footer-two-sidebar-four' => array(),
                'footer-three-sidebar-one' => array(),
                
                'footer-three-sidebar-two' => array(),
                'footer-three-sidebar-three' => array(),
                'causes-wisget-section' => array(),
                'charity-shop' => array()
            );
            update_option('sidebars_widgets', $old_sidebar);
        endif;
        $response['id'] = ( parent::parse_import_data($sidebar_data) ) ? true : new WP_Error('widget_import_submit', 'Unknown Error');
        
    }

    public static function select_all_widgets($sidebar_data) {
        $widgets = array();
        if (isset($sidebar_data)) :
            
           // print_r(parent::order_sidebar_widgets($sidebar_data) );
            foreach (parent::order_sidebar_widgets($sidebar_data) as $sidebar_name => $widget_list) :
                //print_r($widget_list);
                if (count($widget_list) == 0) {
                    continue;
                }
                $sidebar_info = parent::get_sidebar_info($sidebar_name);
                if ($sidebar_info) :
                    foreach ($widget_list as $widget) :
                        $widget_type = trim(substr($widget, 0, strrpos($widget, '-')));
                        $widget_type_index = trim(substr($widget, strrpos($widget, '-') + 1));
                        $widgets[$widget_type][$widget_type_index] = 'on';//array($widget_type_index => 'on');
                        //$widgets[$widget_type] = array($widget_type_index => 'on');
                    endforeach;
                endif;
            endforeach;

        endif;
        return $widgets;
    }

}

CharityWidgetsImport::charityinit();

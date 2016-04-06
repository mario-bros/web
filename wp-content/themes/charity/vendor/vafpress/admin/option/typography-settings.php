<?php

return array(
    array(
        'type' => 'section',
        'title' => __('Typography Settings', 'charity'),
        'name' => 'typography-settings',
        'description' => __('Maintain Typography Settings To Run Theme As Per Need', 'charity'),
        'fields' => array(
        	
        		array(
        				'type' => 'color',
        				'name' => 'theme_color',
        				'label' => __('Select Theme Color', 'charity'),
        				'description' => __('Choose Theme Color That You Want.', 'charity'),
        				'default' => '#d6b014',
        				'format' => 'hex',
        		),
        		array(
        				'type' => 'select',
        				'name' => 'theme_fonts',
        				'label' => __('Select Theme Fonts', 'charity'),
        				'default' => '"Montserrat",sans-serif',
        				'items' => array(
        						array(
        								'value' => "'Montserrat',sans-serif",
        								'label' => __("'Montserrat',sans-serif", 'charity'),
        						),
        						array(
        								'value' => "'Open Sans', sans-serif",
        								'label' => __("'Open Sans', sans-serif", 'charity'),
        						),
        						array(
        								'value' => "'Libre Baskerville', sans-serif",
        								'label' => __("'Libre Baskerville', sans-serif", 'charity'),
        						),
        				),
        		),
        		array(
        				'type' => 'select',
        				'name' => 'sticky_header',
        				'label' => __('Sticky Header', 'charity'),
        				'default' => 'sticky-no',
        				'items' => array(
        						array(
        								'value' => 'sticky-no',
        								'label' => __('Normal', 'charity'),
        						),
        						array(
        								'value' => 'sticky-yes',
        								'label' => __('Intelligent', 'charity'),
        						),
        				),
        		),
        		array(
        				'type' => 'select',
        				'name' => 'page_layout',
        				'label' => __('Page Layout', 'charity'),
        				'default' => 'full-width',
        				'items' => array(
        						array(
        								'value' => 'full-width',
        								'label' => __('Full Width', 'charity'),
        						),
        						array(
        								'value' => 'boxed',
        								'label' => __('Boxed', 'charity'),
        						),
        				),
        		),
        )
    		)
		);
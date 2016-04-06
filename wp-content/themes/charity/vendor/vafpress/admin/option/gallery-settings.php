<?php

return array(
    array(
        'type' => 'section',
        'title' => __('Gallery Layout', 'charity'),
        'name' => 'gallery_layout_settings',
        'description' => __('Gallery Layout Settings With all Possible Options To Perform Changes', 'charity'),
        'fields' => array(
           
        		array(
        				'type' => 'radioimage',
        				'name' => 'charity-gallery-layout',
        				'label' => __('Choose Any Gallery Layout Here', 'charity'),
        				'description' => __('Select Here Gallery Layout', 'charity'),
        				'items' => array(
        						array(
        								'value' => 'col-sm-6',
        								'label' => __('Two Column', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/galleryTwoColumn.png',
        						),
        						array(
        								'value' => 'col-sm-4',
        								'label' => __('Three Column', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/galleryThreeColumn.png',
        						),
        				),
        		),
        		
            	),
			)
		);
			
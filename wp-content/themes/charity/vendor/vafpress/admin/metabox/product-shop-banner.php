<?php

return array(
    'id' => 'product_shop_banner',
    'types' => array("product"),
    'title' => __('Product Shop Banner', 'charity'),
    'priority' => 'high',
    'template' => array(
    	array(
            'type' => 'textbox',
            'name' => 'banner_title',
            'label' => __('Banner Title', 'charity'),
            'description' => __('Enter Here Banner Title', 'charity'),
        ),
    		array(
    				'type' => 'textbox',
    				'name' => 'banner_sub_title',
    				'label' => __('Banner Sub Title', 'charity'),
    				'description' => __('Enter Here Banner Sub Title', 'charity'),
    		),
    		array(
            'type' => 'textarea',
            'name' => 'banner_desc',
            'label' => __('Banner Description', 'charity'),
            'description' => __('Enter Here Banner Description', 'charity'),
        ),
    ),
);

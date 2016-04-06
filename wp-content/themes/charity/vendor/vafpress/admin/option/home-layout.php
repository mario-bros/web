<?php

return array(
    array(
        'type' => 'section',
        'title' => __('HOME LAYOUT', 'charity'),
        'name' => 'home_layout_settings',
        'description' => __('Manage Home Page Layout Settings Here According To Choice', 'charity'),
        'fields' => array(
            array(
                'type' => 'select',
                'name' => 'charity_home_1_select',
                'label' => __('Select Home Page 1', 'charity'),
                'description' => __('Select page for home 1', 'charity'),
                'items' => array(
                    'data' => array(
                        array(
                            'source' => 'function',
                            'value' => 'vp_get_pages',
                        ),
                    ),
                ),
            ),
            array(
                'type' => 'select',
                'name' => 'charity_home_2_select',
                'label' => __('Select Home Page 2', 'charity'),
                'description' => __('Select page for home 2', 'charity'),
                'items' => array(
                    'data' => array(
                        array(
                            'source' => 'function',
                            'value' => 'vp_get_pages',
                        ),
                    ),
                ),
            ),
            array(
                'type' => 'select',
                'name' => 'charity_home_3_select',
                'label' => __('Select Home Page 3', 'charity'),
                'description' => __('Select page for home 3', 'charity'),
                'items' => array(
                    'data' => array(
                        array(
                            'source' => 'function',
                            'value' => 'vp_get_pages',
                        ),
                    ),
                ),
            ),
        		array(
        				'type' => 'select',
        				'name' => 'charity_home_shoplanding',
        				'label' => __('Select Home Shop Landing', 'charity'),
        				'description' => __('Select page for home Shop Landing', 'charity'),
        				'items' => array(
        						'data' => array(
        								array(
        										'source' => 'function',
        										'value' => 'vp_get_pages',
        								),
        						),
        				),
        		),
        		        		
            array(
                'type' => 'radioimage',
                'name' => 'home_layout',
                'label' => __('Select Homepage Layout Here', 'charity'),
                'description' => __('Select Homepage Layout Here', 'charity'),
                'items' => array(
                    array(
                        'value' => 'default',
                        'label' => __('Blog Layout', 'charity'),
                        'img' => CHY_THEME_URL . '/assets/img/blogLayout.jpeg',
                    ),
                    array(
                        'value' => 'one',
                        'label' => __('Home 1', 'charity'),
                        'img' => CHY_THEME_URL . '/assets/img/homeOne.png',
                    ),
                    array(
                        'value' => 'two',
                        'label' => __('Home 2', 'charity'),
                        'img' => CHY_THEME_URL . '/assets/img/homeTwo.png',
                    ),
                    array(
                        'value' => 'three',
                        'label' => __('Home 3', 'charity'),
                        'img' => CHY_THEME_URL . '/assets/img/homeThree.png',
                    ),
                		array(
                				'value' => 'shoplanding',
                				'label' => __('Shop Landing', 'charity'),
                				'img' => CHY_THEME_URL . '/assets/img/shop-landing.png',
                		),
                ),
            ),
        ),
    )
);


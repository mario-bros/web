<?php

return array(
    array(
        'type' => 'section',
        'title' => __('Header Layout', 'charity'),
        'name' => 'header-settings',
        'description' => __('Change Header Settings as per preference', 'charity'),
        'fields' => array(
        		
        		array(
        				'type' => 'radioimage',
        				'name' => 'header_layout',
        				'label' => __('Pick Desired Header Layout From List', 'charity'),
        				'description' => __('Select Here Header Layout', 'charity'),
        				'items' => array(
        						array(
        								'value' => 'one',
        								'label' => __('Header 1', 'charity'),
        								//'img' => CHY_THEME_URL.'/assets/img/charity-home1-icon.jpg',
        								'img' => CHY_THEME_URL.'/assets/img/headerOne.png',
        						),
        						array(
        								'value' => 'two',
        								'label' => __('Header 2', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/headerTwo.png',//charity-home2-icon.jpg
        						),
        						array(
        								'value' => 'three',
        								'label' => __('Header 3', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/headerThree.png',//charity-home3-icon.jpg
        						),
        						array(
        								'value' => 'shop-header',
        								'label' => __('Shop Header', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/shop-header.png',
        						),
        				),
        		),
        		
        		)
        )
		);
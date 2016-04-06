<?php

return array(
    array(
        'type' => 'section',
        'title' => __('Footer Layout', 'charity'),
        'name' => 'footer-settings',
        'description' => __('Make Changes In Footer Settings As Per Desire', 'charity'),
        'fields' => array(
        		
        		array(
        				'type' => 'radioimage',
        				'name' => 'footer_layout',
        				'label' => __('Choose Footer Layout Here', 'charity'),
        				'description' => __('Select Here Footer Layout', 'charity'),
        				'items' => array(
        						array(
        								'value' => 'one',
        								'label' => __('Footer 1', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/footerOne.png',//charity-footer1-icon.jpg
        						),
        						array(
        								'value' => 'two',
        								'label' => __('Footer 2', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/footerTwo.png',//charity-footer2-icon.jpg
        						),
        						array(
        								'value' => 'three',
        								'label' => __('Footer 3', 'charity'),
        								'img' => CHY_THEME_URL.'/assets/img/footerThree.png',//charity-footer3-icon.jpg
        						),
        				),
        		),
        		
        		)
        )
		);
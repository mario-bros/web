<?php

return array(
    'id' => 'causes-short-code',
    'types' => array('charity-causes'),
    'title' => __('Causes Easy Step Shortcode', 'charity'),
    'priority' => 'high',
    'template' => array(
    		array(
    				'type' => 'textarea',
    				'name' =>'causesshortcode',
    				'label' => __('Causes Easy Step Short Code', 'charity'),
    				'description' => __('Enter Here Causes Easy Step Short Code', 'charity'),
    		),
    ),
);

/**
 * EOF
 */

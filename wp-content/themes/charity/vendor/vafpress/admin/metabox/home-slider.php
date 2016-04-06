<?php

return array(
    'id' => 'homeslider',
    'types' => array('post','page'),
    'title' => __('Home Slider Shortcode', 'charity'),
    'priority' => 'high',
    'template' => array(
    		array(
    				'type' => 'textarea',
    				'name' => 'slidershortcode',
    				'label' => __('Short Code', 'charity'),
    				'description' => __('Enter Here Revolution Slider Short Code', 'charity'),
    		),
    ),
);

/**
 * EOF
 */

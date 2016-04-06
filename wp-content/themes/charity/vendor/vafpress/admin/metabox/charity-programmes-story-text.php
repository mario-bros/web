<?php

return array(
    'id' => 'our_programmes_text',
    'types' => array("post", "page", 'charity_programmes'),
    'title' => __('Our Programmes Sammury Text', 'charity'),
    'priority' => 'high',
    'template' => array(

                array(
                    'type' => 'textarea',
                    'name' => 'our_programmes_text_sammury',
                    'label' => __('Our Programmes Summary Text', 'charity'),
                    'description' => __('Enter Here Our Programmes Sammury text', 'charity'),
                ),
    		array(
    				'type' => 'textarea',
    				'name' => 'our_programmes_text',
    				'label' => __('Our Programmes Text', 'charity'),
    				'description' => __('Enter Here Our Programmes Text', 'charity'),
    		),
  
    ),
);

/**
 * EOF
 */

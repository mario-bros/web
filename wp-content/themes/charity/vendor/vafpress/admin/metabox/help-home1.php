<?php

return array(
    'id' => 'helpicon',
    'types' => array('post','page'),
    'title' => __('How Can Help', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'group',
            'repeating' => true,
            'name' => 'helpicon_group',
            'title' => __('Help Icon', 'charity'),
            'fields' => array(
                array(
                    'type' => 'upload',
                    'name' => 'helpicon',
                    'label' => __('Fontawesome Icon', 'charity'),
                    'description' => __('Chooser icon', 'charity'),
                ),
            		array(
            				'type' => 'textbox',
            				'name' => 'helptitle',
            				'label' => __('Help Title', 'charity'),
            				'description' => __('Enter Text Here', 'charity'),
            		),
            		array(
            				'type' => 'textarea',
            				'name' => 'helpcontent',
            				'label' => __('Help Content', 'charity'),
            				'description' => __('Enter Content Here', 'charity'),
            		),
            ),
        ),
    		array(
    				'type' => 'upload',
    				'name' => 'help-video-image',
    				'label' => __('Video Image', 'charity'),
    				'description' => __('Upload Here Video Image', 'charity'),
    		),
    		
    		array(
    				'type' => 'textarea',
    				'name' => 'help-video',
    				'label' => __('Video', 'charity'),
    				'description' => __('Enter Here video iframe', 'charity'),
    		),
    		array(
    				'type' => 'textarea',
    				'name' => 'shortdescription',
    				'label' => __('Description for home 3', 'charity'),
    				'description' => __('Enter Here short description for home 3', 'charity'),
    		),
    ),
);

/**
 * EOF
 */

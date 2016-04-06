<?php

return array(
    'id' => 'usericon',
    'types' => array('post','page'),
    'title' => __('Get Involved User Image', 'charity'),
    'priority' => 'high',
    'template' => array(
                array(
                    'type' => 'Upload',
                    'name' => 'usericon',
                    'label' => __('User Image', 'charity'),
                    'description' => __('Upload User Image', 'charity'),
                ),

    ),
);

/**
 * EOF
 */

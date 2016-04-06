<?php

return array(
    'id' => 'our_programmes',
    'types' => array("post", "page", 'charity_programmes'),
    'title' => __('Our programmes Image', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'group',
            'repeating' => true,
            'name' => 'our_programmes_group',
            'title' => __('Our programmes Images', 'charity'),
            'fields' => array(
                array(
                    'type' => 'upload',
                    'name' => 'our_programmes_image',
                    'label' => __('Image', 'charity'),
                    'description' => __('Upload Here Our programmes Image', 'charity'),
                ),
            ),
        ),
    ),
);

/**
 * EOF
 */

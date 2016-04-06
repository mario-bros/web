<?php

return array(
    'id' => 'gallery_meta',
    'types' => array('post'),
    'title' => __('Gallery Post Format Fields', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'group',
            'repeating' => true,
            'name' => 'gallery_group',
            'title' => __('Gallery Images', 'charity'),
            'fields' => array(
                array(
                    'type' => 'upload',
                    'name' => 'gallery_image',
                    'label' => __('Image', 'charity'),
                    'description' => __('Upload Here Gallery Image', 'charity'),
                ),
            ),
        ),
    ),
);

/**
 * EOF
 */

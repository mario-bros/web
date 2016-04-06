<?php

return array(
    'id' => 'our_story',
    'types' => array("post", "page", "charity_our_story"),
    'title' => __('Our story Image', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'group',
            'repeating' => true,
            'name' => 'our_story_group',
            'title' => __('Our Story Images', 'charity'),
            'fields' => array(
                array(
                    'type' => 'upload',
                    'name' => 'Our_story_image',
                    'label' => __('Image', 'charity'),
                    'description' => __('Upload Here Our Story Image', 'charity'),
                ),
            ),
        ),
    ),
);

/**
 * EOF
 */

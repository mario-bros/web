<?php

return array(
    array(
        'type' => 'section',
        'title' => __('Coming Soon', 'charity'),
        'name' => 'coming_soon_settings',
        'description' => __('Change Coming Soon Page Settings From Here Accordingly', 'charity'),
        'fields' => array(
            array(
                'type' => 'upload',
                'name' => 'coming_soon_page_logo',
                'label' => __('Upload Logo', 'charity'),
                'description' => __('Upload Any Logo for Your Site', 'charity'),
            ),
            array(
                'type' => 'upload',
                'name' => 'coming_soon_page_bg',
                'label' => __('Background Image', 'charity'),
                'description' => __('Insert Page Background Image from Here ', 'charity'),
            ),
            array(
                'type' => 'date',
                'name' => 'coming_soon_date',
                'label' => __('Select Date', 'charity'),
                'description' => __('Choose Particular Date Here to Set', 'charity'),
                'format' => 'dd-mm-yy',
            ),
            array(
                'type' => 'textbox',
                'name' => 'coming_soon_newsletter',
                'label' => __('Newsletter Shortcode', 'charity'),
                'description' => __('This section is to include Newsletter Shortcode', 'charity'),
            ),
            array(
                'type' => 'toggle',
                'name' => 'coming_soon_mode',
                'label' => __('Coming Soon Mode', 'charity'),
                'description' => __('Set Coming Soon Mode In On or Off mode', 'charity'),
            ),
            array(
                'type' => 'select',
                'name' => 'coming_soon_page',
                'label' => __('Coming Soon Page', 'charity'),
                'description' => __('Select page for coming soon', 'charity'),
                'items' => array(
                    'data' => array(
                        array(
                            'source' => 'function',
                            'value' => 'vp_get_pages',
                        ),
                    ),
                ),
                'dependency' => array(
                    'field' => 'coming_soon_mode',
                    'function' => 'vp_dep_boolean',
                ),
            ),
            array(
                'type' => 'textbox',
                'name' => 'coming_soon_copyright_text',
                'label' => __('Copyright Text', 'charity'),
                'description' => __('Enter Copyright Text for Footer', 'charity'),
            ),
        )
    ),
);

<?php

return array(
    array(
        'type' => 'section',
        'title' => __('SOCIAL PROFILES', 'charity'),
        'name' => 'ch_social_settings',
        'description' => __('Manage All Your Social Profile Settings Easily', 'charity'),
        'fields' => array(
            array(
                'type' => 'textbox',
                'name' => 'charity-facebook',
                'label' => __('Facebook', 'charity'),
                'description' => __('Enter Link of Facebook Profile.', 'charity'),
                'validation' => 'url',
            ),
            array(
                'type' => 'textbox',
                'name' => 'charity-google-plus',
                'label' => __('Google Plus', 'charity'),
                'description' => __('Enter Google Plus Profile Link Here', 'charity'),
                'validation' => 'url',
            ),
        	array(
                'type' => 'textbox',
                'name' => 'charity-twitter',
                'label' => __('Twitter', 'charity'),
                'description' => __('Include Twitter Profile Link Here', 'charity'),
                'validation' => 'url',
            ),
            array(
                'type' => 'textbox',
                'name' => 'charity-linkedin',
                'label' => __('LinkedIn', 'charity'),
                'description' => __('Provide LinkedIn Profile Link Here', 'charity'),
                'validation' => 'url',
            ),
            array(
                'type' => 'textbox',
                'name' => 'charity-vimeo-square',
                'label' => __('Vimeo Square', 'charity'),
                'description' => __('Insert Vimeo Square Profile Link', 'charity'),
                'validation' => 'url',
            ),
        )
    ),
    /*array(
        'type' => 'sorter',
        'name' => 'auther_social_linlk',
        'max_selection' => 6,
        'label' => __('Blog auther social link', 'charity'),
        'description' => __('Select blog auther social link', 'charity'),
        'items' => array(
            'data' => array(
                array(
                    'source' => 'function',
                    'value' => 'charity_get_social_medias',
                ),
            ),
        ),
    ),
    array(
        'type' => 'sorter',
        'name' => 'site_social_link',
        'max_selection' => 5,
        'label' => __('Header/Footer social link', 'charity'),
        'description' => __('Select Header/Footer social link', 'charity'),
        'items' => array(
            'data' => array(
                array(
                    'source' => 'function',
                    'value' => 'charity_get_social_medias',
                ),
            ),
        ),
    ),
    array(
        'type' => 'sorter',
        'name' => 'ouer_team_social_link',
        'max_selection' => 5,
        'label' => __('Ouer team social link', 'charity'),
        'description' => __('Ouer team auther social link', 'charity'),
        'items' => array(
            'data' => array(
                array(
                    'source' => 'function',
                    'value' => 'charity_get_social_medias',
                ),
            ),
        ),
    )*/
);

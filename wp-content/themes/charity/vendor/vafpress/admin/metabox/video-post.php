<?php

return array(
    'id' => 'video_meta',
    'types' => array('post'),
    'title' => __('Video Post Format Fields', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'upload',
            'name' => 'video_image',
            'label' => __('Image', 'charity'),
            'description' => __('Upload Here Image For Video', 'charity'),
        ),
        array(
            'type' => 'textbox',
            'name' => 'charity_you_tube_url',
            'label' => __('YouTube video url', 'charity'),
            'description' => __('eg. '.esc_html('&lt;if'.'rame src="https://www.youtube.com/embed/G8o8u7OtuZY"&gt;&lt;/if'.'rame&gt;'), 'charity'),
        ),
        array(
            'type' => 'textbox',
            'name' => 'charity_viemo_url',
            'label' => __('Viemo video url', 'charity'),
            'description' => __('eg.  https://vimeo.com/17147778  ', 'charity'),
        ),
        array(
            'type' => 'notebox',
            'name' => 'nb_or_post_format_video',
            'label' => __('Enter only Youtube video play url OR Vimeo video play url', 'charity'),
            'description' => '',
            'status' => 'info',
        ),
    ),
);


<?php

return array(
    'id' => 'cahrity-meta-type-settings',
    'types' => array('charity-gallery', 'charity-portfolio'),
    'title' => __('Metabox Type Settings', 'charity'),
    'priority' => 'high',
    'template' => array(
    		array(
    				'type' => 'radiobutton',
    				'name' => 'choose-meta-type',
    				'label' => __('Choose Type', 'charity'),
    				'items' => array(
    						array(
    								'value' => 'image',
    								'label' => __('Image/Gallery', 'charity'),
    						),
    						array(
    								'value' => 'video',
    								'label' => __('Video', 'charity'),
    						),
    				)
    		),
    		array(
    				'type' => 'group',
    				'repeating' => true,
    				'name' => 'charity-meta-images',
    				'title' => __('Gallery/Images Settings', 'charity'),
    				'fields' => array(
    						array(
    								'type' => 'upload',
    								'name' => 'upload-gallery-or-image',
    								'label' => __('Image', 'charity'),
    								'description' => __('Upload Here Gallery Image', 'charity'),
    						),
    				),
    				'dependency' => array(
    						'field'    => 'choose-meta-type',
    						'function' => 'vp_gallery_image'
    				)
    		),
    		
    		array(
    				'type'      => 'group',
    				'repeating' => false,
    				'sortable'  => false,
    				'name'      => 'charity-meta-video',
    				'title'     => __('Video Settings', 'charity'),
    				'fields'    => array(
					        array(
					            'type' => 'upload',
					            'name' => 'upload-video-image',
					            'label' => __('Image', 'charity'),
					            'description' => __('Upload Here Image For Video', 'charity'),
					        ),
					        array(
					            'type' => 'textbox',
					            'name' => 'txt-youtube-video-url',
					            'label' => __('YouTube video url', 'charity'),
					            'description' => __('eg. https://youtube.com/watch?v=jN5-3QToN1g', 'charity'),
					        ),
					        array(
					            'type' => 'textbox',
					            'name' => 'txt-vimeo-video-url',
					            'label' => __('Viemo video url', 'charity'),
					            'description' => __('eg.  https://vimeo.com/17147778  ', 'charity'),
					        )
    						
    				),	
    				'dependency' => array(
    						'field'    => 'choose-meta-type',
    						'function' => 'vp_gallery_video'
    				)
    		),
    		
    	
	),
		
);


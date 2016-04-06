<?php
return array (
		'id' => 'testimonial',
		'types' => array ("testimonial"),
		'title' => __ ( 'Testimonial', 'charity' ),
		'priority' => 'high',
		'template' => array (
				array(
		            'type' => 'textbox',
		            'name' => 'companey_name',
		            'label' => __('Companey Name', 'charity'),
		            'description' => __('Enter Here Companey Name and Designation', 'charity'),
		          ),
				array(
						'type' => 'textbox',
						'name' => 'urllink',
						'label' => __('Site Url', 'charity'),
						'description' => __('Enter Here Site Url', 'charity'),
				),
		) 
);

/**
 * EOF
 */

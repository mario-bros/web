<?php

return array(
    'id' => 'charity_testimonial_one',
    'types' => array('page'),
    'title' => __('Home One Testimonial background', 'charity'),
    'priority' => 'high',
    'template' => array(
    		array(
    				'type' => 'upload',
    				'name' => 'testimonial_bg',
    				'label' => __('Testimonial Background', 'charity'),
    				'description' => __('Enter Here Testimonial Background', 'charity'),
    		),
    ),
);

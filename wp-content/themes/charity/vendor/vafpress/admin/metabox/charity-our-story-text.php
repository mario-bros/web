<?php

return array(
    'id' => 'our_story_text',
    'types' => array("post", "page", 'charity_our_story'),
    'title' => __('Our story Sammury Text', 'charity'),
    'priority' => 'high',
    'template' => array(

                array(
                    'type' => 'textarea',
                    'name' => 'our_story_text_sammury',
                    'label' => __('Our Story Sammury Text', 'charity'),
                    'description' => __('Enter Here Our Story Sammury text', 'charity'),
                ),
    		array(
    				'type' => 'textarea',
    				'name' => 'our_story_text',
    				'label' => __('Our Story Text', 'charity'),
    				'description' => __('Enter Here Our Story Text', 'charity'),
    		),
  
    ),
);

/**
 * EOF
 */

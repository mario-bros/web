<?php

return array(
    'id' => 'charity-sub-page',
    'types' => array("post", "page"),
    'title' => __('Select sub page', 'charity'),
    'priority' => 'high',
    'template' => array(

               array(
			'type' => 'select',
			'name' => 'charity_ask_us',
			'label' => __('Select Page', 'charity'),
			'description' => __('Select field with WP Pages Data Source', 'charity'),
			'items' => array(
				'data' => array(
					array(
						'source' => 'function',
						'value'  => 'vp_get_pages',
					),
				),
			),
		),
    		
  
    ),
);

/**
 * EOF
 */

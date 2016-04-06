<?php

return array(
    array(
        'type' => 'section',
        'title' => __('CAUSES DONATE NOW SETTINGS', 'charity'),
        'name' => 'charity_causes_settings',
        'description' => __('All Options To Manage Causes Settings Are Available Here', 'charity'),
        'fields' => array(
				array(
        				'type' => 'select',
        				'name' => 'charity_global_donation_cause_id',
        				'label' => __('Global Donation Causes', 'charity'),
        				'description' => __('Select Global Donation Causes Here ', 'charity'),
        				'items' => array(
        						'data' => array(
        								array(
        										'source' => 'function',
        										'value' => 'vp_get_draft_causes',
        								),
        						),
        				)
        		),
        		array(
        				'type' => 'select',
        				'name' => 'charity_urgent_cause_id',
        				'label' => __('Urgent Causes Donation', 'charity'),
        				'description' => __('Pick Causes Here For Urgent Donation', 'charity'),
        				'items' => array(
        						'data' => array(
        								array(
        										'source' => 'function',
        										'value' => 'vp_get_all_causes',
        								),
        						),
        				)
        		),
        		
        )
    ),

);

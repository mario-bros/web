<?php

return array(
    array(
        'type' => 'section',
        'title' => __('Other Settings', 'charity'),
        'name' => 'general-other-settings',
        'description' => __('General Other Settings Can Be Maintained Easily', 'charity'),
        'fields' => array(
        		
        		
        		array(
        				'type' => 'textbox',
        				'name' => 'become_volunteer_btn_text',
        				'label' => __('Become Volunteer Text', 'charity'),
        				'description' => __('Enter Text For Become Volunteer Button', 'charity'),
        		),
        		array(
        				'type' => 'textbox',
        				'name' => 'become_volunteer_btn_link',
        				'label' => __('Become Volunteer Link', 'charity'),
        				'description' => __('Provide Become Volunteer Button Link In This Section', 'charity'),
        		),
        		array(
        				'type' => 'textbox',
        				'name' => 'welcome_tag_line',
        				'label' => __('Welcome Tag Line', 'charity'),
        				'description' => __('Add Welcome Tag Line Here', 'charity'),
        		),
        		array(
        				'type' => 'textbox',
        				'name' => 'info_mail_id',
        				'label' => __('Contact Mail ID', 'charity'),
        				'description' => __('Mention Contact Mail ID', 'charity'),
        		),
        		
        		
        		
        		
        		)
        ),
		
		array(
				'type' => 'section',
				'title' => __('Footer Settings', 'charity'),
				'name' => 'ch_footer_settings',
				'description' => __('Footer Settings', 'charity'),
				'fields' => array(
						array(
								'type' => 'textbox',
								'name' => 'copy_right',
								'label' => __('Copy Right Text', 'charity'),
								'description' => __('', 'charity'),
								'validation' => 'required',
						),
						array(
								'type' => 'wpeditor',
								'name' => 'reserve_textarea_error',
								'label' => '',
								'description' => '',
						),
		
				)
		),
		
		);
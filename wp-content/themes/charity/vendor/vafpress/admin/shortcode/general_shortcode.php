<?php

return array(

	'General Shortcode' => array(
		'elements' => array(

			'blockquote' => array(
				'title'   => __('BlockQuote', 'charity'),
				'code'    => '[blockquote][/blockquote]',
				'attributes' => array(
                    array(
                        'name' => 'blockquote_text_area',
                        'type' => 'textarea',
                        'label' => __('BlockQuote Text', 'charity'),
                    ),
                    ),
				
			),
			
			'blogrow' => array(
				'title'   => __('Blog Row', 'charity'),
				'code'    => '[blogrow][/blogrow]',
			),
			
			'blogTwocol' => array(
				'title'   => __('Blog Two Column', 'charity'),
				'code'    => '[blogTwocol][/blogTwocol]',
				'attributes' => array(
				array(
                        'name' => 'blog_two_column_heading',
                        'type' => 'textbox',
                        'label' => __('Blog Two Column Heading', 'charity'),
                    ),
                    array(
                        'name' => 'blog_two_column_text',
                        'type' => 'textarea',
                        'label' => __('Blog Two Column Text', 'charity'),
                    ),
				),
			),
			
			'portfolio_detail-summary' => array(
				'title'   => __('Portfolio Summary Strong Content', 'charity'),
				'code'    => '[portfolio-strong-text][/portfolio-strong-text]',
				'attributes' => array(
                    array(
                        'name' => 'portfolio_strong_text_area',
                        'type' => 'textarea',
                        'label' => __('Portfolio Strong Text', 'charity'),
                    ),
                    ),
				
			),		
			
			'List_Style_Yellow_Icon' => array(
				'title'   => __('List Style Yellow Icon', 'charity'),
				'code'    => '[list-style-yello-icon][/list-style-yello-icon]',
				'attributes' => array(
                    array(
                        'name' => 'list_yellow_icon_textarea',
                        'type' => 'textarea',
                        'label' => __('Yello Icon List (For List Item (li) Please Enter [list-item]List Content[/list-item])', 'charity'),
                    ),
                    ),
				
			),			
			
			'default_button' => array(
				'title'   => __('Default Button Style', 'charity'),
				'code'    => '[default-button-style][/default-button-style]',
				'attributes' => array(
                    array(
                        'name' => 'default_button_text',
                        'type' => 'textbox',
                        'label' => __('Enter Here Button text', 'charity'),
                    ),
                    array(
                        'name' => 'default_button_link',
                        'type' => 'textbox',
                        'label' => __('Enter Here Button Link', 'charity'),
                    ),
                    ),
				
			),	
			
			'contact_info' => array(
				'title'   => __('Contact Information', 'charity'),
				'code'    => '[contact-info][/contact-info]',
				'attributes' => array(
                    array(
                        'name' => 'address_first_line',
                        'type' => 'textbox',
                        'label' => __('Enter Here Address First Line', 'charity'),
                    ),
                    array(
                        'name' => 'address_second_line',
                        'type' => 'textbox',
                        'label' => __('Enter Here Address Second Line', 'charity'),
                    ),
                    array(
                        'name' => 'email_id',
                        'type' => 'textbox',
                        'label' => __('Enter Here Email Id', 'charity'),
                    ),
                    array(
                        'name' => 'telephone_no',
                        'type' => 'textbox',
                        'label' => __('Enter Here TelePhone No', 'charity'),
                    ),
                    array(
                        'name' => 'telephone_no_link',
                        'type' => 'textbox',
                        'label' => __('Enter Here TelePhone No For Link', 'charity'),
                    ),
                    array(
                        'name' => 'telephone_no_two',
                        'type' => 'textbox',
                        'label' => __('Enter Here TelePhone No', 'charity'),
                    ),
                    array(
                        'name' => 'telephone_no_two_link',
                        'type' => 'textbox',
                        'label' => __('Enter Here TelePhone No For Link', 'charity'),
                    ),					
                    ),
				
			),	

				'volunteer' => array(
						'title'   => __('Become Volunteer', 'charity'),
						'code'    => '[volunteer][/volunteer]',
						'attributes' => array(
								array(
										'name' => 'step',
										'type' => 'textbox',
										'label' => __('Enter Here Step Number', 'charity'),
								),
								array(
										'name' => 'title',
										'type' => 'textbox',
										'label' => __('Enter Here Headding Title', 'charity'),
								),
								array(
										'name' => 'content',
										'type' => 'textarea',
										'label' => __('Enter Here Text', 'charity'),
								),
								
						),
				
				),
				
				'Message' => array(
						'title'   => __('Message', 'charity'),
						'code'    => '[message][/message]',
						'attributes' => array(							
								array(
										'name' => 'content',
										'type' => 'textarea',
										'label' => __('Enter Here Text', 'charity'),
								),
				
						),
				
				),

				// List style short code start here
				'List style' => array(
						'title'   => __('List style', 'charity'),
						'code'    => '[list_style][/list_style]',
						'attributes' => array(
								array(
										'name' => 'style_type',
										'type' => 'select',
										'label' => __('List Style Type (For List Item (li) Please Enter [list_style]List Content[/list_style])', 'charity'),
										'items' => array(
												array(
														'value' => 'list-unstyled',
														'label' => 'Unordered'
												),
												array(
														'value' => 'ordered',
														'label' => 'Ordered'
												),
												array(
														'value' => 'list-arrowstyled',
														'label' => 'Arrow'
												),
												array(
														'value' => 'list-circlestyled',
														'label' => 'Circle'
												),
										),
								),
								array(
										'name' => 'content',
										'type' => 'textarea',
										'label' => __('Enter Here Text', 'charity'),
								),
				
						),
				
				),

				// Notification short code start here
				'Notification' => array(
						'title'   => __('Notification', 'charity'),
						'code'    => '[notification][/notification]',
						'attributes' => array(
								array(
										'name' => 'class',
										'type' => 'select',
										'label' => __('Select Notification', 'charity'),
										'items' => array(
												array(
														'value' => 'alert-danger',
														'label' => 'Danger/Warning'
												),
												array(
														'value' => 'alert-success',
														'label' => 'Success'
												),
												array(
														'value' => 'alert-info',
														'label' => 'Info'
												),
												array(
														'value' => 'alert-warning',
														'label' => 'Warning'
												),
												array(
														'value' => 'alert-default',
														'label' => 'Default'
												),
										),
								),
								array(
										'name' => 'content',
										'type' => 'textarea',
										'label' => __('Enter Here Text', 'charity'),
								),
				
						),
				
				),
				
				// charity slider range
				'slider_range' => array(
						'title'   => __('Slider Range', 'charity'),
						'code'    => '[sliderrange][/sliderrange]',
						'attributes' => array(
								array(
										'name' => 'type',
										'type' => 'select',
										'label' => __('Select Slider Type', 'charity'),
										'items' => array(
												array(
														'value' => '1',
														'label' => '1'
												),
												array(
														'value' => 'progress-bar-custom',
														'label' => '2'
												),
												array(
														'value' => 'progress-bar-striped',
														'label' => '3'
												),
												array(
														'value' => 'slide-ranger',
														'label' => '4'
												),
										),
								),
								array(
										'name' => 'slider_range',
										'type' => 'slider',
										'label' => __('Slider', 'charity'),
										'default' => '20',
										'min' => 5,
										'max' => 90,
								),
						),
				
				),
				// charity Tabs Short Code
				/*'tabs' => array(
						'title'   => __('Tabs', 'charity'),
						'code'    => '[tabs][/tabs]',
						'attributes' => array(
								array(
										'name' => 'tabs_heading',
										'type' => 'textbox',
										'label' => __('Charity Tabs Heading', 'charity'),
								),
								array(
										'name' => 'tabs_text',
										'type' => 'textarea',
										'label' => __('Charity Tabs Text', 'charity'),
								),
						),
				),
				*/

		// charity Table Short Code
		'Table' => array(
			'title'   => __('Table', 'charity'),
			'code'    => '[table][/table]',
			'attributes' => array(
			  array(
				'name' => 'class',
				'type' => 'select',
				'label' => __('Select Table Type', 'charity'),
				'items' => array(
				   array(
					'value' => 'table-bordered',
					'label' => 'Default'
				   ),
				   array(
					'value' => 'b-border',
					'label' => 'Basic'
				   ),
				   array(
					'value' => 'table-striped',
					'label' => 'Striped rows	'
				   ),
				   array(
					'value' => 'table-bordered table-striped',
					'label' => 'Bordered table'
					),
				  array(
					'value' => 'table-bordered tables-outline',
					'label' => 'Outline Table'
				      ),
				   ),
				 ),
			),
				
		),
				
				'tableRow' => array(
						'title'   => __('Table Row', 'charity'),
						'code'    => '[table-row][/table-row]',
						'attributes' => array(
								array(
										'name' => 'tableRow_text',
										'type' => 'textarea',
										'label' => __('Charity Table Text(For Table Header Put [table-heading]Table Heading[/table-heading] And For Table Data Put [table-data]Table Data[/table-data])', 'charity'),
								),
						),
				
				),
				
				
    // for join Today button
				'Charity Join Today Button' => array(
						'title'   => __('Join Today Button', 'charity'),
						'code'    => '[charity_join_today_button][/charity_join_today_button]',
						 
				),
				
		),
	),

		
		'Causes Donation Summary' => array(
				'elements' => array(
		
						'summary' => array(
								'title'   => __('Summary', 'charity'),
								'code'    => '[donationsummary][/donationsummary]',
								'attributes' => array(
										array(
												'name' => 'donation_summary_text_area',
												'type' => 'textarea',
												'label' => __('Donation Summary Text', 'charity'),
										),
								),
		
						),																									
				),
		),
		
		'Causes Donation Bullet Content' => array(
		 'elements' => array(
		 'bulletcontent' => array(
		 'title'   => __('Bullet Content', 'charity'),
		 'code'    => '[donationbulletcontent][/donationbulletcontent]',
		 'attributes' => array(
		 array(
		 'name' => 'donation_bullet_content',
		 'type' => 'wpeditor',
		 		'use_external_plugins' => 1,
		 		'disabled_externals_plugins' => 'vp_sc_button',
		 		'disabled_internals_plugins' => '',
		 'label' => __('Donation Bullet Content', 'charity'),
		 ),
		 ),
		 	
		 ),
		 		
		 		'Charity Global Donation Button' => array(
		 				'title'   => __('Global Donation Button', 'charity'),
		 				'code'    => '[charity_global_donation_button][/charity_global_donation_button]',
		 		
		 		),
		 		
		 		'Causes Donation Easy Step' => array(	 				
		 				'title'   => __('Causes Donation Easy Step', 'charity'),
		 				'code'    => '[causes_donation_easy_step][/causes_donation_easy_step]',
		 				'attributes' => array(
		 						array(
		 								'name' => 'style_type',
		 								'type' => 'select',
		 								'label' => __('Select Step Number', 'charity'),
		 								'items' => array(
		 										array(
		 												'value' => '1',
		 												'label' => 'Step One'
		 										),
		 										array(
		 												'value' => '2',
		 												'label' => 'Step Two'
		 										),
		 										array(
		 												'value' => '3',
		 												'label' => 'Step Three'
		 										),
		 								),
		 						   ),
		 						array(
		 								'name' => 'donation_step_title',
		 								'type' => 'textbox',
		 								'label' => __('Donation Title Text', 'charity'),
		 						),
		 						array(
		 								'name' => 'donation_step_content',
		 								'type' => 'textarea',
		 								'label' => __('Donation Content Text', 'charity'),
		 						),
		 						
		 				),
		 				 
		 		),
		 	
		 ),
		),

// charity toggle short code start here

'Charity Toggle Shortcode' => array(
		'elements' => array(
			'toggle' => array(
				'title'   => __('Charity Toggle', 'charity'),
				'code'    => '[charity_toggle][/charity_toggle]',
				'attributes' => array(
				array(
                        'name' => 'charity_toggle_heading',
                        'type' => 'textbox',
                        'label' => __('Charity Toggle Heading', 'charity'),
                    ),
                    array(
                        'name' => 'charity_toggle_text',
                        'type' => 'textarea',
                        'label' => __('Charity Toggle Text', 'charity'),
                    ),
				),
			),		
		),
	),


 	

		
);

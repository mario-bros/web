<?php
return array (
		'id' => 'general_question',
		'types' => array ("charity_faq"),
		'title' => __ ( 'General Question Postion', 'charity' ),
		'priority' => 'high',
		'template' => array (
				array (
						'type' => 'radiobutton',
						'name' => 'question-position',
						'label' => __ ( 'Question', 'charity' ),
						'items' => array(
								array (
										'value' => 'left',
										'label' => __ ( 'Left', 'charity' ) 
								),
								array (
										'value' => 'right',
										'label' => __ ( 'Right', 'charity' ) 
								) 
						),
						'default' => array (
								'left' 
						) 
				) 
		) 
);

/**
 * EOF
 */

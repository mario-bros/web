<?php

        
return array(
    'id' => 'doantion-settings',
    'types' => array('charity-causes'),
    'title' => __('Causes donation settings', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'textbox',
            'name' => 'donation-target',
            'label' => __('Target amount ($)', 'charity'),
            'description' => __('Target amount of donation', 'charity'),
            'default' => '0',
            'validation' => 'numeric',
        ),
        array(
            'type' => 'textbox',
            'name' => 'donation-achivement',
            'label' => __('Current achivment ($)', 'charity'),
            'description' => __('Current achive amount of donation', 'charity'),
            'default' => '0',
            'validation' => 'numeric',
        ),
        array(
            'type' => 'toggle',
            'name' => 'doantion-status',
            'label' => __('Donation status', 'charity'),
            'description' => __('Toggle donation status', 'charity'),
            'default' => 'checked'
        ),
        array(
            'type' => 'notebox',
            'name' => 'donation-list',
            'label' => __('Doantion List via "Easy Pay"', 'charity'),
            'description' => cuasesListTable(),
            'status' => 'normal',
        )
    ),
);


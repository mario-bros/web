<?php

return array(
    'id' => 'quote_meta',
    'types' => array('post'),
    'title' => __('Quote Post Format Fields', 'charity'),
    'priority' => 'high',
    'template' => array(
                array(
                    'type' => 'textarea',
                    'name' => 'quote_text',
                    'label' => __('Enter Here Blockquote', 'charity'),
                    'description' => __('Enter Here Blockquote For Quote Format', 'charity'),
                ),
                 array(
                    'type' => 'textbox',
                    'name' => 'quote_footer_text',
                    'label' => __('Enter Here Blockquote Author', 'charity'),
                    'description' => __('Enter Here Blockquote Author For Quote Format', 'charity'),
                ),
    ),
);


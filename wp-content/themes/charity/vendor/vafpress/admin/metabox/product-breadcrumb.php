<?php
/**
 * Charity - Product breadcrumb metabox
 *
 * @package  charity
 * @version  v.1.0
 */
return array(
    'id' => 'product_breadcrumb',
    'types' => array("product"),
    'title' => __('Breadcrumb settings For Product Single', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'upload',
            'name' => 'single_shopimage',
            'label' => __('Breadcrumb image', 'charity'),
            'description' => __('Upload Breadcrumb Image Size 1348X899', 'charity'),
        ),
    ),
);


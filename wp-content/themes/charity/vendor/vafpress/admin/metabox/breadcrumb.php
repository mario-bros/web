<?php
/**
 * Charity - breadcrumb metabox
 *
 * @package  charity
 * @version  v.1.0
 */
return array(
    'id' => 'breadcrumb',
    'types' => array("post", "page", "charity-causes","charity-portfolio"),
    'title' => __('Breadcrumb settings', 'charity'),
    'priority' => 'high',
    'template' => array(
        array(
            'type' => 'upload',
            'name' => 'image',
            'label' => __('Breadcrumb image', 'charity'),
            'description' => __('Upload Breadcrumb Image Size 1348X899', 'charity'),
        ),
        /*array(
            'type' => 'toggle',
            'name' => 'breadcrumb-toggle',
            'label' => __('breadcrumb Toggle', 'charity'),
            'description' => __('Toggle breadcrumb', 'charity'),
            'default' => 'checked'
        )*/
    ),
);


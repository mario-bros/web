<?php

function charity_causes_column( $column ) {
    $column['donation-target'] = __('Target','charity');
    $column['doantion-achivement'] = __('Achivement','charity');
    $column['doantion-status'] = __('Status','charity');

    return $column;
}
add_filter( 'manage_charity-causes_posts_columns', 'charity_causes_column' );

function charity_causes_row( $column_name, $post_id ) {

    //$custom_fields = get_post_custom( $post_id );

    switch ($column_name) {
        case 'donation-target' :
           echo vp_metabox('doantion-settings.donation-target');
            break;

        case 'doantion-achivement' :
           echo vp_metabox('doantion-settings.donation-achivement');
            break;
        case 'doantion-status' :
           $status=vp_metabox('doantion-settings.doantion-status');
            echo ($status == 0) ? "off" : "on";
            break;

        default:
    }
}

add_action( 'manage_charity-causes_posts_custom_column', 'charity_causes_row', 10, 2 );
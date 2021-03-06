<?php

    /*
     * Function that returns a front-end logout message from the qum-logout shortcode
     *
     * @param $atts     The shortcode attributes
     */
    function qum_front_end_logout( $atts ) {

        if( !is_user_logged_in() )
            return;

        $current_user = get_userdata( get_current_user_id() );

        extract( shortcode_atts( array( 'text' => sprintf( __('You are currently logged in as %s. ','quickusermanager') ,$current_user->user_login) , 'redirect' => qum_curpageurl(), 'link_text' => __('Log out &raquo;','quickusermanager')), $atts ) );

        $logout_link = '<a href="' . wp_logout_url( $redirect ) . '" class="qum-logout-url" title="' . __( 'Log out of this account', 'quickusermanager' ) . '">' . $link_text . '</a>';

        $meta_tags = apply_filters( 'qum_front_end_logout_meta_tags', array( '{{meta_user_name}}', '{{meta_first_name}}', '{{meta_last_name}}', '{{meta_display_name}}' ) );
        $meta_tags_values = apply_filters( 'qum_front_end_logout_meta_tags_values', array( $current_user->user_login, $current_user->first_name, $current_user->last_name, $current_user->display_name ) );

        $text = apply_filters( 'qum_front_end_logout_text', str_replace( $meta_tags, $meta_tags_values, $text ), $current_user );

        return '<p class="qum-front-end-logout"><span>' . $text . '</span>' . $logout_link . '</p>';
    }
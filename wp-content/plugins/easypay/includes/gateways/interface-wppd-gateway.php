<?php

/**
 * The gateway interface for WPPD plugin
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/public
 */

/**
 * The gateway interface for WPPD plugin
 *
 * @package    WPPD
 * @subpackage WPPD/public
 * @author     theem'on <support@theemon.com>
 */

interface WPPD_Gateway {
    
    public function wppd_prepare_payment_url($payment_fields_arr, $insert_id, $hash, $wppd_payment_action);
    public function wppd_prepare_gateway_fields($post);
    public function wppd_validate_gateway_fields($payment_fields_arr);
    public function wppd_send_to_gateway($post);
    public function wppd_gateway_hidden_fields();
    public function wppd_update_gateway_settings($data);

}

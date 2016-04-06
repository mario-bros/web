<?php

/**
 * Charity back compat functionality
 *
 * Prevents Charity from running on WordPress versions prior to 3.9
 *
 * @package charity.inc
 * @version 1.0
 */

/**
 * Prevent switching to Charity on old versions of WordPress.
 */
function chy_switch_theme() {
    switch_theme(WP_DEFAULT_THEME, WP_DEFAULT_THEME);
    unset($_GET['activated']);
    add_action('admin_notices', 'chy_upgrade_notice');
}

add_action('after_switch_theme', 'chy_switch_theme');

/**
 * Add message for unsuccessful theme switch.
 */
function chy_upgrade_notice() {
    $message = sprintf(__('charity requires at least WordPress version 3.9 You are running version %s. Please upgrade and try again.', 'charity'), $GLOBALS['wp_version']);
    printf('<div class="error"><p>%s</p></div>', $message);
}

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 3.9
 */
function chy_preview() {
    if (isset($_GET['preview'])) {
        wp_die(sprintf(__('charity requires at least WordPress version 3.9 You are running version %s. Please upgrade and try again.', 'charity'), $GLOBALS['wp_version']));
    }
}

add_action('template_redirect', 'chy_preview');

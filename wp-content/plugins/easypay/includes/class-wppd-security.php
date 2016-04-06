<?php

/**
 * Register all actions and filters for the plugin
 *
 * 
 * @since      1.0.0
 *
 * @package    WPPD
 * @subpackage WPPD/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    WPPD
 * @subpackage WPPD/includes
 * @author     theem'on <support@theemon.com>
 */
class WPPD_Security {

    /**
     * generate hash key for payments
     * 
     * @return string
     */
    function generate_hash_code() {


        list( $usec, $sec ) = explode(' ', microtime());
        $seed = (float) $sec + ( (float) $usec * 100000 );

        srand($seed);
        $randval = rand();

        // Adding additonal random values
        $rand_wp_str = $randval . $this->create_random_key(20);
        return $rand_wp_str;
    }

    /**
     * generate md5 random key to secure the hash
     * 
     * @param int $amount
     * @return string
     */
    function create_random_key($amount) {

        $keyset = 'abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randkey = '';
        for ($i = 0; $i < $amount; $i++)
            $randkey .= substr($keyset, rand(0, strlen($keyset) - 1), 1);
        return $randkey;
    }

}

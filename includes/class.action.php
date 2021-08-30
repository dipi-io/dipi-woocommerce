<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Action
{
    public static function activate()
    {
        $current_user = wp_get_current_user();

        wp_remote_post( 'https://dipi.io/api/plugins/activate', array(
            'timeout' => 45,
            'headers' => array( 'Accept' => 'application/json' ),
            'body'    => array (
                'platform' => 'wordpress',
                'kind'     => 'woocommerce',
                'data'     => array(
                    'plugin_version' => DIPI_WOOCOMMERCE_VERSION,
                    'php_version'    => phpversion(),
                    'wp_version'     => get_bloginfo( 'version' ),
                    'blog_name'      => get_bloginfo( 'name' ),
                    'blog_url'       => get_site_url(),
                    'user_email'     => $current_user->user_email,
                )
            )
        ) );
    }

    public static function deactivate()
    {
        $current_user = wp_get_current_user();

        wp_remote_post( 'https://dipi.io/api/plugins/deactivate', array(
            'timeout' => 45,
            'headers' => array( 'Accept' => 'application/json' ),
            'body'    => array (
                'platform' => 'wordpress',
                'kind'     => 'woocommerce',
                'data'     => array(
                    'plugin_version' => DIPI_WOOCOMMERCE_VERSION,
                    'php_version'    => phpversion(),
                    'wp_version'     => get_bloginfo( 'version' ),
                    'blog_name'      => get_bloginfo( 'name' ),
                    'blog_url'       => get_site_url(),
                    'user_email'     => $current_user->user_email,
                )
            )
        ) );
    }
}
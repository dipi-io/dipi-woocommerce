<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Update
{
    /**
     * Check if there is a new version.
     */
    public function check_for_new_version()
    {
        $transient = null;
        if ( ! $remote = get_transient( 'dipi_woocommerce_update' ) ) {
            $remote = wp_remote_get( 'https://api.dipi.io/plugins/wp/woocommerce', array(
                'timeout' => 10,
                'headers' => array( 'Accept' => 'application/json' )
            ) );
            if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 ) {
                set_transient( 'dipi_woocommerce_update', $remote, 3600 );
            }

            if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 ) {
                $remote = json_decode( $remote['body'] );
                if ( $remote && version_compare( ADVIRAL_WOOCOMMERCE_VERSION, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo( 'version' ), '<' ) ) {
                    $plugin = 'dipi-woocommerce/dipi-woocommerce.php';
                    $transient->response[$plugin] = (object) array(
                        'slug' => 'dipi-woocommerce',
                        'plugin' => $plugin,
                        'new_version' => $remote->version,
                        'tested' => $remote->tested,
                        'package' => $remote->package,
                        'url' => $remote->url,
                    );
                }
            }
        }
        
        return $transient;
    }
    
    /**
     * Show the plugin information.
     */
    public function display_plugin_information( $action, $args )
    {
        if ( $action !== 'plugin_information' || $args->slug !== 'dipi-woocommerce' ) {
            return false;
        }
    
        if ( 'dipi-woocommerce' !== $args->slug ) {
            return $res;
        }
    
        if ( ! $remote = get_transient( 'dipi_woocommerce_update' ) ) {
            $remote = wp_remote_get( 'https://api.dipi.io/plugins/wp/woocommerce', array(
                'timeout' => 10,
                'headers' => array( 'Accept' => 'application/json' )
            ) );
            if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 ) {
                set_transient( 'dipi_woocommerce_update', $remote, 3600 );
            }
        }
    
        if ( $remote ) {
            $remote = json_decode( $remote['body'] );
    
            return (object) array(
                'name' => 'Dipi woocommerce',
                'slug' => 'dipi-woocommerce',
                'version' => $remote->version,
                'tested' => $remote->tested,
                'requires' => $remote->requires,
                'author' => '<a href="https://dipi.media">Dipi AB</a>',
                'author_profile' => 'https://dipi.media',
                'download_link' => $remote->package,
                'trunk' => $remote->package,
                'last_updated' => $remote->last_updated,
                'requires_php' => $remote->requires_php,
                'sections' => array(
                    'description' => $remote->sections->description,
                    'installation' => $remote->sections->installation,
                    'changelog' => $remote->sections->changelog,
                ),
                'banners' => array(
                    'low' => $remote->banners->low ?: 'http://placehold.it/772x250',
                    'high' => $remote->banners->high ?: 'http://placehold.it/1544x500',
                )
            );
        }
    
        return false;
    }

    /**
     * Clear the cache after plugin update.
     */
    public function clear_plugin_cache( $upgrader_object, $options )
    {
        if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
            delete_transient( 'dipi_woocommerce_update' );
        }
    }
}
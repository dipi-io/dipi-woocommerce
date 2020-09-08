<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Ajax
{
    public function get_voucher_api_status()
    {
        $store_ships_to_countries = 'all';
        if ( get_option( 'dipi_woocommerce_store_ships_to' ) == 'specific' && get_option( 'dipi_woocommerce_store_ships_to_countries' ) ) {
            $store_ships_to_countries = implode( ',', get_option( 'dipi_woocommerce_store_ships_to_countries' ) );
        }
        $response = wp_remote_get( 'https://api.dipi.io/v1/advertiser/voucher/status?countries=' . $store_ships_to_countries . '&campaign_id=' . get_option( 'dipi_woocommerce_campaign_id' ), array(
            'headers' => array(
                'x-api-key' => get_option( 'dipi_woocommerce_api_key' )
            )
        ) );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( $response->get_error_message() );
        }

        if ( $response['response']['code'] == 200 ) {
            $data = json_decode( $response['body'] );
            if ( $data->endpoint == home_url() . '/wp-json/dipi-woocommerce/coupon/create/' && $data->status == get_option( 'dipi_woocommerce_coupon_api_status' ) ) {
                wp_send_json_success( 'Connected' );
            } else {
                wp_send_json_error( 'Not connected' );
            }
        }

        wp_send_json_error( json_decode( $response['body'] )->error );
    }
}
<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Coupons
{
    protected $base_url = 'https://dipi.io/api/v1/brand/';

    public function setup_api_routes()
    {
        register_rest_route( 'dipi/v1', 'coupons', [
            'methods' => 'POST',
            'callback' => array( $this, 'create_coupon' )
        ] );
    }

    public function create_coupon( WP_REST_Request $request )
    {
        if ( ! $_GET['k'] || $_GET['k'] != esc_attr( get_option( 'dipi_woocommerce_coupon_url_key') ) ) {
            return new WP_REST_Response( 'Unauthenticated', 401 );
        }

        $data = $request->get_json_params();

        $codes = array(
            esc_attr( $data['coupon']['primary'] ),
            esc_attr( $data['coupon']['secondary'] ),
        );

        foreach ( $codes as $code ) {
            if ( $this->check_code_availability( $code ) ) {
                return $this->reply_to_dipi(
                    $data,
                    $this->create_coupon_code( $data, $code )
                );
            }
        }

        return $this->reply_to_dipi(
            $data,
            $this->generate_unique_coupon_code( $data )
        );
    }

    public function check_code_availability( $code )
    {
        $coupon_id = wc_get_coupon_id_by_code( $code );

        return get_post( $coupon_id ) ? false : true;
    }

    public function create_coupon_code( $data, $code )
    {
        $coupon = new WC_Coupon();
        $coupon->set_code( esc_attr( $code ) );
        if ( $description = esc_attr( get_option( 'dipi_woocommerce_coupon_description' ) ) ) {
            $coupon->set_description( $description );
        }
        $coupon->set_discount_type( 'percent' );
        $coupon->set_amount( esc_attr( $data['coupon']['percent'] ) );
        $coupon->set_date_expires( date( 'Y-m-d H:i:s', time() + ( 86400 * esc_attr( get_option( 'dipi_woocommerce_coupon_expiry_days', 60 ) ) ) ) );
        $coupon->save();

        return $coupon;
    }

    public function generate_unique_coupon_code( $data )
    {
        do {
            $code = uniqid();
        } while ( ! $this->check_code_availability( $code ) );

        return $this->create_coupon_code( $data, $code );
    }

    public function reply_to_dipi( $data, $coupon )
    {
        $response = wp_remote_post( $this->base_url . 'coupons', [
            'method' => 'POST',
            'body' => json_encode( [
                'profile_id' => $data['profile']['id'],
                'coupon_code' => $coupon->get_code(),
                'coupon_percent' => $coupon->get_amount(),
                'coupon_valid_from' => date( 'Y-m-d H:i:s' ),
                'coupon_valid_to' => $coupon->get_date_expires()->format( 'Y-m-d H:i:s' ),
            ] ),
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . esc_attr( get_option( 'dipi_woocommerce_coupon_token' ) )
            )
        ] );

        return new WP_REST_Response( json_decode( wp_remote_retrieve_body( $response ), true ), 200 );
    }
}
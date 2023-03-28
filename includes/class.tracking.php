<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Tracking
{
    protected $brand_id;
    protected $tracking_token;
    protected $base_url = 'https://dipi.test/t/s2s/';

    public function __construct()
    {
        add_filter( 'https_ssl_verify', '__return_false' );
        $this->brand_id = esc_attr( get_option( 'dipi_woocommerce_brand_id' ) );
        $this->tracking_token = esc_attr( get_option( 'dipi_woocommerce_tracking_token' ) );

        // Fix for Cloudflare
        if ( isset( $_SERVER["HTTP_CF_CONNECTING_IP"] ) ) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
    }

    public function click()
    {
        try {
            $cookie_id = '';
            if ( ! isset( $_COOKIE['dp_' . $this->brand_id] ) ) {
                $cookie_id = md5( uniqid() );
                setcookie( 'dp_' . $this->brand_id, $cookie_id, time() + ( 86400 * 30 ), '/' );
            } else {
                $cookie_id = $_COOKIE['dp_' . $this->brand_id];
            }
            wp_remote_post( $this->base_url . 'click/' . $this->brand_id, array(
                'method' => 'POST',
                'body' => array(
                    'token' => $this->tracking_token,
                    'dipi' => esc_attr( $_GET['dipi'] ),
                    'ip_address' => esc_attr( $_SERVER['REMOTE_ADDR'] ),
                    'user_agent' => esc_attr( $_SERVER['HTTP_USER_AGENT'] ),
                    'cookie' => $cookie_id,
                )
            ) );
        } catch ( Exception $e ) {
            // Do nothing
        }
    }

    public function sale( $order_id )
    {
        if ( $order_id && $this->brand_id ) {
            $order = wc_get_order( $order_id );
            $coupons = null;
            if ( count( $order->get_coupon_codes() ) > 0 ) {
                $coupons = implode( ',', $order->get_coupon_codes() );
            }

            $customer_orders = get_posts( array(
                'numberposts' => 1,
                'meta_key'    => '_customer_user',
                'meta_value'  => get_current_user_id(),
                'post_type'   => 'shop_order',
                'post_status' => 'wc-completed',
                'fields'      => 'ids',
            ) );

            try {
                wp_remote_post( $this->base_url . 'sale/' . $this->brand_id, array(
                    'method' => 'POST',
                    'body' => array(
                        'token' => $this->tracking_token,
                        'amount' => $order->get_total(),
                        'currency' => get_woocommerce_currency(),
                        'reference' => $order_id,
                        'discount_code' => $coupons,
                        'cookie' => isset( $_COOKIE['dp_' . $this->brand_id] ) ? $_COOKIE['dp_' . $this->brand_id] : '',
                        'ip_address' => esc_attr( $_SERVER['REMOTE_ADDR'] ),
                        'user_agent' => esc_attr( $_SERVER['HTTP_USER_AGENT'] ),
                        'new_customer' => ( count( $customer_orders ) == 0 ) ? 1 : 0,
                    )
                ) );
            } catch ( Exception $e ) {
                // Do nothing
            }
        }        
    }
}

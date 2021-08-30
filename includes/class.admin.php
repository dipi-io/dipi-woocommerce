<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce_Admin
{
    public function enqueue_styles()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'wc-dipi') {
            wp_enqueue_style( 'dipi-woocommerce', plugin_dir_url( __FILE__ ) . '../assets/dipi.css' );
        }
    }

    public function enqueue_scripts()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'wc-dipi') {
            wp_enqueue_script( 'dipi-woocommerce', plugin_dir_url( __FILE__ ) . '../assets/dipi.js' );
        }
    }

    public function admin_menu()
    {
        add_submenu_page( 'woocommerce', 'Dipi', 'Dipi', 'manage_options', 'wc-dipi', array( $this, 'settings_page' ) );
    }

    public function settings_page()
    {
        $coupon_url_key = get_option( 'dipi_woocommerce_coupon_url_key' );
        if ( ! $coupon_url_key ) {
            $coupon_url_key = sha1( uniqid() );
            update_option( 'dipi_woocommerce_coupon_url_key', $coupon_url_key );
        }

        require_once plugin_dir_path( __FILE__ ) . '../views/settings.php';
    }

    public function save_settings()
    {
        update_option( 'dipi_woocommerce_brand_id', $_POST['dipi_woocommerce_brand_id'] );
        update_option( 'dipi_woocommerce_cname', $_POST['dipi_woocommerce_cname'] );
        update_option( 'dipi_woocommerce_coupon_token', $_POST['dipi_woocommerce_coupon_token'] );

        wp_redirect('?page=wc-dipi');
        exit;
    }
}
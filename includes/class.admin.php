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
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if (isset($_GET['page']) && $_GET['page'] == 'wc-dipi') {
            wp_enqueue_style( 'dipi-woocommerce', plugin_dir_url( __FILE__ ) . '../assets/dipi.css' );
        }
    }

    public function enqueue_scripts()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if (isset($_GET['page']) && $_GET['page'] == 'wc-dipi') {
            wp_enqueue_script( 'dipi-woocommerce', plugin_dir_url( __FILE__ ) . '../assets/dipi.js' );
        }
    }

    public function admin_menu()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        add_submenu_page( 'woocommerce', 'Dipi', 'Dipi', 'manage_options', 'wc-dipi', array( $this, 'settings_page' ) );
    }

    public function settings_page()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
    
        $coupon_url_key = get_option( 'dipi_woocommerce_coupon_url_key' );
        if ( ! $coupon_url_key ) {
            $coupon_url_key = sha1( uniqid() );
            update_option( 'dipi_woocommerce_coupon_url_key', esc_attr( $coupon_url_key ) );
        }

        require_once plugin_dir_path( __FILE__ ) . '../views/settings.php';
    }

    public function save_settings()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        update_option( 'dipi_woocommerce_brand_id', esc_attr( $_POST['dipi_woocommerce_brand_id'] ) );
        update_option( 'dipi_woocommerce_tracking_token', esc_attr( $_POST['dipi_woocommerce_tracking_token'] ) );
        update_option( 'dipi_woocommerce_cname', esc_attr( $_POST['dipi_woocommerce_cname'] ) );
        update_option( 'dipi_woocommerce_coupon_token', esc_attr( $_POST['dipi_woocommerce_coupon_token'] ) );
        update_option( 'dipi_woocommerce_coupon_expiry_days', esc_attr( $_POST['dipi_woocommerce_coupon_expiry_days'] ) );
        update_option( 'dipi_woocommerce_coupon_description', esc_attr( $_POST['dipi_woocommerce_coupon_description'] ) );
        update_option( 'dipi_woocommerce_widget_language', esc_attr( $_POST['dipi_woocommerce_widget_language'] ) );
        update_option( 'dipi_woocommerce_widget_button_color', esc_attr( $_POST['dipi_woocommerce_widget_button_color'] ) );
        update_option( 'dipi_woocommerce_widget_text_color', esc_attr( $_POST['dipi_woocommerce_widget_text_color'] ) );

        wp_redirect('?page=wc-dipi');
        exit;
    }
}
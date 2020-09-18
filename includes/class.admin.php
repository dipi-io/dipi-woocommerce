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
        require_once plugin_dir_path( __FILE__ ) . '../views/settings.php';
    }

    public function save_settings()
    {
        update_option( 'dipi_woocommerce_brand_id', $_POST['dipi_woocommerce_brand_id'] );
        update_option( 'dipi_woocommerce_cname', $_POST['dipi_woocommerce_cname'] );

        wp_redirect('?page=wc-dipi');
        exit;
    }

    public function shop_coupon_columns( $columns )
    {
        $columns['is_dipi'] = 'Dipi Coupon';

        return $columns;
    }
}
<?php

/**
 * @since      1.0.0
 * @package    Dipi
 * @subpackage Dipi/includes
 * @author     Dipi AB <tech@dipi.io>
 */

class Dipi_Woocommerce
{
    protected $loader;

    public function run()
    {
        $this->load_dependencies();
        $this->define_public_hooks();

        // Only to run in admin
        if ( is_admin() ) {
            $this->update_check();
            $this->define_admin_hooks();
        }

        $this->loader->run();
    }

    private function load_dependencies()
    {
        // Load classes
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.public.php';

        // Only to run in admin
        if ( is_admin() ) {
            
            // Admin classes
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.update.php';
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class.admin.php';
        }

        $this->loader = new Dipi_Woocommerce_Loader();
    }

    private function update_check()
    {
        $update = new Dipi_Woocommerce_Update();

        $this->loader->add_filter( 'site_transient_update_plugins', $update, 'check_for_new_version', 10, 1 );
        $this->loader->add_filter( 'plugins_api', $update, 'display_plugin_information', 20, 3 );
        $this->loader->add_action( 'upgrader_process_complete', $update, 'clear_plugin_cache', 10, 2 );
    }

    private function define_public_hooks()
    {
        $public = new Dipi_Woocommerce_Public;

        $this->loader->add_action( 'wp_footer', $public, 'woocommerce_page_view' );
        $this->loader->add_action( 'woocommerce_thankyou', $public, 'woocommerce_thankyou' );
    }

    private function define_admin_hooks()
    {
        $admin = new Dipi_Woocommerce_Admin();
        
        // Load styles and scripts
        $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );

        // Add settings page
        $this->loader->add_action( 'admin_menu', $admin, 'admin_menu', 99 );
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_GET['action'] ) && $_GET['action'] == 'dipi-woocommerce-save-settings') {
            $this->loader->add_action( 'admin_init', $admin, 'save_settings' );
        }
    }
}
<?php

/**
 * @since             1.0.0
 * @package           Dipi
 * @author            Dipi AB <tech@dipi.io>
 * 
 * Plugin Name:       Dipi Woocommerce
 * Description:       Connect your store with Dipi. This plugin installs the server 2 server pixels and connects your store to the Dipi Coupon API.
 * Author:            Dipi AB
 * Version:           2.1.0
 * Author URI:        https://dipi.io
 * Plugin URI:        https://dipi.io
 */

/**
 * No direct access!
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if Woocommerce is active
 */

$woocommerce_active = false;
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
    $woocommerce_active = is_plugin_active_for_network( 'woocommerce/woocommerce.php' ); 
} else {
	$woocommerce_active = is_plugin_active( 'woocommerce/woocommerce.php');
}
if ( $woocommerce_active ) {
	/**
	 * Plugin version.
	 */
	define( 'DIPI_WOOCOMMERCE_VERSION', '2.0.0' );

	/**
	 * Activate plugin.
	 */
	function activate_dipi_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class.action.php';
		Dipi_Woocommerce_Action::activate();
	}

	/**
	 * Deactivate plugin.
	 */
	function deactivate_dipi_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class.action.php';
		Dipi_Woocommerce_Action::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_dipi_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_dipi_woocommerce' );

	/**
	 * Load the core class.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class.dipi.php';

	/**
	 * Here we go...
	 */
	function run_dipi_woocommerce() {
		$dipi = new Dipi_Woocommerce();
		$dipi->run();
	}
	run_dipi_woocommerce();
} else {
	add_action( 'admin_notices', 'dipi_woocommerce_no_woocommerce' );
	function dipi_woocommerce_no_woocommerce() {
		echo '<div class="notice notice-error"><p>Please install and activate Woocommerce to use Dipi Woocommerce.</p></div>';
	}
}
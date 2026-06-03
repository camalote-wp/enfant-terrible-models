<?php
/**
 * Plugin Name:       Camalote WP - Editorial Control
 * Description:       This plugin provides a dedicated settings page within the WordPress admin dashboard, where users can manage and configure various custom-made features and settings.
 * Version:           1.1.0
 * Requires at least: 4.9
 * Requires PHP:      8.2
 * Author:            Martín García
 * Author URI:        https://github.com/tingeka
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       camalote-wp-editorial-control
 * Domain Path:       /languages
 * Update URI:        https://github.com/camalote-wp/editorial-control
 *
 * @package           CamaloteWP\EditorialControl
 */

// Useful global constants.
define( 'CAMALOTE_WP_VENDOR', 'camalote-wp' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_SLUG', 'camalote-wp-editorial-control' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_REST_NAMESPACE', CAMALOTE_WP_VENDOR . '/editorial-control/v1' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_OPTION', 'camalote_wp_editorial_control' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_VERSION', '1.1.0' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_URL', plugin_dir_url( __FILE__ ) );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_INC', CAMALOTE_WP_EDITORIAL_CONTROL_PATH . 'src/' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_DIST_URL', CAMALOTE_WP_EDITORIAL_CONTROL_URL . 'dist/' );
define( 'CAMALOTE_WP_EDITORIAL_CONTROL_DIST_PATH', CAMALOTE_WP_EDITORIAL_CONTROL_PATH . 'dist/' );

$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' ) || strpos( home_url(), '.site' );
$is_local     = $is_local_env || $is_local_url;

if ( $is_local && file_exists( __DIR__ . '/dist/fast-refresh.php' ) ) {
	require_once __DIR__ . '/dist/fast-refresh.php';

	if ( function_exists( 'TenUpToolkit\set_dist_url_path' ) ) {
		TenUpToolkit\set_dist_url_path( 
			basename( __DIR__ ), 
			CAMALOTE_WP_EDITORIAL_CONTROL_DIST_URL, 
			CAMALOTE_WP_EDITORIAL_CONTROL_DIST_PATH 
		);
	}
}

// Only check for Composer autoload in local environments.
if ( $is_local && ! file_exists( CAMALOTE_WP_EDITORIAL_CONTROL_PATH . 'vendor/autoload.php' ) ) {
	throw new \Exception(
		'Vendor autoload file not found. Please run `composer install`.'
	);
}

// Bail if vendor deps are not scoped.
if ( ! file_exists( CAMALOTE_WP_EDITORIAL_CONTROL_PATH . 'vendor-scoped/scoper-autoload.php' ) ) {
	throw new \Exception(
		'Vendor deps not scoped. Please run `composer scope`.'
	);
}

require_once CAMALOTE_WP_EDITORIAL_CONTROL_PATH . 'vendor-scoped/scoper-autoload.php';

$plugin_core = new \CamaloteWP\EditorialControl\PluginCore();

// Activation/Deactivation.
register_activation_hook( __FILE__, [ $plugin_core, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin_core, 'deactivate' ] );

// Bootstrap.
$plugin_core->setup();

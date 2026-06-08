<?php
/**
 * Plugin Name:       Camalote WP - Editorial Control
 * Description:       This plugin provides a dedicated settings page within the WordPress admin dashboard, where users can manage and configure various custom-made features and settings.
 * Version:           1.0.3
 * Requires at least: 4.9
 * Requires PHP:      8.2
 * Author:            Martín García
 * Author URI:        https://github.com/tingeka
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       camalote-wp-zorzal-models
 * Domain Path:       /languages
 * Update URI:        https://github.com/camalote-wp/zorzal-models
 *
 * @package           CamaloteWP\ZorzalModels
 */

// Useful global constants.
if( !defined( 'CAMALOTE_WP_VENDOR' ) ) {
	define( 'CAMALOTE_WP_VENDOR', 'camalote-wp' );
}
define( 'CAMALOTE_WP_ZORZAL_MODELS_SLUG', 'camalote-wp-zorzal-models' );
define( 'CAMALOTE_WP_ZORZAL_MODELS_REST_NAMESPACE', CAMALOTE_WP_VENDOR . '/zorzal-models/v1' );
define( 'CAMALOTE_WP_ZORZAL_MODELS_OPTION', 'camalote_wp_zorzal_models' );
define( 'CAMALOTE_WP_ZORZAL_MODELS_VERSION', '1.0.3' );
define( 'CAMALOTE_WP_ZORZAL_MODELS_URL', plugin_dir_url( __FILE__ ) );
define( 'CAMALOTE_WP_ZORZAL_MODELS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAMALOTE_WP_ZORZAL_MODELS_INC', CAMALOTE_WP_ZORZAL_MODELS_PATH . 'src/' );
define( 'CAMALOTE_WP_ZORZAL_MODELS_DIST_URL', CAMALOTE_WP_ZORZAL_MODELS_URL . 'dist/' );
define( 'CAMALOTE_WP_ZORZAL_MODELS_DIST_PATH', CAMALOTE_WP_ZORZAL_MODELS_PATH . 'dist/' );

$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' ) || strpos( home_url(), '.site' );
$is_local     = $is_local_env || $is_local_url;
$is_plugin_dev = $is_local && file_exists( __DIR__ . '/composer.json' );

if ( $is_local && file_exists( __DIR__ . '/dist/fast-refresh.php' ) ) {
	require_once __DIR__ . '/dist/fast-refresh.php';

	if ( function_exists( 'TenUpToolkit\set_dist_url_path' ) ) {
		TenUpToolkit\set_dist_url_path( 
			basename( __DIR__ ), 
			CAMALOTE_WP_ZORZAL_MODELS_DIST_URL, 
			CAMALOTE_WP_ZORZAL_MODELS_DIST_PATH 
		);
	}
}

// Only check for Composer autoload in local environments.
if ( $is_plugin_dev && ! file_exists( CAMALOTE_WP_ZORZAL_MODELS_PATH . 'vendor/autoload.php' ) ) {
	throw new \Exception(
		'Vendor autoload file not found. Please run `composer install`.'
	);
}

// Bail if vendor deps are not scoped.
if ( ! file_exists( CAMALOTE_WP_ZORZAL_MODELS_PATH . 'vendor-scoped/scoper-autoload.php' ) ) {
	throw new \Exception(
		'Vendor deps not scoped. Please run `composer scope`.'
	);
}

require_once CAMALOTE_WP_ZORZAL_MODELS_PATH . 'vendor-scoped/scoper-autoload.php';

$plugin_core = new \CamaloteWP\ZorzalModels\Core\Plugin();

// Activation/Deactivation.
register_activation_hook( __FILE__, [ $plugin_core, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin_core, 'deactivate' ] );

// Bootstrap.
$plugin_core->run();

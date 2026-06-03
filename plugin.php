<?php
/**
 * Plugin Name:       CamaloteWP - Editorial Control
 * Description:       This plugin provides a dedicated settings page within the WordPress admin dashboard, where users can manage and configure various custom-made features and settings.
 * Version:           1.1.0
 * Requires at least: 4.9
 * Requires PHP:      8.2
 * Author:            Martín García
 * Author URI:        https://github.com/tingeka
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       camalotewp-editorial-control
 * Domain Path:       /languages
 * Update URI:        https://github.com/camalote-wp/cmlt-editorial-control
 *
 * @package           CamaloteWP\EditorialControl
 */

// Useful global constants.
define( 'CAMALOTEWP_EDITORIAL_CONTROL_VERSION', '1.1.0' );
define( 'CAMALOTEWP_EDITORIAL_CONTROL_URL', plugin_dir_url( __FILE__ ) );
define( 'CAMALOTEWP_EDITORIAL_CONTROL_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAMALOTEWP_EDITORIAL_CONTROL_INC', CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'src/' );
define( 'CAMALOTEWP_EDITORIAL_CONTROL_DIST_URL', CAMALOTEWP_EDITORIAL_CONTROL_URL . 'dist/' );
define( 'CAMALOTEWP_EDITORIAL_CONTROL_DIST_PATH', CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'dist/' );

$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' ) || strpos( home_url(), '.site' );
$is_local     = $is_local_env || $is_local_url;

if ( $is_local && file_exists( __DIR__ . '/dist/fast-refresh.php' ) ) {
	require_once __DIR__ . '/dist/fast-refresh.php';

	if ( function_exists( 'TenUpToolkit\set_dist_url_path' ) ) {
		TenUpToolkit\set_dist_url_path( 
			basename( __DIR__ ), 
			CAMALOTEWP_EDITORIAL_CONTROL_DIST_URL, 
			CAMALOTEWP_EDITORIAL_CONTROL_DIST_PATH 
		);
	}
}

// Only check for Composer autoload in local environments.
if ( $is_local && ! file_exists( CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'vendor/autoload.php' ) ) {
	throw new \Exception(
		'Vendor autoload file not found. Please run `composer install`.'
	);
}

// Bail if vendor deps are not scoped.
if ( ! file_exists( CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'vendor-scoped/scoper-autoload.php' ) ) {
	throw new \Exception(
		'Vendor deps not scoped. Please run `composer scope`.'
	);
}

require_once CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'vendor-scoped/scoper-autoload.php';

$plugin_core = new \CamaloteWP\EditorialControl\PluginCore();

// Activation/Deactivation.
register_activation_hook( __FILE__, [ $plugin_core, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin_core, 'deactivate' ] );

// Bootstrap.
$plugin_core->setup();

<?php
/**
 * Plugin Name:       Enfant Terrible - Models
 * Description:       This plugin provides a dedicated settings page within the WordPress admin dashboard, where users can manage and configure various custom-made features and settings.
 * Version:           0.2.0
 * Requires at least: 4.9
 * Requires PHP:      8.2
 * Author:            Martín García
 * Author URI:        https://github.com/tingeka
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       enfant-terrible-models
 * Domain Path:       /languages
 * Update URI:        https://github.com/camalote-wp/enfant-terrible-models
 *
 * @package           EnfantTerrible\Models
 */

// Useful global constants.
if( !defined( 'ENFANT_TERRIBLE_VENDOR' ) ) {
	define( 'ENFANT_TERRIBLE_VENDOR', 'enfant-terrible' );
}
define( 'ENFANT_TERRIBLE_MODELS_SLUG', 'enfant-terrible-models' );
define( 'ENFANT_TERRIBLE_MODELS_REST_NAMESPACE', ENFANT_TERRIBLE_VENDOR . '/models/v1' );
define( 'ENFANT_TERRIBLE_MODELS_OPTION', 'enfant_terrible_models' );
define( 'ENFANT_TERRIBLE_MODELS_VERSION', '0.2.0' );
define( 'ENFANT_TERRIBLE_MODELS_URL', plugin_dir_url( __FILE__ ) );
define( 'ENFANT_TERRIBLE_MODELS_PATH', plugin_dir_path( __FILE__ ) );
define( 'ENFANT_TERRIBLE_MODELS_INC', ENFANT_TERRIBLE_MODELS_PATH . 'src/' );
define( 'ENFANT_TERRIBLE_MODELS_DIST_URL', ENFANT_TERRIBLE_MODELS_URL . 'dist/' );
define( 'ENFANT_TERRIBLE_MODELS_DIST_PATH', ENFANT_TERRIBLE_MODELS_PATH . 'dist/' );

$is_local_env = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' ) || strpos( home_url(), '.site' );
$is_local     = $is_local_env || $is_local_url;
$is_plugin_dev = $is_local && file_exists( __DIR__ . '/composer.json' );

if ( $is_local && file_exists( __DIR__ . '/dist/fast-refresh.php' ) ) {
	require_once __DIR__ . '/dist/fast-refresh.php';

	if ( function_exists( 'TenUpToolkit\set_dist_url_path' ) ) {
		TenUpToolkit\set_dist_url_path( 
			basename( __DIR__ ), 
			ENFANT_TERRIBLE_MODELS_DIST_URL, 
			ENFANT_TERRIBLE_MODELS_DIST_PATH 
		);
	}
}

// Only check for Composer autoload in local environments.
if ( $is_plugin_dev && ! file_exists( ENFANT_TERRIBLE_MODELS_PATH . 'vendor/autoload.php' ) ) {
	throw new \Exception(
		'Vendor autoload file not found. Please run `composer install`.'
	);
}

// Bail if vendor deps are not scoped.
if ( ! file_exists( ENFANT_TERRIBLE_MODELS_PATH . 'vendor-scoped/scoper-autoload.php' ) ) {
	throw new \Exception(
		'Vendor deps not scoped. Please run `composer scope`.'
	);
}

require_once ENFANT_TERRIBLE_MODELS_PATH . 'vendor-scoped/scoper-autoload.php';

$plugin_core = new \EnfantTerrible\Models\Core\Plugin();

// Activation/Deactivation.
register_activation_hook( __FILE__, [ $plugin_core, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin_core, 'deactivate' ] );

// Bootstrap.
$plugin_core->run();

<?php
/**
 * PluginCore module.
 *
 * @package CamaloteWP\EditorialControl
 */

namespace CamaloteWP\EditorialControl;

use CamaloteWP\EditorialControl\Vendor\TenupFramework\ModuleInitialization;

/**
 * PluginCore module.
 *
 * @package CamaloteWP\EditorialControl
 */
class PluginCore {
	
	/**
	 * Default setup routine
	 *
	 * @return void
	 */
	public function setup() {
		add_action( 'init', [ $this, 'i18n' ], 8 );
		add_action( 'init', [ $this, 'init' ], apply_filters( 'camalote_wp_editorial_control_init_priority', 8 ) );

		do_action( 'camalote_wp_editorial_control_loaded' );
	}

	/**
	 * Registers the default textdomain.
	 *
	 * @return void
	 */
	public function i18n() {
		$locale = apply_filters( 'plugin_locale', get_locale(), CAMALOTE_WP_EDITORIAL_CONTROL_SLUG );
		load_textdomain( CAMALOTE_WP_EDITORIAL_CONTROL_SLUG, WP_LANG_DIR . '/' . CAMALOTE_WP_EDITORIAL_CONTROL_SLUG . '/' . CAMALOTE_WP_EDITORIAL_CONTROL_SLUG . '-' . $locale . '.mo' );
		load_plugin_textdomain( CAMALOTE_WP_EDITORIAL_CONTROL_SLUG, false, plugin_basename( CAMALOTE_WP_EDITORIAL_CONTROL_PATH ) . '/languages/' );
	}
	/**
	 * Initializes the plugin and fires an action other plugins can hook into.
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'camalote_wp_editorial_control_before_init' );

		if ( ! class_exists( 'CamaloteWP\EditorialControl\Vendor\TenupFramework\ModuleInitialization' ) ) {
			add_action(
				'admin_notices',
				function () {
					$class = 'notice notice-error';

					printf(
						'<div class="%1$s"><p>%2$s</p></div>',
						esc_attr( $class ),
						wp_kses_post(
							__(
								'Please ensure the <a href="https://github.com/10up/wp-framework"><code>10up/wp-framework</code></a> composer package is installed.',
								CAMALOTE_WP_EDITORIAL_CONTROL_SLUG
							)
						)
					);
				}
			);

			return;
		}
		ModuleInitialization::instance()->init_classes( CAMALOTE_WP_EDITORIAL_CONTROL_INC );

		require_once CAMALOTE_WP_EDITORIAL_CONTROL_PATH . 'src/Inc/GlobalHelpers.php';
		
		do_action( 'camalote_wp_editorial_control_init' );
	}

	/**
	 * Activate the plugin
	 *
	 * @return void
	 */
	public function activate() {
		// First load the init scripts in case any rewrite functionality is being loaded
		$this->init();
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 *
	 * Uninstall routines should be in uninstall.php
	 *
	 * @return void
	 */
	public function deactivate() {
		// Do nothing.
	}

	/**
	 * Get an initialized class by its full class name, including namespace.
	 *
	 * @param string $class_name The class name including the namespace.
	 *
	 * @return false|\TenupFramework\ModuleInterface
	 */
	public static function get_module( $class_name ) {
		return \TenupFramework\ModuleInitialization::get_module( $class_name );
	}
}

<?php
/**
 * Admin Assets module.
 *
 * @package CamaloteWP\ZorzalModels\Admin
 */

namespace CamaloteWP\ZorzalModels\Admin;

use CamaloteWP\ZorzalModels\Vendor\TenupFramework\Assets\GetAssetInfo;
use CamaloteWP\ZorzalModels\Vendor\TenupFramework\Module;
use CamaloteWP\ZorzalModels\Vendor\TenupFramework\ModuleInterface;

/**
 * Admin Assets module.
 *
 * @package CamaloteWP\ZorzalModels\Admin
 */
class AdminAssets implements ModuleInterface {

	use Module;
	use GetAssetInfo;

	/**
	 * Can this module be registered?
	 *
	 * @return bool
	 */
	public function can_register() {
		return true;
	}

	/**
	 * Register any hooks and filters.
	 *
	 * @return void
	 */
	public function register() {
		$this->setup_asset_vars(
			dist_path: CAMALOTE_WP_ZORZAL_MODELS_PATH . 'dist/',
			fallback_version: CAMALOTE_WP_ZORZAL_MODELS_VERSION
		);

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
	}

	/**
	 * Enqueue scripts for admin.
	 *
	 * @return void
	 */
	public function admin_scripts() {
		$screen = get_current_screen();
		
		if ( $screen && 'toplevel_page_editorial-control-page' === $screen->id ) {
			
			wp_enqueue_script(
				CAMALOTE_WP_ZORZAL_MODELS_SLUG . '_admin-settings-page',
				CAMALOTE_WP_ZORZAL_MODELS_URL . 'dist/js/settings/admin-settings-page.js',
				$this->get_asset_info( 'settings/admin-settings-page', 'dependencies' ),
				$this->get_asset_info( 'settings/admin-settings-page', 'version' ),
				true
			);

			// Set up JavaScript translations
			wp_set_script_translations(
				CAMALOTE_WP_ZORZAL_MODELS_SLUG . '_admin-settings-page',
				'camalote-wp-zorzal-models',
				CAMALOTE_WP_ZORZAL_MODELS_PATH . 'languages'
			);
		}

	}

	/**
	 * Enqueue styles for admin.
	 *
	 * @return void
	 */
	public function admin_styles() {
		$screen = get_current_screen();

		if ( $screen && 'toplevel_page_editorial-control-page' === $screen->id ) {
			// This is the source of truth for the entire component's dependencies.
			$deps = [];
	
			wp_enqueue_style(
				CAMALOTE_WP_ZORZAL_MODELS_SLUG . '_admin-settings-page-styles',
				CAMALOTE_WP_ZORZAL_MODELS_URL . 'dist/css/settings/admin-settings-page.css', // Note the corrected file name
				$deps,
				$this->get_asset_info( 'settings/admin-settings-page', 'version' ),
			);
		}
	}
}

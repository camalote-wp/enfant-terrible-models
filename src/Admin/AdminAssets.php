<?php
/**
 * Admin Assets module.
 *
 * @package CamaloteWP\EditorialControl\Admin
 */

namespace CamaloteWP\EditorialControl\Admin;

use CamaloteWP\EditorialControl\Vendor\TenupFramework\Assets\GetAssetInfo;
use CamaloteWP\EditorialControl\Vendor\TenupFramework\Module;
use CamaloteWP\EditorialControl\Vendor\TenupFramework\ModuleInterface;

/**
 * Admin Assets module.
 *
 * @package CamaloteWP\EditorialControl\Admin
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
			dist_path: CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'dist/',
			fallback_version: CAMALOTEWP_EDITORIAL_CONTROL_VERSION
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
			$handle = 'camalotewp_editorial_control_admin_settings_page';
			
			wp_enqueue_script(
				$handle,
				CAMALOTEWP_EDITORIAL_CONTROL_URL . 'dist/js/settings/admin-settings-page.js',
				$this->get_asset_info( 'settings/admin-settings-page', 'dependencies' ),
				$this->get_asset_info( 'settings/admin-settings-page', 'version' ),
				true
			);

			// Set up JavaScript translations
			wp_set_script_translations(
				$handle,
				'camalotewp-editorial-control',
				CAMALOTEWP_EDITORIAL_CONTROL_PATH . 'languages'
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
				'camalotewp_editorial_controlfotoperiodismo_admin_page_styles',
				CAMALOTEWP_EDITORIAL_CONTROL_URL . 'dist/css/settings/admin-settings-page.css', // Note the corrected file name
				$deps,
				$this->get_asset_info( 'settings/admin-settings-page', 'version' ),
			);
		}
	}
}

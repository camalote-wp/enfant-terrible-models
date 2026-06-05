<?php
/**
 * Global Assets module.
 *
 * @package CamaloteWP\ZorzalModels
 */

namespace CamaloteWP\ZorzalModels;

use CamaloteWP\ZorzalModels\Vendor\TenupFramework\Assets\GetAssetInfo;
use CamaloteWP\ZorzalModels\Vendor\TenupFramework\Module;
use CamaloteWP\ZorzalModels\Vendor\TenupFramework\ModuleInterface;

/**
 * Global Assets module.
 *
 * @package CamaloteWP\ZorzalModels
 */
class Assets implements ModuleInterface {

	use Module;
	use GetAssetInfo;

	private const ASSETS_DIST_PATH = CAMALOTE_WP_ZORZAL_MODELS_PATH . 'dist/';
	private const ASSETS_FALLBACK_VERSION = CAMALOTE_WP_ZORZAL_MODELS_VERSION;

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
			dist_path: self::ASSETS_DIST_PATH,
			fallback_version: self::ASSETS_FALLBACK_VERSION
		);

		$this->register_admin_assets();
	}

	/**
	 * Registers the admin assets by attaching the actions to enqueue the scripts
	 * and styles.
	 *
	 * @return void
	 */
	public function register_admin_assets() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'admin_block_editor_scripts' ] );
	}

	/**
	 * Enqueue scripts for admin.
	 *
	 * @return void
	 */
	public function admin_scripts() {
	}

	/**
	 * Enqueue styles for admin.
	 *
	 * @return void
	 */
	public function admin_styles() {
	}

	/**
	 * Enqueue block scripts for the admin.
	 *
	 * This function retrieves dependencies and version information for the block
	 * scripts and enqueues them in the admin area.
	 *
	 * @return void
	 */
	public function admin_block_editor_scripts() {}
}

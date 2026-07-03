<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/tingeka
 * @since      1.0.0
 *
 * @package    EnfantTerrible/Models
 * @subpackage EnfantTerrible/Models/includes
 */

namespace EnfantTerrible\Models\Core;
use EnfantTerrible\Models\Core\Loader;
use EnfantTerrible\Models\Core\I18n;
use EnfantTerrible\Models\Core\Activator;
use EnfantTerrible\Models\Core\Deactivator;
use EnfantTerrible\Models\Interfaces\Registerable;
use EnfantTerrible\Models\Interfaces\Hookable;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    EnfantTerrible/Models
 * @subpackage EnfantTerrible/Models/includes
 * @author     Martín García <tin.geka@gmail.com>
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ENFANT_TERRIBLE_MODELS_VERSION' ) ) {
			$this->version = ENFANT_TERRIBLE_MODELS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'enfant-terrible-models';

		$this->load_dependencies();
		$this->set_locale();
		$this->load_definitions();
		$this->enqueue_global_assets();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Et_Models_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Load all definitions for the plugin.
	 *
	 * This function is responsible for loading all of the definitions for the plugin.
	 * It takes an array of class names, instantiates them, and then registers them with
	 * WordPress using the loader class.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_definitions(): void {
		$models = [
			// \EnfantTerrible\Models\Definitions\Example\Bootstrap::class,
			\EnfantTerrible\Models\Definitions\Page\Bootstrap::class
		];

		foreach ( $models as $class_name ) {
			$bootstrap = new $class_name( $this->plugin_name, $this->version );

			// Auto-instantiate Components
			if ( method_exists( $bootstrap, 'get_components' ) ) {
				foreach ( $bootstrap->get_components() as $component_class ) {
					
					// INSTANTIATE
					$instance = new $component_class( $bootstrap->get_model_name() );
					
					
					// REGISTER (Defer to init to fix Fatal Error)
					if ( $instance instanceof Registerable ) {
						$this->loader->add_action( 'init', $instance, 'register' );
					}
					
					
					// HOOKS
					if ( $instance instanceof Hookable ) {
						foreach ( $instance->get_hooks() as $hook ) {
							if ( ! isset( $hook['type'], $hook['hook'], $hook['callback'] ) ) {
								continue;
							}
							$method = ( $hook['type'] === 'filter' ) ? 'add_filter' : 'add_action';
							$this->loader->$method(
								$hook['hook'],
								$instance,
								$hook['callback'],
								$hook['priority'] ?? 10,
								$hook['accepted_args'] ?? 1
							);
						}
					}
				}
			}
		}
	}

	public function enqueue_global_assets(): void {
		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_assets' );
	}

	public function enqueue_assets(): void {
		wp_enqueue_style( 'enfant-terrible-models-shared-styles', ENFANT_TERRIBLE_MODELS_URL . 'assets/build/shared/index.css' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public static function activate(): void {
		Activator::activate();
	}

	public static function deactivate(): void {
		Deactivator::deactivate();
	}

}

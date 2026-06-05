<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/tingeka
 * @since      1.0.0
 *
 * @package    CamaloteWP/ZorzalModels
 * @subpackage CamaloteWP/ZorzalModels/includes
 */

namespace CamaloteWP\ZorzalModels\Core;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CamaloteWP/ZorzalModels
 * @subpackage CamaloteWP/ZorzalModels/includes
 * @author     Martín García <tin.geka@gmail.com>
 */
class I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'et-models',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

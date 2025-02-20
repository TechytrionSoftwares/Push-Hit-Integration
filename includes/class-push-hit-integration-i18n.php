<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://techytrionsoftwares.com/
 * @since      1.0.0
 *
 * @package    Push_Hit_Integration
 * @subpackage Push_Hit_Integration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Push_Hit_Integration
 * @subpackage Push_Hit_Integration/includes
 * @author     Techytrion <testingemailer1212@gmail.com>
 */
class Push_Hit_Integration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'push-hit-integration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

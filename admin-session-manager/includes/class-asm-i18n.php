<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/talentedaamer/admin-session-manager
 * @since      1.0.0
 *
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/includes
 * @author     Aamer Shahzad <talentedaamer@gmail.com>
 */
class ASM_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'asm',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

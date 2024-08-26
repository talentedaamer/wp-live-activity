<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       Admin Session Manager
 * Plugin URI:        #
 * Description:       Admin Session Manager...
 * Version:           1.0.0
 * Author:            Aamer Shahzad
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       asm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'ASM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-asm-activator.php
 */
function activate_admin_session_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-asm-activator.php';
	Admin_Session_Manager_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_admin_session_manager' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-asm-deactivator.php
 */
function deactivate_admin_session_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-asm-deactivator.php';
	Admin_Session_Manager_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_admin_session_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-asm.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_admin_session_manager() {
	$plugin = new Admin_Session_Manager();
	$plugin->run();
}
add_action( 'init', 'run_admin_session_manager' );

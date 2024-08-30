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
 * plugin defined constants
 */
define( 'ASM_NAME', 'asm' );
define( 'ASM_VERSION', '1.0.0' );
define( 'ASM_SITE_DATE_FORMAT', get_option( 'date_format', 'F j, Y' ) );
define( 'ASM_SITE_TIME_FORMAT', get_option( 'time_format', 'H:i' ) );
define( 'ASM_CACHE_KEY_USERS', 'asm_cached_users' );
define( 'ASM_CACHE_EXPIRY_USERS', 60 );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-asm-activator.php
 */
function activate_admin_session_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-asm-activator.php';
	ASM_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_admin_session_manager' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-asm-deactivator.php
 */
function deactivate_admin_session_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-asm-deactivator.php';
	ASM_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_admin_session_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-asm.php';

/**
 * The filter method to adjust heartbeat frequency
 * the default value is between 15-120 seconds
 */
function admin_session_manager_heartbeat_interval($settings) {
    $settings['interval'] = 120;
    return $settings;
}
add_filter('heartbeat_settings', 'admin_session_manager_heartbeat_interval');

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

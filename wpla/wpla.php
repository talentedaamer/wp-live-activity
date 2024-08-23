<?php

/**
 * The plugin bootstrap file
 * 
 * @since             1.0.0
 * @package           Wpla
 *
 * @wordpress-plugin
 * Plugin Name:       WP Live Activity
 * Plugin URI:        https://wordpress.org/plugins/wp-live-activity/
 * Description:       WP Live Activity is a WordPress plugin that provides real-time updates and notifications to your admin dashboard. It instantly alerts you to important events like new comments, user registrations, and plugin updates without needing a page refresh. Customize notifications and get popup alerts for urgent updates. Manage user roles and track activity with historical logs. Stay informed and manage your site effortlessly with WP Live Activity.
 * Version:           1.0.0
 * Author:            Aamer Shahzad
 * Author URI:        https://wordpress.org/plugins/wp-live-activity/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpla
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin activation/deactivation hooks
 * The code that runs during plugin activation and deactivation.
 * 
 * This action is documented in includes/class-wpla-activator.php &
 * This action is documented in includes/class-wpla-deactivator.php
 */
function activate_wpla() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpla-activator.php';
	Wpla_Activator::activate();
}
function deactivate_wpla() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpla-deactivator.php';
	Wpla_Deactivator::deactivate();
}

/**
 * register functions on activation and deactivation hooks
 */
register_activation_hook( __FILE__, 'activate_wpla' );
register_deactivation_hook( __FILE__, 'deactivate_wpla' );

/**
 * The core plugin class that is used to define
 * internationalization, admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpla.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wpla() {
	$plugin = new Wpla();
	$plugin->run();
}
// run_wpla();
add_action( 'init', 'run_wpla' );

function wpla_heartbeat_settings( $settings ) {
    $settings['interval'] = 15; //Anything between 15-120
    return $settings;
}
add_filter( 'heartbeat_settings', 'wpla_heartbeat_settings' );
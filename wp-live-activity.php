<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WP Live Activity
 * Plugin URI:        https://wordpress.org/plugins/wp-live-activity/
 * Description:       WP Live Activity is a WordPress plugin that provides real-time updates and notifications to your admin dashboard. It instantly alerts you to important events like new comments, user registrations, and plugin updates without needing a page refresh. Customize notifications and get popup alerts for urgent updates. Manage user roles and track activity with historical logs. Stay informed and manage your site effortlessly with WP Live Activity.
 * Author:            Aamer Shahzad
 * Author URI:        #
 * Version:           1.0.1
 * Text Domain:       wpla
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

 /**
  * TODOS
  * On plugin deactivation remove users meta
  * try localstorage to store users and comments, also rmove on plugin deactivation
  */
/**
 * exit if file is accessed directly
 */
defined('ABSPATH') || exit;

/**
 * plugin dir path and url
 */
if ( ! defined( 'WPLA_DIR_PATH' ) )
	define( 'WPLA_DIR_PATH', plugin_dir_path( __FILE__ ) );

// if ( ! defined( 'WPLA_DIR_URI' ) )
// 	define( 'WPLA_DIR_URI', plugin_dir_url( __FILE__ ) );

// /**
//  * define plugin name
//  */
// define( 'WPLA_NAME', 'WP Live Activity' );

/**
 * define plugin version
 */
define( 'WPLA_VERSION', '1.0.1' );

// if ( wp_script_is( 'heartbeat', 'registered' ) ) {
//     echo 'Heartbeat API is available.';
// } else {
//     echo 'Heartbeat API is not available.';
// }

/**
 * The core plugin class.
 */
		
require WPLA_DIR_PATH . 'includes/class-wpla-base.php';
require WPLA_DIR_PATH . 'includes/class-wp-live-activity.php';

/**
 * Begins execution of the plugin.
 *
 * initialize and create instance of the plugin
 */
function wpla_run_wp_live_activity() {
	$plugin = new WPLiveActivity(WPLA_VERSION);
}
// wpla_run_wp_live_activity();
add_action( 'init', 'wpla_run_wp_live_activity' );

function wpla_heartbeat_settings( $settings ) {
    $settings['interval'] = 15; //Anything between 15-120
    return $settings;
}
add_filter( 'heartbeat_settings', 'wpla_heartbeat_settings' );

function wpla_query_number_users() {
    return 5;
}
add_filter( 'wpla_filter_number_users', 'wpla_query_number_users' );

function wpla_user_avatar_size_callback() {
    return 40;
}
add_filter( 'wpla_user_avatar_size', 'wpla_user_avatar_size_callback' );

function wpla_comment_user_avatar_size_callback() {
    return 40;
}
add_filter( 'wpla_comment_user_avatar_size', 'wpla_comment_user_avatar_size_callback' );

// add_action( 'init', 'process_post' );
// function process_post() {
//     // wp_die();
// }

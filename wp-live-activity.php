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

// /**
//  * define plugin version
//  */
// define( 'WPLA_VERSION', '1.0.1' );

// if ( wp_script_is( 'heartbeat', 'registered' ) ) {
//     echo 'Heartbeat API is available.';
// } else {
//     echo 'Heartbeat API is not available.';
// }

/**
 * The core plugin class.
 */
require WPLA_DIR_PATH . 'includes/class-wp-live-activity.php';

/**
 * Begins execution of the plugin.
 *
 * initialize and create instance of the plugin
 */
function wpla_run_wp_live_activity() {
	$plugin = new WPLiveActivity();
}
wpla_run_wp_live_activity();

function wp_heartbeat_settings_3242( $settings ) {
    $settings['interval'] = 15; //Anything between 15-120
    return $settings;
}
add_filter( 'heartbeat_settings', 'wp_heartbeat_settings_3242' );

// add_action( 'init', 'process_post' );
// function process_post() {
//     $users = array(
//         array('username' => 'johnsmith', 'email' => 'john.smith@example.com', 'display_name' => 'John Smith'),
//         array('username' => 'janeDoe', 'email' => 'jane.doe@example.com', 'display_name' => 'Jane Doe'),
//         array('username' => 'robertsmith', 'email' => 'robert.smith@example.com', 'display_name' => 'Robert Smith'),
//         array('username' => 'maryjones', 'email' => 'mary.jones@example.com', 'display_name' => 'Mary Jones'),
//         array('username' => 'davidbrown', 'email' => 'david.brown@example.com', 'display_name' => 'David Brown'),
//         array('username' => 'lisaWhite', 'email' => 'lisa.white@example.com', 'display_name' => 'Lisa White'),
//         array('username' => 'michaelGreen', 'email' => 'michael.green@example.com', 'display_name' => 'Michael Green'),
//         array('username' => 'emilyClark', 'email' => 'emily.clark@example.com', 'display_name' => 'Emily Clark'),
//         array('username' => 'williamMiller', 'email' => 'william.miller@example.com', 'display_name' => 'William Miller'),
//         array('username' => 'susanMoore', 'email' => 'susan.moore@example.com', 'display_name' => 'Susan Moore'),
//         array('username' => 'richardTaylor', 'email' => 'richard.taylor@example.com', 'display_name' => 'Richard Taylor'),
//         array('username' => 'juliaWilson', 'email' => 'julia.wilson@example.com', 'display_name' => 'Julia Wilson'),
//         array('username' => 'jamesJohnson', 'email' => 'james.johnson@example.com', 'display_name' => 'James Johnson'),
//         array('username' => 'oliviaMartin', 'email' => 'olivia.martin@example.com', 'display_name' => 'Olivia Martin'),
//         array('username' => 'georgeLee', 'email' => 'george.lee@example.com', 'display_name' => 'George Lee'),
//         array('username' => 'natalieYoung', 'email' => 'natalie.young@example.com', 'display_name' => 'Natalie Young'),
//         array('username' => 'henryScott', 'email' => 'henry.scott@example.com', 'display_name' => 'Henry Scott'),
//         array('username' => 'lauraHarris', 'email' => 'laura.harris@example.com', 'display_name' => 'Laura Harris'),
//         array('username' => 'charlesWalker', 'email' => 'charles.walker@example.com', 'display_name' => 'Charles Walker'),
//         array('username' => 'sophiaHall', 'email' => 'sophia.hall@example.com', 'display_name' => 'Sophia Hall'),
//         array('username' => 'danielAllen', 'email' => 'daniel.allen@example.com', 'display_name' => 'Daniel Allen')
//     );
    
//     // Define the static password for all users
//     $password = 'password';
    
//     // Get the current timestamp
//     $current_timestamp = current_time('timestamp');
    
//     // Loop to create users
//     foreach ($users as $index => $user) {
//         $username = $user['username'];
//         $email = $user['email'];
//         $display_name = $user['display_name'];
    
//         // Create user
//         $user_id = wp_create_user($username, $password, $email);
    
//         // Check if user creation was successful
//         if (!is_wp_error($user_id)) {
//             // Update user data to set the display name and role
//             $user_data = array(
//                 'ID' => $user_id,
//                 'display_name' => $display_name,
//                 'role' => 'administrator'
//             );
//             wp_update_user($user_data);
    
//             // Calculate the timestamp, reducing by 10 minutes for each user
//             $timestamp_for_user = $current_timestamp - ($index * 600); // 600 seconds = 10 minutes
            
//             // Add meta field _wpla_last_active with calculated timestamp
//             update_user_meta($user_id, '_wpla_last_active', $timestamp_for_user);
    
//             echo "User $display_name created successfully with timestamp $timestamp_for_user.<br>";
//         } else {
//             echo "Error creating user $username: " . $user_id->get_error_message() . "<br>";
//         }
//     }
//     wp_die();
// }
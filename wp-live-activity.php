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
//     // $gmt_offset = get_option('gmt_offset');
//     // $gmt_time = gmdate('Y-m-d H:i:s');
//     // $site_time = date('Y-m-d H:i:s', strtotime($gmt_time) + $gmt_offset * HOUR_IN_SECONDS);
//     // // update_option('_test_time', $site_time);

//     // $stored_time = get_option('_test_time');
//     // // $time_ago = time_ago($site_time, $stored_time);

//     $date_format = get_option('date_format');
//     $time_format = get_option('time_format');
//     // $custom_time = current_time($date_format . ' '. $time_format);

//     // // echo 'time ago:';
//     // // print_r($time_ago);
//     // echo '<pre>';
//     // print_r($date_format);
//     // print_r($time_format);

//     // echo '<br>';
//     // print_r($custom_time);
//     // echo '<br>';
//     // print_r($site_time);

//     $current_timestamp = current_time( 'timestamp' );
//     $timeframe = 48 * 60 * 60; // For example, 60 minutes
    
//     $args = array(
//         'meta_key'     => '_wpla_last_active',
//         'meta_value'   => $current_timestamp - $timeframe,
//         'meta_compare' => '>=',
//         'fields'       => array( 'ID', 'display_name', 'user_email', 'user_login' )
//     );

//     $user_query = new WP_User_Query( $args );

//     if ( ! empty( $user_query->results ) ) {
//         $online_users = array();
//         foreach ( $user_query->results as $user ) {
//             $last_active_timestamp = get_user_meta( $user->ID, '_wpla_last_active', true );
//             $last_active_date = date_i18n($date_format, $last_active_timestamp);
//             $last_active_time = date_i18n($time_format, $last_active_timestamp);
//             $last_active_datetime = $last_active_date . ' ' . $last_active_time;

//             $avatar = get_avatar( $user->ID, 50 );
//             // $user_info = get_userdata( $user->ID );
            
//             $edit_link = get_edit_user_link( $user->ID );
            
//             // echo '<br>current_timestamp: ';
//             // print_r(date_i18n($date_format . ' ' . $time_format, $current_timestamp));

//             // echo '<br>last_active_timestamp: ';
//             // print_r(date_i18n($date_format . ' ' . $time_format, $last_active_timestamp));

//             $time_ago = time_ago($current_timestamp, $last_active_timestamp);

//             $online_users[] = array(
//                 'ID' => $user->ID,
//                 'display_name' => $user->display_name,
//                 'user_email' => $user->user_email,
//                 'user_login' => $user->user_login,
//                 // 'avatar' => $avatar,
//                 'date_format' => $date_format,
//                 'time_format' => $time_format,
//                 'last_active_date' => $last_active_date,
//                 'last_active_time' => $last_active_time,
//                 'last_active_timestamp' => $last_active_timestamp,
//                 'last_active_datetime' => $last_active_datetime,
//                 'time_ago' => $time_ago,
//                 'edit_link' => $edit_link,
//             );
//         }
//         // Send the online users data back to the frontend
//         $response['wpla_online_users'] = $online_users;
//     } else {
//         $response['wpla_online_users'] = array();
//     }

//     foreach($response['wpla_online_users'] as $user) {
//         echo '<pre>';
//         print_r($user);
//     }
    
//     wp_die();
// }

// function time_ago($time_now, $past_time) {
//     // Calculate the time difference in seconds
//     $time_difference = $time_now - $past_time;
//     // echo 'time diff:';
//     // print_r($time_difference);
//     // print_r(strtotime("1723636547"));
//     // echo '<br>';

//     // echo 'time diff:';
//     // print_r(strtotime($past_time));
//     // echo '<br>';

//     // Determine the time ago
//     if ($time_difference < 60) {
//         return 'just now';
//     } elseif ($time_difference < 3600) { // Less than 1 hour
//         $minutes = floor($time_difference / 60);
//         return "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
//     } elseif ($time_difference < 86400) { // Less than 1 day
//         $hours = floor($time_difference / 3600);
//         return "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
//     } elseif ($time_difference < 604800) { // Less than 1 week
//         $days = floor($time_difference / 86400);
//         return "$days day" . ($days > 1 ? 's' : '') . " ago";
//     } elseif ($time_difference < 2629743) { // Less than 1 month
//         $weeks = floor($time_difference / 604800);
//         return "$weeks week" . ($weeks > 1 ? 's' : '') . " ago";
//     } elseif ($time_difference < 31556926) { // Less than 1 year
//         $months = floor($time_difference / 2629743);
//         return "$months month" . ($months > 1 ? 's' : '') . " ago";
//     } else { // More than 1 year
//         $years = floor($time_difference / 31556926);
//         return "$years year" . ($years > 1 ? 's' : '') . " ago";
//     }
// }
<?php
/**
 * Plugin Name: WP Live Activity
 * Plugin URI: #
 * Description: WP Live Activity is a WordPress plugin that provides real-time updates and notifications to your admin dashboard. It instantly alerts you to important events like new comments, user registrations, and plugin updates without needing a page refresh. Customize notifications and get popup alerts for urgent updates. Manage user roles and track activity with historical logs. Stay informed and manage your site effortlessly with WP Live Activity.
 * Version: 1.0.1
 * Author: Aamer Shahzad
 * Author URI: #
 * License: GPLv2 or later
 * Text Domain: wpla
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLiveActivity {

    // plugin version
    private $version;

    // plugin directory path
    private $plugin_dir_path;

    // plugin directory uri
    private $plugin_dir_uri;

    public function __construct() {
        // Initialize member variables
        $this->version = '1.0.1';
        $this->plugin_dir_path = plugin_dir_path( __FILE__ );
        $this->plugin_dir_uri = plugin_dir_url( __FILE__ );

        add_action( 'admin_enqueue_scripts', array( $this, 'wpla_enqueue_admin_scripts' ) );
        add_filter( 'heartbeat_received', array( $this, 'wpla_heartbeat_received_callback' ), 10, 2 );
        add_action( 'wp_ajax_wpla_check_notifications', array( $this, 'wpla_ajax_check_notifications' ) );

        // overwrite how often 'heartbeat-tick' is called
        add_filter( 'heartbeat_interval', array( $this, 'wpla_heartbeat_interval_callback' ) );

        // dashboard widget for active users
        add_action( 'wp_dashboard_setup', array( $this, 'wpla_register_dashboard_widget' ) );
        
    }

    // Enqueue the JavaScript for handling Heartbeat API
    public function wpla_enqueue_admin_scripts() {
        wp_enqueue_script(
            'wpla-heartbeat',
            $this->plugin_dir_uri . 'assets/js/wpla-heartbeat.js',
            array( 'heartbeat' ),
            $this->version,
            true
        );

        // Localize script to pass variables to JavaScript
        // get current user id
        $current_user_id = get_current_user_id();
        wp_localize_script( 'wpla-heartbeat', 'wpla_params', array(
            'wpla_ajax_url' => admin_url( 'admin-ajax.php' ),
            'wpla_interval' => apply_filters( 'wpla_heartbeat_interval', 30 ),
            'wpla_current_user_id' => $current_user_id
        ));
    }

    // Heartbeat API callback function
    public function wpla_heartbeat_received_callback( $response, $data ) {
        
        if ( isset( $data['active_user'] ) ) {
            $active_user = $data['active_user'];
            $current_time = current_time( 'timestamp' );

            // Store the user's active state with a timestamp
            update_user_meta( $active_user, '_wpla_last_active', $current_time );

            // Optionally, add active users to the response for debugging or display
            $response['active_user'][] = $active_user;
        }


        if ( isset( $data['action'] ) && $data['action'] === 'wpla_check_notifications' ) {
            // Here you would query for notifications and add them to the response
            $response['notifications'] = array(
                'new_comments' => 5,
                'new_users' => 3
            );
        }

        return $response;
    }

    // AJAX action to handle notifications
    public function wpla_ajax_check_notifications() {
        // This function would handle notifications and send a response
        $notifications = array(
            'new_comments' => 5, // Example data
            'new_users' => 3 // Example data
        );

        wp_send_json_success( $notifications );
    }

    public function wpla_heartbeat_interval_callback( $interval ) {
        return 10;
    }

    public function wpla_register_dashboard_widget() {
        wp_add_dashboard_widget('wpla_active_users_widget', 'Active Users', array( $this, 'wpla_dashboard_widget_content' ));
    }

    public function wpla_dashboard_widget_content() {
        // Define the timeframe for user activity (e.g., 10 minutes)
        $timeframe = 10 * MINUTE_IN_SECONDS; // 10 minutes
    
        // Get current time
        $current_time = current_time( 'timestamp' );
    
        // Query users who have been active within the specified timeframe
        $args = array(
            'meta_key'     => '_wpla_last_active',
            'meta_value'   => $current_time - $timeframe,
            'meta_compare' => '>=',
            'fields'       => array( 'ID', 'display_name' )
        );
    
        $user_query = new WP_User_Query( $args );
    
        echo 'Users...';
        if ( ! empty( $user_query->results ) ) {
            echo '<ul>';
            foreach ( $user_query->results as $user ) {
                echo '<li>' . esc_html( $user->display_name ) . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No active users found.';
        }
    }
    
}

// Initialize the plugin
new WPLiveActivity();

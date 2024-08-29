<?php
/**
 * ASM_Utils class.
 *
 * This class is responsible for managing the plugin's utility functions.
 *
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/includes
 */

class ASM_Utils {

    /**
     * Configuration settings.
     */
    private $config;

    /**
     * Constructor.
     * Initializes the configurations.
     *
     * @param array $config The configuration array.
     */
    public function __construct( $config = null ) {
        $this->config = $config;
    }

    /**
     * Format a timestamp.
     *
     * @param string $timestamp The timestamp to format.
     * @return string The formatted date and time.
     */
    public function format_date( $timestamp ) {
        $date = date_i18n($this->config->get('site_date_format'), $timestamp);
        $time = date_i18n($this->config->get('site_time_format'), $timestamp);

        return $date . ' ' . $time;
    }

    public function is_user_logged( $user_id ) {
        // Get the session tokens for the user
        $sessions = WP_Session_Tokens::get_instance( $user_id );
        
        // Get all session tokens (if any)
        $all_sessions = $sessions->get_all();
        
        // Check if there are any active sessions
        if ( !empty( $all_sessions ) ) {
            return true;
        } else {
            return false;
        }
    }

    public function get_session_url( $user_id ) {
        $query_args = array(
            'action'  => 'asm_end_user_session',
            'user_id' => $user_id,
            '_wpnonce' => wp_create_nonce( 'asm_end_session_nonce' ),
        );

        return add_query_arg( $query_args, admin_url() );
    }

    public function time_ago($past_time) {
        $current_timestamp = current_time( 'timestamp' );
        $time_difference = $current_timestamp - $past_time;

        $seconds_in_minute = 60;
        $seconds_in_hour = 60 * 60;
        $seconds_in_day = 60 * 60 * 24;
        $seconds_in_week = 60 * 60 * 24 * 7;
        $seconds_in_month = 60 * 60 * 24 * 30; // Approximate month length
        $seconds_in_year = 60 * 60 * 24 * 365; // Approximate year length

        if ($time_difference < $seconds_in_minute) {
            return array('class' => 'just-now', 'value' => 'just now');
        } elseif ($time_difference < $seconds_in_hour) { // Less than 1 hour
            $minutes = floor($time_difference / $seconds_in_minute);
            return array('class' => 'less-than-hour', 'value' => "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago");
        } elseif ($time_difference < $seconds_in_day) { // Less than 1 day
            $hours = floor($time_difference / $seconds_in_hour);
            return array('class' => 'less-than-day', 'value' => "$hours hour" . ($hours > 1 ? 's' : '') . " ago");
        } elseif ($time_difference < $seconds_in_week) { // Less than 1 week
            $days = floor($time_difference / $seconds_in_day);
            return array('class' => 'less-than-week', 'value' => "$days day" . ($days > 1 ? 's' : '') . " ago");
        } elseif ($time_difference < $seconds_in_month) { // Less than 1 month
            $weeks = floor($time_difference / $seconds_in_week);
            return array('class' => 'less-than-month', 'value' => "$weeks week" . ($weeks > 1 ? 's' : '') . " ago");
        } elseif ($time_difference < $seconds_in_year) { // Less than 1 year
            $months = floor($time_difference / $seconds_in_month);
            return array('class' => 'less-than-year', 'value' => "$months month" . ($months > 1 ? 's' : '') . " ago");
        } else { // More than 1 year
            $years = floor($time_difference / $seconds_in_year);
            return array('class' => 'more-than-year', 'value' => "$years year" . ($years > 1 ? 's' : '') . " ago");
        }
    }
}

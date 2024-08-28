<?php
/**
 * Main users class
 *
 * This class defines the live users feature
 * and widget on the dashboard Showing live users activity
 *
 * @since      1.0.0
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/includes
 * @author     Aamer <talentedaamer@gmail.com>
 */
class ASM_Users {

	/**
	 * The main configurations of the plugin
	 *
	 * @since    1.0.0
	 * @access   private 
	 * @var      array    the saved plugin configurations.
	 */
	private  $config;

    /**
	 * Initialize the class and load configurations
	 *
	 * @since    1.0.0
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}

    public function heartbeat_users_received_data( $response, $data ) {
        if ( isset( $data['asm_active_user_id'] ) ) {
            $asm_active_user_id = $data['asm_active_user_id'];
            $current_time = current_time( 'timestamp' );
            update_user_meta( $asm_active_user_id, '_asm_last_active', $current_time );
            $response['asm_active_user_ids'][] = $asm_active_user_id;
        }

        if ( isset( $data['asm_is_user_active'] ) && $data['asm_is_user_active'] ) {
            $response["asm_active_users"] = $this->fetch_recent_users();
        } else {
            $response['asm_active_users'] = array();
        }

        return $response;
    }

    /**
     * Fetch live users.
     * 
     * @since    1.0.0
     * @return array
     */
    public function fetch_recent_users() {
        $current_timestamp = current_time( 'timestamp' );
        // $live_users = get_transient($this->config->get('cache_key_users'));
        // if ($live_users === false) {
            $timeframe_diff = 48 * 60 * 60; // For example, 60 minutes
            $args = array(
                'meta_query' => array(
                    array(
                        'key'     => '_asm_last_active',
                        'value'   => $current_timestamp - $timeframe_diff,
                        'compare' => '>=',
                        'type'    => 'NUMERIC'
                    )
                ),
                'fields'       => array('ID', 'display_name', 'user_email', 'user_login'),
                'orderby'      => 'meta_value_num', // Order by numeric meta value
                'meta_key'     => '_asm_last_active', // Meta key for ordering
                'order'        => 'DESC',
                'number'       => apply_filters( 'asm_filter_number_users', 5 ),
            );
            $user_query = new WP_User_Query( $args );

            $live_users = array();
            if ( ! empty( $user_query->results ) ) {
                foreach ( $user_query->results as $user ) {
                    /**
                     * get the stored timestap for the user
                     */
                    $last_active_timestamp = get_user_meta( $user->ID, '_asm_last_active', true );

                    /**
                     * convert timestamp to site date and time formats
                     */
                    $last_active_datetime = $this->format_datetime( $last_active_timestamp );

                    /**
                     * get user avatar
                     */
                    $avatar = get_avatar( $user->ID, apply_filters( 'asm_user_avatar_size', 40 ) );

                    /**
                     * get profile edit link for the user
                     */
                    $edit_link = get_edit_user_link( $user->ID );

                    /**
                     * time difference user was last logged in
                     */
                    $time_ago = $this->time_ago($last_active_timestamp);

                    $end_session_url = add_query_arg( array(
                        'action'  => 'asm_end_user_session',
                        'user_id' => $user->ID,
                        '_wpnonce' => wp_create_nonce( 'asm_end_session_nonce' ),
                    ), admin_url() );

                    $logged_in = $this->is_user_logged( $user->ID );

                    $live_users[] = array(
                        'ID' => $user->ID,
                        'author' => $user->display_name,
                        'author_email' => $user->user_email,
                        'user_login' => $user->user_login,
                        'author_avatar' => $avatar,
                        'last_active_timestamp' => $last_active_timestamp,
                        'last_active_datetime' => $last_active_timestamp,
                        'date_time' => $last_active_datetime,
                        'time_ago' => $time_ago,
                        'edit_link' => $edit_link,
                        'logged_in' => $logged_in,
                        'end_user_session_link' => esc_url( $end_session_url )
                    );
                }
            }

            // set_transient($this->config->get('cache_key_users'), $live_users, $this->config->get('cache_expiry_users'));
        // }

        return $live_users;
    }

    public function format_datetime( $timestamp ) {
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

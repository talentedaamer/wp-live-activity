<?php

/**
 * Users class to handle live users
 *
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/includes
 */

/**
 * Main users class
 *
 * This class defines the live users feature
 * and widget on the dashboard Showing live users activity
 *
 * @since      1.0.0
 * @package    Wpla
 * @subpackage Wpla/includes
 * @author     Aamer <talentedaamer@gmail.com>
 */
class Wpla_Users {

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

    /**
     * Fetch live users.
     * 
     * @since    1.0.0
     * @return array
     */
    public function fetch_recent_users() {
        $live_users = get_transient($this->config->get('cache_key_users'));
        if ($live_users === false) {
            $current_timestamp = current_time( 'timestamp' );
            $timeframe = 48 * 60 * 60; // For example, 60 minutes
            $number_users = apply_filters( 'wpla_filter_number_users', 10 );
            $args = array(
                // 'meta_query' => array(
                //     array(
                //         'key'     => '_wpla_last_active',
                //         'value'   => $current_timestamp - $timeframe,
                //         'compare' => '>=',
                //         'type'    => 'NUMERIC'
                //     )
                // ),
                'fields'       => array('ID', 'display_name', 'user_email', 'user_login'),
                'orderby'      => 'meta_value_num', // Order by numeric meta value
                'meta_key'     => '_wpla_last_active', // Meta key for ordering
                'order'        => 'DESC',
                'number'       => $number_users,
            );
            $user_query = new WP_User_Query( $args );

            $live_users = array();

            print_r($user_query->results);
            // die("hi");
            if ( ! empty( $user_query->results ) ) {
                foreach ( $user_query->results as $user ) {
                    /**
                     * get the stored timestap for the user
                     */
                    $last_active_timestamp = get_user_meta( $user->ID, '_wpla_last_active', true );

                    /**
                     * convert timestamp to site date and time formats
                     */
                    // $last_active_date = date_i18n($this->config->get('site_date_format'), strtotime($last_active_timestamp));
                    // $last_active_time = date_i18n($this->config->get('site_time_format'), strtotime($last_active_timestamp));
                    $last_active_datetime = $this->format_datetime($last_active_timestamp, $this->config);

                    /**
                     * get user avatar
                     */
                    $avatar = get_avatar( $user->ID, apply_filters( 'wpla_user_avatar_size', 40 ) );

                    /**
                     * get profile edit link for the user
                     */
                    $edit_link = get_edit_user_link( $user->ID );

                    /**
                     * time difference user was last logged in
                     */
                    $time_ago = $this->time_ago($current_timestamp, $last_active_timestamp);

                    $live_users[] = array(
                        'ID' => $user->ID,
                        'display_name' => $user->display_name,
                        'user_email' => $user->user_email,
                        'user_login' => $user->user_login,
                        'avatar' => $avatar,
                        'date_format' => $this->date_format,
                        'time_format' => $this->time_format,
                        'last_active_date' => $last_active_date,
                        'last_active_time' => $last_active_time,
                        'last_active_timestamp' => $last_active_timestamp,
                        'last_active_datetime' => $last_active_datetime,
                        'time_ago' => $time_ago,
                        'edit_link' => $edit_link,
                    );
                }
            }

            set_transient($this->config->get('cache_key_users'), $live_users, $this->config->get('cache_expiry_users'));
        }

        return $live_users;
    }

    public function format_datetime($timestamp, $config) {
        $date = date_i18n($config->get('site_date_format'), strtotime($timestamp));
        $time = date_i18n($config->get('site_time_format'), strtotime($timestamp));
        return $date . ' ' . $time;
    }

    public function time_ago($current_time, $past_time) {
        // Calculate the time difference in seconds
        $time_difference = $current_time - $past_time;
        // Define time constants
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

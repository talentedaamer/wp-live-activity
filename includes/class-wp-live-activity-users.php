<?php
/**
 * WP Live Activity users
 */
class WPLiveActivityUsers extends WplaBase {
    
    public function __construct() {
        parent::__construct();
        add_filter( 'heartbeat_received', array( $this, 'wpla_heartbeat_received_users_callback' ), 10, 2 );
        add_action( 'wp_dashboard_setup', array( $this, 'wpla_register_users_dashboard_widget' ) );
	}

    public function wpla_heartbeat_received_users_callback( $response, $data ) {
        if ( isset( $data['active_user'] ) ) {
            $active_user = $data['active_user'];
            $current_time = current_time( 'timestamp' );
            update_user_meta( $active_user, '_wpla_last_active', $current_time );
            $response['active_user'][] = $active_user;
        }

        if ( isset( $data['wpla_check_online_users'] ) && $data['wpla_check_online_users'] ) {
            $online_users = $this->wpla_get_users();
            $response["wpla_online_users"] = $online_users;
        } else {
            $response['wpla_online_users'] = array();
        }

        return $response;
    }

    public function wpla_get_users() {
        $current_timestamp = current_time( 'timestamp' );
        $timeframe = 48 * 60 * 60; // For example, 60 minutes
        $number_users = apply_filters( 'wpla_filter_number_users', 10 );
        $args = array(
            'meta_query' => array(
                array(
                    'key'     => '_wpla_last_active',
                    'value'   => $current_timestamp - $timeframe,
                    'compare' => '>=',
                    'type'    => 'NUMERIC'
                )
            ),
            'fields'       => array('ID', 'display_name', 'user_email', 'user_login'),
            'orderby'      => 'meta_value_num', // Order by numeric meta value
            'meta_key'     => '_wpla_last_active', // Meta key for ordering
            'order'        => 'DESC',
            'number'       => $number_users,
        );
        $user_query = new WP_User_Query( $args );
        $online_users = array();
        if ( ! empty( $user_query->results ) ) {
            foreach ( $user_query->results as $user ) {
                $last_active_timestamp = get_user_meta( $user->ID, '_wpla_last_active', true );
                $last_active_date = date_i18n($this->get_date_format(), $last_active_timestamp);
                $last_active_time = date_i18n($this->get_time_format(), $last_active_timestamp);
                $last_active_datetime = $last_active_date . ' ' . $last_active_time;
                $user_avatar_size = apply_filters( 'wpla_user_avatar_size', 50 );
                $avatar = get_avatar( $user->ID, $user_avatar_size );
                $edit_link = get_edit_user_link( $user->ID );
                $time_ago = $this->time_ago($current_timestamp, $last_active_timestamp);
                $online_users[] = array(
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

        return $online_users;
    }

    public function wpla_register_users_dashboard_widget() {
        wp_add_dashboard_widget(
            'wpla_active_users_widget',
            '<div><span class="dashicons dashicons-admin-site"></span> Active Users</div>',
            array( $this, 'wpla_comments_dashboard_widget_content' ),
            null,
            null,
            'normal',
            'high'
        );
    }

    public function wpla_comments_dashboard_widget_content() {
        ?>
        <div id="wpla-online-users" class="wpla-online-users">
            <div class="wpla-loader">
                <span class="wpla-pulse wpla-green"></span>
                <span class="wpla-loader-text">Loading...</span>
            </div>
        </div>
        <?php
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

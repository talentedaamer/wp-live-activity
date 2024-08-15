<?php
/**
 * WP Live Activity users
 */
class WPLiveActivityUsersActivity {
    private $date_format;
    private $time_format;

	public function __construct() {
        $this->date_format = get_option('date_format');
        $this->time_format = get_option('time_format');

        add_filter( 'heartbeat_received', array( $this, 'wpla_heartbeat_received_callback' ), 10, 2 );
        add_action( 'wp_dashboard_setup', array( $this, 'wpla_register_dashboard_widget' ) );
	}

    public function wpla_heartbeat_received_callback( $response, $data ) {
        
        if ( isset( $data['active_user'] ) ) {
            $active_user = $data['active_user'];
            $current_time = current_time( 'timestamp' );

            // Store the user's active state with a timestamp
            update_user_meta( $active_user, '_wpla_last_active', $current_time );

            // Optionally, add active users to the response for debugging or display
            $response['active_user'][] = $active_user;
        }

        if ( isset( $data['wpla_check_online_users'] ) && $data['wpla_check_online_users'] ) {
            $current_timestamp = current_time( 'timestamp' );
            $timeframe = 48 * 60 * 60; // For example, 60 minutes
            
            $args = array(
                'meta_key'     => '_wpla_last_active',
                'meta_value'   => $current_timestamp - $timeframe,
                'meta_compare' => '>=',
                'fields'       => array( 'ID', 'display_name', 'user_email', 'user_login' )
            );
        
            $user_query = new WP_User_Query( $args );
        
            if ( ! empty( $user_query->results ) ) {
                $online_users = array();
                foreach ( $user_query->results as $user ) {
                    $last_active_timestamp = get_user_meta( $user->ID, '_wpla_last_active', true );
                    $last_active_date = date_i18n($this->date_format, $last_active_timestamp);
                    $last_active_time = date_i18n($this->time_format, $last_active_timestamp);
                    $last_active_datetime = $last_active_date . ' ' . $last_active_time;

                    $avatar = get_avatar( $user->ID, 50 );
                    // $user_info = get_userdata( $user->ID );
                    
                    $edit_link = get_edit_user_link( $user->ID );

                    $time_ago = $this->time_ago($current_timestamp, $last_active_timestamp);

                    $online_users[] = array(
                        'ID' => $user->ID,
                        'display_name' => $user->display_name,
                        'user_email' => $user->user_email,
                        'user_login' => $user->user_login,
                        'avatar' => $avatar,
                        'date_format' => $date_format,
                        'time_format' => $time_format,
                        'last_active_date' => $last_active_date,
                        'last_active_time' => $last_active_time,
                        'last_active_timestamp' => $last_active_timestamp,
                        'last_active_datetime' => $last_active_datetime,
                        'time_ago' => $time_ago,
                        'edit_link' => $edit_link,
                    );
                }
                // Send the online users data back to the frontend
                $response['wpla_online_users'] = $online_users;
            } else {
                $response['wpla_online_users'] = array();
            }
        }

        return $response;

    }

    public function wpla_register_dashboard_widget() {
        wp_add_dashboard_widget('wpla_active_users_widget', 'Active Users', array( $this, 'wpla_dashboard_widget_content2' ), null, null, 'normal', 'high');
    }

    public function wpla_dashboard_widget_content2() {
        ?>
        <!-- <div class="wpla-online-users">
        <table class="widefat striped">
            <tbody>
            <tr>
                <td>
                    <div class="wpla-user-profile">
                        <div class="wpla-user-avatar"><img alt="" src="http://0.gravatar.com/avatar/0616eca3c03a19eb672d55849febe79e?s=50&amp;d=mm&amp;r=g" srcset="http://0.gravatar.com/avatar/0616eca3c03a19eb672d55849febe79e?s=100&amp;d=mm&amp;r=g 2x" class="avatar avatar-50 photo" height="50" width="50" loading="lazy" decoding="async"></div>
                        <div class="wpla-user-info">
                            <a href="#"><strong class="wpla-user-name">Aamer Shahzad</strong></a>
                            <span class="wpla-user-email">talentedaamer@gmail.com</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wpla-user-status">
                        <span class="wpla-pulse wpla-green"></span>
                        <strong class="wpla-online-time">Online</strong>
                    </div>
                </td>
                <td>
                    <div class="wpla-user-actions">
                        <a class="" href="http://wordpress.dev.local/wp-admin/profile.php"><span class="dashicons dashicons-edit"><span class="screen-reader-text">Edit</span></span></a>
                        <a class="button-link-delete" href="#" class="button-link-delete"><span class="dashicons dashicons-trash"><span class="screen-reader-text">Clear Session</span></span></a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </div> -->
        <div id="wpla-online-users" class="wpla-online-users">
            <div class="wpla-loader">
                <span class="wpla-pulse wpla-green"></span>
                <span class="wpla-loader-text">Loading...</span>
            </div>
        </div>
        <?php
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
            'fields'       => array( 'ID', 'display_name', 'user_email', 'user_login' )
        );
    
        $user_query = new WP_User_Query( $args );
    
        if ( ! empty( $user_query->results ) ) {
            echo '<table class="widefat striped">';
            foreach ( $user_query->results as $user ) {
                $avatar = get_avatar( $user->ID, 50 );
                $user_info = get_userdata( $user->ID );
                $last_active = get_user_meta( $user->ID, '_wpla_last_active', true );
                $edit_link = get_edit_user_link( $user->ID );
                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                // echo '<li><strong>Role:</strong> ' . esc_html( implode( ', ', $user_info->roles ) ) . '</li>';
                // echo '<li><strong>Last Active:</strong> ' . esc_html( wp_date( $time_format, $last_active ) ) . '</li>';
                
                // // You can access other fields as well
                // echo '<li><strong>First Name:</strong> ' . esc_html( $user_info->first_name ) . '</li>';
                // echo '<li><strong>Last Name:</strong> ' . esc_html( $user_info->last_name ) . '</li>';
                // echo '<li><strong>Registration Date:</strong> ' . esc_html( $user_info->user_registered ) . '</li>';
                // echo '<li>' . esc_html( $user->user_email ) . '</li>';
                ?>
                <tr>
                    <td class="author column-author" data-colname="Author">
                        <strong>
                            <?php echo $avatar; ?>
                            <?php echo esc_html( $user->display_name ); ?>
                        </strong>
                        <br>
                        <?php echo esc_html( $user->user_email ); ?>
                    </td>
                    <td>
                        <span class="dashicons dashicons-marker"><span class="screen-reader-text">Online</span></span>
                        <br>
                        <strong>Online</strong>
                    </td>
                    <td class="alignright">
                        <?php if ($edit_link) { ?>
                            <a href="<?php echo $edit_link; ?>"><span class="dashicons dashicons-edit"><span class="screen-reader-text">Edit</span></span></a>
                        <?php } ?>
                        <a href="#" class="button-link-delete"><span class="dashicons dashicons-trash"><span class="screen-reader-text">Clear Session</span></span></a>
                    </td>
                </tr>
                <?php
            }
            echo '</table>';
        } else {
            echo 'No active users found.';
        }
    }

    public function time_ago($current_time, $past_time) {
        // Calculate the time difference in seconds
        $time_difference = $current_time - $past_time;
        // Determine the time ago
        if ($time_difference < 60) {
            return 'just now';
        } elseif ($time_difference < 3600) { // Less than 1 hour
            $minutes = floor($time_difference / 60);
            return "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
        } elseif ($time_difference < 86400) { // Less than 1 day
            $hours = floor($time_difference / 3600);
            return "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
        } elseif ($time_difference < 604800) { // Less than 1 week
            $days = floor($time_difference / 86400);
            return "$days day" . ($days > 1 ? 's' : '') . " ago";
        } elseif ($time_difference < 2629743) { // Less than 1 month
            $weeks = floor($time_difference / 604800);
            return "$weeks week" . ($weeks > 1 ? 's' : '') . " ago";
        } elseif ($time_difference < 31556926) { // Less than 1 year
            $months = floor($time_difference / 2629743);
            return "$months month" . ($months > 1 ? 's' : '') . " ago";
        } else { // More than 1 year
            $years = floor($time_difference / 31556926);
            return "$years year" . ($years > 1 ? 's' : '') . " ago";
        }
    }
}

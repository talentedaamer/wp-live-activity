<?php
/**
 * WP Live Activity users
 */
class WPLiveActivityComments {
    
    public function __construct() {
        add_filter( 'heartbeat_received', array( $this, 'wpla_heartbeat_received_comments_callback' ), 10, 2 );
        add_action( 'wp_dashboard_setup', array( $this, 'wpla_register_comments_dashboard_widget' ) );
	}

    public function wpla_heartbeat_received_comments_callback( $response, $data ) {
        if ( isset( $data['wpla_check_live_comments'] ) && $data['wpla_check_live_comments'] ) {
            $response["wpla_live_comments"] = "comments";
        }

        return $response;
    }

    public function wpla_register_comments_dashboard_widget() {
        wp_add_dashboard_widget(
            'wpla_live_comments_widget',
            'Live Comments',
            array( $this, 'wpla_comments_dashboard_widget_content' ),
            null,
            null,
            'normal',
            'high'
        );
    }

    public function wpla_comments_dashboard_widget_content() {
        ?>
        <div id="wpla-live-comments" class="wpla-live-comments">
            <div class="wpla-loader">
                <span class="wpla-pulse wpla-green"></span>
                <span class="wpla-loader-text">Loading...</span>
            </div>
        </div>
        <?php
    }
}

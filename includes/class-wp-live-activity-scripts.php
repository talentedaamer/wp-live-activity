<?php
/**
 * WP Live Activity plugin scripts loader
 */
class WPLiveActivityScripts {

    private $version;

	public function __construct() {
        $this->version = '1.0.1';
		add_action( 'admin_enqueue_scripts', array( $this, 'wpla_enqueue_admin_scripts' ) );
	}

	function wpla_enqueue_admin_scripts() {
        $screen = get_current_screen();

        wp_enqueue_script(
            'wpla-wp-live-activity-users',
            plugins_url( 'assets/js/wp-live-activity-users.js', dirname( __FILE__ ) ),
            array( 'heartbeat' ),
            $this->version,
            true
        );

        $current_user_id = get_current_user_id();
        wp_localize_script( 'wpla-wp-live-activity-users', 'wpla_params', array(
            'wpla_ajax_url' => admin_url( 'admin-ajax.php' ),
            'wpla_interval' => apply_filters( 'wpla_heartbeat_interval', 30 ),
            'wpla_current_user_id' => $current_user_id
        ));

        // Check if we are on the dashboard page
        if ( $screen->id === 'dashboard' ) {
            wp_enqueue_style(
                'wpla-wp-live-activity-users',
                plugins_url( 'assets/css/wp-live-activity-users.css', dirname( __FILE__ ) ),
                array(),
                $this->version,
                false
            );
        }
	}
}

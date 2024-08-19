<?php
/**
 * WP Live Activity plugin scripts loader
 */
class WPLiveActivityScripts {

    private $version;

    private $wplaComments;

    private $wplaUsers;

	public function __construct() {
        $this->version = '1.0.1';
		add_action( 'admin_enqueue_scripts', array( $this, 'wpla_enqueue_admin_scripts' ) );
	}

	function wpla_enqueue_admin_scripts() {
        $screen = get_current_screen();
        // Check if we are on the dashboard page
        if ( $screen->id === 'dashboard' ) {
            wp_enqueue_style(
                'wpla-wp-live-activity-users',
                plugins_url( 'assets/css/wp-live-activity-widgets.css', dirname( __FILE__ ) ),
                array(),
                $this->version,
                false
            );
        }
	}

    public function wpla_load_users_script($users = array()) {
        wp_enqueue_script(
            'wpla-wp-live-activity-users',
            plugins_url( 'assets/js/wp-live-activity-users.js', dirname( __FILE__ ) ),
            array( 'heartbeat' ),
            $this->version,
            true
        );
        $localize_script_data = array(
            'wpla_current_user_id' => get_current_user_id(),
            'wpla_users' => $users,
            'nonce' => wp_create_nonce( 'wpla_nonce' ),
        );
        wp_localize_script( 'wpla-wp-live-activity-users', 'wpla_users_params', $localize_script_data);
    }

    public function wpla_load_comments_script($comments = array()) {
        wp_enqueue_script(
            'wpla-wp-live-activity-comments',
            plugins_url( 'assets/js/wp-live-activity-comments.js', dirname( __FILE__ ) ),
            array( 'heartbeat' ),
            $this->version,
            true
        );
        $localize_script_data = array(
            'wpla_comments' => $comments,
            'nonce' => wp_create_nonce( 'wpla_nonce' ),
        );
        wp_localize_script( 'wpla-wp-live-activity-comments', 'wpla_comments_params', $localize_script_data);
    }
}

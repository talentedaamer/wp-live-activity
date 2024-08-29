<?php
/**
 * Register a dashboard widget to display live users.
 *
 * @since      1.0.0
 *
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/includes
 */

class ASM_Dashboard_Widget_Users {

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
     * Register the widget with the WordPress dashboard.
     */
    public function register_users_dashboard_widget() {
        wp_add_dashboard_widget(
            'asm_users_widget',
            __( 'Live Users', 'asm' ),
            array( $this, 'display_users_dashboard_widget' ),
            'normal',
            'core'
        );
    }

    /**
     * Display the content of the widget.
     */
    public function display_users_dashboard_widget() {
        ?>
        <div id="asm-sync">
            <span class="asm-rotate dashicons dashicons-update"></span>
            <span class="asm-loader-text">data syncing...</span>
        </div>
        
        <div id="asm-users" class="asm-users">
            <div class="asm-loader">
                <span class="asm-pulse asm-green"></span>
                <span class="asm-loader-text">Loading users...</span>
            </div>
        </div>
        <?php
    }

    public function asm_handle_end_user_session() {
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'asm_end_user_session' && isset( $_GET['user_id'] ) ) {
            // Verify the nonce for security
            if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'asm_end_session_nonce' ) ) {
                wp_die( 'Security check failed' );
            }
    
            // Check permissions
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( 'You do not have permission to perform this action.' );
            }
    
            $user_id = intval( $_GET['user_id'] );
    
            if ( ! $user_id ) {
                wp_die( 'Invalid user ID.' );
            }
    
            // End the user session
            if ( function_exists( 'wp_destroy_user_session' ) ) {
                wp_destroy_user_session( $user_id );
            } else {
                // Fallback for older WordPress versions
                delete_user_meta( $user_id, 'session_tokens' );
            }
    
            // Redirect to avoid the action being triggered again on page refresh
            wp_redirect( remove_query_arg( array( 'action', 'user_id', '_wpnonce' ) ) );
            exit;
        }
    }
}



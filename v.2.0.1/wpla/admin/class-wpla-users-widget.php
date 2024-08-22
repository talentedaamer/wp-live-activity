<?php
/**
 * Register a dashboard widget to display live users.
 *
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/admin
 */

class Wpla_Users_Widget {

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

    public function heartbeat_received_users_data() {
        if ( isset( $data['wpla_check_live_comments'] ) && $data['wpla_check_live_comments'] ) {
            $comments_instance = new Wpla_Comments($this->config);
            $response["wpla_live_comments"] = $comments_instance->fetch_recent_comments();
        } else {
            $response['wpla_live_comments'] = array();
        }

        return $response;
    }

    /**
     * Register the widget with the WordPress dashboard.
     */
    public function register_users_dashboard_widget() {
        wp_add_dashboard_widget(
            'wpla_users_widget',
            __( 'Live Users', 'wpla' ),
            array( $this, 'display_users_dashboard_widget' )
        );
    }

    /**
     * Display the content of the widget.
     */
    public function display_users_dashboard_widget() {
        ?>
        <div id="wpla-users" class="wpla-users">
            <div class="wpla-loader">
                <span class="wpla-pulse wpla-green"></span>
                <span class="wpla-loader-text">Loading users...</span>
            </div>
        </div>
        <?php
    }

}



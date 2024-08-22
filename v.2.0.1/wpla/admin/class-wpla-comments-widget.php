<?php
/**
 * Register a dashboard widget to display live comments.
 *
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/admin
 */

class Wpla_Comments_Widget {

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

    public function heartbeat_comments_received_data( $response, $data ) {
        if ( isset( $data['wpla_check_comments'] ) && $data['wpla_check_comments'] ) {
            $comments_instance = new Wpla_Comments($this->config);
            $response["wpla_comments"] = $comments_instance->fetch_recent_comments();
        } else {
            $response['wpla_comments'] = array();
        }

        return $response;
    }

    /**
     * Register the widget with the WordPress dashboard.
     */
    public function register_comments_dashboard_widget() {
        wp_add_dashboard_widget(
            'wpla_comments_widget',
            __( 'Live Comments', 'wpla' ),
            array( $this, 'display_comments_dashboard_widget' )
        );
    }

    /**
     * Display the content of the widget.
     */
    public function display_comments_dashboard_widget() {
        ?>
        <div id="wpla-comments" class="wpla-comments">
            <div class="wpla-loader">
                <span class="wpla-pulse wpla-green"></span>
                <span class="wpla-loader-text">Loading comments...</span>
            </div>
        </div>
        <?php
    }

}


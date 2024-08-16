<?php
/**
 * The core wp live activity plugin class
 */

class WPLiveActivity {

    /**
     * hooks and filters loader
     * load and register hooks and filters with wordpress
     */
    private $loader;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies.
	 */
	private function load_dependencies() {

		/**
		 * class responsible for actions and filters registration
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-live-activity-scripts.php';

		/**
		 * active online users
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-live-activity-users.php';

		/**
		 * live comments
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-live-activity-comments.php';
	}

	private function define_admin_hooks() {
		$plugin_scripts = new WPLiveActivityScripts();
		$wpla_live_activity_users = new WPLiveActivityUsers();
		$wpla_live_activity_comments = new WPLiveActivityComments();
	}
}

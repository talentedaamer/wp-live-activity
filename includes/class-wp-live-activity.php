<?php
/**
 * The core wp live activity plugin class
 */

class WPLiveActivity extends WplaBase {
	private static $instance = null;

    /**
     * hooks and filters loader
     * load and register hooks and filters with wordpress
     */
    private $loader;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct($version) {
		self::$version = '1.0.0';
        self::$cache_key_users = 'wp_live_activity_users';
        self::$cache_key_comments = 'wp_live_activity_comments';
        self::$cache_users_expiry = 120;
        self::$cache_comments_expiry = 120;
        self::$date_format = get_option('date_format');
        self::$time_format = get_option('time_format');

		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new WPLiveActivity();
        }
        return self::$instance;
    }

	/**
	 * Load the required dependencies.
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-live-activity-scripts.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-live-activity-users.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-live-activity-comments.php';
	}

	private function define_admin_hooks() {
		$plugin_scripts = new WPLiveActivityScripts();

		$wpla_users = new WPLiveActivityUsers();
		$wpla_comments = new WPLiveActivityComments();

		$plugin_scripts->wpla_load_users_script($wpla_users->wpla_get_users());
		$plugin_scripts->wpla_load_comments_script($wpla_comments->wpla_get_comments());
	}
}

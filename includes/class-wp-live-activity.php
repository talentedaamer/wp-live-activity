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

	private $cache_key;
	private $cache_expiry;
	private $date_format;
	private $time_format;
	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		$this->cache_key = 'wpla_cached_comments';
		$this->cache_expiry = 2 * 60;
		$this->date_format = get_option('date_format');
        $this->time_format = get_option('time_format');

		$this->load_dependencies();
		$this->define_admin_hooks();
		// add_action('plugins_loaded', 'wpla_initialize_plugin');
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

		$wpla_users = new WPLiveActivityUsers($this->date_format, $this->time_format);
		$wpla_comments = new WPLiveActivityComments($this->cache_key, $this->cache_expiry);

		$plugin_scripts->wpla_load_users_script($wpla_users->wpla_get_users());
		$plugin_scripts->wpla_load_comments_script($wpla_comments->wpla_get_comments());
	}
}

<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpla
 * @subpackage Wpla/includes
 * @author     Aamer <talentedaamer@gmail.com>
 */
class Wpla {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpla_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The main configurations of the plugin
	 *
	 * @since    1.0.0
	 * @access   private 
	 * @var      array    the saved plugin configurations.
	 */
	private  $config;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->load_dependencies();

		/**
		 * init config class after it is required from the load_depencies() method
		 * initialize dynamic config vaules from options
		 */
		$this->config = WPLA_Config::get_instance();

		// set date & time configurations from site settings
		$this->config->set('site_date_format', get_option('date_format'));
		$this->config->set('site_time_format', get_option('time_format'));

		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpla-loader.php';

		/**
		 * The class responsible for defining configurations, internationalization, comments, and users functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpla-config.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpla-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpla-comments.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpla-users.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpla-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpla-comments-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpla-users-widget.php';

		/**
		 * init the Wpla loader
		 */
		$this->loader = new Wpla_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpla_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Wpla_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$wpla_admin = new Wpla_Admin($this->config);
		
		$this->loader->add_action( 'admin_enqueue_scripts', $wpla_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $wpla_admin, 'enqueue_scripts' );

		$wpla_comments_widget = new Wpla_Comments_Widget($this->config);
		$this->loader->add_action( 'heartbeat_received', $wpla_comments_widget, 'heartbeat_comments_received_data', 10, 2 );
		$this->loader->add_action( 'wp_dashboard_setup', $wpla_comments_widget, 'register_comments_dashboard_widget' );

		$wpla_users_widget = new Wpla_Users_Widget($this->config);
		$this->loader->add_action( 'heartbeat_received', $wpla_users_widget, 'heartbeat_users_received_data', 10, 2 );
		$this->loader->add_action( 'wp_dashboard_setup', $wpla_users_widget, 'register_users_dashboard_widget' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wpla_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}

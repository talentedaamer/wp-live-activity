<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/talentedaamer/admin-session-manager
 * @since      1.0.0
 *
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/admin
 * @author     Aamer Shahzad <talentedaamer@gmail.com>
 */
class ASM_Admin {

	/**
	 * The main configurations of the plugin
	 *
	 * @since    1.0.0
	 * @access   private 
	 * @var      array    the saved plugin configurations.
	 */
	private  $config;

	/**
	 * Plugin name
	 *
	 * @since    1.0.0
	 * @access   private 
	 * @var      string    plugin name
	 */
	private $plugin_name;

	/**
	 * Plugin version
	 *
	 * @since    1.0.0
	 * @access   private 
	 * @var      string    plugin version number
	 */
	private $plugin_version;

    /**
	 * Initialize the class and load configurations
	 *
	 * @since    1.0.0
	 */
	public function __construct( $config ) {
		$this->config = $config;
		$this->plugin_name = $this->config->get('name');
		$this->plugin_version = $this->config->get('version');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * Main plugin stylesheet for the admin area.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/asm-admin.css', array(), $this->plugin_version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$asm_users = new ASM_Users( $this->config );
		
		/**
		 * localize data for the asm-admin.js file
		 */
		$localized_data = array(
            'current_user_id' => get_current_user_id(),
            'asm_active_users' => $asm_users->fetch_recent_users(),
			'is_current_user_admin' => current_user_can('administrator'),
            'nonce' => wp_create_nonce( 'asm_nonce' ),
        );

		/**
		 * custom confirmation dialog js
		 */
		// wp_enqueue_script( $this->plugin_name . 'confirm', plugin_dir_url( __FILE__ ) . 'js/asm-dialog.js', array(), $this->plugin_version, false );

		/**
		 * Main plugin script file for the admin area.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/asm-admin.js', array( 'jquery' ), $this->plugin_version, false );

		/**
		 * localize the admin script
		 * and pass the above localized_data to the script
		 * localized data is available under wpla_params variable in wpla-admin.js
		 */
        wp_localize_script( $this->plugin_name, 'asm_params', $localized_data);

	}

}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpla
 * @subpackage Wpla/admin
 * @author     Aamer <talentedaamer@gmail.com>
 */
class Wpla_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpla_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpla_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->config->get('name'), plugin_dir_url( __FILE__ ) . 'css/wpla-admin.css', array(), $this->config->get('version'), 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// TODO:
		// localize script and pass comments
		// $wpla_comments = new Wpla_Comments();

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpla_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpla_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->config->get('name'), plugin_dir_url( __FILE__ ) . 'js/wpla-admin.js', array( 'jquery' ), $this->config->get('version'), false );

	}

}

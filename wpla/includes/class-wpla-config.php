<?php
/**
 * WPLA_Config class.
 *
 * This class is responsible for managing the plugin's configuration settings.
 *
 * @package    WPLA
 * @subpackage WPLA/includes
 */

class WPLA_Config {

    /**
     * The single instance of the class.
     *
     * @var WPLA_Config
     */
    private static $instance = null;

    /**
     * Configuration settings.
     *
     * @var array
     */
    private $config = array();

    /**
     * Constructor.
     * Initializes the configurations
     */
    private function __construct() {
        $saved_config = get_option( 'wpla_plugin_config' );

        // If no settings exist in the database, use default ones.
        $this->config = ! empty( $saved_config ) ? $saved_config : array(
            'name'                  => 'wpla',
            'version'               => '1.0.0',
            'cache_key_users'       => 'wpla_cached_users',
            'cache_key_comments'    => 'wpla_cached_comments',
            'cache_expiry_users'    => 120,
            'cache_expiry_comments' => 120,
            'site_date_format'      => 'F j, Y',
            'site_time_format'      => 'H:i',
        );
    }

    /**
     * Gets the single instance of the class.
     *
     * @return WPLA_Config
     */
    public static function get_instance() {
        if ( self::$instance == null ) {
            self::$instance = new WPLA_Config();
        }
        return self::$instance;
    }

    /**
     * Get a configuration value.
     *
     * @param string $key The configuration key.
     * @return mixed The configuration value or null if not found.
     */
    public function get( $key ) {
        return isset( $this->config[ $key ] ) ? $this->config[ $key ] : null;
    }

    /**
     * Set a configuration value.
     *
     * @param string $key The configuration key.
     * @param mixed  $value The configuration value.
     */
    public function set( $key, $value ) {
        $this->config[ $key ] = $value;
        update_option( 'wpla_plugin_config', $this->config );
    }

    /**
     * Get all configurations.
     *
     * @return array All configurations.
     */
    public function get_all() {
        return $this->config;
    }
}


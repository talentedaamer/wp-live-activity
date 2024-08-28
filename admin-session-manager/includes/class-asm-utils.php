<?php
/**
 * ASM_Utils class.
 *
 * This class is responsible for managing the plugin's utility functions.
 *
 * @package    Admin_Session_Manager
 * @subpackage Admin_Session_Manager/includes
 */

class ASM_Utils {

    /**
     * Configuration settings.
     */
    private $config;

    /**
     * Constructor.
     * Initializes the configurations.
     *
     * @param array $config The configuration array.
     */
    public function __construct( $config = null ) {
        $this->config = $config;
    }

    /**
     * Format a timestamp.
     *
     * @param string $timestamp The timestamp to format.
     * @return string The formatted date and time.
     */
    public function format_date( $timestamp ) {
        $date = date_i18n($this->config->get('site_date_format'), $timestamp);
        $time = date_i18n($this->config->get('site_time_format'), $timestamp);

        return $date . ' ' . $time;
    }
}

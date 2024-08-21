<?php

/**
 * The plugin bootstrap file
 * @link              https://example.com
 * @since             1.0.0
 * @package           Wpla
 *
 * @wordpress-plugin
 * Plugin Name:       WP Live Activity
 * Plugin URI:        https://example.com
 * Description:       Desc...
 * Version:           1.0.0
 * Author:            Aamer
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpla
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPLA_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpla-activator.php
 */
function activate_wpla() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpla-activator.php';
	Wpla_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpla-deactivator.php
 */
function deactivate_wpla() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpla-deactivator.php';
	Wpla_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpla' );
register_deactivation_hook( __FILE__, 'deactivate_wpla' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpla.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpla() {

	$plugin = new Wpla();
	$plugin->run();

}
run_wpla();

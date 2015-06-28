<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.jbwebservices.com
 * @since             1.0.0
 * @package           WPCLT_Presentations
 *
 * @wordpress-plugin
 * Plugin Name:       WPCLT - Presentations
 * Plugin URI:        www.wpclt.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jamie Bowman
 * Author URI:        http://www.jbwebservices.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpclt-presentations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpclt-presentations-activator.php
 */
function activate_WPCLT_Presentations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpclt-presentations-activator.php';
	WPCLT_Presentations_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpclt-presentations-deactivator.php
 */
function deactivate_WPCLT_Presentations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpclt-presentations-deactivator.php';
	WPCLT_Presentations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WPCLT_Presentations' );
register_deactivation_hook( __FILE__, 'deactivate_WPCLT_Presentations' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpclt-presentations.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WPCLT_Presentations() {

	$plugin = new WPCLT_Presentations();
	$plugin->run();

}
run_WPCLT_Presentations();

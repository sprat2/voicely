<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://voicely.org
 * @since             1.0.0
 * @package           Voicely_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Voicely Core
 * Plugin URI:        http://voicely.org
 * Description:       Enables voicely core functionality.
 * Version:           1.0.0
 * Author:            Nathan Rollins
 * Author URI:        http://voicely.org
 * Text Domain:       voicely
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-voicely-core-activator.php
 */
function activate_voicely_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-voicely-core-activator.php';
	Voicely_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-voicely-core-deactivator.php
 */
function deactivate_voicely_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-voicely-core-deactivator.php';
	Voicely_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_voicely_core' );
register_deactivation_hook( __FILE__, 'deactivate_voicely_core' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-voicely-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_voicely_core() {
	
	$plugin = new Voicely_Core();
	$plugin->run();

}
run_voicely_core();

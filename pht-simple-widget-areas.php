<?php

/**
 *
 * @link              https://github.com/pehaa/pht-simple-widget-areas
 * @since             1.0.0
 * @package           PeHaa_Themes_Simple_Widget_Areas
 *
 * @wordpress-plugin
 * Plugin Name:       PHT Simple Widget Areas
 * Plugin URI:        https://github.com/pehaa/pht-simple-widget-areas
 * Description:       Simple user interface that allows adding widgetized areas. 
 * Version:           1.0.0
 * Author:            PeHaa THEMES
 * Author URI:        http://wptemplates.pehaa.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pht-simple-widget-areas
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/pehaa/pht-simple-widget-areas
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pht-simple-widget-areas-activator.php
 */
function activate_pht_simple_widget_areas() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pht-simple-widget-areas-activator.php';
	PHT_Simple_Widget_Areas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pht-simple-widget-areas-deactivator.php
 */
function deactivate_pht_simple_widget_areas() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pht-simple-widget-areas-deactivator.php';
	PHT_Simple_Widget_Areas_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pht_simple_widget_areas' );
register_deactivation_hook( __FILE__, 'deactivate_pht_simple_widget_areas' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pht-simple-widget-areas.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pht_simple_widget_areas() {

	$plugin = new PHT_Simple_Widget_Areas();
	$plugin->run();

}
run_pht_simple_widget_areas();

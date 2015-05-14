<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       https://github.com/pehaa/pht-simple-widget-areas
 * @since      1.0.0
 *
 * @package    PHT_Simple_Widget_Areas
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_name = 'pht_simple_widget_areas';

$phtswa_sidebars = get_option( $option_name, array() );
$phtswa_sidebars_ids = array();

foreach ( $phtswa_sidebars as $sidebar_index => $sidebar ) {
	$phtswa_sidebars_ids[] = $sidebar['id'];
}

$widgetized_sidebars = get_option( 'sidebars_widgets', array() );

foreach ( $phtswa_sidebars_ids as $id ) {
	unset( $widgetized_sidebars[$id] );
}

update_option( 'sidebars_widgets', $widgetized_sidebars );

delete_option( $option_name );

// For site options in multisite
delete_site_option( $option_name ); 

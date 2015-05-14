# PHT Simple Widget Areas #
**Contributors:** pehaa  
**Tags:** wordpress, wordpress plugin, plugin, wordpress admin, sidebars, custom sidebars, dynamic sidebar, simple, widget, widgets, admin  
**Requires at least:** 3.6.0  
**Tested up to:** 4.2.2  
**Stable tag:** 1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Add prefixed widget areas in your admin widgets.php

## Description ##
This plugin is particularly addressed to theme developers. It adds a user interface allowing adding and removing wigetized area (sidebars) in the WordPress admin (wp-admin/widgets.php).

Note that nothing will happen on the front-end side.

You can refer to the area id (displayed below the area name in widgets.php) and use dynamic_sidebar() function.

The default output tag is 'li' with classes 'widget phtswa-widget'. The tag and classes can be modified by the 'pht-simple-widget-areas_widget_tag' and 'pht-simple-widget-areas_widget_classes' filters. You can also modify the widget title tag (h2) with 'pht-simple-widget-areas_widget_before_title' and 'pht-simple-widget-areas_widget_after_title'.


Use PHT_Simple_Widget_Areas::$pht_simple_widget_areas or get_option( 'pht_simple_widget_areas' ) for an array of all generated areas.

## Installation ##
Upload `pht-simple-widget-areas` to the `/wp-content/plugins/` directory

## Changelog ##
### 1.0.0 ###
* Initial release.
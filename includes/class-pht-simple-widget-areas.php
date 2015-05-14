<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       https://github.com/pehaa/pht-simple-widget-areas
 * @since      1.0.0
 *
 * @package    PHT_Simple_Widget_Areas
 * @subpackage PHT_Simple_Widget_Areas/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    PHT_Simple_Widget_Areas
 * @subpackage PHT_Simple_Widget_Areas/includes
 * @author     PeHaa THEMES <info@pehaa.com>
 */
class PHT_Simple_Widget_Areas {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PHT_Simple_Widget_Areas_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string   The string used to uniquely identify this plugin.
	 */
	protected $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	public static $pht_simple_widget_areas = array();

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->name = 'pht-simple-widget-areas';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		self::$pht_simple_widget_areas = PHT_Simple_Widget_Areas_Admin::$pht_simple_widget_areas;

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - PHT_Simple_Widget_Areas_Loader. Orchestrates the hooks of the plugin.
	 * - PHT_Simple_Widget_Areas_i18n. Defines internationalization functionality.
	 * - PHT_Simple_Widget_Areas_Admin. Defines all hooks for the dashboard.
	 * - PHT_Simple_Widget_Areas_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pht-simple-widget-areas-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pht-simple-widget-areas-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pht-simple-widget-areas-admin.php';

		$this->loader = new PHT_Simple_Widget_Areas_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the PHT_Simple_Widget_Areas_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PHT_Simple_Widget_Areas_i18n();
		$plugin_i18n->set_domain( $this->get_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new PHT_Simple_Widget_Areas_Admin( $this->get_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_loaded', $plugin_admin, 'get_registered_sidebars', 11 );
		$this->loader->add_action( 'sidebar_admin_page', $plugin_admin, 'display_admin_page' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widget_areas', 11 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'update_widget_areas_options' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    PHT_Simple_Widget_Areas_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

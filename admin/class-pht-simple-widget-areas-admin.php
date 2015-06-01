<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://github.com/pehaa/pht-simple-widget-areas
 * @since      1.0.0
 *
 * @package    PHT_Simple_Widget_Areas
 * @subpackage PHT_Simple_Widget_Areas/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @package    PHT_Simple_Widget_Areas
 * @subpackage PHT_Simple_Widget_Areas/admin
 * @author     PeHaa THEMES <info@pehaa.com>
 */
class PHT_Simple_Widget_Areas_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $options_array_slug = 'pht_simple_widget_areas';
	
	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $plugin_screen_hook_suffix = null;

	private $regex_pattern = '/^[0-9a-z-\s_]{0,20}$/';
	
	private $id_prefix = 'phtswa-';

	private $nonce = 'pht_simple_widget_areas';

	private $submit = 'phtswa_submit';

	private $capabilities;

	private $post_field = 'phtswa-sidebar-name';

	public static $pht_simple_widget_areas;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $name  The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;
		$this->id_prefix = apply_filters( $this->name . '_sidebar_prefix', $this->id_prefix );
		$this->plugin_screen_hook_suffix = array( 'widgets' );
		$this->capabilities = apply_filters( $this->name . '_required_capabilities', 'edit_theme_options' );
		$this->get_simple_widget_areas_options();	

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( $this->viewing_this_plugin() ) {
			wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/phtswa-admin.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( $this->viewing_this_plugin() ) {
			wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/pht-simple-widget-areas-admin.min.js', array( 'jquery' ), $this->version, false );

			$notifications = $this->notifications();
			
			wp_localize_script( 
				'jquery', 
				'phtswa_data', 
				 array(
				 	'field_id' => $this->post_field,
				 	'regex_pattern' => substr( $this->regex_pattern, 1, -1 ),
				 	'simple_widget_areas' => self::$pht_simple_widget_areas,
					'reserved_terms' => $this->registered_sidebars,
					'error_messages' => $notifications['error'],
					'confirmation'=> apply_filters( $this->name . '_confirmation_question' , __( 'Are you sure you want to delete this widget area?', $this->name ) ),
					'prefix' => $this->id_prefix, 
				)					
			);
		}
	}

	private function get_simple_widget_areas_options() {

		self::$pht_simple_widget_areas = get_option( $this->options_array_slug, array() );
		
	}

	public function display_admin_page() {
		
		if ( ! current_user_can( $this->capabilities ) ) {
			return;
		}
		include_once( 'partials/pht-simple-widget-areas-admin-display.php' );
	}

	/**
		 * Check if viewing one of this plugin's admin pages.
		 *
		 * @since   1.0.0
		 *
		 * @return  bool
		 */
	private function viewing_this_plugin() {		
						
		if ( ! isset( $this->plugin_screen_hook_suffix ) )
			return false;

		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffix ) )
			return true;
		else
			return false;
	}
	
	/**
		 * Retrieves all registered sidebars
		 *
		 * @since   1.0.0
		 *
		 * @return  void
		 */
	public function get_registered_sidebars() {
		
		global $wp_registered_sidebars;
		foreach ( (array) $wp_registered_sidebars as $registered_sidebar ) {
			$this->registered_sidebars[] = array(
				'id' => $registered_sidebar['id'],
				'name' => $registered_sidebar['name']
			);
		}
		
	}

	/**
	 * Register all simple widget areas
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	public function register_widget_areas() {
	
		$tag = apply_filters( $this->name . '_widget_tag', 'li' );
		$classes = apply_filters( $this->name . '_widget_classes', 'widget phtswa-widget' );
		$before_title = apply_filters( $this->name . '_widget_before_title', '<h2 class="widgettitle phtswa-widget-title">' );
		$after_title = apply_filters( $this->name . '_widget_after_title', '</h2>' );

		if ( is_array( self::$pht_simple_widget_areas ) ) {
			foreach ( self::$pht_simple_widget_areas as $sidebar ) {
				register_sidebar( 
					array(
						'name' => __( 'PHT SWA: ' ) . $sidebar['name'],
						'id' => $sidebar['id'],
						'before_widget' => '<' . $tag . ' id="%1$s" class="' . $classes . ' %2$s">',
						'after_widget' => '</' . $tag . '>',
						'before_title' => $before_title,
						'after_title' => $after_title,
						'description' => __( 'Add widgets here to appear in your custom sidebar.', $this->name ),
					)
				);
			}
		}
		
	}

	/**
	 * Updates the plugin's options on user action
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	public function update_widget_areas_options() {

		if ( ! current_user_can( $this->capabilities ) ) {
			return;
		}
		
		$this->update_widget_areas_options_on_add();
		
		$this->update_widget_areas_options_on_remove();
			
	}

	/**
	 * Updates the plugin's options on user action - add
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	private function update_widget_areas_options_on_add() {

		if ( isset( $_POST[ $this->submit ] ) ) {
		
			check_admin_referer( $this->nonce );	
			
			if ( $this->validate_data( $_POST ) ) {
				
				$name = $this->sanitize_area_name( trim ( $_POST[ $this->post_field ] ) );

				$id = $this->generate_id( $name );
				
				if ( $this->check_if_unique_id( $id ) ) {
					
					$phtswa_options = self::$pht_simple_widget_areas;
					array_push( $phtswa_options, array( 'id' => $id, 'name' => $name ) );
					$phtswa_options = stripslashes_deep( $phtswa_options );
					update_option( $this->options_array_slug, $phtswa_options );
					
				}	
				
			}
			wp_redirect( admin_url( 'widgets.php?phtswamsg=' . $this->screen_message ) );
			exit();
			
		}
	}

	/**
	 * Updates the plugin's options on user action - remove
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	private function update_widget_areas_options_on_remove() {

		if ( isset( $_GET['deleteid'] ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], $this->name .'_delete_nonce' ) )	{
			
			$delete_id = $_GET['deleteid'];

			$delete_index = NULL;

			foreach ( self::$pht_simple_widget_areas as $sidebar_index => $sidebar ) {

				if ( isset( $sidebar['id'] ) && $sidebar['id'] ===  $delete_id ) {
					$delete_index = $sidebar_index;
					
					break;
				}

			}
			
			if ( NULL !== $delete_index ) {
				
				$this->refresh_widgets( $delete_id );
				
				unset( self::$pht_simple_widget_areas[ $delete_index ] );
				
				update_option( $this->options_array_slug, array_values( self::$pht_simple_widget_areas ) );

				$this->screen_message = 'updated-2';

			} else {

				$this->screen_message = 'error-4';

			}

			wp_redirect( admin_url( 'widgets.php?phtswamsg=' . $this->screen_message ) );
			exit();
		}		
	}

	/**
	 * Deletes all widgets attached to a simple widget area on remove
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	private function refresh_widgets( $delete_id ) {


		// Contains an array of all sidebars and widgets inside each sidebar.
		$widgetized_sidebars = get_option( 'sidebars_widgets', array() );

		$delete_widgetized_sidebars = array();

		foreach ( $widgetized_sidebars as $id => $bar ) {
			if ( $id === $delete_id ) {

				$delete_widgetized_sidebars[] = $id;
				
			}
		}

		foreach ( $delete_widgetized_sidebars as $id ) {
			unset( $widgetized_sidebars[$id] );
		}

		update_option( 'sidebars_widgets', $widgetized_sidebars );
	
	}

	/**
	 * Validates the area name, set the screen notification 
	 *
	 * @since   1.0.0
	 *
	 * @return  boolean
	 */
	private function validate_data( $data ) {

		if ( isset( $data[ $this->post_field ] ) ) {

			$field = trim( $data[ $this->post_field ] );

			if ( !empty( $field ) ) {

				if ( ! preg_match( $this->regex_pattern, $field ) ) {
					$this->screen_message = 'error-2';
					return false;
				} else {
					$this->screen_message = 'updated-1';
					return true;
				}	

			}
			
		}

		$this->screen_message = 'error-1';
		return false;
	
	}


	/**
	 * Sanitizes area name 
	 *
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	private function sanitize_area_name( $name ) {
		return trim( wp_strip_all_tags( $name ) );
	}	
	
	/**
	 * Check if area id is unique
	 *
	 * @since   1.0.0
	 *
	 * @return  boolean
	 */
	private function check_if_unique_id( $id ) {
		
		if ( '' === $id ) return false;
		
		if ( (array) $this->registered_sidebars ) {
			foreach ( $this->registered_sidebars as $registered_sidebar ) {
				if ( $id === $registered_sidebar[ 'id' ] ) {
					$this->screen_message = 'error-3';
					return false;
				}
			}
		}
		
		return true;
		
	}

	/**
	 * Generated area id
	 *
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	private function generate_id( $string ) {
		
		if ( (string) $string ) {
			$string = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $string );
			$string = strtolower( preg_replace( '/[^A-Za-z0-9_-]/', '', $string ) );			
		}		
		
		return $this->id_prefix . $string;
	
	}

	/**
	 * Returns the success/error messages
	 *
	 * @since   1.0.0
	 *
	 * @return  array
	 */
	private function notifications() {
		
		return apply_filters( $this->name . '_notifications', array(
			'updated' => array(
				'1' => __( 'A new sidebar was successfully added.', $this->name ),
				'2' => __( 'The sidebar was successfully deleted.', $this->name ),
				),
			'error' => array(
				'1' => __( 'The sidebar name must field must be filled in.', $this->name ),
				'2' => __( 'The sidebar name cannot contain other caracters that digits, a-Z letters, "-", "_" and spaces. The maximum length of the sidebar name is 20 chars.', $this->name ),
				'3' => __( 'The submitted sidebar name already exists or is a reserved WordPress term.', $this->name ),
				'4' => __( 'The sidebar could not be removed.', $this->name ),
				)
			) 
		);
		
	}

	/**
	 * Prints the notification
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	public function get_notification() {
		
		if ( isset( $_GET['phtswamsg'] ) ) :

			$notifications = $this->notifications();
			
			preg_match( '/\A(updated|error)-(\d+)\z/',  $_GET['phtswamsg'], $matches );
			
			if ( $matches && isset( $notifications[$matches[1]][$matches[2]] ) ) { ?>
				<div id="message" class="<?php echo $matches[1]; ?>">
					<?php echo $notifications[$matches[1]][$matches[2]]; ?>
				</div>
			<?php }
		
		endif;
	}

}

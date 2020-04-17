<?php
/**
 * Plugin Name: TJ Extras
 * Plugin URI: https://www.theme-junkie.com/
 * Description: Add extra features like widgets, metaboxes, and custom Elementor widgets.
 * Version: 1.0.2
 * Author: Theme Junkie
 * Author URI: https://www.theme-junkie.com/
 * Requires at least: 4.6.0
 * Tested up to: 4.9.8
 *
 * Text Domain: tj-extras
 * Domain Path: /languages/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TJ_Extras' ) ) {
	/**
	 * Main TJ_Extras Class
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class TJ_Extras {

		/**
		 * The token.
		 *
		 * @var     string
		 * @access  public
		 * @since   1.0.0
		 */
		public $token = '';

		/**
		 * The version number.
		 *
		 * @var     string
		 * @access  public
		 * @since   1.0.0
		 */
		public $version = '';

		/**
		 * Directory path to the plugin folder.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $path = '';

		/**
		 * Directory URI to the plugin folder.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $uri = '';

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			static $instance = null;

			if ( is_null( $instance ) ) {
				$instance = new self;
				$instance->setup();
				$instance->includes();
				$instance->setup_actions();
			}

			return $instance;
		}

		/**
		 * Constructor function.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		private function __construct() {}

		/**
		 * Initial plugin setup.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function setup() {

			$this->token   = 'tj-extras';
			$this->path    = trailingslashit( plugin_dir_path( __FILE__ ) );
			$this->uri     = trailingslashit( plugin_dir_url( __FILE__ ) );
			$this->version = '1.0.1';

			// Use it all over the plugin files.
			define( 'TE_URL', $this->uri );
			define( 'TE_PATH', $this->path );

		}

		/**
		 * Loads include and admin files for the plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function includes() {

			// Load extensions.
			require_once( TE_PATH . 'ext/butterbean/butterbean.php' );
			require_once( TE_PATH . 'ext/kirki/kirki.php' );

			// Load plugin functions.
			require_once( TE_PATH . 'inc/library.php' );
			require_once( TE_PATH . 'inc/metabox.php' );
			require_once( TE_PATH . 'inc/functions.php' );
			require_once( TE_PATH . 'inc/sidebars.php' );

			if ( class_exists( 'Elementor\Plugin' ) ) {
				require_once( TE_PATH . 'inc/elementor.php' );
			}

			// Sets up auto update
			require_once( TE_PATH . 'inc/updates.php' );

		}

		/**
		 * Setup all the things.
		 * @return void
		 */
		private function setup_actions() {

			// Method that runs only when the plugin is activated.
			register_activation_hook( __FILE__, array( $this, 'install' ) );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Load custom widgets
			add_action( 'widgets_init', array( $this, 'custom_widgets' ), 10 );

			// Allow shortcodes in text widgets
			add_filter( 'widget_text', 'do_shortcode' );

			// Scripts & styles for the plugin.
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
		}

		/**
		 * Load the localisation file.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'tj-extras', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
		}

		/**
		 * Installation.
		 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function install() {
			$this->_log_version_number();
		}

		/**
		 * Log the plugin version number.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function _log_version_number() {
			update_option( $this->token . '-version', $this->version );
		}

		/**
		 * Custom widgets
		 */
		public static function custom_widgets() {

			if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
				return;
			}

			// Define array of custom widgets for the theme
			$widgets = apply_filters( 'tj_extras_custom_widgets', array(
				'about-me',
				'contact-info',
				'custom-links',
				'facebook',
				'flickr',
				'recent-posts',
				'social',
				'social-share',
				'tags',
				'twitter',
				'video'
			) );

			// Loop through widgets and load their files
			if ( $widgets && is_array( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					$file = TE_PATH . '/inc/widgets/' . $widget .'.php';
					if ( file_exists ( $file ) ) {
						require_once( $file );
					}
				}
			}

		}

		/**
		 * Enqueue scripts
		 */
		public function scripts() {

			// Load custom widgets stylesheet.
			wp_enqueue_style( 'tj-extras-widgets-style', TE_URL . 'assets/css/widgets.css' );
			if ( is_RTL() ) {
				wp_enqueue_style( 'tj-extras-widgets-style-rtl', TE_URL . 'assets/css/widgets-rtl.css' );
			}

			// Load font awesome.
			wp_enqueue_style( 'font-awesome', TE_URL . 'assets/css/font-awesome.min.css' );

		}

	} // End Class

	TJ_Extras::get_instance();

}

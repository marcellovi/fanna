<?php
/**
 * Elementor functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The Metabox class
if ( ! class_exists( 'TJ_Extras_Elementor' ) ) {

	/**
	 * Elementor functions.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class TJ_Extras_Elementor {

		/**
		 * Sets up initial actions.
		 */
		private function setup_actions() {

			// Add new category for Elementor
			add_action( 'elementor/init', array( $this, 'elementor_init' ), 1 );

			// Add the action here so that the widgets are always visible
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );

		}

		/**
		 * Add new category for Elementor.
		 */
		public function elementor_init() {

			$elementor = \Elementor\Plugin::$instance;

			// Add element category in panel
			$elementor->elements_manager->add_category(
				'tj_extras_elements',
				[
					'title' => esc_attr__( 'TJ Elements', 'tj-extras' ),
					'icon' => 'font',
				],
				1
			);
		}

		/**
		 * Register the custom Elementor widgets
		 */
		public function widgets_registered() {

			// We check if the Elementor plugin has been installed / activated.
			if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

				// Define dir
				$dir = TE_PATH . 'inc/elementor-widgets/';

				// Array of new widgets
				$build_widgets = apply_filters( 'tj_extras_widgets', array(
					'logo'      => $dir . 'logo.php',
					'menu'      => $dir . 'menu.php',
					'search'    => $dir . 'search.php',
					'post'      => $dir . 'post.php',
					'post_list' => $dir . 'post-list.php',
					'post_grid' => $dir . 'post-grid.php',
					'post_alt'  => $dir . 'post-alt.php',
					'events'    => $dir . 'events.php',
					'product'   => $dir . 'product.php',
				) );

				// Load files
				foreach ( $build_widgets as $widget_filename ) {
					include $widget_filename;
				}

			}

		}

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
				$instance->setup_actions();
			}
			return $instance;
		}

		/**
		 * Constructor method.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function __construct() {}

	}

	TJ_Extras_Elementor::get_instance();

}

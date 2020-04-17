<?php
/**
 * Helper functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The Metabox class
if ( ! class_exists( 'TJ_Extras_Functions' ) ) {

	/**
	 * Helper functions.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class TJ_Extras_Functions {

		/**
		 * Sets up initial actions.
		 *
		 * @since 1.0.0
		 */
		private function setup_actions() {

			// Add extra user fields
			add_filter( 'user_contactmethods', array( $this, 'user_fields' ) );

		}

		/**
		 * Add new user fields.
		 */
		public function user_fields( $contactmethods ) {

			$contactmethods['twitter']     = esc_html__( 'Twitter URL', 'tj-extras' );
			$contactmethods['facebook']    = esc_html__( 'Facebook URL', 'tj-extras' );
			$contactmethods['gplus']       = esc_html__( 'Google Plus URL', 'tj-extras' );
			$contactmethods['instagram']   = esc_html__( 'Instagram URL', 'tj-extras' );
			$contactmethods['pinterest']   = esc_html__( 'Pinterest URL', 'tj-extras' );
			$contactmethods['linkedin']    = esc_html__( 'Linkedin URL', 'tj-extras' );
			$contactmethods['dribbble']    = esc_html__( 'Dribbble URL', 'tj-extras' );

			return $contactmethods;
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

	TJ_Extras_Functions::get_instance();

}

<?php
/**
 * Adds custom metabox
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The Metabox class
if ( ! class_exists( 'TJ_Extras_Metabox' ) ) {

	/**
	 * Main ButterBean class.  Runs the show.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class TJ_Extras_Metabox {

		/**
		 * The post types
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $post_types = '';

		/**
		 * Sets up initial actions.
		 *
		 * @since 1.0.0
		 */
		private function setup_actions() {

			// Post types to add the metabox to
			$this->post_types = apply_filters( 'tj_extras_metaboxes_post_types', array(
				'post',
				'page',
				'product',
				'tj_library',
				'elementor_library',
			) );

			// Register fields
			add_action( 'butterbean_register', array( $this, 'register' ), 10, 2 );

			// Load scripts and styles for the metaboxes
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Sidebar
			add_filter( 'tj_extras_get_sidebar', array( $this, 'get_sidebar' ) );

		}

		/**
		 * Load scripts and styles
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts( $hook ) {

			// Only needed on these admin screens
			if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
				return;
			}

			// Get global post
			global $post;

			// Return if post is not object
			if ( ! is_object( $post ) ) {
				return;
			}

			// Post types scripts
			$post_types_scripts = apply_filters( 'tj_extras_metaboxes_post_types_scripts', $this->post_types );

			// Return if wrong post type
			if ( ! in_array( $post->post_type, $post_types_scripts ) ) {
				return;
			}

			// Default style
			wp_enqueue_style( 'tj-extras-metaboxes', TE_URL . 'assets/css/admin.css' );

		}

		/**
		 * Registration callback
		 *
		 * @since 1.0.0
		 */
		public function register( $butterbean, $post_type ) {

			// Post types to add the metabox to
			$post_types = $this->post_types;

			// Register managers, sections, controls, and settings here.
			$butterbean->register_manager(
				'tj_extras_mb_settings',
				array(
					'label'     => esc_html__( 'Settings', 'tj-extras' ),
					'post_type' => $post_types,
					'context'   => 'normal',
					'priority'  => 'high'
				)
			);
			
			$manager = $butterbean->get_manager( 'tj_extras_mb_settings' );
			
			$manager->register_section(
				'tj_extras_mb_general',
				array(
					'label' => esc_html__( 'General', 'tj-extras' ),
					'icon'  => 'dashicons-admin-generic'
				)
			);

			$manager->register_control(
				'tj_extras_post_layout', // Same as setting name.
				array(
					'section'       => 'tj_extras_mb_general',
					'type'          => 'select',
					'label'         => esc_html__( 'Content Layout', 'tj-extras' ),
					'description'   => esc_html__( 'Select your custom layout.', 'tj-extras' ),
					'choices'       => array(
						''                  => esc_html__( 'Default', 'tj-extras' ),
						'right-sidebar'     => esc_html__( 'Right Sidebar', 'tj-extras' ),
						'left-sidebar'      => esc_html__( 'Left Sidebar', 'tj-extras' ),
						'full-width'        => esc_html__( 'Full Width', 'tj-extras' ),
						'full-width-narrow' => esc_html__( 'Full Width Narrow', 'tj-extras' )
					),
				)
			);
			
			$manager->register_setting(
				'tj_extras_post_layout', // Same as control name.
				array(
					'sanitize_callback' => 'sanitize_key',
				)
			);
			
			$manager->register_control(
				'tj_extras_sidebar', // Same as setting name.
				array(
					'section'       => 'tj_extras_mb_general',
					'type'          => 'select',
					'label'         => esc_html__( 'Sidebar', 'tj-extras' ),
					'description'   => esc_html__( 'Select your custom sidebar.', 'tj-extras' ),
					'choices'       => $this->helpers( 'widget_areas' ),
				)
			);
			
			$manager->register_setting(
				'tj_extras_sidebar', // Same as control name.
				array(
					'sanitize_callback' => 'sanitize_key',
				)
			);

			$manager->register_section(
				'tj_extras_mb_header',
				array(
					'label' => esc_html__( 'Header', 'tj-extras' ),
					'icon'  => 'dashicons-sticky'
				)
			);
			
			$manager->register_control(
				'tj_extras_custom_header_template', // Same as setting name.
				array(
					'section'       => 'tj_extras_mb_header',
					'type'          => 'select',
					'label'         => esc_html__( 'Select Template', 'tj-extras' ),
					'description'   => esc_html__( 'Choose a template created in Appearance > My Library.', 'tj-extras' ),
					'choices'       => $this->helpers( 'library' ),
				)
			);
			
			$manager->register_setting(
				'tj_extras_custom_header_template', // Same as control name.
				array(
					'sanitize_callback' => 'sanitize_key',
				)
			);
			
			$manager->register_section(
				'tj_extras_mb_footer',
				array(
					'label' => esc_html__( 'Footer', 'tj-extras' ),
					'icon'  => 'dashicons-hammer'
				)
			);
			
			$manager->register_control(
				'tj_extras_custom_footer_template', // Same as setting name.
				array(
					'section'       => 'tj_extras_mb_footer',
					'type'          => 'select',
					'label'         => esc_html__( 'Select Template', 'tj-extras' ),
					'description'   => esc_html__( 'Choose a template created in Appearance > My Library.', 'tj-extras' ),
					'choices'       => $this->helpers( 'library' ),
				)
			);
			
			$manager->register_setting(
				'tj_extras_custom_footer_template', // Same as control name.
				array(
					'sanitize_callback' => 'sanitize_key',
				)
			);

		}

		/**
		 * Helpers
		 *
		 * @since 1.0.0
		 */
		public static function helpers( $return = NULL ) {

			// Library
			if ( 'library' == $return ) {
				$templates 		= array( esc_html__( 'Select a Template', 'tj-extras' ) );
				$get_templates 	= get_posts( array( 'post_type' => 'tj_library', 'numberposts' => -1, 'post_status' => 'publish' ) );

				if ( ! empty ( $get_templates ) ) {
					foreach ( $get_templates as $template ) {
						$templates[ $template->ID ] = $template->post_title;
					}
				}

				return $templates;
			}

			// Widgets
			elseif ( 'widget_areas' == $return ) {
				global $wp_registered_sidebars;
				$widgets_areas = array( esc_html__( 'Default', 'tj-extras' ) );
				$get_widget_areas = $wp_registered_sidebars;
				if ( ! empty( $get_widget_areas ) ) {
					foreach ( $get_widget_areas as $widget_area ) {
						$name = isset ( $widget_area['name'] ) ? $widget_area['name'] : '';
						$id = isset ( $widget_area['id'] ) ? $widget_area['id'] : '';
						if ( $name && $id ) {
							$widgets_areas[$id] = $name;
						}
					}
				}
				return $widgets_areas;
			}

		}

		/**
		 * Returns the correct sidebar ID
		 *
		 * @since  1.0.0
		 */
		public function get_sidebar( $sidebar ) {
			
			if ( $meta = get_post_meta( tj_extras_post_id(), 'tj_extras_sidebar', true ) ) {
				$sidebar = $meta;
			}

			return $sidebar;

		}

		/**
		 * Custom footer template
		 *
		 * @since  1.3.3
		 */
		public function custom_footer_template( $template ) {
			
			if ( $meta = get_post_meta( tj_extras_post_id(), 'tj_extras_custom_footer_template', true ) ) {
				$template = $meta;
			}

			return $template;

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

	TJ_Extras_Metabox::get_instance();

}
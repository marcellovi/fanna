<?php
/**
 * My Library
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class TJ_Extras_Library {

	/**
	 * Start things up
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'library_post_type' ) );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_page' ) );
		}

		add_action( 'template_redirect', array( $this, 'block_template_frontend' ) );
	}

	/**
	 * Add sub menu page
	 */
	public function add_page() {

		$name = esc_html__( 'My Library', 'tj-extras' );

		add_submenu_page(
			'themes.php',
			$name,
			$name,
			'manage_options',
			'edit.php?post_type=tj_library'
		);

	}

	/**
	 * Register library post type
	 */
	public static function library_post_type() {

		$name = esc_html__( 'My Library', 'tj-extras' );

		// Register the post type
		register_post_type( 'tj_library', apply_filters( 'tj_library_args', array(
			'labels' => array(
				'name' 					=> $name,
				'singular_name' 		=> esc_html__( 'Template', 'tj-extras' ),
				'add_new' 				=> esc_html__( 'Add New', 'tj-extras' ),
				'add_new_item' 			=> esc_html__( 'Add New Template', 'tj-extras' ),
				'edit_item' 			=> esc_html__( 'Edit Template', 'tj-extras' ),
				'new_item' 				=> esc_html__( 'Add New Template', 'tj-extras' ),
				'view_item' 			=> esc_html__( 'View Template', 'tj-extras' ),
				'search_items' 			=> esc_html__( 'Search Template', 'tj-extras' ),
				'not_found' 			=> esc_html__( 'No Templates Found', 'tj-extras' ),
				'not_found_in_trash' 	=> esc_html__( 'No Templates Found In Trash', 'tj-extras' ),
				'menu_name' 			=> $name,
			),
			'public' 					=> true,
			'hierarchical'          	=> false,
			'show_ui'               	=> true,
			'show_in_menu' 				=> false,
			'show_in_nav_menus'     	=> false,
			'can_export'            	=> true,
			'exclude_from_search'   	=> true,
			'capability_type' 			=> 'post',
			'rewrite' 					=> false,
			'supports' 					=> array( 'title', 'editor', 'author', 'elementor' ),
		) ) );

	}

	/**
	 * Make the post type inaccessible
	 *
	 * @since 1.0.0
	 */
	public static function block_template_frontend() {
		if ( is_singular( 'tj_library' ) && ! self::is_current_user_can_edit() ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}

	/**
	 * If the current user can edit
	 *
	 * @since 1.0.0
	 */
	public static function is_current_user_can_edit( $post_id = 0 ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( 'trash' === get_post_status( $post_id ) ) {
			return false;
		}

		$post_type_object = get_post_type_object( get_post_type( $post_id ) );
		if ( empty( $post_type_object ) ) {
			return false;
		}

		if ( ! isset( $post_type_object->cap->edit_post ) ) {
			return false;
		}

		$edit_cap = $post_type_object->cap->edit_post;
		if ( ! current_user_can( $edit_cap, $post_id ) ) {
			return false;
		}

		if ( get_option( 'page_for_posts' ) === $post_id ) {
			return false;
		}

		return true;
	}

}
new TJ_Extras_Library();

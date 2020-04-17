<?php
/**
 * Theme functions file
 *
 * Contains all of the Theme's setup functions, custom functions,
 * custom hooks and Theme settings.
 */

if ( ! function_exists( 'ultrastore_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function ultrastore_theme_setup() {

		// Make the theme available for translation.
		load_theme_textdomain( 'ultrastore', trailingslashit( get_template_directory() ) . 'languages' );

		// Add custom stylesheet file to the TinyMCE visual editor.
		add_editor_style( array( 'assets/css/editor-style.css' ) );

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails.
		add_theme_support( 'post-thumbnails' );

		// Declare image sizes.
		add_image_size( 'ultrastore-post', 846, 530, true );
		add_image_size( 'ultrastore-post-small', 524, 350, true );
		add_image_size( 'ultrastore-post-large', 1170, 660, true );
		add_image_size( 'ultrastore-featured', 772, 675, true );
		add_image_size( 'ultrastore-featured-two', 555, 600, true );

		// Register custom navigation menu.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Navigation', 'ultrastore' ),
				'mobile'  => esc_html__( 'Mobile Navigation', 'ultrastore' ),
				'account' => esc_html__( 'My Account Navigation', 'ultrastore' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Setup the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'ultrastore_custom_background_args', array(
			'default-color' => 'faf1ed'
		) ) );

		// Enable support for Custom Logo
		add_theme_support( 'custom-logo', array(
			'height'      => 40,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );

		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}
endif; // ultrastore_theme_setup
add_action( 'after_setup_theme', 'ultrastore_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ultrastore_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'ultrastore_content_width', 825 );
}
add_action( 'after_setup_theme', 'ultrastore_content_width', 0 );

/**
 * Sets custom content width when current layout is full-width
 */
if ( ! function_exists( 'ultrastore_fullwidth_content_width' ) ) :

	function ultrastore_fullwidth_content_width() {
		global $content_width;

		if ( in_array( get_theme_mod( 'theme_layout' ), array( 'full-width' ) ) ) {
			$content_width = 1110;
		}
	}

endif;
add_action( 'template_redirect', 'ultrastore_fullwidth_content_width' );

/**
 * Registers widget areas and custom widgets.
 *
 * @link  http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function ultrastore_sidebars_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Primary', 'ultrastore' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Main sidebar that appears on the right.', 'ultrastore' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title module-title">',
			'after_title'   => '</h3>',
		)
	);

	if ( ultrastore_is_woocommerce_activated() ) {
		register_sidebar(
			array(
				'name'          => __( 'WooCommerce Sidebar', 'ultrastore' ),
				'id'            => 'woo_sidebar',
				'description'   => __( 'Widgets in this area are used in your WooCommerce sidebar for shop pages and product posts.', 'ultrastore' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	// Get the footer widget column from Customizer.
	$widget_columns = get_theme_mod( 'footer_widgets_columns', '4' );
	for ( $i = 1; $i <= $widget_columns; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf( esc_html__( 'Footer %s', 'ultrastore' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => esc_html__( 'Sidebar that appears on the bottom of your site.', 'ultrastore' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title module-title">',
				'after_title'   => '</h3>',
			)
		);
	}

}
add_action( 'widgets_init', 'ultrastore_sidebars_init' );

/**
 * Custom template tags for this theme.
 */
require trailingslashit( get_template_directory() ) . 'inc/template-tags.php';

/**
 * Helpers functions.
 */
require trailingslashit( get_template_directory() ) . 'inc/helpers.php';

/**
 * Enqueue scripts and styles.
 */
require trailingslashit( get_template_directory() ) . 'inc/scripts.php';

/**
 * Require and recommended plugins list.
 */
require trailingslashit( get_template_directory() ) . 'inc/plugins.php';

/**
 * Demo importer
 */
require trailingslashit( get_template_directory() ) . 'inc/demo/demo-importer.php';

/**
 * Updater
 */
require trailingslashit( get_template_directory() ) . 'inc/updater.php';

/**
 * Extras
 */
if ( ultrastore_is_tj_extras_activated() ) {
	require trailingslashit( get_template_directory() ) . 'inc/customizer/helpers.php';
	require trailingslashit( get_template_directory() ) . 'inc/customizer/customizer.php';
}

/**
 * WooCommerce integration
 */
if ( ultrastore_is_woocommerce_activated() ) {
	require trailingslashit( get_template_directory() ) . 'inc/woocommerce.php';
}

<?php
/**
 * Enqueue scripts and styles.
 */

/**
 * Loads the theme styles & scripts.
 */
function ultrastore_enqueue() {

	// Load plugins stylesheet
	wp_enqueue_style( 'ultrastore-plugins-style', trailingslashit( get_template_directory_uri() ) . 'assets/css/plugins.min.css' );

	// if WP_DEBUG and/or SCRIPT_DEBUG turned on, load the unminified styles & script.
	if ( ! is_child_theme() && WP_DEBUG || SCRIPT_DEBUG ) {

		// Load main stylesheet
		wp_enqueue_style( 'ultrastore-style', get_stylesheet_uri() );

		// Load custom js plugins.
		wp_enqueue_script( 'ultrastore-plugins', trailingslashit( get_template_directory_uri() ) . 'assets/js/plugins.min.js', array( 'jquery' ), null, true );

		// Load custom js methods.
		wp_enqueue_script( 'ultrastore-main', trailingslashit( get_template_directory_uri() ) . 'assets/js/main.js', array( 'jquery' ), null, true );

		// Script handle
		$script_handle = 'ultrastore-main';

	} else {

		// Load main stylesheet
		wp_enqueue_style( 'ultrastore-style', trailingslashit( get_template_directory_uri() ) . 'style.min.css' );

		// Load custom js plugins.
		wp_enqueue_script( 'ultrastore-scripts', trailingslashit( get_template_directory_uri() ) . 'assets/js/ultrastore.min.js', array( 'jquery' ), null, true );

		// Script handle
		$script_handle = 'ultrastore-scripts';

	}

	/**
	 * js / no-js script.
	 *
	 * @copyright http://www.paulirish.com/2009/avoiding-the-fouc-v3/
	 */
	wp_add_inline_script( $script_handle, "document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,'js');" );

	// If child theme is active, load the stylesheet.
	if ( is_child_theme() ) {
		wp_enqueue_style( 'ultrastore-child-style', get_stylesheet_uri() );
	}

	// Load comment-reply script.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Loads HTML5 Shiv
	wp_enqueue_script( 'ultrastore-html5', trailingslashit( get_template_directory_uri() ) . 'assets/js/html5shiv.min.js', array( 'jquery' ), null, false );
	wp_script_add_data( 'ultrastore-html5', 'conditional', 'lte IE 9' );

	// Loads Fontello for IE7
	wp_enqueue_style( 'ultrastore-fontello-ie7', trailingslashit( get_template_directory_uri() ) . 'assets/css/fontello-ie7.css', array( 'ultrastore-style' ) );
	wp_style_add_data( 'ultrastore-fontello-ie7', 'conditional', 'lte IE 7' );

}
add_action( 'wp_enqueue_scripts', 'ultrastore_enqueue' );

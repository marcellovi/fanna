<?php
/**
 * Theme Customizer
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ultrastore_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	// Enable selective refresh to the Site Title
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'         => '.site-title a',
			'settings'         => array( 'blogname' ),
			'render_callback'  => function() {
				return get_bloginfo( 'name', 'display' );
			}
		) );
	}

	// Enable selective refresh to the Site Description
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'         => '.site-description',
			'settings'         => array( 'blogdescription' ),
			'render_callback'  => function() {
				return get_bloginfo( 'description', 'display' );
			}
		) );
	}

	// Remove section
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'background_image' );

	// Move custom logo control
	$wp_customize->get_control( 'custom_logo' )->section = 'logo';

}
add_action( 'customize_register', 'ultrastore_customize_register' );

/**
 * This function adds some styles to the WordPress Customizer
 */
function ultrastore_custom_customizer_style() { ?>
	<style>
		.customize-control {
			margin-bottom: 20px;
		}
		.select2-container .select2-selection--single {
			height: 30px;
		}
		.customize-control-kirki-radio-image .image label {
			width: 30%;
			line-height: 1;
			margin-right: 3%;
			margin-bottom: 2%;
		}
		.customize-control-kirki-radio-image input:checked + label img {
			box-shadow: none;
			border: none;
			outline: 2px solid #3498DB;
		}
	</style>
	<?php
}
add_action( 'customize_controls_print_styles', 'ultrastore_custom_customizer_style', 999 );

/**
 * Add the configuration.
 * This way all the fields using the 'ultrastore_options' ID
 * will inherit these options
 */
Kirki::add_config( 'ultrastore_options', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );

/**
 * Disable Kirki loader
 */
add_filter( 'kirki/config', function( $config ) {
	$config['disable_loader'] = true;
	return $config;
} );

/**
 * Add sections
 */
/* General */
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/general/general.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/general/container.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/general/back-to-top.php';

/* Header */
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/header/header.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/header/site-title.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/header/logo.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/header/menu.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/header/mobile-menu.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/header/search.php';

/* Appearance */
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/appearance/appearance.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/appearance/typography.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/appearance/page-layouts.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/appearance/buttons.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/appearance/forms.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/appearance/widgets.php';

/* Blog */
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/blog/blog.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/blog/post.php';

/* Footer */
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/footer/footer.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/footer/copyrights.php';

/* Shop */
require trailingslashit( get_template_directory() ) . 'inc/customizer/options/shop/catalog.php';

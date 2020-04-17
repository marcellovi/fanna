<?php
/**
 * TGM Plugin Lists
 */

// Include the TGM_Plugin_Activation class.
require trailingslashit( get_template_directory() ) . 'inc/ext/tgmpa.php';

/**
 * Register required and recommended plugins.
 */
function ultrastore_register_plugins() {

	$plugins = array(

		array(
			'name'     => 'TJ Extras',
			'slug'     => 'tj-extras',
			'source'   => trailingslashit( get_template_directory() ) . 'inc/plugins/tj-extras.zip',
			'required' => true,
		),

		array(
			'name'     => 'Contact Form Builder for WordPress – Conversion Tools by HubSpot',
			'slug'     => 'leadin',
			'source'   => trailingslashit( get_template_directory() ) . 'inc/plugins/leadin.zip',
			'required' => false,
		),

		array(
			'name'     => 'Elementor',
			'slug'     => 'elementor',
			'required' => true,
		),

		array(
			'name'     => 'One Click Demo Import',
			'slug'     => 'one-click-demo-import',
			'required' => false,
		),

		array(
			'name'     => 'MailChimp for WordPress',
			'slug'     => 'mailchimp-for-wp',
			'required' => false,
		),

		array(
			'name'     => 'WooCommerce',
			'slug'     => 'woocommerce',
			'required' => true,
		),

		array(
			'name'     => 'WPC Smart Compare for WooCommerce',
			'slug'     => 'woo-smart-compare',
			'required' => false,
		),

		array(
			'name'     => 'WPC Smart Quick View for WooCommerce',
			'slug'     => 'woo-smart-quick-view',
			'required' => false,
		),

		array(
			'name'     => 'YITH WooCommerce Wishlist',
			'slug'     => 'yith-woocommerce-wishlist',
			'required' => false,
		),

	);

	$config = array(
		'id'           => 'tgmpa',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'ultrastore_register_plugins' );

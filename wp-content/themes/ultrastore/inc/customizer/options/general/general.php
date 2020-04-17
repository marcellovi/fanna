<?php
/**
 * General panel
 */
Kirki::add_panel( 'general', array(
	'title'          => esc_attr__( 'General', 'ultrastore' ),
	'description'    => esc_attr__( 'Customize general elements of the theme.', 'ultrastore' ),
	'priority'       => 130,
) );

/**
 * General section
 */
Kirki::add_section( 'general_settings', array(
	'title'          => esc_attr__( 'General Settings', 'ultrastore' ),
	'priority'       => 1,
	'panel'          => 'general'
) );

/**
 * Loading
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'loading',
	'label'       => esc_attr__( 'Page Loading', 'ultrastore' ),
	'description' => esc_attr__( 'Enable page loading animation.', 'ultrastore' ),
	'section'     => 'general_settings',
	'default'     => '1',
) );

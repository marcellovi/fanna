<?php
/**
 * Header panel
 */
Kirki::add_panel( 'header', array(
	'title'          => esc_attr__( 'Header', 'ultrastore' ),
	'description'    => esc_attr__( 'Customize the header area of the theme.', 'ultrastore' ),
	'priority'       => 140,
) );

/**
 * Header section
 */
Kirki::add_section( 'header_settings', array(
	'title'          => esc_attr__( 'General', 'ultrastore' ),
	'priority'       => 1,
	'panel'          => 'header'
) );

/**
 * Header style
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'select',
	'settings'    => 'header_style',
	'label'       => esc_attr__( 'Style', 'ultrastore' ),
	'section'     => 'header_settings',
	'default'     => 'default',
	'multiple'    => 1,
	'choices'     => array(
		'default' => esc_attr__( 'Default', 'ultrastore' ),
		'custom'  => esc_attr__( 'Custom Header', 'ultrastore' )
	),
) );

/**
 * Header custom
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'select',
	'settings'    => 'header_template',
	'label'       => esc_attr__( 'Select Template', 'ultrastore' ),
	'description' => esc_attr__( 'Choose a template created in Appearance > My Library.', 'ultrastore' ),
	'section'     => 'header_settings',
	'default'     => '0',
	'multiple'    => 1,
	'choices'     => ultrastore_library( 'library' ),
	'required'    => array(
		array(
			'setting'  => 'header_style',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

/**
 * Shadow
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'header_shadow',
	'label'       => esc_attr__( 'Shadow', 'ultrastore' ),
	'description' => esc_attr__( 'Enable shadow for the header.', 'ultrastore' ),
	'section'     => 'header_settings',
	'default'     => '1',
	'output' => array(
		array(
			'element'       => '.site-header',
			'property'      => 'box-shadow',
			'value_pattern' => '0 0 6px rgba(248, 211, 194, 0.55)',
			'exclude'       => array( false )
		),
	),
	'required'    => array(
		array(
			'setting'  => 'header_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Sticky
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'header_sticky',
	'label'       => esc_attr__( 'Sticky', 'ultrastore' ),
	'description' => esc_attr__( 'Enable the sticky header.', 'ultrastore' ),
	'section'     => 'header_settings',
	'default'     => '0',
	'output' => array(
		array(
			'element'       => '.site-header',
			'property'      => 'position',
			'media_query'   => '@media (min-width: 768px)',
			'value_pattern' => 'fixed',
			'exclude'       => array( false )
		),
		array(
			'element'       => '.site-header',
			'property'      => 'width',
			'media_query'   => '@media (min-width: 768px)',
			'value_pattern' => '100%',
			'exclude'       => array( false )
		),
		array(
			'element'       => '.site-header',
			'property'      => 'z-index',
			'media_query'   => '@media (min-width: 768px)',
			'value_pattern' => '99',
			'exclude'       => array( false )
		),
		array(
			'element'       => '.site-content',
			'property'      => 'padding-top',
			'media_query'   => '@media (min-width: 768px)',
			'value_pattern' => '22rem',
			'exclude'       => array( false )
		),
	),
	'required'    => array(
		array(
			'setting'  => 'header_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Height
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'slider',
	'settings'    => 'header_height',
	'label'       => esc_attr__( 'Height', 'ultrastore' ),
	'description' => esc_attr__( 'Control the height of the header.', 'ultrastore' ),
	'section'     => 'header_settings',
	'default'     => '14',
	'choices'     => array(
		'min'  => '5',
		'max'  => '20',
		'step' => '1',
	),
	'output'      => array(
		array(
			'element'  => '.site-header .container',
			'property' => 'min-height',
			'units'    => 'rem',
			'exclude'  => array( '14' ),
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.site-header .container',
			'property' => 'min-height',
			'function' => 'css',
			'units'    => 'rem',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'header_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Background color
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'header_bg',
	'label'       => esc_attr__( 'Background Color', 'ultrastore' ),
	'section'     => 'header_settings',
	'default'     => '#faf1ed',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.site-header',
			'property' => 'background-color',
			'exclude'  => array( '#faf1ed' ),
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.site-header',
			'property' => 'background-color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'header_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

<?php
/**
 * Footer panel
 */
Kirki::add_panel( 'footer', array(
	'title'          => esc_attr__( 'Footer', 'ultrastore' ),
	'description'    => esc_attr__( 'Customize the footer area of the theme.', 'ultrastore' ),
	'priority'       => 150,
) );

/**
 * Footer widgets
 */
Kirki::add_section( 'footer_widgets', array(
	'title'          => esc_attr__( 'Footer Widgets', 'ultrastore' ),
	'priority'       => 1,
	'panel'          => 'footer'
) );

/**
 * Footer style
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'select',
	'settings'    => 'footer_style',
	'label'       => esc_attr__( 'Style', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => 'default',
	'multiple'    => 1,
	'choices'     => array(
		'default' => esc_attr__( 'Default', 'ultrastore' ),
		'custom'  => esc_attr__( 'Custom Footer', 'ultrastore' )
	),
) );

/**
 * Header custom
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'select',
	'settings'    => 'footer_template',
	'label'       => esc_attr__( 'Select Template', 'ultrastore' ),
	'description' => esc_attr__( 'Choose a template created in Appearance > My Library.', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '0',
	'multiple'    => 1,
	'choices'     => ultrastore_library( 'library' ),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
			'operator' => '==',
			'value'    => 'custom',
		),
	),
) );

/**
 * Columns
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'slider',
	'settings'    => 'footer_widgets_columns',
	'label'       => esc_attr__( 'Columns', 'ultrastore' ),
	'description' => esc_attr__( 'Footer widgets columns.', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '4',
	'choices'      => array(
		'min'  => 2,
		'max'  => 4,
		'step' => 1,
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
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
	'settings'    => 'footer_widgets_height',
	'label'       => esc_attr__( 'Height', 'ultrastore' ),
	'description' => esc_attr__( 'Control the height of the footer widget area.', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '60',
	'choices'     => array(
		'min'  => '0',
		'max'  => '150',
		'step' => '5',
	),
	'output'      => array(
		array(
			'element'  => '.sidebar-footer',
			'property' => 'padding-top',
			'units'    => 'px',
			'exclude'  => array( '60' )
		),
		array(
			'element'  => '.sidebar-footer',
			'property' => 'padding-bottom',
			'units'    => 'px',
			'exclude'  => array( '60' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.sidebar-footer',
			'property' => 'padding-top',
			'units'    => 'px',
			'function' => 'css',
		),
		array(
			'element'  => '.sidebar-footer',
			'property' => 'padding-bottom',
			'units'    => 'px',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
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
	'settings'    => 'footer_widgets_bg',
	'label'       => esc_attr__( 'Background Color', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '#faf1ed',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.sidebar-footer',
			'property' => 'background-color',
			'exclude'  => array( '#faf1ed' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.sidebar-footer',
			'property' => 'background-color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Color
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'footer_widgets_color',
	'label'       => esc_attr__( 'Color', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '#3b3939',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.sidebar-footer',
			'property' => 'color',
			'exclude'  => array( '#3b3939' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.sidebar-footer',
			'property' => 'color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Link Color
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'footer_widgets_color_link',
	'label'       => esc_attr__( 'Color: Link', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '#3b3939',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.sidebar-footer a',
			'property' => 'color',
			'exclude'  => array( '#3b3939' )
		),
		array(
			'element'  => '.sidebar-footer a:visited',
			'property' => 'color',
			'exclude'  => array( '#3b3939' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.sidebar-footer a',
			'property' => 'color',
			'function' => 'css',
		),
		array(
			'element'  => '.sidebar-footer a:visited',
			'property' => 'color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Link Color: Hover
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'footer_widgets_color_hover',
	'label'       => esc_attr__( 'Color: Hover', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => '#fbbfa3',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.sidebar-footer a:hover',
			'property' => 'color',
			'exclude'  => array( '#fbbfa3' )
		),
		array(
			'element'  => '.sidebar-footer li a:hover',
			'property' => 'color',
			'exclude'  => array( '#ff4552' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.sidebar-footer a:hover',
			'property' => 'color',
			'function' => 'css',
		),
		array(
			'element'  => '.sidebar-footer li a:hover',
			'property' => 'color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

/**
 * Widget title
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'typography',
	'settings'    => 'footer_widgets_title',
	'label'       => esc_attr__( 'Widget Title', 'ultrastore' ),
	'section'     => 'footer_widgets',
	'default'     => array(
		'font-family'    => 'Muli',
		'variant'        => '800',
		'font-size'      => '20px',
		'letter-spacing' => '0',
		'color'          => '#3b3939',
		'text-transform' => 'capitalize'
	),
	'output'       => array(
		array(
			'element'  => '.sidebar-footer .widget-title'
		),
	),
	'required'    => array(
		array(
			'setting'  => 'footer_style',
			'operator' => '==',
			'value'    => 'default',
		),
	),
) );

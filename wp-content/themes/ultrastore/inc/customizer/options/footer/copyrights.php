<?php
/**
 * Copyrights
 */
Kirki::add_section( 'copyrights', array(
	'title'          => esc_attr__( 'Copyrights', 'ultrastore' ),
	'priority'       => 5,
	'panel'          => 'footer'
) );

/**
 * Enable copyright area
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'copyrights_enable',
	'label'       => esc_attr__( 'Enable copyright', 'ultrastore' ),
	'description' => esc_attr__( 'Show footer text area.', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => '1'
) );

/**
 * Footer text
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'     => 'textarea',
	'settings' => 'copyrights_text',
	'label'    => esc_attr__( 'Text', 'ultrastore' ),
	'section'  => 'copyrights',
	'default'  => '&copy; Copyright ' . date( 'Y' ) . ' <a href="' . esc_url( home_url() ) . '">' . esc_attr( get_bloginfo( 'name' ) ) . '</a> &middot; Designed and Developed by <a href="http://www.theme-junkie.com/">Theme Junkie</a>',
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Height
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'slider',
	'settings'    => 'copyrights_height',
	'label'       => esc_attr__( 'Height', 'ultrastore' ),
	'description' => esc_attr__( 'Control the height of the copyrights area.', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => '20',
	'choices'     => array(
		'min'  => '20',
		'max'  => '50',
		'step' => '5',
	),
	'output'      => array(
		array(
			'element'  => '.copyrights',
			'property' => 'padding-top',
			'units'    => 'px',
			'exclude'  => array( '20' )
		),
		array(
			'element'  => '.copyrights',
			'property' => 'padding-bottom',
			'units'    => 'px',
			'exclude'  => array( '20' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.copyrights',
			'property' => 'padding-top',
			'units'    => 'px',
			'function' => 'css',
		),
		array(
			'element'  => '.copyrights',
			'property' => 'padding-bottom',
			'units'    => 'px',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Background color
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'copyrights_bg',
	'label'       => esc_attr__( 'Background Color', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => '#faf1ed',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.copyrights',
			'property' => 'background-color',
			'exclude'  => array( '#faf1ed' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.copyrights',
			'property' => 'background-color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Color
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'copyrights_color',
	'label'       => esc_attr__( 'Color', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => '#3b3939',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.copyrights',
			'property' => 'color',
			'exclude'  => array( '#3b3939' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.copyrights',
			'property' => 'color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Link Color
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'copyrights_color_link',
	'label'       => esc_attr__( 'Color: Link', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => '#3b3939',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.copyrights a',
			'property' => 'color',
			'exclude'  => array( '#3b3939' )
		),
		array(
			'element'  => '.copyrights a:visited',
			'property' => 'color',
			'exclude'  => array( '#3b3939' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.copyrights a',
			'property' => 'color',
			'function' => 'css',
		),
		array(
			'element'  => '.copyrights a:visited',
			'property' => 'color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Link Color: Hover
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'color',
	'settings'    => 'copyrights_color_hover',
	'label'       => esc_attr__( 'Color: Hover', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => '#fbbfa3',
	'choices'     => array(
		'alpha' => true,
	),
	'output'      => array(
		array(
			'element'  => '.copyrights a:hover',
			'property' => 'color',
			'exclude'  => array( '#fbbfa3' )
		),
	),
	'transport'   => 'postMessage',
	'js_vars'     => array(
		array(
			'element'  => '.copyrights a:hover',
			'property' => 'color',
			'function' => 'css',
		),
	),
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Typography
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'typography',
	'settings'    => 'copyrights_typography',
	'label'       => esc_attr__( 'Typography', 'ultrastore' ),
	'section'     => 'copyrights',
	'default'     => array(
		'font-family'    => 'Muli',
		'variant'        => 'regular',
		'font-size'      => '14px',
		'letter-spacing' => '0',
		'text-transform' => 'none'
	),
	'output'       => array(
		array(
			'element'  => '.copyrights'
		),
	),
	'required'    => array(
		array(
			'setting'  => 'copyrights_enable',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

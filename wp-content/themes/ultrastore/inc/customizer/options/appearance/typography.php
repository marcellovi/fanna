<?php
/**
 * Typography section
 */
Kirki::add_section( 'typography', array(
	'title'          => esc_attr__( 'Global Typography', 'ultrastore' ),
	'priority'       => 5,
	'panel'          => 'appearance'
) );

/**
 * Body text
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'typography',
	'settings'    => 'body_text',
	'label'       => esc_attr__( 'Body Text', 'ultrastore' ),
	'section'     => 'typography',
	'default'     => array(
		'font-family'    => 'Muli',
		'variant'        => 'regular',
		'font-size'      => '17px',
		'letter-spacing' => '0',
		'text-transform' => 'none'
	),
	'output'       => array(
		array(
			'element'  => 'body'
		),
	),
) );

/**
 * Heading text
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'typography',
	'settings'    => 'heading_text',
	'label'       => esc_attr__( 'Heading Text', 'ultrastore' ),
	'section'     => 'typography',
	'default'     => array(
		'font-family'    => 'Muli',
		'variant'        => '800',
		'letter-spacing' => '0',
		'text-transform' => 'none'
	),
	'output'       => array(
		array(
			'element'  => ultrastore_heading_selector()
		),
	),
) );

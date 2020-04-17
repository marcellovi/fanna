<?php
/**
 * Site title section
 */
Kirki::add_section( 'site_title', array(
	'title'          => esc_attr__( 'Site Title', 'ultrastore' ),
	'priority'       => 5,
	'panel'          => 'header'
) );

/**
 * Site title
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'typography',
	'settings'    => 'site_title_typography',
	'label'       => esc_attr__( 'Site Title', 'ultrastore' ),
	'section'     => 'site_title',
	'default'     => array(
		'font-family'    => 'Muli',
		'variant'        => '800',
		'font-size'      => '30px',
		'letter-spacing' => '0',
		'color'          => '#3b3939',
		'text-transform' => 'uppercase'
	),
	'output'       => array(
		array(
			'element'  => '.site-title a',
			'suffix'   => '!important'
		),
	),
) );

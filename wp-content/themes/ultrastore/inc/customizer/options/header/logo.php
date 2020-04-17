<?php
/**
 * Logo section
 */
Kirki::add_section( 'logo', array(
	'title'          => esc_attr__( 'Logo', 'ultrastore' ),
	'priority'       => 10,
	'panel'          => 'header'
) );

Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'image',
	'settings'    => 'retina_logo',
	'label'       => esc_attr__( 'Logo Retina', 'ultrastore' ),
	'description' => esc_attr__( 'Select a retina version of your logo.', 'ultrastore' ),
	'section'     => 'logo',
	'default'     => '',
	'choices'     => array(
		'save_as' => 'id',
	),
) );

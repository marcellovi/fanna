<?php
/**
 * Post section
 */
Kirki::add_section( 'post', array(
	'title'          => esc_attr__( 'Single Post', 'ultrastore' ),
	'priority'       => 5,
	'panel'          => 'blog'
) );

/**
 * Post layout
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'radio-image',
	'settings'    => 'post_layout',
	'label'       => esc_html__( 'Post Layout', 'ultrastore' ),
	'description' => esc_html__( 'Single post layout.', 'ultrastore' ),
	'section'     => 'post',
	'default'     => 'right-sidebar',
	'choices'     => array(
		'right-sidebar'     => trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/img/rs.png',
		'left-sidebar'      => trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/img/ls.png',
		'full-width'        => trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/img/fw.png',
		'full-width-narrow' => trailingslashit( get_template_directory_uri() ) . 'inc/customizer/assets/img/fwn.png',
	),
) );

/**
 * Featured image
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_featured_image',
	'label'       => esc_attr__( 'Featured Image', 'ultrastore' ),
	'description' => esc_attr__( 'Enable featured image', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

/**
 * Meta
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_meta',
	'label'       => esc_attr__( 'Post Meta', 'ultrastore' ),
	'description' => esc_attr__( 'Enable post meta', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

/**
 * Tags
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_tags',
	'label'       => esc_attr__( 'Post Tags', 'ultrastore' ),
	'description' => esc_attr__( 'Enable post tags', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

/**
 * Tags title
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'     => 'text',
	'settings' => 'post_tags_title',
	'label'    => esc_attr__( 'Tags Title', 'ultrastore' ),
	'section'  => 'post',
	'default'  => esc_attr__( 'Topics', 'ultrastore' ),
	'required'    => array(
		array(
			'setting'  => 'post_tags',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Post navigation
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_navigation',
	'label'       => esc_attr__( 'Post Navigation', 'ultrastore' ),
	'description' => esc_attr__( 'Enable next & prev post', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

/**
 * Post author box
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_author_box',
	'label'       => esc_attr__( 'Post Author', 'ultrastore' ),
	'description' => esc_attr__( 'Enable post author box', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

/**
 * Post related
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_related',
	'label'       => esc_attr__( 'Related Posts', 'ultrastore' ),
	'description' => esc_attr__( 'Enable related posts', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

/**
 * Post related title
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'     => 'text',
	'settings' => 'post_related_title',
	'label'    => esc_attr__( 'Related Title', 'ultrastore' ),
	'section'  => 'post',
	'default'  => esc_attr__( 'You Might Also Like:', 'ultrastore' ),
	'required'    => array(
		array(
			'setting'  => 'post_related',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Post related number
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'slider',
	'settings'    => 'post_related_number',
	'label'       => esc_attr__( 'Related Number', 'ultrastore' ),
	'description' => esc_attr__( 'The number of related posts', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '3',
	'choices'      => array(
		'min'  => 3,
		'max'  => 9,
		'step' => 1,
	),
	'required'    => array(
		array(
			'setting'  => 'post_related',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/**
 * Post comment
 */
Kirki::add_field( 'ultrastore_options', array(
	'type'        => 'toggle',
	'settings'    => 'post_comment',
	'label'       => esc_attr__( 'Post Comment', 'ultrastore' ),
	'description' => esc_attr__( 'Enable post comment', 'ultrastore' ),
	'section'     => 'post',
	'default'     => '1'
) );

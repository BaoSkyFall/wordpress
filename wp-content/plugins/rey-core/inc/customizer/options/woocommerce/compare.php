<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = 'woocommerce_rey_compare';

ReyCoreKirki::add_section($section, array(
    'title'          => esc_html__('Compare', 'rey-core'),
	'priority'       => 90,
	'panel'			=> 'woocommerce'
));


ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'compare__enable',
	'label'       => esc_html__( 'Enable Compare', 'rey-core' ),
	'section'     => $section,
	'default'     => false,
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'dropdown-pages',
	'settings'    => 'compare__default_url',
	'label'       => esc_html__( 'Compare page', 'rey-core' ),
	'section'     => $section,
	'default'     => '',
	'active_callback' => [
		[
			'setting'  => 'compare__enable',
			'operator' => '==',
			'value'    => true,
		],
	],
	'choices' => [
		'' => esc_html__('- Select page -', 'rey-core')
	]
] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'select',
		'settings'    => 'compare__after_add',
		'label'       => esc_html__( 'After add to list', 'rey-core' ),
		'section'     => $section,
		'default'     => 'notice',
		'choices'     => [
			'' => esc_html__( 'Do nothing', 'rey-core' ),
			'notice' => esc_html__( 'Show Notice', 'rey-core' ),
		],
		'active_callback' => [
			[
				'setting'  => 'compare__enable',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );


reycore_customizer__title([
	'title'       => esc_html__('Catalog', 'rey-core'),
	'section'     => $section,
	'size'        => 'md',
	'border'      => 'top',
	'upper'       => true,
	'active_callback' => [
		[
			'setting'  => 'compare__enable',
			'operator' => '==',
			'value'    => true,
		],
	],
]);


	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'toggle',
		'settings'    => 'compare__loop_enable',
		'label'       => esc_html__( 'Enable button', 'rey-core' ),
		'section'     => $section,
		'default'     => true,
		'active_callback' => [
			[
				'setting'  => 'compare__enable',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );


reycore_customizer__title([
	'title'       => esc_html__('Product Page', 'rey-core'),
	'section'     => $section,
	'size'        => 'md',
	'border'      => 'top',
	'upper'       => true,
	'active_callback' => [
		[
			'setting'  => 'compare__enable',
			'operator' => '==',
			'value'    => true,
		],
	],
]);

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'toggle',
		'settings'    => 'compare__pdp_enable',
		'label'       => esc_html__( 'Enable button', 'rey-core' ),
		'section'     => $section,
		'default'     => true,
		'active_callback' => [
			[
				'setting'  => 'compare__enable',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'toggle',
		'settings'    => 'compare__pdp_wtext',
		'label'       => esc_html__( 'Show text in button?', 'rey-core' ),
		'section'     => $section,
		'default'     => true,
		'active_callback' => [
			[
				'setting'  => 'compare__enable',
				'operator' => '==',
				'value'    => true,
			],
			[
				'setting'  => 'compare__pdp_enable',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'select',
		'settings'    => 'compare__pdp_position',
		'label'       => esc_html__( 'Button Position', 'rey-core' ),
		'section'     => $section,
		'default'     => 'after',
		'choices'     => [
			'inline' => esc_html__( 'Inline with ATC. button', 'rey-core' ),
			'before' => esc_html__( 'Before ATC. button', 'rey-core' ),
			'after' => esc_html__( 'After ATC. button', 'rey-core' ),
		],
		'active_callback' => [
			[
				'setting'  => 'compare__enable',
				'operator' => '==',
				'value'    => true,
			],
			[
				'setting'  => 'compare__pdp_enable',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'select',
		'settings'    => 'compare__pdp_btn_style',
		'label'       => esc_html__( 'Button Style', 'rey-core' ),
		'section'     => $section,
		'default'     => 'btn-line',
		'choices'     => [
			'none' => esc_html__( 'None', 'rey-core' ),
			'btn-line' => esc_html__( 'Underlined on hover', 'rey-core' ),
			'btn-line-active' => esc_html__( 'Underlined', 'rey-core' ),
			'btn-primary' => esc_html__( 'Regular', 'rey-core' ),
			'btn-primary btn--block' => esc_html__( 'Regular & Full width', 'rey-core' ),
			'btn-primary-outlined' => esc_html__( 'Regular outline', 'rey-core' ),
			'btn-primary-outlined btn--block' => esc_html__( 'Regular outline & Full width', 'rey-core' ),
			'btn-secondary' => esc_html__( 'Regular', 'rey-core' ),
			'btn-secondary btn--block' => esc_html__( 'Regular & Full width', 'rey-core' ),
		],
		'active_callback' => [
			[
				'setting'  => 'compare__enable',
				'operator' => '==',
				'value'    => true,
			],
			[
				'setting'  => 'compare__pdp_enable',
				'operator' => '==',
				'value'    => true,
			],
		],
	] );

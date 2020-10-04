<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = 'header_cart_options';

ReyCoreKirki::add_section($section, array(
    'title'          => esc_attr__('Shopping Cart', 'rey-core'),
	'priority'       => 60,
	'panel'			 => 'header_options'
));

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'header_enable_cart',
	'label'       => esc_html__( 'Enable Shopping Cart?', 'rey-core' ),
	'section'     => $section,
	'default'     => true,
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'header_cart_layout',
	'label'       => esc_html__( 'Cart Layout', 'rey-core' ),
	'section'     => $section,
	'default'     => 'bag',
	'choices'     => [
		'bag' => esc_html__( 'Icon - Shopping Bag', 'rey-core' ),
		'bag2' => esc_html__( 'Icon - Shopping Bag 2', 'rey-core' ),
		'bag3' => esc_html__( 'Icon - Shopping Bag 3', 'rey-core' ),
		'basket' => esc_html__( 'Icon - Shopping Basket', 'rey-core' ),
		'basket2' => esc_html__( 'Icon - Shopping Basket 2', 'rey-core' ),
		'cart' => esc_html__( 'Icon - Shopping Cart', 'rey-core' ),
		'cart2' => esc_html__( 'Icon - Shopping Cart 2', 'rey-core' ),
		'cart3' => esc_html__( 'Icon - Shopping Cart 3', 'rey-core' ),
		'text' => esc_html__( 'Text (deprecated)', 'rey-core' ),
		'disabled' => esc_html__( 'No Icon', 'rey-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'header_enable_cart',
			'operator' => '==',
			'value'    => true,
		],
	],
] );

// TODO: Remove in 2.0
ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'     => 'text',
	'settings' => 'header_cart_text',
	'label'    => esc_html__( 'Cart Text', 'rey-core' ),
	'description'    => esc_html__( 'Use {{total}} string to add the cart totals.', 'rey-core' ),
	'section'  => $section,
	'default'  => '',
	'active_callback' => [
		[
			'setting'  => 'header_enable_cart',
			'operator' => '==',
			'value'    => true,
		],
		[
			'setting'  => 'header_cart_layout',
			'operator' => '==',
			'value'    => 'text',
		],
	],
	'input_attrs' => [
		'placeholder' => esc_html__( 'eg: CART', 'rey-core' )
	]
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'     => 'text',
	'settings' => 'header_cart_text_v2',
	'label'    => esc_html__( 'Cart Text', 'rey-core' ),
	'description'    => esc_html__( 'Use {{total}} string to add the cart totals.', 'rey-core' ),
	'section'  => $section,
	'default'  => '',
	'active_callback' => [
		[
			'setting'  => 'header_enable_cart',
			'operator' => '==',
			'value'    => true,
		],
		// TODO: remove in v2.0
		// added this to make sure there aren't 2 text fields
		[
			'setting'  => 'header_cart_layout',
			'operator' => '!=',
			'value'    => 'text',
		],
	],
	'input_attrs' => [
		'placeholder' => esc_html__( 'eg: CART', 'rey-core' )
	]
] );


ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'header_cart_hide_empty',
	'label'       => esc_html__( 'Hide Cart if empty?', 'rey-core' ),
	'description' => esc_html__( 'Will hide the cart icon if no products in cart.', 'rey-core' ),
	'section'     => $section,
	'default'     => 'no',
	'choices'     => [
		'yes' => esc_html__( 'Yes', 'rey-core' ),
		'no' => esc_html__( 'No', 'rey-core' ),
	],
] );

/* ------------------------------------ PANEL ------------------------------------ */

reycore_customizer__title([
	'title'       => esc_html__('Cart Panel', 'rey-core'),
	'section'     => $section,
	'size'        => 'md',
	'border'      => 'none',
	'upper'       => true,
]);

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'header_cart__panel_disable',
	'label'       => esc_html__( 'Disable Panel', 'rey-core' ),
	'section'     => $section,
	'default'     => false,
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'header_cart__panel_width',
	'label'       => esc_html__( 'Panel Width Type', 'rey-core' ),
	'section'     => $section,
	'default'     => 'default',
	'choices'     => [
		'default'   => esc_html__( 'Default', 'rey-core' ),
		'px'  => esc_html__( 'Custom in Pixels (px)', 'rey-core' ),
		'vw' => esc_html__( 'Custom in Viewport (vw)', 'rey-core' ),
	],
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        		=> 'rey-number',
	'settings'    		=> 'header_cart__panel_width__vw',
	'label'       		=> esc_attr__( 'Panel Width (vw)', 'rey-core' ),
	'section'           => $section,
	'default'     		=> 90,
	'choices'     		=> [
		'min'  => 10,
		'max'  => 100,
		'step' => 1,
	],
	'transport'   		=> 'auto',
	'output'      		=> [
		[
			'element'  		=> ':root',
			'property' 		=> '--header-cart-width',
			'units'    		=> 'vw',
		]
	],
	'active_callback' => [
		[
			'setting'  => 'header_cart__panel_width',
			'operator' => '==',
			'value'    => 'vw',
		],
	],
	'responsive' => true
]);

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        		=> 'rey-number',
	'settings'    		=> 'header_cart__panel_width__px',
	'label'       		=> esc_attr__( 'Panel Width (px)', 'rey-core' ),
	'section'           => $section,
	'default'     		=> 470,
	'choices'     		=> array(
		'min'  => 200,
		'max'  => 2560,
		'step' => 10,
	),
	'transport'   		=> 'auto',
	'output'      		=> [
		[
			'element'  		=> ':root',
			'property' 		=> '--header-cart-width',
			'units'    		=> 'px',
		]
	],
	'active_callback' => [
		[
			'setting'  => 'header_cart__panel_width',
			'operator' => '==',
			'value'    => 'px',
		],
	],
	'responsive' => true
]);

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'color',
	'settings'    => 'header_cart__bg_color',
	'label'       => esc_html__( 'Background Color', 'rey-core' ),
	'section'     => $section,
	'default'     => '',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   		=> 'auto',
	'output'      		=> [
		[
			'element'  		=> ':root',
			'property' 		=> '--header-cart-bgcolor',
		]
	],
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'header_cart__text_theme',
	'label'       => esc_html__( 'Text color theme', 'rey-core' ),
	'section'     => $section,
	'default'     => 'def',
	'choices'     => [
		'def' => esc_html__( 'Default', 'rey-core' ),
		'light' => esc_html__( 'Light', 'rey-core' ),
		'dark' => esc_html__( 'Dark', 'rey-core' ),
	],
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'header_cart__btns_inline',
	'label'       => esc_html__( 'Buttons inline', 'rey-core' ),
	'section'     => $section,
	'default'     => false,
] );

reycore_customizer__separator([
	'section' => $section,
	'id'      => 'c1',
]);

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'text',
	'settings'    => 'header_cart__title',
	'label'       => esc_html__( 'Panel Title', 'rey-core' ),
	'section'     => $section,
	'default'     => '',
	'input_attrs'     => [
		'placeholder' => esc_html__('eg: Shopping Bag ( %s )', 'rey-core'),
	],
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'header_cart_gs',
	'label'       => esc_html__( 'Empty Cart Content', 'rey-core' ),
	'description' => esc_html__( 'Add custom Elementor content into the Cart Panel if no products are added into it.', 'rey-core' ),
	'section'     => $section,
	'default'     => 'none',
	'choices'     => class_exists('ReyCore_GlobalSections') ? ReyCore_GlobalSections::get_global_sections('generic', ['none' => '- None -']) : [],
	'active_callback' => [
		[
			'setting'  => 'header_cart_hide_empty',
			'operator' => '==',
			'value'    => 'no',
		],
	],
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'header_cart_show_shipping',
	'label'       => esc_html__( 'Show Shipping under subtotal', 'rey-core' ),
	'section'     => $section,
	'default'     => false,
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'header_cart_show_qty',
	'label'       => esc_html__( 'Show quantity controls', 'rey-core' ),
	'section'     => $section,
	'default'     => true,
] );

reycore_customizer__help_link([
	'url' => 'https://support.reytheme.com/kb/customizer-header-settings/#shopping-cart',
	'section' => $section
]);

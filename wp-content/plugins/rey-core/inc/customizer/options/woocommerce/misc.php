<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = 'woocommerce_product_catalog_misc';


ReyCoreKirki::add_section($section, array(
    'title'          => esc_html__('Product Catalog - Misc.', 'rey-core'),
	'priority'       => 11,
	'panel'			=> 'woocommerce'
));

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'shop_catalog',
	'label'       => esc_html__( 'Enable Catalog Mode', 'rey-core' ),
	'section'     => $section,
	'default'     => false,
	'priority'    => 5,
	'description' => __( 'Enabling catalog mode will disable all cart functionalities.', 'rey-core' ),
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'shop_catalog_variable',
	'label'       => esc_html__( 'Disable variable product form?', 'rey-core' ),
	'section'     => $section,
	'default'     => true,
	'priority'    => 5,
	'active_callback' => [
		[
			'setting'  => 'shop_catalog',
			'operator' => '==',
			'value'    => true,
		],
	],
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'shop_catalog_page_exclude',
	'label'       => esc_html__( 'Exclude categories from Shop Page', 'rey-core' ),
	'section'     => $section,
	'default'     => '',
	'priority'    => 5,
	'multiple'    => 100,
	'choices'     => reycore_wc__product_categories([
		'parent' => 0,
		'hide_empty' => false,
	]),
] );


ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'toggle',
	'settings'    => 'archive__title_back',
	'label'    => reycore_customizer__title_tooltip(
		__('Enable back arrow', 'rey-core'),
		__('If enabled, a back arrow will be displayed in the left side of the product archive.', 'rey-core')
	),
	'section'     => $section,
	'default'     => false,
	'priority'    => 5,
] );

ReyCoreKirki::add_field( 'rey_core_kirki', [
	'type'        => 'select',
	'settings'    => 'archive__back_behaviour',
	'label'       => esc_html__( 'Behaviour', 'rey-core' ),
	'section'     => $section,
	'default'     => 'parent',
	'choices'     => [
		'parent' => esc_html__( 'Back to parent', 'rey-core' ),
		'shop' => esc_html__( 'Back to shop page', 'rey-core' ),
		'page' => esc_html__( 'Back to previous page', 'rey-core' ),
	],
	'priority'    => 5,
	'active_callback' => [
		[
			'setting'  => 'archive__title_back',
			'operator' => '==',
			'value'    => true,
			],
	],
	'rey_group_start' => [
		'label'       => esc_html__( 'Back button options', 'rey-core' ),
	],
	'rey_group_end' => true
] );


reycore_customizer__help_link([
	'url' => 'https://support.reytheme.com/kb/customizer-woocommerce/#product-catalog-miscellaneous',
	'section' => $section
]);

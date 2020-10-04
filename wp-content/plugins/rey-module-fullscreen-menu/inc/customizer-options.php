<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly


add_action('reycore/customizer/init', function(){

	// Nav
	ReyCoreKirki::add_section('header_fs_options', array(
		'title'          => esc_attr__('FullScreen Navigation', 'rey-module-fullscreen-menu'),
		'priority'       => 10,
		'panel'			 => 'header_options'
	));

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'custom',
		'settings'    => 'header_fs_title',
		'section'     => 'header_fs_options',
		'default'     => '<h2>' . esc_html__('Fullscreen Navigation', 'rey-module-fullscreen-menu') . '</h2> <hr>',
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'select',
		'settings'    => 'header_fs_type',
		'label'       => esc_html__( 'Content Type', 'rey-module-fullscreen-menu' ),
		'section'     => 'header_fs_options',
		'default'     => 'menu',
		'priority'    => 10,
		'choices'     => [
			'menu' => esc_html__( 'Menu', 'rey-module-fullscreen-menu' ),
			'gs' => esc_html__( 'Global Section', 'rey-module-fullscreen-menu' ),
		],
	] );

	if( class_exists('ReyCore_GlobalSections') ){

		$sections = ReyCore_GlobalSections::get_global_sections('generic', [
			''  => esc_html__( '- Select -', 'rey-module-fullscreen-menu' )
		]);

		ReyCoreKirki::add_field( 'rey_core_kirki', [
			'type'        => 'select',
			'settings'    => 'header_fs_gs',
			'label'       => esc_html__( 'Global Section', 'rey-module-fullscreen-menu' ),
			'section'     => 'header_fs_options',
			'default'     => '',
			'priority'    => 10,
			'choices'     => $sections,
			'active_callback' => [
				[
					'setting'  => 'header_fs_type',
					'operator' => '==',
					'value'    => 'gs',
				],
			],
		] );
	}

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'select',
		'settings'    => 'header_fs_menu',
		'label'       => esc_html__( 'Menu source', 'rey-module-fullscreen-menu' ),
		'description' => esc_html__( 'Please select the menu to be shown inside the menu.', 'rey-module-fullscreen-menu' ),
		'section'     => 'header_fs_options',
		'default'     => '',
		'choices'     => ['' => esc_html__('- Select -', 'rey-module-fullscreen-menu')] + reycore__get_all_menus(),
		'active_callback' => [
			[
				'setting'  => 'header_fs_type',
				'operator' => '==',
				'value'    => 'menu',
			],
		],
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'select',
		'settings'    => 'header_fs_size',
		'label'       => esc_html__( 'Menu Items Size', 'rey-module-fullscreen-menu' ),
		'description' => esc_html__( 'Select the menu items size.', 'rey-module-fullscreen-menu' ),
		'section'     => 'header_fs_options',
		'default'     => 'xl',
		'choices'     => [
			'sm' => esc_html__('Small', 'rey-module-fullscreen-menu'),
			'default' => esc_html__('Normal', 'rey-module-fullscreen-menu'),
			'lg' => esc_html__('Large', 'rey-module-fullscreen-menu'),
			'xl' => esc_html__('Extra Large', 'rey-module-fullscreen-menu'),
			'xxl' => esc_html__('Extra Extra Large', 'rey-module-fullscreen-menu'),
		],
		'active_callback' => [
			[
				'setting'  => 'header_fs_type',
				'operator' => '==',
				'value'    => 'menu',
			],
			[
				'setting'  => 'header_fs_menu',
				'operator' => '!=',
				'value'    => '',
			],
		],
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'image',
		'settings'    => 'header_fs_logo',
		'label'       => esc_html__( 'Logo Image', 'rey-module-fullscreen-menu' ),
		'description' => esc_html__( 'This logo will be shown above the menu.', 'rey-module-fullscreen-menu' ),
		'section'     => 'header_fs_options',
		'default'     => '',
		'transport'   => 'auto',
		'choices'     => [
			'save_as' => 'id',
		],
		'active_callback' => [
			[
				'setting'  => 'header_fs_type',
				'operator' => '==',
				'value'    => 'menu',
			],
			[
				'setting'  => 'header_fs_menu',
				'operator' => '!=',
				'value'    => '',
			],
		],
	] );

	ReyCoreKirki::add_field( 'rey_core_kirki', [
		'type'        => 'color',
		'settings'    => 'header_fs_theme_custom',
		'label'       => esc_html__( 'Theme Color', 'rey-module-fullscreen-menu' ),
		'description' => esc_html__( 'Text color is automatically calculated to be in contrast.', 'rey-module-fullscreen-menu' ),
		'section'     => 'header_fs_options',
		'default'     => '',
		'choices'     => [
			'alpha' => true,
		],
	] );

	reycore_customizer__help_link([
		'url' => 'https://support.reytheme.com/kb/customizer-header-settings/#fullscreen-navigation',
		'section' => 'header_fs_options'
	]);

});

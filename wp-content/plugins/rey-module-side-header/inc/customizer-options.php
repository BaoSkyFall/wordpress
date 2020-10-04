<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly


if( !class_exists('ReyModuleSideHeader__Customizer') ):

	class ReyModuleSideHeader__Customizer
	{
		public function __construct(){
			add_action('init', [$this, 'init']);
			add_action( 'reycore/kirki_fields/replace_field', [ $this, 'add_options' ], 10, 2 );
		}

		function init(){

			if( ! $this->can_add_options() ){
				return;
			}

			$this->add_customizer_controls();
			$this->disable_customizer_controls();
		}

		function add_customizer_controls(){

			$section = 'side_header_options';

			ReyCoreKirki::add_section($section, array(
				'title'          => esc_attr__('Side Header', 'rey-module-side-header'),
				'priority'       => 15,
				'panel'			 => 'header_options'
			));

			ReyCoreKirki::add_field( 'rey_core_kirki', [
				'type'        => 'custom',
				'settings'    => 'side_header_title',
				'section'     => $section,
				'default'     => '<h2>' . esc_html__('Side Header', 'rey-module-side-header') . '</h2> <hr>',
			] );

			ReyCoreKirki::add_field( 'rey_core_kirki', [
				'type'        => 'toggle',
				'settings'    => 'side_header_enable',
				'label'       => esc_html__( 'Enable Side Header', 'rey-module-side-header' ),
				'section'     => $section,
				'default'     => false,
			] );

			ReyCoreKirki::add_field( 'rey_core_kirki', [
				'type'        => 'select',
				'settings'    => 'side_header_basic_style',
				'label'       => esc_html__( 'Layout Style', 'rey-module-side-header' ),
				'section'     => $section,
				'default'     => '',
				'choices'     => [
					'' => esc_html__( 'Default', 'rey-module-side-header' ),
					'--s1' => esc_html__( 'Middle Navigation', 'rey-module-side-header' ),
					'--s2' => esc_html__( 'Middle Logo', 'rey-module-side-header' ),
				],
				'active_callback' => [
					[
						'setting'  => 'side_header_enable',
						'operator' => '==',
						'value'    => true,
					],
					[
						'setting'  => 'header_layout_type',
						'operator' => '==',
						'value'    => 'default',
					],
				],
			] );

			ReyCoreKirki::add_field( 'rey_core_kirki', [
				'type'        => 'select',
				'settings'    => 'side_header_x_align',
				'label'       => esc_html__( 'Horizontal Align', 'rey-module-side-header' ),
				'tooltip'       => esc_html__( 'Select a horizontal alignment for the inner header components.', 'rey-module-side-header' ),
				'section'     => $section,
				'default'     => 'start',
				'priority'    => 10,
				'choices'     => [
					'start' => esc_html__( 'Start', 'rey-module-side-header' ),
					'center' => esc_html__( 'Center', 'rey-module-side-header' ),
					'end' => esc_html__( 'End', 'rey-module-side-header' ),
				],
				'active_callback' => [
					[
						'setting'  => 'side_header_enable',
						'operator' => '==',
						'value'    => true,
					],
				],
			] );

			ReyCoreKirki::add_field( 'rey_core_kirki', [
				'type'        => 'toggle',
				'settings'    => 'side_header_hover_effect',
				'label'       => esc_html__( 'Enable Hover Effect', 'rey-module-side-header' ),
				'section'     => $section,
				'default'     => true,
				'active_callback' => [
					[
						'setting'  => 'side_header_enable',
						'operator' => '==',
						'value'    => true,
					],
				],
			] );

			ReyCoreKirki::add_field( 'rey_core_kirki', array(
				'type'        		=> 'slider',
				'settings'    		=> 'side_header_width',
				'label'       		=> esc_attr__( 'Width', 'rey-module-side-header' ),
				'section'           => $section,
				'default'     		=> 250,
				'choices'     		=> array(
					'min'  => 30,
					'max'  => 400,
					'step' => 1,
				),
				// 'transport'   => 'auto',
				'output'      		=> [
					[
						'element'  		=> ':root',
						'property' 		=> '--side-header--width',
						'units'    		=> 'px',
					],
				],
				'active_callback' => [
					[
						'setting'  => 'side_header_enable',
						'operator' => '==',
						'value'    => true,
					],
				],
			));

			ReyCoreKirki::add_field( 'rey_core_kirki', [
				'type'        => 'dimensions',
				'settings'    => 'side_header_padding',
				'label'       => esc_html__( 'Side Padding', 'rey-module-side-header' ),
				'description' => __( 'Will add padding around the header container. Dont forget to include unit (eg: px, em, rem).', 'rey-module-side-header' ),
				'section'     => $section,
				'default'     => [
					'padding-top'    => '',
					'padding-right'  => '',
					'padding-bottom' => '',
					'padding-left'   => '',
				],
				'choices'     => [
					'labels' => [
						'padding-top'  => esc_html__( 'Top', 'rey-module-side-header' ),
						'padding-right' => esc_html__( 'Right', 'rey-module-side-header' ),
						'padding-bottom'  => esc_html__( 'Bottom', 'rey-module-side-header' ),
						'padding-left' => esc_html__( 'Left', 'rey-module-side-header' ),
					],
				],
				'transport'   		=> 'auto',
				'output'      		=> [
					[
						'element'  		=> ':root',
						'property' 		=> '--side-header-',
					],
				],
				'input_attrs' => [
					'data-needs-unit' => 'px',
					'data-control-class' => 'dimensions-4-cols',
				],
				'active_callback' => [
					[
						'setting'  => 'side_header_enable',
						'operator' => '==',
						'value'    => true,
					],
				],
			] );

			if( function_exists('reycore_customizer__help_link') ):
			reycore_customizer__help_link([
				'url' => 'https://support.reytheme.com/kb/customizer-header-settings/#fullscreen-navigation',
				'section' => $section
			]);
			endif;

		}

		function disable_customizer_controls(){

			if( ! get_theme_mod('side_header_enable', false) ){
				return;
			}

			add_filter('reycore/kirki_fields/disable_field=header_position', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=custom_header_width', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_width', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_height', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_separator', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_separator_bar', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_separator_bar_color', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_separator_bar_mobile', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_fixed_overlap', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_fixed_disable_mobile', '__return_true');
			add_filter('reycore/kirki_fields/disable_field=header_nav_items_spacing', '__return_true');
		}

		function add_options($setting, $args){

			if( ! $this->can_add_options() ){
				return;
			}

			// Adds vertical spacing
			if( $setting === 'header_nav_items_spacing' ){

				ReyCoreKirki::add_field( 'rey_core_kirki', [
					'type'        => 'slider',
					'settings'    => 'header_nav_items_spacing_y',
					'label'       => esc_html__( 'Vertical Spacing (px)', 'rey-module-side-header' ),
					'section'     => $args['section'],
					'default'     => 5,
					'transport'   => 'auto',
					'choices'     => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'output'      		=> [
						[
							'media_query'	=> '@media (min-width: 1025px)',
							'element'  		=> ':root',
							'property' 		=> '--header-nav-y-spacing',
							'units'    		=> 'px',
						],
					],
				] );

			}

		}

		function can_add_options(){
			return class_exists('ReyCoreKirki');
		}
	}

	new ReyModuleSideHeader__Customizer;
	endif;

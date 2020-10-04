<?php
/**
 * Plugin Name: Rey Module - Fullscreen Menu
 * Description: A Rey Module to add a Fullscreen Menu into the header.
 * Plugin URI: http://www.reytheme.com/
 * Version: 1.1.4
 * Author: ReyTheme
 * Author URI:  https://twitter.com/mariushoria
 * Text Domain: rey-module-fullscreen-menu
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if( class_exists('ReyCore') && !class_exists('ReyModuleFullscreenMenu') ):

class ReyModuleFullscreenMenu
{

	public function __construct()
	{
		$this->define_constants();
		$this->init_hooks();
	}

	/**
	 * Initialize Hooks
	 *
	 * @since 1.0.0
	 */
	public function init_hooks()
	{
		add_action( 'plugins_loaded', [$this, 'plugins_loaded'], 20);
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
		remove_action('rey/header/row', 'rey__header__navigation', 20);
		add_action('rey/header/row', [$this, 'add_panel_markup'], 20);
		add_action( 'rey/after_site_wrapper', [$this, 'add_panel_markup']);
		add_filter('body_class', [$this, 'body_class'], 15);
		add_filter('rey/css_styles', [$this, 'css_style'] );

	}

	/**
	 * Define Constants.
	 * @since 1.0.0
	 */
	private function define_constants()
	{
		$this->define( 'REY_MODULE_FULLSCREEN_MENU_DIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'REY_MODULE_FULLSCREEN_MENU_URI', plugin_dir_url( __FILE__ ) );
		$this->define( 'REY_MODULE_FULLSCREEN_MENU__CORE_DEPENDENCY', version_compare(REY_CORE_VERSION, '1.0.4', '>=') );

		if( !defined('REY_DEV_MODE') ){
			$this->define( 'REY_MODULE_FULLSCREEN_MENU_VERSION', '1.1.4' );
		}
		else {
			// cache buster
			$this->define( 'REY_MODULE_FULLSCREEN_MENU_VERSION', rand(100, 99999) );
		}
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	function enqueue_frontend_scripts()
	{
		// Enqueue scripts
		wp_enqueue_script( 'reyModuleFullscreenMenu-scripts', REY_MODULE_FULLSCREEN_MENU_URI . 'assets/js/script.js', ['jquery', 'rey-script', 'animejs'], REY_MODULE_FULLSCREEN_MENU_VERSION, true );
		// Enqueue Styles
		wp_enqueue_style('reyModuleFullscreenMenu-styles', REY_MODULE_FULLSCREEN_MENU_URI . 'assets/css/styles.css', false, REY_MODULE_FULLSCREEN_MENU_VERSION, null);
	}

	// Load localization file
	function load_plugin_textdomain(){
		load_plugin_textdomain( 'rey-module-fullscreen-menu', false, plugin_basename(dirname(__FILE__)) . '/languages');
	}

	function plugins_loaded(){

		$this->load_plugin_textdomain();

		//#! Load core files
		require_once REY_MODULE_FULLSCREEN_MENU_DIR . 'inc/customizer-options.php';
		require_once REY_MODULE_FULLSCREEN_MENU_DIR . 'inc/elementor.php';
	}

	/**
	 * Filter Body Class
	 *
	 * @since 1.0.0
	 **/
	function body_class($classes)
	{
		$classes['site_navigation'] = 'rey-navFullscreen';

		return $classes;
	}

	/**
	 * Add panel markup
	 *
	 * @since 1.0.0
	 */
	function add_panel_markup(){
		if( REY_MODULE_FULLSCREEN_MENU__CORE_DEPENDENCY ){
			reycore__get_template_part('template-parts/rey-module-fullscreen-menu-panel', REY_MODULE_FULLSCREEN_MENU_DIR);
		}
	}

	/**
	 * CSS Styles
	 *
	 * @since 1.0.0
	 */
	function css_style( $styles_output )
	{
		$color = get_theme_mod('header_fs_theme_custom', '');

		if( !empty($color) && class_exists('ReyCore_ColorUtilities') && is_callable('ReyCore_ColorUtilities::hex2rgba') ) {

			$root = ':root {';
			$root .= sprintf( '--fs-menu-bg-color: %s;', $color);
			$root .= sprintf( '--fs-menu-bg-color-1: %s;', ReyCore_ColorUtilities::adjust_color_brightness($color, -10) );
			$root .= sprintf( '--fs-menu-bg-color-2: %s;', ReyCore_ColorUtilities::adjust_color_brightness($color, 20) );
			$text_color = ReyCore_ColorUtilities::readable_colour($color);
			$root .= sprintf( '--fs-menu-color: %s;', $text_color );
			$root .= sprintf( '--fs-menu-hover-color: %s;', ReyCore_ColorUtilities::hex2rgba($text_color, 0.75) );
			$root .= '}';

			$styles_output[] = $root;
		}

		return $styles_output;
	}

}

new ReyModuleFullscreenMenu;

endif;

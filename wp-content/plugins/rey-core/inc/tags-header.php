<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add classes to header
 *
 * @since 1.0.0
 */
add_filter('body_class', function($classes){

	if( $rey_body_class = reycore__get_option('rey_body_class', '') ){
		$classes[] = esc_attr($rey_body_class);
	}

	unset($classes['search_style']);

	return $classes;
}, 20);


if(!function_exists('reycore__language_switcher_markup')):
	/**
	 * Language switcher markup for Header
	 *
	 * @since 1.0.0
	 **/
	function reycore__language_switcher_markup($args = [], $options = []){

		if( empty($args['languages']) ) {
			return;
		}

		$options = wp_parse_args($options, [
			'show_flags' => 'yes',
			'show_active_flag' => ''
		]);

		$html = '<div class="rey-langSwitcher rey-langSwitcher--'. $args['type'] .' rey-headerDropSwitcher rey-header-dropPanel rey-headerIcon">';

			$active_flag = '';

			if( $options['show_active_flag'] === 'yes' && isset($args['current_flag']) && ($current_flag_img = $args['current_flag']) ){
				$active_flag = sprintf( '<img src="%1$s" alt="%2$s">', $current_flag_img, $args['current'] );
			}

			$html .= '<button class="btn rey-headerIcon-btn rey-header-dropPanel-btn"> ' . $active_flag . $args['current'] . '</button>';
			$html .= '<div class="rey-header-dropPanel-content">';
				$html .= '<ul>';
				foreach ($args['languages'] as $key => $language) {

					$item_flag = '';

					if( $options['show_flags'] === 'yes' ){
						if( strpos($language['flag'], '<img') !== false ){
							$item_flag = $language['flag'];
						}
						else {
							$item_flag = sprintf( '<img src="%1$s" alt="%2$s">', $language['flag'], $language['name'] );
						}
					}

					$html .= sprintf( '<li class="%3$s"><a href="%4$s">%1$s<span>%2$s</span></a></li>',
						$item_flag,
						$language['name'],
						$language['active'] ? '--active' : '',
						$language['url']
					);
				}
				$html .= '</ul>';
			$html .= '</div>';
		$html .= '</div>';

		return apply_filters('reycore/language_switcher_markup', $html, $args);
	}
endif;


if(!function_exists('reycore__language_switcher_markup_mobile')):
	/**
	 * Language switcher markup for Mobile panel
	 *
	 * @since 1.0.0
	 **/
	function reycore__language_switcher_markup_mobile($args = []){

		if( empty($args['languages']) ) {
			return;
		}

		$template = '';

		$html = '<ul class="rey-mobileNav--footerItem rey-dropSwitcher-mobile rey-langSwitcher-mobile rey-langSwitcher-mobile--'. $args['type'] .'">';
		$html .= '<li class="rey-dropSwitcher-mobileTitle">'. esc_html_x('LANGUAGE:', 'Language switcher title in Mobile panel.', 'rey-core') .'</li>';
		foreach ($args['languages'] as $key => $language) {

			if( strpos($language['flag'], '<img') !== false ){
				$item_flag = $language['flag'];
			}
			else {
				$item_flag = sprintf( '<img src="%1$s" alt="%2$s">', $language['flag'], $language['name'] );
			}

			$item_template = sprintf( '<li class="%2$s"><a href="%3$s">%1$s<span>%4$s</span></a></li>',
				$item_flag,
				$language['active'] ? '--active' : '',
				$language['url'],
				$language['code']
			);
			$html .= apply_filters('reycore/language_switcher/markup_mobile/item', $item_template, $language);
		}
		$html .= '</ul>';

		return apply_filters('reycore/language_switcher_markup_mobile', $html, $args);
	}
endif;


if(!function_exists('reycore_wc__get_header_search_args')):
	/**
	 * Get account panel options
	 * @since 1.0.0
	 **/
	function reycore_wc__get_header_search_args( $option = '' ){

		$options = wp_parse_args( get_query_var('rey__header_search'), [
			'search_complementary' => get_theme_mod('search_complementary', 'menu'),
			'search_menu_source'   => get_theme_mod('search_menu', ''),
			'keywords' => get_theme_mod('search_suggestions', '' ),
			'search_style' => get_theme_mod('header_search_style', 'wide'),
			'search_text_order' => 'left',
		]);

		if( !empty($option) && isset($options[$option]) ){
			return $options[$option];
		}

		return $options;
	}
endif;

if(!function_exists('reycore__header_cart_params')):
	/**
	 * Default settings
	 *
	 * @since 1.6.10
	 **/
	function reycore__header_cart_params()
	{
		return apply_filters('reycore/header/cart_params', [
			'hide_empty' => get_theme_mod('header_cart_hide_empty', 'no'),
		]);
	}
endif;


if(!function_exists('reycore__remove_button_search')):
	/**
	 * Remove default search button
	 *
	 * @since 1.0.0
	 */
	function reycore__remove_button_search() {
		if( get_theme_mod('header_enable_search', true) && in_array(reycore_wc__get_header_search_args('search_style'), ['wide', 'side']) ){
			remove_action('rey/header/row', 'rey__header__search', 30);
		}
	}
endif;
add_action('wp', 'reycore__remove_button_search');


if(!function_exists('reycore__header__search')):
	/**
	 * Add search button markup
	 *
	 * @since 1.0.0
	 **/
	function reycore__header__search(){
		// return if search is disabled
		if( get_theme_mod('header_enable_search', true) && in_array(reycore_wc__get_header_search_args('search_style'), ['wide', 'side']) ) {
			reycore__get_template_part('template-parts/header/search-toggle');
		}
	}
endif;
add_action('rey/header/row', 'reycore__header__search', 30);


if(!function_exists('reycore__header__add_search_panel')):
	/**
	 * Add search panel markup
	 *
	 * @since 1.0.0
	 **/
	function reycore__header__add_search_panel(){
		// return if search is disabled
		if( get_theme_mod('header_enable_search', true) && in_array(reycore_wc__get_header_search_args('search_style'), ['wide', 'side']) ) {
			reycore__get_template_part('template-parts/header/search-panel');
		}
	}
endif;
add_action('rey/after_site_wrapper', 'reycore__header__add_search_panel');


if(!function_exists('reycore__header__search_complementary_menu')):
	/**
	 * Load Search panel complementary navigation
	 *
	 * @since 1.0.0
	 **/
	function reycore__header__search_complementary_menu($args)
	{
		if( isset($args['search_complementary']) && $args['search_complementary'] === 'menu' ){
			reycore__get_template_part('template-parts/header/search-complementary-menu');
		}
	}
endif;
add_action('reycore/search_panel/after_search_form', 'reycore__header__search_complementary_menu', 20);


if(!function_exists('reycore__header__search_complementary_keywords')):
	/**
	 * Load Search panel complementary keywords suggestion
	 *
	 * @since 1.0.0
	 **/
	function reycore__header__search_complementary_keywords($args)
	{
		if( isset($args['search_complementary']) && $args['search_complementary'] === 'keywords' ){
			reycore__get_template_part('template-parts/header/search-complementary-keywords');
		}
	}
endif;
add_action('reycore/search_panel/after_search_form', 'reycore__header__search_complementary_keywords', 20);


if(!function_exists('reycore__add_sticky_global_sections')):
	/**
	 * Append Top Sticky Content Hook
	 *
	 * @since 1.0.0
	 **/
	function reycore__add_sticky_global_sections()
	{
		if( wp_doing_ajax() || ! get_the_ID() ){
			return;
		}
		if( ! class_exists('ReyCore_GlobalSections') || ! class_exists('\Elementor\Plugin') ){
			return;
		}
		if( \Elementor\Plugin::instance()->editor->is_edit_mode() || \Elementor\Plugin::instance()->preview->is_preview_mode() ){
			return;
		}
		if( ReyCore_GlobalSections::POST_TYPE === get_post_type() ){
			return;
		}

		$positions = [
			'top' => 'top_sticky_gs',
			'bottom' => 'bottom_sticky_gs',
		];

		foreach ($positions as $position => $option) {

			if( ($gs = reycore__get_option( $option, '' )) && $gs !== '' ) {

				// load their css
				add_filter('reycore/global_sections/css', function($css) use ($gs) {
					array_push($css, $gs);
					return $css;
				});

				// add into position
				add_action( "rey/after_site_wrapper", function() use ($gs, $option, $position) {

					set_query_var('rey__is_sticky', true);

					$attributes = 'data-offset="'. esc_attr( reycore__get_option( $option . '_offset' ) ) .'"';
					$attributes .= ' data-align="'. esc_attr( $position ) .'"';

					if( reycore__get_option( $option . '_close' ) ){
						$attributes .= sprintf(' data-close="%s"', esc_attr(apply_filters('reycore/sticky_global_section/expiration', 'week')));
					}

					$classes = '';

					$sticky_gs_hide_devices__default = get_theme_mod($position . '_sticky_gs_hide_on_mobile', true) === false ? [] : ['mobile'];
					$sticky_gs_hide_devices = get_theme_mod($position . '_sticky_gs_hide_devices', $sticky_gs_hide_devices__default);

					foreach ($sticky_gs_hide_devices as $key => $value) {
						$classes = ' --dnone-' . $value;
					}

					if( $position === 'bottom' && get_theme_mod('bottom_sticky_gs_always_visible', false) ){
						$classes = ' --always-visible';
					}

					echo '<div class="rey-stickyContent '. $classes .'" '. $attributes .'>';
						echo ReyCore_GlobalSections::do_section( $gs );
					echo '</div>';

					set_query_var('rey__is_sticky', false);
				}, 0);
			}
		}
	}
endif;
add_action('wp', 'reycore__add_sticky_global_sections');

if(!function_exists('reycore__search_wide_logo')):
	/**
	 * Add suport for custom logo in Wide Search panel (when opened)
	 *
	 * @since 1.1.0
	 **/
	function reycore__search_wide_logo($html){

		if( $search_wide_logo = get_theme_mod('search_wide_logo', '') ){
			$to_add = sprintf( 'data-search-logo="%s" ', wp_get_attachment_url( $search_wide_logo ) );
			$html = str_replace('class="custom-logo', $to_add .'class="custom-logo',  $html);
		}

		$html = str_replace('class="',  'data-no-lazy="1" data-skip-lazy="1" class="', $html);

		return $html;
	}
endif;
add_filter('rey/header/logo_img_html', 'reycore__search_wide_logo');


if(!function_exists('reycore__header_fixed_nonoverlapping_helper')):
	/**
	 * Add Fixed header non-overlapping helper
	 *
	 * @since 1.2.0
	 **/
	function reycore__header_fixed_nonoverlapping_helper()
	{
		if(
			reycore__get_option('header_layout_type', 'default') !== 'none' &&
			(reycore__get_option('header_position', 'rel') === 'fixed' || reycore__get_option('header_position', 'rel') === 'absolute')
		) {
			// Fix when Header position Customizer option is set on Relative, but page has Fixed/Absolute
			// This will set Overlap Customizer option as true, always but only if it's overwridden in the page settings.
			if( get_theme_mod('header_position', 'rel') === 'rel' ){
				add_filter('theme_mod_header_fixed_overlap', '__return_true');
			}

			printf('<div class="rey-siteHeader-helper %s"></div>', implode(' ', array_map('esc_attr', apply_filters('reycore/header_helper/overlap_classes', [
				'desktop' => filter_var( reycore__get_option('header_fixed_overlap', true) , FILTER_VALIDATE_BOOLEAN) === true ? '--dnone-desktop' : '',
				'tablet' => filter_var( reycore__get_option('header_fixed_overlap_tablet', true) , FILTER_VALIDATE_BOOLEAN) === true ? '--dnone-tablet' : '',
				'mobile' => filter_var( reycore__get_option('header_fixed_overlap_mobile', true) , FILTER_VALIDATE_BOOLEAN) === true ? '--dnone-mobile' : '',
			] ) ) ) );
		}
	}
endif;
add_action('rey/after_header', 'reycore__header_fixed_nonoverlapping_helper', 0); // 0 priority

if(!function_exists('reycore__header_navigation_classes')):
/**
 * Filter menu navigation classes
 *
 * @since 1.5.0
 **/
function reycore__header_navigation_classes($classes, $args, $device) {

	if( $device === 'desktop' ){
		$classes['shadow'] = '--shadow-' . get_theme_mod('header_nav_submenus_shadow', '1');
	}

	return $classes;
}
endif;
add_filter('rey/header/nav_classes', 'reycore__header_navigation_classes', 10, 3);

if(!function_exists('reycore__tags_logo_block')):
	/**
	 * Shows logo only
	 *
	 * @since 1.5.0
	 */
	function reycore__tags_logo_block(){
		echo '<div class="rey-logoBlock-header">';
		get_template_part('template-parts/header/logo');
		echo '</div>';
	}
endif;

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists('ReyCore_Widget_Menu__Account_Menu') ):

	class ReyCore_Widget_Menu__Account_Menu extends \Elementor\Skin_Base
	{

		public function get_id() {
			return 'account-menu';
		}

		public function get_title() {
			return __( 'Account Menu (WooCommerce)', 'rey-core' );
		}

		protected function _register_controls_actions() {
			parent::_register_controls_actions();

			add_action( 'elementor/element/reycore-menu/section_settings/before_section_end', [ $this, 'register_items_controls' ] );
		}

		public function register_items_controls( $element ){

			$element->add_control(
				'show_logged_out_menu',
				[
					'label' => esc_html__( 'Show Login/Register?', 'rey-core' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
					'condition' => [
						'_skin' => 'account-menu',
					],
				]
			);

			$element->add_control(
				'singin_text',
				[
					'label' => esc_html__( 'Login Text', 'rey-core' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'LOGIN', 'rey-core' ),
					'placeholder' => esc_html__( 'eg: Login', 'rey-core' ),
					'condition' => [
						'_skin' => 'account-menu',
						'show_logged_out_menu' => 'yes',
					],
				]
			);

			$element->add_control(
				'singup_text',
				[
					'label' => esc_html__( 'Register Text', 'rey-core' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'REGISTER', 'rey-core' ),
					'placeholder' => esc_html__( 'eg: Register', 'rey-core' ),
					'condition' => [
						'_skin' => 'account-menu',
						'show_logged_out_menu' => 'yes',
					],
				]
			);

		}

		function custom_menu_items(){

			if( ! (($menu_items = get_theme_mod('header_account_menu_items', [])) && is_array($menu_items) ) ){
				return [];
			}

			$class = '';

			if( get_theme_mod('header_account_menu_items__glue', true) ){

				$logout['text'] = esc_html__( 'Logout', 'rey-core' );
				$logout['url'] = esc_url( wc_get_account_endpoint_url( 'customer-logout' ) );
				$logout['target'] = '';
				$menu_items[] = $logout;
			}

			return $menu_items;
		}

		public function render_menu($settings)
		{
			$items = wc_get_account_menu_items();

			if( !empty($items) ){

				echo '<div class="reyEl-menu-navWrapper">';

					printf('<ul class="reyEl-menu-nav rey-navEl --menuHover-%s">', $settings['hover_style']);

					$current_user = wp_get_current_user();

					foreach ($items as $endpoint => $label) {

						$url = esc_url( wc_get_account_endpoint_url( $endpoint ) );
						$is_active = reycore__current_url() === $url;

						if( $endpoint == 'orders' ){
							$label = sprintf('%s <span class="acc-count">%d</span>', $label, reycore_wc__count_orders($current_user->ID));
						}

						if( reycore_wc__check_downloads_endpoint() && $endpoint == 'downloads' ){
							$label = sprintf('%s <span class="acc-count">%d</span>', $label, reycore_wc__count_downloads($current_user->ID));
						}

						printf(
							'<li class="menu-item %3$s"><a href="%2$s"><span>%1$s</span></a></li>',
							wp_kses_post($label),
							$url,
							($is_active ? 'current-menu-item' : '')
						);
					}

					$custom_menu_items = $this->custom_menu_items();


					foreach ( $custom_menu_items as $menu_item ) {
						printf(
							'<li class="menu-item %4$s"><a href="%2$s" target="%3$s"><span>%1$s</span></a></li>',
							$menu_item['text'],
							esc_url( $menu_item['url'] ),
							esc_attr($menu_item['target']),
							(reycore__current_url() === $menu_item['url'] ? 'current-menu-item' : '')
						);
					}


					echo '</ul>';
				echo '</div>';
			}
		}

		public function render() {

			$settings = $this->parent->get_settings_for_display();

			$is_logged = is_user_logged_in();

			$this->parent->render_start($settings, [
				'',
				$is_logged ? '--accMenu--in' : '--accMenu--out'
			]);

			if( $is_logged ){
				$this->parent->render_title($settings);
				$this->render_menu($settings);
			}
			else {
				if( $settings['show_logged_out_menu'] === 'yes' ){

					$account_url = esc_url( get_permalink( wc_get_page_id('myaccount') ) );
					echo '<div class="reyEl-menu-acc">';

					if( $login = $settings['singin_text'] ){
						printf( '<a class="btn btn-secondary reyEl-menu--accLogin" href="%2$s"><span>%1$s</span></a>', $login, $account_url );
					}
					if( $register = $settings['singup_text'] ){
						printf( '<a class="btn btn-primary reyEl-menu--accReg" href="%2$s"><span>%1$s</span></a>', $register, $account_url );
					}

					echo '</div>';
				}
			}

			$this->parent->render_end();
		}
	}
endif;

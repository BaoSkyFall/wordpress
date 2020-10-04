<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists('ReyCore_Widget_Menu__Custom_Items') ):

	class ReyCore_Widget_Menu__Custom_Items extends \Elementor\Skin_Base
	{

		public function get_id() {
			return 'custom-items';
		}

		public function get_title() {
			return __( 'Custom Items', 'rey-core' );
		}

		protected function _register_controls_actions() {
			parent::_register_controls_actions();

			add_action( 'elementor/element/reycore-menu/section_settings/before_section_end', [ $this, 'register_items_controls' ] );
		}

		public function register_items_controls( $element ){

			$items = new \Elementor\Repeater();

			$items->add_control(
				'title',
				[
					'label'       => __( 'Title', 'rey-core' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
				]
			);

			$items->add_control(
				'link',
				[
					'label' => __( 'Link', 'rey-core' ),
					'type' => \Elementor\Controls_Manager::URL,
					'dynamic' => [
						'active' => true,
					],
					'placeholder' => __( 'https://your-link.com', 'rey-core' ),
					'default' => [
						'url' => '#',
					],
				]
			);

			$element->add_control(
				'custom_items',
				[
					'label' => __( 'Items', 'rey-core' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $items->get_controls(),
					'default' => [
						[
							'title' => __( 'Link #1', 'rey-core' ),
							'button_url' => [
								'url' => '#',
							],
						],
						[
							'title' => __( 'Link #2', 'rey-core' ),
							'button_url' => [
								'url' => '#',
							],
						],
					],
					'condition' => [
						'_skin' => 'custom-items',
					],
				]
			);

		}


		public function render_menu($settings)
		{
			if( !empty($settings['custom_items']) ){

				echo '<div class="reyEl-menu-navWrapper">';

					printf('<ul class="reyEl-menu-nav rey-navEl --menuHover-%s">', $settings['hover_style']);

					foreach ($settings['custom_items'] as $i => $item) {

						if( ! isset($item['link']['url']) ){
							continue;
						}

						$is_active = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" === $item['link']['url'];

						printf(
							'<li class="menu-item %4$s"><a href="%2$s" target="%3$s" %5$s><span>%1$s</span></a></li>',
							$item['title'],
							$item['link']['url'],
							($item['link']['is_external'] ? "_blank" : "_self"),
							($is_active ? 'current-menu-item' : ''),
							($item['link']['nofollow'] ? ' rel="nofollow"' : '')
						);
					}

					echo '</ul>';
				echo '</div>';
			}
		}

		public function render() {

			$settings = $this->parent->get_settings_for_display();

			$this->parent->render_start($settings);
			$this->parent->render_title($settings);
			$this->render_menu($settings);
			$this->parent->render_end();
		}
	}
endif;

<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if( !class_exists('ReyCore_WooCommerce_Tabs') ):

	class ReyCore_WooCommerce_Tabs
	{

		public function __construct() {
			add_action( 'init', [$this, 'initialize']);
		}

		function initialize(){

			add_filter( 'woocommerce_product_tabs', [$this,'add_information_panel'], 10);
			add_filter( 'woocommerce_product_additional_information_heading', [$this,'rename_additional_info_panel'], 10);
			add_filter( 'woocommerce_product_description_heading', [$this,'rename_description_title'], 10);
			add_filter( 'woocommerce_product_tabs', [$this, 'manage_tabs'], 20);
			add_filter( 'wc_product_enable_dimensions_display', [$this, 'disable_specifications_dimensions'], 10);
			add_action( 'wp_footer', [$this, 'reviews__start_open'], 999);
			add_filter( 'rey/woocommerce/product_panels_classes', [$this, 'add_blocks_class']);
			add_filter( 'the_content', [$this, 'add_description_toggle']);
			add_filter( 'wp', [$this, 'move_specifications_block']);
			add_filter( 'acf/load_value/key=field_5ecae99f56e6d', [$this, 'load_custom_tabs'], 10, 3);
			add_action( 'woocommerce_after_single_product_summary', [$this, 'move_reviews_tab_outside'], 10 );


			$this->remove_tabs_titles();

		}

		/**
		 * Add Information Panel
		 *
		 * @since 1.0.0
		 **/
		function information_panel_content()
		{
			echo reycore__parse_text_editor( reycore__get_option( 'product_info_content' ) );
		}

		/**
		 * Add Information Panel
		 *
		 * @since 1.0.0
		 **/
		function add_information_panel($tabs)
		{
			if( ($ip = reycore__get_option('product_info', '2')) && ($ip === '1' || $ip === 'custom') ) {

				$title = __( 'Information', 'rey-core' );

				if( $custom_title = get_theme_mod('single__product_info_title', '') ){
					$title = $custom_title;
				}

				$tabs['information'] = array(
					'title'    => $title,
					'priority' => 15,
					'callback' => [$this, 'information_panel_content'],
				);
			}

			return $tabs;
		}

		/**
		 * Rename Description title
		 *
		 * @since 1.6.10
		 **/
		function rename_description_title($heading)
		{
			if( $title = get_theme_mod('product_content_blocks_title', '') ){

				if( $title == '0' ){
					return false;
				}

				return $title;
			}

			return $heading;
		}


		/**
		 * Rename Additional Information Panel
		 *
		 * @since 1.0.0
		 **/
		function rename_additional_info_panel($heading)
		{
			if( $title = get_theme_mod('single_specifications_title', '') ){

				if( $title == '0' ){
					return false;
				}

				return $title;
			}

			return esc_html__( 'Specifications', 'rey-core' );
		}

		function manage_tabs( $tabs ){

			// change priorities
			foreach ([

				'description' => get_theme_mod('single_description_priority', 10),
				'information' => get_theme_mod('single_custom_info_priority', 15),
				'additional_information' => get_theme_mod('single_specs_priority', 20),
				'reviews' => get_theme_mod('single_reviews_priority', 30),

			] as $key => $value) {
				if( isset($tabs[$key]) && isset($tabs[$key]['priority']) ){
					$tabs[$key]['priority'] = absint($value);
				}
			}

			// Description title
			if( $desc_title = get_theme_mod('product_content_blocks_title', '') ){
				$tabs['description']['title'] = $desc_title;
			}

			// Specs title
			if( $specs_title = get_theme_mod('single_specifications_title', '') ){
				$tabs['additional_information']['title'] = $specs_title;
			}

			// disable specs tab
			if( ! get_theme_mod('single_specifications_block', true) ){
				unset( $tabs['additional_information'] );
			}

			// disable reviews tab, to print outside
			if( get_theme_mod('single_tabs__reviews_outside', false) && get_theme_mod('product_content_layout', 'blocks') === 'tabs' ){
				unset( $tabs['reviews'] );
			}

			/**
			 * Custom Tabs
			 */
			$custom_tabs = get_theme_mod('single__custom_tabs', '');

			if( is_array($custom_tabs) && !empty($custom_tabs) && class_exists('ACF') && $custom_tabs_content = get_field('product_custom_tabs') ){

				foreach ($custom_tabs_content as $key => $c_tab) {
					$tab_content = isset($c_tab['tab_content']) ? reycore__parse_text_editor( $c_tab['tab_content'] ) : '';
					if( empty($tab_content) ){
						continue;
					}
					$slug = sanitize_title($c_tab['tab_title']);
					$tabs[$slug] = [
						'title' => $c_tab['tab_title'],
						'priority' => absint($custom_tabs[$key]['priority']),
						'callback' => function() use ($tab_content) {
							echo $tab_content;
						},
						'type' => 'custom'
					];
				}
			}

			return $tabs;
		}


		function move_reviews_tab_outside() {

			$maybe[] = get_theme_mod('single_tabs__reviews_outside', false) && get_theme_mod('product_content_layout', 'blocks') === 'tabs' && wc_reviews_enabled();

			if( $product = wc_get_product() ){
				$maybe[] = $product->get_reviews_allowed();
			}

			if( in_array(false, $maybe, true) ){
				return;
			}

			reycore__get_template_part('template-parts/woocommerce/single-block-reviews');
		}



		/**
		 * Move Specifications / Additional Information block/tab into Summary
		 *
		 * @since 1.6.7
		 */
		function move_specifications_block(){

			if( ! get_theme_mod('single_specifications_block', true) ){
				return;
			}

			if( ! ($pos = get_theme_mod('single_specifications_position', '')) ){
				return;
			}

			// move specifications / additional in summary
			add_action( 'woocommerce_single_product_summary', 'woocommerce_product_additional_information_tab', $pos );

			add_filter( 'woocommerce_product_tabs', function( $tabs ) {
				unset( $tabs['additional_information'] );
				return $tabs;
			}, 99 );
		}



		function disable_specifications_dimensions(){
			return get_theme_mod('single_specifications_block_dimensions', true);
		}

		function remove_tabs_titles(){

			if( get_theme_mod('product_content_layout', 'blocks') !== 'tabs' ){
				return;
			}

			if( ! get_theme_mod('product_content_tabs_disable_titles', true) ){
				return;
			}

			add_filter('woocommerce_product_description_heading', '__return_false');
			add_filter('woocommerce_product_additional_information_heading', '__return_false');
			add_filter('woocommerce_post_class', function($classes){
				$classes['remove-titles'] = '--tabs-noTitles';
				return $classes;
			});
		}


		function reviews__start_open()
		{
			if( get_theme_mod('single_reviews_start_opened', false) ){ ?>
			<script>
				jQuery(document).on('ready', function(){
					jQuery('.single-product .rey-reviewsBtn.js-toggle-target').trigger('click');
				});
			</script>
			<?php }
		}


		/**
		 * Customize product page's blocks
		 *
		 * @since 1.0.12
		 **/
		function add_blocks_class( $classes )
		{
			if( get_theme_mod('product_content_layout', 'blocks') === 'blocks' ){
				$classes[] = get_theme_mod('product_content_blocks_desc_stretch', false) ? '--stretch-desc' : '';
			}

			return $classes;
		}

		function add_description_toggle($content){

			if( is_product() && get_theme_mod('product_content_blocks_desc_toggle', false) ){
				return sprintf(
					'<div class="rey-prodDescToggle u-toggle-text-next-btn %s">%s</div><button class="btn btn-line-active"><span data-read-more="%s" data-read-less="%s"></span></button>',
					apply_filters('reycore/productdesc/mobile_only', false) ? '--mobile' : '',
					$content,
					esc_html_x('Read more', 'Toggling the product excerpt in Compact layout.', 'rey-core'),
					esc_html_x('Less', 'Toggling the product excerpt in Compact layout.', 'rey-core')
				);
			}

			return $content;
		}

		function load_custom_tabs($value, $post_id, $field) {

			if ($value !== false) {
				return $value;
			}

			$tabs = get_theme_mod('single__custom_tabs', '');

			if( is_array($tabs) && !empty($tabs) ){
				$value = [];
				foreach ($tabs as $key => $tab) {
					$value[]['field_5ecae9c356e6e'] = $tab['text'];
				}
			}

			return $value;
		}
	}

	new ReyCore_WooCommerce_Tabs;

endif;

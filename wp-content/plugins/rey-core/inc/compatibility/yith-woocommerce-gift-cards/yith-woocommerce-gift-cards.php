<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( class_exists('WooCommerce') && class_exists('YITH_WooCommerce_Gift_Cards') && !class_exists('ReyCore_Compatibility__YithGiftCards') ):

	class ReyCore_Compatibility__YithGiftCards
	{
		private $settings = [];

		public function __construct()
		{
			add_action( 'init', [ $this, 'init' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'load_styles' ] );
			add_filter( 'theme_mod_single_skin', [$this, 'disable_fullscreen_pdp_skin']);
			add_filter( 'theme_mod_product_page_summary_fixed', '__return_false');
			add_filter( 'yith_woocommerce_gift_cards_amount_range', [$this, 'fix_range_dash']);
			add_action( 'yith_gift_cards_template_after_gift_card_form', [ReyCore_WooCommerce_Single::getInstance(), 'wrap_cart_qty'], 19);
			add_action( 'yith_gift_cards_template_after_gift_card_form', 'reycore_wc__generic_wrapper_end', 21);
			add_filter( 'single_atc_qty_controls_styles', [$this, 'disable_qty_style']);
		}

		public function init(){
			$this->settings = apply_filters('reycore/yith_gift_cards/params', [
			]);
		}

		public function load_styles(){
            wp_enqueue_style( 'reycore-yithgiftcards-styles', REY_CORE_COMPATIBILITY_URI . basename(__DIR__) . '/style.css', [], REY_CORE_VERSION );
		}

		function disable_fullscreen_pdp_skin( $skin ){

			if( $skin === 'fullscreen' ){
				return 'default';
			}

			return $skin;
		}

		function disable_qty_style(){
			return 'default';
		}

		function fix_range_dash($price){
			return str_replace('&ndash;', '', $price);
		}

	}

	new ReyCore_Compatibility__YithGiftCards();
endif;

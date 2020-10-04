<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( defined( 'VI_WOOCOMMERCE_PHOTO_REVIEWS_VERSION' ) && !class_exists('ReyCore_Compatibility__WooPhotoReviews') ):

	class ReyCore_Compatibility__WooPhotoReviews
	{
		private $settings = [];

		public function __construct()
		{
			add_action( 'init', [ $this, 'init' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'load_styles' ] );
			reycore__remove_filters_for_anonymous_class( 'admin_init', 'VI_WOOCOMMERCE_PHOTO_REVIEWS_Admin_Admin', 'check_update', 10 );
			add_filter( 'woocommerce_reviews_title', [$this, 'title_improvement'], 20, 3);
		}

		function title_improvement($reviews_title, $count, $product){

			// reset
			$reviews_title = '';
			// rating
			$rating_average = $product->get_average_rating();
			$reviews_title .= sprintf('<div class="rey-reviewTop">%s <span><strong>%s</strong>/5</span></div>', wc_get_rating_html( $rating_average, $count ), $rating_average);
			// title
			$reviews_title .= sprintf( '<div class="rey-reviewTitle">' . esc_html( _n( '%s Customer review', '%s Customer reviews', $count, 'rey-core' ) ) . '</div>' , esc_html( $count ) );

			return $reviews_title;
		}

		public function init(){
			$this->settings = apply_filters('reycore/woo_photo_reviews/params', []);
		}

		public function load_styles(){
            wp_enqueue_style( 'reycore-woo-photo-reviews-styles', REY_CORE_COMPATIBILITY_URI . basename(__DIR__) . '/style.css', [], REY_CORE_VERSION );
		}


	}

	new ReyCore_Compatibility__WooPhotoReviews();
endif;

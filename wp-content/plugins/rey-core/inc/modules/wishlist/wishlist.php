<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( class_exists('WooCommerce') && !class_exists('ReyCore_WooCommerce_WishlistRey') ):

	class ReyCore_WooCommerce_WishlistRey {

		const COOKIE_KEY = 'rey_wishlist_ids';

		public function __construct() {
			add_action('init', [$this, 'init']);
		}

		function init(){

			if( class_exists('TInvWL_Public_AddToWishlist') ){
				return;
			}

			if( ! reycore_wc__check_wishlist() ){
				return;
			}

			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			add_filter( 'reycore/woocommerce/wishlist/button_html', [$this, 'catalog_button_html']);
			add_filter( 'reycore/woocommerce/wishlist/url', [$this, 'wishlist_url']);
			add_filter( 'the_content', [$this, 'append_wishlist_page_marker']);
			add_filter( 'body_class', [$this, 'append_wishlist_page_class']);
			add_action( 'reycore/loop_inside_thumbnail/top-left', [$this, 'add_remove_buttons']);

			add_filter( 'rey/main_script_params', [$this, 'script_params']);

			add_filter( 'reycore/woocommerce/wishlist/ids', [$this, 'get_wishlist_ids']);
			add_filter( 'reycore/woocommerce/wishlist/counter_html', [$this, 'wishlist_counter_html']);
			add_filter( 'reycore/woocommerce/wishlist/title', [$this, 'wishlist_title']);

			add_filter( 'woocommerce_account_menu_items', [$this, 'add_wishlist_page_to_account_menu']);
			add_filter( 'woocommerce_get_endpoint_url', [$this, 'add_wishlist_url_endpoint'], 20, 4);

			add_action( 'woocommerce_before_single_product', [$this, 'pdp_button']);

			add_action( 'wp_ajax_rey_wishlist_add_to_user_meta', [ $this, 'add_to_user_meta'] );
			add_action( 'wp_ajax_nopriv_rey_wishlist_add_to_user_meta', [ $this, 'add_to_user_meta'] );
			add_action( 'wp_login', [$this, 'update_ids_after_login'], 10, 2);

			add_action( 'wp_footer', [$this, 'after_add_markup']);


			// TODO:
			// Elementor element for header, with counter & drop panel & list view
			// List style view in Accoutnt drop;
			// Modal choice instead of tooltip with table list
			// Shareble url
			// PDP outlibe button with tooltip
		}

		public function enqueue_scripts(){
            wp_enqueue_style( 'reycore-wishlist', REY_CORE_MODULE_URI . basename(__DIR__) . '/style.css', [], REY_CORE_VERSION );
            wp_enqueue_script( 'reycore-wishlist', REY_CORE_MODULE_URI . basename(__DIR__) . '/script.js', ['reycore-scripts'], REY_CORE_VERSION , true);
		}

		public static function get_cookie_key(){
			return self::COOKIE_KEY . '_' . (is_multisite() ? get_current_blog_id() : 0);
		}

		function script_params($params){

			$params['wishlist_url'] = self::wishlist_url();
			$params['wishlist_after_add'] = get_theme_mod('wishlist__after_add', 'notice');
			$params['wishlist_text_add'] = self::get_texts('wishlist__texts_add');
			$params['wishlist_text_rm'] = self::get_texts('wishlist__texts_rm');

			return $params;
		}

		public static function get_texts( $text = '' ){

			$defaults = [
				'wishlist__text' => __('Wishlist', 'rey-core'),
				'wishlist__texts_add' => esc_html__('Add to wishlist', 'rey-core'),
				'wishlist__texts_rm' => esc_html__('Remove from wishlist', 'rey-core'),
				'wishlist__texts_added' => esc_html__('Added to wishlist!', 'rey-core'),
				'wishlist__texts_btn' => esc_html__('VIEW WISHLIST', 'rey-core'),
				'wishlist__texts_page_title' => __('Wishlist is empty.', 'rey-core'),
				'wishlist__texts_page_text' => __('You don\'t have any products added in your wishlist. Search and save items to your liking!', 'rey-core'),
				'wishlist__texts_page_btn_text' => __('SHOP NOW', 'rey-core'),
			];

			if( !empty($text) ){

				$opt = get_theme_mod($text, $defaults[$text]);

				if( empty($opt) ){
					$opt = $defaults[$text];
				}

				return $opt;
			}

			return '';

		}

		function get_cookie_products_ids(){
			$products = [];

			if ( ! empty( $_COOKIE[self::get_cookie_key()] ) ) { // @codingStandardsIgnoreLine.
				$products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE[self::get_cookie_key()] ) ) ); // @codingStandardsIgnoreLine.
			}

			return $products;
		}

		function get_ids(){

			$products = [];

			if( is_user_logged_in() ){

				$user = wp_get_current_user();
				$products = get_user_meta($user->ID, self::get_cookie_key(), true);

			}
			else {
				$products = $this->get_cookie_products_ids();
			}

			return $products;
		}

		function catalog_button_html( $btn_html ){

			$product = wc_get_product();

			if ( ! ($product && $id = $product->get_id()) ) {
				return $btn_html;
			}

			$button_class = [];
			$active_products = $this->get_ids();

			$button_text = self::get_texts('wishlist__texts_add');

			if( !empty($active_products) && in_array($id, $active_products, true) ){
				$button_class[] = '--in-wishlist';
				$button_text = self::get_texts('wishlist__texts_rm');
			}

			if( is_user_logged_in() ){
				$button_class[] = '--supports-ajax';
			}

			$button_content = $this->get_wishlist_icon();

			$btn_html = sprintf(
				'<a href="%5$s" class="%1$s rey-wishlistBtn" data-id="%2$s" title="%3$s" aria-label="%3$s">%4$s</a>',
				esc_attr(implode(' ', $button_class)),
				esc_attr($id),
				$button_text,
				$button_content,
				esc_url( get_permalink($id) )
			);

			return $btn_html;
		}

		function pdp_button(){

			if( !get_theme_mod('wishlist_pdp__enable', true) ){
				return;
			}

			$position = get_theme_mod('wishlist_pdp__position', 'inline');

			$hooks = [
				'before' => [
					'hook' => 'woocommerce_before_add_to_cart_form',
					'priority' => 10
				],
				'inline' => [
					'hook' => 'woocommerce_after_add_to_cart_button',
					'priority' => 0
				],
				'after' => [
					'hook' => 'woocommerce_after_add_to_cart_form',
					'priority' => 0
				],
			];

			add_action( $hooks[$position]['hook'], function(){

				$product = wc_get_product();

				if ( ! ($product && $id = $product->get_id()) ) {
					return $btn_html;
				}

				$button_class = [];
				$active_products = $this->get_ids();

				$button_text = self::get_texts('wishlist__texts_add');

				if( !empty($active_products) && in_array($id, $active_products, true) ){
					$button_class[] = '--in-wishlist';
					$button_text = self::get_texts('wishlist__texts_rm');
				}

				if( get_theme_mod('wishlist_loop__mobile', false) ){
					$button_class[] = '--show-mobile';
				}

				if( is_user_logged_in() ){
					$button_class[] = '--supports-ajax';
				}

				$button_content = $this->get_wishlist_icon();

				if( get_theme_mod('wishlist_pdp__wtext', true) && $button_text ){
					$button_content .= sprintf('<span class="rey-wishlistBtn-text">%s</span>', $button_text);
					if( ($btn_style = get_theme_mod('wishlist_pdp__btn_style', 'btn-line')) && $btn_style !== 'none' ){
						$button_class[] = 'btn ' . $btn_style;
					}
				}

				$btn_html = sprintf(
					'<div class="rey-wishlistBtn-wrapper"><a href="%5$s" class="%1$s rey-wishlistBtn" data-id="%2$s" title="%3$s" aria-label="%3$s">%4$s</a></div>',
					esc_attr(implode(' ', $button_class)),
					esc_attr($id),
					$button_text,
					$button_content,
					esc_url( get_permalink($id) )
				);

				echo $btn_html;

			}, $hooks[$position]['priority'] );

		}

		function get_wishlist_icon(){
			return reycore__get_svg_icon__core([
				'id' => 'reycore-icon-' . get_theme_mod('wishlist__icon_type', 'heart'),
				'class' => 'rey-wishlistBtn-icon'
			]);
		}

		/**
		 * Wishlist page
		 */

		public static function wishlist_page_id(){
			if( $wishlist_page_id = get_theme_mod('wishlist__default_url', '') ){
				return absint($wishlist_page_id);
			}
		}

		public static function wishlist_url( $url = '' ){

			if( $wishlist_page_id = self::wishlist_page_id() ){
				return esc_url( get_permalink($wishlist_page_id) );
			}

			return $url;
		}

		function append_wishlist_page_class($classes){

			$classes[] = 'rey-wishlist';

			if( ($wishlist_page_id = self::wishlist_page_id()) && is_page($wishlist_page_id) ){
				$classes[] = 'woocommerce';
				$classes[] = 'rey-wishlist-page';
			}

			return $classes;
		}

		function append_wishlist_page_marker( $content ){

			$wishlist_content = '';

			if( function_exists('reycore__elementor_edit_mode') && reycore__elementor_edit_mode() ){
				return $content;
			}

			if( ($wishlist_page_id = self::wishlist_page_id()) && is_page($wishlist_page_id) ){
				return $this->get_wishlist_content();
			}

			return $content;
		}

		function get_wishlist_content(){

			$product_ids = $this->get_ids();

			add_filter('comments_open', '__return_false', 20, 2);
			add_filter('pings_open', '__return_false', 20, 2);
			add_filter('comments_array', '__return_empty_array', 10, 2);


			if( !empty($product_ids) ){

				$product_ids = array_reverse($product_ids);

				add_filter('reycore/loop_components', function($components){

					// loop components
					$components['view_selector'] = false;
					$components['filter_button'] = false;
					$components['filter_top_sidebar'] = false;
					$components['mobile_filter_button'] = false;
					$components['wishlist'] = [
						'bottom' => false,
						'topright' => false,
						'bottomright' => false,
					];

					return $components;
				});


				do_action( 'woocommerce_before_shop_loop' );

					woocommerce_product_loop_start();

						foreach ( $product_ids as $product_id ) :
							$post_object = get_post( $product_id );
							setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
							wc_get_template_part( 'content', 'product' );
						endforeach;

					woocommerce_product_loop_end();

				do_action( 'woocommerce_after_shop_loop' );
			}

			$this->empty_wishlist_content();

		}

		function empty_wishlist_content()
		{ ?>
			<div class="rey-wishlist-emptyPage">

				<div class="rey-wishlist-emptyPage-icon">
					<?php echo $this->get_wishlist_icon(); ?>
				</div>

				<div class="rey-wishlist-emptyPage-title">
					<h2><?php echo self::get_texts('wishlist__texts_page_title'); ?></h2>
				</div>

				<div class="rey-wishlist-emptyPage-content">
					<p><?php echo self::get_texts('wishlist__texts_page_text'); ?></p>
					<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ) ?>" class="btn btn-primary">
						<?php echo self::get_texts('wishlist__texts_page_btn_text') ?>
					</a>
				</div>
			</div><?php
		}

		function add_remove_buttons(){

			if( !($wishlist_page_id = self::wishlist_page_id()) ){
				return;
			}

			if( ! is_page($wishlist_page_id) ){
				return;
			}

			global $product;

			if( ! $product ){
				return;
			}

			printf('<a class="rey-wishlist-removeBtn" href="#" data-id="%1$d" data-tooltip-text="%3$s" aria-label="%3$s">%2$s</a>',
				$product->get_id(),
				reycore__get_svg_icon(['id' => 'rey-icon-close']),
				self::get_texts('wishlist__texts_rm')
			);
		}

		function after_add_markup(){

			if( ! reycore__can_add_public_content() ){
				return;
			}

			$type = get_theme_mod('wishlist__after_add', 'notice');

			if( $type === 'notice' ){

				$url = '';

				if( $wishlist_url = self::wishlist_url() ){
					$url = sprintf('<a href="%1$s" class="btn btn-line-active">%2$s</a>',
						$wishlist_url,
						self::get_texts('wishlist__texts_btn')
					);
				}

				printf( '<div class="rey-wishlist-notice-wrapper"><div class="rey-wishlist-notice"><span>%1$s</span> %2$s</div></div>',
					self::get_texts('wishlist__texts_added'),
					$url
				);
			}
		}

		function add_wishlist_page_to_account_menu($items){

			$c = false;

			if( isset($items['customer-logout']) ){
				$c = $items['customer-logout'];
				unset($items['customer-logout']);
			}

			if( self::wishlist_page_id() ){
				$items['rey_wishlist'] = $this->wishlist_title() . sprintf(' <span class="acc-count">%s</span>', $this->wishlist_counter_html() );
			}

			if( $c ){
				$items['customer-logout'] = $c;
			}

			return $items;
		}

		function add_wishlist_url_endpoint($url, $endpoint, $value, $permalink){

			if( $endpoint === 'rey_wishlist') {
				$url = self::wishlist_url();
			}

			return $url;
		}

		function wishlist_counter_html(){
			return '<span class="rey-wishlistCounter-number --empty"></span>';
		}

		function wishlist_title(){
			return self::get_texts('wishlist__text');
		}

		function get_wishlist_ids( $ids ){

			$product_ids = $this->get_ids();

			if( empty($product_ids) ){
				return $ids;
			}

			return array_reverse($product_ids);
		}

		public function add_to_user_meta(){

			if( ! is_user_logged_in() ){
				wp_send_json_error(esc_html__('Not logged in!', 'rey-core'));
			}

			$user = wp_get_current_user();
			$product_ids = $this->get_cookie_products_ids();

			if( update_user_meta($user->ID, self::get_cookie_key(), $product_ids) ){
				wp_send_json_success($product_ids);
			}

		}

		public function update_ids_after_login( $user_login, $user){

			$product_ids = $this->get_cookie_products_ids();
			$saved_product_ids = get_user_meta($user->ID, self::get_cookie_key(), true);

			if( ! is_array($saved_product_ids) ) {
				$saved_product_ids = [];
			}

			update_user_meta($user->ID, self::get_cookie_key(), array_unique(array_merge($product_ids, $saved_product_ids)) );
		}

	}

	new ReyCore_WooCommerce_WishlistRey();
endif;

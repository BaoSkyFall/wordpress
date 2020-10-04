<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if( !class_exists('ReyCore_WooCommerce_Loop_Skin_Basic') ):
/**
 * Basic Products loop skin
 */
class ReyCore_WooCommerce_Loop_Skin_Basic extends ReyCore_WooCommerce_Loop
{
	const TYPE = 'basic';

	public function __construct()
	{
		add_action( 'init', [$this, 'init'] );
	}

	public function init()
	{
		if ( is_customize_preview() ) {
			add_action( 'customize_preview_init', [$this, 'load_skin_hooks'] );
			return;
		}
		$this->load_skin_hooks();
	}

	public function load_skin_hooks()
	{
		if( $this->get_loop_active_skin() !== self::TYPE ){
			return;
		}

		add_action( 'woocommerce_before_shop_loop_item', [$this, 'apply_extra_thumbs_filter'], 10);
		add_action( 'woocommerce_after_shop_loop_item', [$this, 'remove_extra_thumbs_filter'], 10);

		add_action( 'woocommerce_after_shop_loop_item_title', [$this, 'wrap_product_footer'], 0);
		add_filter( 'woocommerce_loop_add_to_cart_link', [$this, 'wrap_add_to_cart_button'], 10);
		add_filter( 'post_class', [$this,'custom_css_classes'], 20 );
		add_filter( 'product_cat_class', [$this,'custom_css_classes'], 20 );
		add_filter( 'reycore/loop/component_hooks', [$this, 'get_component_hooks'] );

		do_action( 'reycore/woocommerce/loop/after_skin_init', $this, self::TYPE);
	}

	/**
	 * Override default components.
	 *
	 * @since 1.3.0
	 */
	public function get_component_hooks( $components ){

		$component_hooks  =  [
			'brands'         => [
				'type'          => 'action',
				'tag'           => 'woocommerce_before_shop_loop_item_title',
				'callback'      => [ $this, 'component_brands' ],
				'priority'      => 60,
			],
			'category'       => [
				'type'          => 'action',
				'tag'           => 'woocommerce_before_shop_loop_item_title',
				'callback'      => [ $this, 'component_product_category'],
				'priority'      => 70,
			],
			'prices'         => [
				'type'          => 'action',
				'tag'           => 'reycore/woocommerce/after_shop_loop_item',
				'callback'      => 'woocommerce_template_loop_price',
				'priority'      => 10,
			],
			'add_to_cart'    => [
				'type'          => 'action',
				'tag'           => 'reycore/woocommerce/after_shop_loop_item',
				'callback'      => 'woocommerce_template_loop_add_to_cart',
				'priority'      => 20,
			],
			'quickview'      => [
				'bottom' => [
					'type'          => 'action',
					'tag'           => 'reycore/woocommerce/after_shop_loop_item',
					'callback'      => [ $this, 'component_quickview_button' ],
					'priority'      => 30,
				],
			],
			'wishlist'       => [
				'bottom' => [
					'type'          => 'action',
					'tag'           => 'reycore/woocommerce/after_shop_loop_item',
					'callback'      => [ $this, 'component_wishlist'],
					'priority'      => 40,
				]
			],
		];

		$component_hooks = reycore__wp_parse_args( $component_hooks, $components );

		return $component_hooks;
	}


	/**
	 * Wrap product info - start
	 *
	 * @since 1.0.0
	 **/
	function product_details_wrapper_start()
	{ ?>
		<div class="rey-productLoop-footer">
		<?php
	}

	/**
	 * Wrap product info - end
	 *
	 * @since 1.0.0
	 **/
	function product_details_wrapper_end()
	{
		/**
		 * Adds wrapper after shop loop item (QuickView & Wishlist)
		 *
		 * @since 1.0.0
		 */
		do_action('reycore/woocommerce/after_shop_loop_item'); ?>

		</div>
		<!-- /.rey-productLoop-footer -->
		<?php
	}

	/**
	 * Wrap Product Item's footer.
	 *
	 * @since 1.0.0
	 */
	function wrap_product_footer()
	{
		add_action( 'woocommerce_after_shop_loop_item', [$this, 'product_details_wrapper_start'], 9);
		add_action( 'woocommerce_after_shop_loop_item', [$this, 'product_details_wrapper_end'], 900);
	}

	/**
	 * Wrap add to cart link into special markup
	 *
	 * @since 1.0.0
	 */
	function wrap_add_to_cart_button($html)
	{
		return sprintf( '<div class="rey-productFooter-item rey-productFooter-item--addtocart"><div class="rey-productFooter-inner">%s</div></div>' , $html);
	}

	/**
	 * Get quickview button HTML Markup
	 *
	 * @since 1.0.0
	 */
	public function component_quickview_button()
	{ ?>
		<div class="rey-productFooter-item rey-productFooter-item--quickview">
			<div class="rey-productFooter-inner">
				<?php echo ReyCore_WooCommerce_QuickView::get_button_html(); ?>
			</div>
		</div><?php
	}

	/**
	* Add the icon, wrapped in custom div
	*
	* @since 1.0.0
	*/
	public function component_wishlist()
	{
		if( ReyCore_WooCommerce_Wishlist::catalog_default_position() === 'bottom' ): ?>
			<div class="rey-productFooter-item rey-productFooter-item--wishlist">
				<div class="rey-productFooter-inner">
					<?php ReyCore_WooCommerce_Wishlist::get_button_html();; ?>
				</div>
			</div>
			<?php
		endif;
	}

	/**
	 * Adds custom CSS Classes
	 *
	 * @since 1.1.2
	 */
	function custom_css_classes( $classes )
	{
		if( is_admin() ){
			return $classes;
		}

		if ( $this->is_product() ) {

			if( get_theme_mod('loop_hover_animation', '1') == '1' ) {
				$classes['hover-animated'] = 'is-animated';
			}
		}

		if( $general_css_classes = $this->general_css_classes() ){
			$classes = array_merge($classes, $general_css_classes);
		}

		return $classes;
	}

}

new ReyCore_WooCommerce_Loop_Skin_Basic;

endif;

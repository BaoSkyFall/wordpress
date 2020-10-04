<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if( !class_exists('ReyCore_WooCommerce_ProductVideos') ):

	class ReyCore_WooCommerce_ProductVideos
	{
		public $video_url;
		public $product_id;

		public function __construct() {
			add_action('wp', [$this, 'init']);
		}

		function init(){

			if( ! is_product() ){
				return;
			}

			$product = wc_get_product();

			if( ! $product ){
				return;
			}

			$this->product_id = $product->get_id();
			$this->video_url = reycore__acf_get_field('product_video_url', $this->product_id );

			if( ! $this->video_url ){
				return;
			}

			$this->add_to_summary();

			add_action('woocommerce_before_single_product_summary', [$this, 'add_video_button_into_gallery']);

			if( ! reycore__acf_get_field('product_video_main_image', $this->product_id ) ){
				add_filter('reycore/woocommerce/product_mobile_gallery/html', [$this, 'add_extra_image__mobile'], 20, 3);
				add_action( 'woocommerce_product_thumbnails', [$this, 'add_extra_image__desktop'], 20);
			}
		}

		function add_extra_image__desktop(){

			if( ($image = reycore__acf_get_field('product_video_gallery_image', $this->product_id )) && isset($image['id']) ){

				echo '<div class="woocommerce-product-gallery__image">';

					$this->print_button([
						'text' => wp_get_attachment_image($image['id'], 'large'),
						'icon' => '<span class="rey-galleryPlayVideo-icon">'. reycore__get_svg_icon__core(['id' => 'reycore-icon-play']) .'</span>',
						'tag' => 'a',
						'attr' => [
							'href' => '#',
							'class' => 'rey-galleryPlayVideo',
						],
					]);

				echo '</div>';
			}
		}

		function add_extra_image__mobile($gallery_image, $gallery_img_id, $is_last){

			if( $is_last && ($image = reycore__acf_get_field('product_video_gallery_image', $this->product_id )) && isset($image['id']) ){

				$gallery_image .= '<div>';

				$gallery_image .= $this->print_button([
					'text' => wp_get_attachment_image($image['id'], 'large'),
					'icon' => '<span class="rey-galleryPlayVideo-icon">'. reycore__get_svg_icon__core(['id' => 'reycore-icon-play']) .'</span>',
					'tag' => 'a',
					'attr' => [
						'href' => '#',
						'class' => 'rey-galleryPlayVideo',
					],
					'echo' => false
				]);

				$gallery_image .= '</div>';

			}

			return $gallery_image;
		}

		function add_video_button_into_gallery(){

			if( reycore__acf_get_field('product_video_main_image', $this->product_id ) !== false ){
				$this->print_button([
					'text' => '',
					'class' => 'rey-singlePlayVideo d-none',
				]);
			}
		}

		function summary_button(){
			$this->print_button();
		}

		function print_button( $args = [] ){

			$args = wp_parse_args($args, [
				'text' => esc_html__('PLAY PRODUCT VIDEO', 'rey-core'),
				'icon' => reycore__get_svg_icon__core(['id' => 'reycore-icon-play']),
				'tag' => 'span',
				'attr' => [
					'title' => esc_html__('PLAY PRODUCT VIDEO', 'rey-core'),
					'class' => 'btn btn-line u-btn-icon-sm',
					'data-elementor-open-lightbox' => 'no',
				],
				'echo' => true
			]);

			$options = [
				'iframe' => esc_url($this->video_url),
				'width' => 750,
			];

			if( $width = reycore__acf_get_field('product_video_modal_size', $this->product_id ) ){
				$options['width'] = absint($width);
			}

			$args['attr']['data-reymodal'] = wp_json_encode($options);

			$button = apply_filters( 'reycore/woocommerce/video_button', sprintf('<%3$s %4$s>%2$s %1$s</%3$s>',
				$args['text'],
				$args['icon'],
				$args['tag'],
				wc_implode_html_attributes($args['attr'])
			, $args ));

			if( $args['echo'] ){
				echo $button;
			}
			else {
				return $button;
			}
		}

		function add_to_summary(){

			if( ! ($summary_position = reycore__acf_get_field('product_video_summary', $this->product_id )) ) {
				return;
			}

			if( $summary_position === 'disabled' ){
				return;
			}

			$available_positions = [
				'after_title'         => 6,
				'before_add_to_cart'  => 29,
				'before_product_meta' => 39,
				'after_product_meta'  => 41,
				'after_share'         => 51,
			];

			add_action( 'woocommerce_single_product_summary', [ $this, 'summary_button' ], $available_positions[$summary_position] );
		}

	}

	new ReyCore_WooCommerce_ProductVideos;

endif;

<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if( class_exists('WooCommerce') && !class_exists('ReyCore_Wc_ProductsCategoriesAttributes') ):

class ReyCore_Wc_ProductsCategoriesAttributes
{
	public function __construct()
	{

		include_once REY_CORE_MODULE_DIR . basename(__DIR__) . '/widget.php';
		include_once REY_CORE_MODULE_DIR . basename(__DIR__) . '/walker.php';

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

	}

	public function enqueue_scripts(){
		wp_register_style( 'reycore-product-catattr-widget', REY_CORE_MODULE_URI . basename(__DIR__) . '/style.css', [], REY_CORE_VERSION );
		wp_register_script( 'reycore-product-catattr-widget', REY_CORE_MODULE_URI . basename(__DIR__) . '/script.js', ['rey-script', 'reycore-scripts', 'rey-woocommerce-script'], REY_CORE_VERSION , true);
	}

}

new ReyCore_Wc_ProductsCategoriesAttributes;

endif;

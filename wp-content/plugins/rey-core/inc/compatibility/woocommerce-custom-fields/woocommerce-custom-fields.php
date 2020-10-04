<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( class_exists('WooCommerce') && defined('WCCF_VERSION') && !class_exists('ReyCore_Compatibility__WCCustomFields') ):

	class ReyCore_Compatibility__WCCustomFields
	{
		public function __construct()
		{
			add_action( 'admin_init', [$this, 'disable_nags'] );
			add_action( 'wp_enqueue_scripts', [$this, 'checkout_scripts'] );
		}

		function disable_nags(){
			if( get_site_option('rightpress_up_dis_woocommerce_custom_fields') != 1 ){
				update_site_option('rightpress_up_dis_woocommerce_custom_fields', 1);
			}
		}

		function checkout_scripts (){

			if( ! is_checkout() ){
				return;
			}

			$script = "
				if( typeof jQuery.fn.selectWoo === 'undefined' ){
					return;
				}

				jQuery( 'select.wccf_checkout_field_select:visible' ).each( function() {
					var select2_args = {
						placeholder: jQuery( this ).attr( 'data-placeholder' ) || jQuery( this ).attr( 'placeholder' ) || '',
						width: '100%'
					};

					jQuery( this )
						.on( 'select2:select', function() {
							jQuery( this ).focus(); // Maintain focus after select https://github.com/select2/select2/issues/4384
						} )
						.selectWoo( select2_args );
				});
			";

			wp_add_inline_script( 'selectWoo', $script );

		}

	}

	new ReyCore_Compatibility__WCCustomFields;
endif;

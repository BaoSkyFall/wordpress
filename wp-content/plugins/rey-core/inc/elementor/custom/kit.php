<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists('ReyCore_Element_Kit') ):
    /**
	 * Kit Overrides and customizations
	 *
	 * @since 1.0.0
	 */
	class ReyCore_Element_Kit {

		function __construct(){
			add_action( 'elementor/element/kit/section_settings-layout/before_section_end', [$this, 'kit_layout_settings'], 10);
			add_action( 'elementor/element/kit/section_layout-settings/before_section_end', [$this, 'kit_layout_settings'], 10);
		}

		/**
		 * Remove Container width as it directly conflicts with Rey's container settings
		 *
		 * @since 1.6.12
		 */
		function kit_layout_settings( $element ){
			$element->remove_control( 'container_width' );
			$element->remove_control( 'container_width_tablet' );
			$element->remove_control( 'container_width_mobile' );
		}

	}
endif;

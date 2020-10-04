<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( class_exists('Header_Footer_Elementor') && !class_exists('ReyCore_Compatibility__HFE') ):

	class ReyCore_Compatibility__HFE
	{
		public function __construct()
		{
			add_action('reycore/ocdi/after_buttons', [$this, 'add_notice']);
		}

		public function add_notice() {
			printf('<div class="rey-adminNotice --error">%s</div>', __('Please disable "<strong>Elementor - Header, Footer & Blocks</strong>" plugin because it\'s not compatible with Rey and will cause problems.', 'rey-core') );
		}
	}

	new ReyCore_Compatibility__HFE;
endif;

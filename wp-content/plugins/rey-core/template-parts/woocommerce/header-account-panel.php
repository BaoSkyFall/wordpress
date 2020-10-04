<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = reycore_wc__get_account_panel_args(); ?>

<div class="rey-accountPanel-wrapper js-rey-accountPanel">
	<div class="rey-accountPanel">
		<?php do_action('reycore/woocommerce/account_panel'); ?>
	</div>
	<!-- .rey-accountPanel -->
</div>
<!-- .rey-accountPanel-wrapper -->

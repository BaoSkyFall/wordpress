<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mini Cart
 */
if ( !class_exists('WooCommerce') ) {
    return;
}

$title = sprintf( '<h3 class="rey-cartPanel-title">%s (<span>%d</span>)</h3>',
	get_theme_mod('header_cart__title', esc_html_x('SHOPPING BAG', 'Shopping bag title in cart panel', 'rey-core')),
	is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : ''
);

$close = sprintf( '<button class="btn rey-cartPanel-close rey-sidePanel-close js-rey-sidePanel-close">%s</button>',
	reycore__get_svg_icon(['id' => 'rey-icon-close'])
);

$panel_class = 'rey-cartPanel';
$panel_class .= ' --cart-theme-' . esc_attr( get_theme_mod('header_cart__text_theme', 'def') );

if( get_theme_mod('header_cart__btns_inline', false) ){
	$panel_class .= ' --btns-inline';
}

$header = '<div class="rey-cartPanel-header">' . $title . $close . '</div>';
?>

<div class="rey-cartPanel-wrapper rey-sidePanel js-rey-cartPanel ">
	<?php the_widget( 'WC_Widget_Cart', 'title=', [
		'before_widget' => '<div class="widget ' . $panel_class . ' %s">' . $header,
	]); ?>
</div>

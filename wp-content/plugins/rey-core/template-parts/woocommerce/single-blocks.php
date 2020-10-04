<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 4.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$blocks = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $blocks ) ) :

	$blocks_classes = [];

	$blocks_count = $blocks;
	unset($blocks_count['reviews']);

	// Stretch description
	if( get_theme_mod('product_content_blocks_desc_stretch', false) ){
		$blocks_classes[] = '--stretch-desc';
		unset($blocks_count['description']);
	}

	if( ! isset($blocks['description']['callback']) ){
		$blocks_classes[] = '--no-description';
		unset($blocks_count['description']);
	}

	$count_blocks_css = sprintf('--blocks-count:%d;', count($blocks_count));

	?>
	<div class="rey-wcPanels <?php echo implode(' ', array_map('esc_attr', apply_filters('rey/woocommerce/product_panels_classes', $blocks_classes))) ?>" style="<?php esc_attr_e($count_blocks_css) ?>">
		<?php
		foreach ( $blocks as $key => $tab ):


			$content = '';

			if ( isset( $tab['callback'] ) ) {

				ob_start();
				call_user_func( $tab['callback'], $key, $tab );
				$the_content = ob_get_clean();

				if( ! $the_content ){
					continue;
				}

				if( $key == 'reviews' && isset( $tab['title'] ) ) {
					$content .= sprintf( '<div class="rey-reviewsBtn btn btn-secondary-outline btn--block js-toggle-target"  data-target=".rey-wcPanel--reviews #reviews"><span>%s</span></div>', $tab['title'] );
				}

				$content .= '<div class="rey-wcPanel-inner">';

					if ( isset($tab['type']) && ($tab['type'] === 'custom') && $tab['title'] ) {
						$content .= sprintf('<h2>%s</h2>', esc_html( $tab['title'] ));
					}

					$content .= $the_content;
				$content .= '</div>';
			}

			if( $content ):

				do_action('reycore/woocommerce/before_block_' . $key); ?>

				<div class="rey-wcPanel rey-wcPanel--<?php echo esc_attr( $key ); ?>">
					<?php echo $content; ?>
				</div>

				<?php
				do_action('reycore/woocommerce/after_block_' . $key);
			endif;

		endforeach; ?>
	</div>

	<?php
	// deprecated
	do_action('reycore/woocommerce/before_blocks_review'); ?>

<?php endif; ?>

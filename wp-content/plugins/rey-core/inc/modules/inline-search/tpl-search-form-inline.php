<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = reycore_wc__get_header_search_args();

$from_sticky = get_query_var('rey__is_sticky', false);
$unique_id = rey__unique_id( 'search-form-' ) ;

?>

<div class="rey-headerIcon rey-searchForm rey-headerSearch--inline rey-searchAjax js-rey-ajaxSearch <?php echo esc_attr( $from_sticky === true ? 'js-from-sticky' : '' ) ?>">

	<button class="btn rey-headerSearch-toggle">
		<?php echo reycore__get_svg_icon(['id' => 'rey-icon-search', 'class' => 'icon-search']) ?>
	</button>

	<div class="rey-inlineSearch-wrapper ">
		<div class="rey-inlineSearch-holder"></div>

		<button class="btn rey-inlineSearch-mobileClose">
			<?php echo reycore__get_svg_icon(['id' => 'rey-icon-close', 'class' => 'icon-close']) ?>
		</button>

		<form role="search" action="<?php echo esc_url(home_url('/')) ?>" method="get">
			<label for="<?php echo esc_attr($unique_id); ?>"  class="screen-reader-text">
				<?php echo esc_html_x( 'Search for:', 'label', 'rey-core' ); ?>
			</label>
			<input type="search" id="<?php echo esc_attr($unique_id); ?>" name="s" placeholder="<?php echo esc_attr( get_theme_mod('header_search__input_placeholder', __( 'type to search..', 'rey-core' )) ); ?>" autocomplete="off"/>
			<button class="search-btn" type="submit" aria-label="<?php esc_html_e('Click to search', 'rey-core') ?>">
				<?php echo reycore__get_svg_icon(['id' => 'rey-icon-search', 'class' => 'icon-search']) ?></button>
			<?php do_action('rey/search_form'); ?>
		</form>

		<?php do_action('reycore/search_panel/after_search_form', $args); ?>
	</div>

</div>

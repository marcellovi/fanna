<?php
/**
 * Number of products on shop page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_single() || ! have_posts() ) return;

$num_prod = ( isset( $_GET['products-per-page'] ) ) ? $_GET['products-per-page'] : 12;

$num_prod_x1 = 12;
$num_prod_x2 = $num_prod_x1 * 2;

$obj  = get_queried_object();
$link = '';

if ( isset( $obj->term_id ) ) {
	$link = get_term_link( $obj->term_id, 'product_cat' );

	if ( is_wp_error( $link ) ) {
		$link = get_term_link( $obj->term_id, 'product_tag' );
	}

} else {
	if ( get_option( 'permalink_structure' ) == "" ) {
		$link = get_post_type_archive_link( 'product' );
	} else {
		$link = get_permalink( wc_get_page_id( 'shop' ) );
	}
}

/**
 * Filter query link for products number
 *
 * @since 1.0.8
 * @param string $link The old query url
 */
$link = apply_filters( 'ultrastore_num_products_link', $link );

if( ! empty( $_GET ) ) {
	foreach( $_GET as $key => $value ){
		$link = add_query_arg( $key, $value, $link  );
	}
}
?>

<div class="wc-products-per-page">
	<span class="view-title"><?php esc_attr_e( 'Show', 'ultrastore' ) ?> </span>
	<a class="view-12<?php if ( $num_prod == $num_prod_x1 ) echo ' active'; ?>" href="<?php echo esc_url( add_query_arg( 'products-per-page', $num_prod_x1, $link  ) ) ?>"><?php echo $num_prod_x1 ?></a>
	<a class="view-24<?php if ( $num_prod == $num_prod_x2 ) echo ' active'; ?>" href="<?php echo esc_url( add_query_arg( 'products-per-page', $num_prod_x2, $link  ) ) ?>"><?php echo $num_prod_x2 ?></a>
	<a class="view-all<?php if ( $num_prod == 'all' ) echo ' active'; ?>" href="<?php echo esc_url( add_query_arg( 'products-per-page', 'all', $link  ) ) ?>"><?php esc_attr_e( 'All', 'ultrastore' ) ?></a>
</div>

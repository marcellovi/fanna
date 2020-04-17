<?php
/**
 * Missing WooCommerce notice.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;
?>

<div class="error">
    <p>
        <strong><?php esc_html_e( 'PayPal Plus Brasil para WooCommerce', 'paypal-plus-brasil' ); ?></strong> <?php echo esc_html( sprintf( __( 'depende da versão do WooCommerce 3.x.x para funcionar! Você está utilizando a versão %s. Por favor atualize.', 'paypal-plus-brasil' ), $woocommerce->version ) ); ?>
    </p>
</div>
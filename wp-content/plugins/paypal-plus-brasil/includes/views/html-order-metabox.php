<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;

// Prevent run if version is less then 3.0.0
if ( WC_PPP_Brasil::woocommerce_incompatible() ) {
	echo sprintf( '<p>%s</p>', 'This feature only works on WooCommerce 3.x.x. Please upgrate to view the content.' );

	return;
}

$order          = new WC_Order( get_the_ID() );
$payment_method = $order->get_payment_method();

$sandbox           = get_post_meta( $order->get_id(), '_wc_paypal_plus_payment_sandbox', true );
$sandbox           = 'yes';
$installments      = get_post_meta( $order->get_id(), 'wc_ppp_brasil_installments', true );
$sale_id           = get_post_meta( $order->get_id(), 'wc_ppp_brasil_sale_id', true );
$sale              = get_post_meta( $order->get_id(), 'wc_ppp_brasil_sale', true );
$currency          = $order->get_currency();
$sale_link_prefix  = 'yes' == $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_history-details-from-hub&id=' : 'https://history.paypal.com/cgi-bin/webscr?cmd=_history-details-from-hub&id=';
$sale_link         = $sale_link_prefix . $sale_id;
$sale_details_link = 'https://www.paypal.com/myaccount/transaction/print-details/' . $sale_id;
?>
<?php if ( $payment_method === 'wc-ppp-brasil-gateway' && ( $order->get_status() === 'processing' || $order->get_status() === 'completed' ) ): ?>
    <ul>
        <li><strong><?php _e( 'ID da venda:', 'paypal-plus-brasil' ); ?></strong> <a href="<?php echo $sale_link; ?>"
                                                                             target="_blank"><?php echo $sale_id; ?></a>
        </li>
        <li>
            <strong><?php _e( 'Tarifa de venda:', 'paypal-plus-brasil' ); ?></strong> <?php echo wc_price( $sale['transaction_fee']['value'] ); ?>
        </li>
		<?php if ( $currency === 'USD' ): ?>
            <li>
                <strong><?php _e( 'Valor:', 'paypal-plus-brasil' ); ?></strong> <?php echo wc_price( $order->get_total() ); ?>
            </li>
		<?php else: ?>
            <li>
                <strong><?php _e( 'Parcelamento:', 'paypal-plus-brasil' ); ?></strong> <?php echo sprintf( '%dx %s', $installments, wc_price( $order->get_total() / $installments ) ); ?>
            </li>
		<?php endif; ?>
		<?php if ( 'yes' == $sandbox ): ?>
            <li><strong><?php _e( 'Sandbox:', 'paypal-plus-brasil' ); ?></strong> <?php _e( 'sim', 'paypal-plus-brasil' ); ?></li>
		<?php else: ?>
            <li>
                <strong><?php _e( 'Detalhes:', 'paypal-plus-brasil' ); ?></strong> <a href="<?php echo $sale_details_link; ?>"
                                                                              target="_blank"><?php _e( 'imprimir', 'paypal-plus-brasil' ); ?></a>
            </li>
		<?php endif; ?>
    </ul>
<?php else: ?>
    <style>
        #wc-ppp-brasil.postbox {
            display: none;
        }
    </style>
<?php endif; ?>

<?php
if ( ! ultrastore_is_woocommerce_activated() ) {
	return;
}
?>
<?php if ( has_nav_menu ( 'account' ) ) : ?>
	<?php wp_nav_menu(
		array(
			'theme_location'  => 'account',
			'menu_id'         => 'menu-account-items',
			'menu_class'      => 'menu-account-items menu',
			'container'       => false
		)
	); ?>
<?php endif; ?>

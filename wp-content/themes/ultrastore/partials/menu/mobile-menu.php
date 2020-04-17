<?php if ( has_nav_menu ( 'mobile' ) ) : ?>
	<nav class="mobile-navigation">
		<a href="#" class="menu-toggle"><i class="icon-cancel"></i> <?php esc_html_e( 'Close Menu', 'ultrastore' ); ?></a>

		<div class="icon-navigation">
			<?php if ( ultrastore_is_woocommerce_activated() ) ultrastore_wc_header_cart(); ?>
			<?php get_template_part( 'partials/menu/shop' ); ?>
			<?php get_template_part( 'partials/menu/search' ); ?>
		</div>

		<?php wp_nav_menu(
			array(
				'theme_location'  => 'mobile',
				'menu_id'         => 'menu-mobile-items',
				'menu_class'      => 'menu-mobile-items',
				'container'       => false
			)
		); ?>
	</nav>
<?php endif; ?>

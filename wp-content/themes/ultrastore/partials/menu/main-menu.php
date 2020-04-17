<?php if ( has_nav_menu ( 'primary' ) ) : ?>
	<nav class="main-navigation" id="site-navigation" style="padding-left:16px">
		<?php wp_nav_menu(
			array(
				'theme_location'  => 'primary',
				'menu_id'         => 'menu-primary-items',
				'menu_class'      => 'menu-primary-items menu',
				'container'       => false
			)
		); ?>

		<?php if ( has_nav_menu ( 'mobile' ) ) : ?>
			<a href="#" class="menu-toggle"><i class="icon-menu"></i></a>
		<?php endif; ?>

		<div class="right-navigation">
			<?php if ( ultrastore_is_woocommerce_activated() ) ultrastore_wc_header_cart(); ?>
			<?php get_template_part( 'partials/menu/shop' ); ?>
			<?php get_template_part( 'partials/menu/search' ); ?>
		</div>

	</nav>
<?php endif; ?>

		</div><!-- #content -->

		<footer id="colophon" class="site-footer">

			<?php do_action( 'ultrastore_footer' ); ?>

			<?php
				$copyright = get_theme_mod( 'copyrights_enable', true );
				if ( $copyright ) :
			?>
				<div class="copyrights">
					<div class="container">
						<?php ultrastore_footer_text(); ?>
					</div><!-- .site-info -->
				</div>
			<?php endif; ?>

		</footer><!-- #colophon -->

	</div><!-- .wide-container -->

</div><!-- #page -->

<div id="search-overlay" class="search-popup popup-content mfp-hide">
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" class="search-field field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'ultrastore' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'ultrastore' ) ?>" />
		<input type="hidden" name="post_type" value="product">
	</form>
</div>

<?php ultrastore_back_to_top(); ?>

<?php wp_footer(); ?>

</body>
</html>

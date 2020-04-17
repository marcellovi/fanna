<?php if ( is_home() || is_archive() || is_search() ) : // If viewing the blog, an archive, or search results. ?>

	<?php global $wp_query; ?>

	<?php
		$type = get_theme_mod( 'posts_pagination', 'number' );
		if ( $type == 'number' ) :
	?>

		<?php the_posts_pagination(); ?>

	<?php elseif ( $type == 'traditional' ) : ?>

		<?php if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav class="navigation pagination">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'ultrastore' ) ?></h2>
				<div class="nav-page">
					<div class="nav-newer newer"><?php previous_posts_link( esc_html__( 'Newer posts', 'ultrastore' ) ); ?></div>
					<div class="nav-older older"><?php next_posts_link( esc_html__( 'Older posts', 'ultrastore' ) ); ?></div>
				</div>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>

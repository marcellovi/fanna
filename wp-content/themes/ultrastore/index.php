<?php
// Get the customizer data.
$type  = get_theme_mod( 'post_style', 'grid' );
$class = 'posts';

// Custom class
if ( $type == 'grid' ) {
	$class = 'posts-grid two-columns';
} elseif ( $type == 'list' ) {
	$class = 'posts-list';
} elseif ( $type == 'alternate' ) {
	$class = 'posts-alternate';
}
?>

<?php get_header(); ?>

	<div class="container">

		<div id="primary" class="content-area">
			<main id="main" class="site-main">

				<?php if ( have_posts() ) : ?>

					<div class="<?php echo esc_attr( $class ); ?>">

						<?php while ( have_posts() ) : the_post(); ?>

							<?php if ( $type == 'default' ) { ?>
								<?php get_template_part( 'partials/content/content' ); ?>
							<?php } elseif ( $type == 'grid' ) { ?>
								<?php get_template_part( 'partials/content/content', 'grid' ); ?>
							<?php } elseif ( $type == 'list' ) { ?>
								<?php get_template_part( 'partials/content/content', 'list' ); ?>
							<?php } elseif ( $type == 'alternate' ) { ?>
								<?php if ( $wp_query->current_post == 0 && !is_paged() ) { ?>
									<?php get_template_part( 'partials/content/content' ); ?>
								<?php } else { ?>
									<?php get_template_part( 'partials/content/content', 'list' ); ?>
								<?php } ?>
							<?php } ?>

						<?php endwhile; ?>

					</div>

					<?php get_template_part( 'pagination' ); // Loads the pagination.php template  ?>

				<?php else : ?>

					<?php get_template_part( 'partials/content/content', 'none' ); ?>

				<?php endif; ?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_sidebar(); // Loads the sidebar.php template. ?>

	</div><!-- .container -->

<?php get_footer(); ?>

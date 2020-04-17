<?php
// Vars
$size  = 'ultrastore-post';
$img   = get_theme_mod( 'post_featured_image', true );
$meta  = get_theme_mod( 'post_meta', true );
$tags  = get_theme_mod( 'post_tags', true );
$title = get_theme_mod( 'post_tags_title', esc_attr__( 'Topics', 'ultrastore' ) );

// Switch the image size in full width mode.
if ( in_array( ultrastore_post_layout(), array( 'full-width' ) ) ) {
	$size = 'ultrastore-post-large';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( true == $img && has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php
				the_post_thumbnail( $size, array(
					'alt' => the_title_attribute( array(
						'echo' => false,
					) ),
				) );
			?>
		</div>
	<?php endif; ?>

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( true == $meta ) : ?>
			<div class="entry-meta">
				<?php ultrastore_post_meta(); ?>
			</div>
		<?php endif; ?>

	</header>

	<div class="entry-content">

		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ultrastore' ),
				'after'  => '</div>',
			) );
		?>

	</div>

	<footer class="entry-footer">

		<?php
			$get_tags = get_the_tags();
			if ( true == $tags && $get_tags ) :
		?>
			<span class="tag-links">
				<span class="tag-title block-title"><?php echo esc_html( $title ); ?></span>
				<?php foreach( $get_tags as $tag ) : ?>
					<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">#<?php echo esc_attr( $tag->name ); ?></a>
				<?php endforeach; ?>
			</span>
		<?php endif; ?>

	</footer>

</article><!-- #post-## -->

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-grid' ); ?>>

	<?php ultrastore_post_thumbnail( 'ultrastore-featured' ); ?>

	<div class="thumbnail-content">

		<div class="entry-header">

			<?php $categories = get_the_category_list( esc_html__( ', ', 'ultrastore' ) );  ?>
			<span class="entry-cat"><?php echo $categories; ?></span>

		</div>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<div class="entry-meta">
			<?php ultrastore_post_meta(); ?>
		</div>

	</div>

</article><!-- #post-## -->

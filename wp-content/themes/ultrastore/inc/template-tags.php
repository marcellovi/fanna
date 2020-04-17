<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 */

if ( ! function_exists( 'ultrastore_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function ultrastore_post_thumbnail( $size = 'post-thumbnail' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}
		?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>">
				<?php
					the_post_thumbnail( $size, array(
						'alt' => the_title_attribute( array(
							'echo' => false,
						) ),
					) );
				?>
			</a>

		<?php
	}
endif;

if ( ! function_exists( 'ultrastore_post_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function ultrastore_post_meta( $category = true ) {

		// Hide on job page.
		if ( 'post' != get_post_type() ) {
			return;
		}

		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) )
		);

		$posted_on = sprintf(
			/* translators: %s: ago. */
			esc_html_x( '%s ago', 'post date', 'ultrastore' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			esc_html__( 'by %s', 'ultrastore' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		$categories = get_the_category_list( esc_html__( ', ', 'ultrastore' ) );

		if ( $category == true ) :
			echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span><span class="cat-links">' . $categories . '</span>'; // WPCS: XSS OK.
		else :
			echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
		endif;
	}
endif;

if ( ! function_exists( 'ultrastore_post_author_box' ) ) :
	/**
	 * Author post informations.
	 */
	function ultrastore_post_author_box() {

		// Get the data from Customizer
		$author = get_theme_mod( 'post_author_box', true );

		if ( true != $author ) {
			return;
		}

		// Bail if not on the single post.
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		// Bail if user hasn't fill the Biographical Info field.
		if ( ! get_the_author_meta( 'description' ) ) {
			return;
		}

		// Get the author social information.
		$url       = get_the_author_meta( 'url' );
		$twitter   = get_the_author_meta( 'twitter' );
		$facebook  = get_the_author_meta( 'facebook' );
		$gplus     = get_the_author_meta( 'gplus' );
		$instagram = get_the_author_meta( 'instagram' );
		$pinterest = get_the_author_meta( 'pinterest' );
		$linkedin  = get_the_author_meta( 'linkedin' );
		$dribbble  = get_the_author_meta( 'dribbble' );
	?>

		<div class="author-bio">

			<div class="author-avatar">
				<?php echo get_avatar( is_email( get_the_author_meta( 'user_email' ) ), apply_filters( 'ultrastore_author_bio_avatar_size', 120 ), '', strip_tags( get_the_author() ) ); ?>
			</div>

			<div class="description">

				<h3 class="author-title name">
					<a class="author-name url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo strip_tags( get_the_author() ); ?></a>
				</h3>

				<p class="bio"><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>

				<?php if ( $url || $twitter || $facebook || $gplus || $instagram || $pinterest || $linkedin || $dribbble ) : ?>
					<div class="author-social-links">
						<?php if ( $url ) { ?>
							<a href="<?php echo esc_url( $url ); ?>"><i class="icon-home"></i></a>
						<?php } ?>
						<?php if ( $twitter ) { ?>
							<a href="<?php echo esc_url( $twitter ); ?>"><i class="icon-twitter"></i></a>
						<?php } ?>
						<?php if ( $facebook ) { ?>
							<a href="<?php echo esc_url( $facebook ); ?>"><i class="icon-facebook"></i></a>
						<?php } ?>
						<?php if ( $gplus ) { ?>
							<a href="<?php echo esc_url( $gplus ); ?>"><i class="icon-gplus"></i></a>
						<?php } ?>
						<?php if ( $instagram ) { ?>
							<a href="<?php echo esc_url( $instagram ); ?>"><i class="icon-instagram"></i></a>
						<?php } ?>
						<?php if ( $pinterest ) { ?>
							<a href="<?php echo esc_url( $pinterest ); ?>"><i class="icon-pinterest"></i></a>
						<?php } ?>
						<?php if ( $linkedin ) { ?>
							<a href="<?php echo esc_url( $linkedin ); ?>"><i class="icon-linkedin"></i></a>
						<?php } ?>
						<?php if ( $dribbble ) { ?>
							<a href="<?php echo esc_url( $dribbble ); ?>"><i class="icon-dribbble"></i></a>
						<?php } ?>
					</div>
				<?php endif; ?>

			</div>

		</div><!-- .author-bio -->

	<?php
	}
endif;

if ( ! function_exists( 'ultrastore_next_prev_post' ) ) :
	/**
	 * Custom next post link
	 */
	function ultrastore_next_prev_post() {

		// Get the data set in customizer
		$nav = get_theme_mod( 'post_navigation', true );

		if ( true != $nav ) {
			return;
		}

		// Display on single post page.
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		// Get the next and previous post id.
		$next = get_adjacent_post( false, '', false );
		$prev = get_adjacent_post( false, '', true );
	?>
		<div class="post-pagination">

			<?php if ( $prev ) : ?>
				<div class="prev-post">

					<div class="post-detail">
						<span><span class="arrow"><i class="icon-arrow-left" aria-hidden="true"></i></span><?php esc_html_e( 'Previous Post', 'ultrastore' ); ?></span>
						<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post-title"><?php echo esc_attr( get_the_title( $prev->ID ) ); ?></a>
					</div>

				</div>
			<?php endif; ?>

			<?php if ( $next ) : ?>
				<div class="next-post">

					<div class="post-detail">
						<span><?php esc_html_e( 'Next Post', 'ultrastore' ); ?><span class="arrow"><i class="icon-arrow-right" aria-hidden="true"></i></span></span>
						<a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="post-title"><?php echo esc_attr( get_the_title( $next->ID ) ); ?></a>
					</div>

				</div>
			<?php endif; ?>

		</div>
	<?php
	}
endif;

if ( ! function_exists( 'ultrastore_related_posts' ) ) :
	/**
	 * Related posts.
	 */
	function ultrastore_related_posts() {

		// Get the data set in customizer
		$related = get_theme_mod( 'post_related', true );
		$title   = get_theme_mod( 'post_related_title', esc_html__( 'You Might Also Like:', 'ultrastore' ) );
		$number  = get_theme_mod( 'post_related_number', 3 );

		// Disable if user choose it.
		if ( true != $related ) {
			return;
		}

		// Get the taxonomy terms of the current page for the specified taxonomy.
		$terms = wp_get_post_terms( get_the_ID(), 'category', array( 'fields' => 'ids' ) );

		// Bail if the term empty.
		if ( empty( $terms ) ) {
			return;
		}

		// Posts query arguments.
		$query = array(
			'post__not_in' => array( get_the_ID() ),
			'tax_query'    => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'id',
					'terms'    => $terms,
					'operator' => 'IN'
				)
			),
			'posts_per_page' => absint( $number ),
			'post_type'      => 'post',
		);

		// Allow dev to filter the query.
		$args = apply_filters( 'ultrastore_related_posts_args', $query );

		// The post query
		$related = new WP_Query( $args );

		if ( $related->have_posts() ) : ?>

			<div class="related-posts">

				<h3 class="related-title module-title"><?php echo wp_kses_post( $title ); ?></h3>

				<div class="posts-small">

					<?php while ( $related->have_posts() ) : $related->the_post(); ?>

						<article <?php post_class(); ?>>

							<?php ultrastore_post_thumbnail( 'ultrastore-post-small' ); ?>

							<header class="entry-header">

								<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

								<div class="entry-meta">
									<?php ultrastore_post_meta( false ); ?>
								</div>

							</header>

						</article><!-- #post-## -->

					<?php endwhile; ?>

				</div>

			</div>

		<?php endif;

		// Restore original Post Data.
		wp_reset_postdata();

	}
endif;

if ( ! function_exists( 'ultrastore_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function ultrastore_comment( $comment, $args, $depth ) {
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment-container">
				<p><?php esc_html_e( 'Pingback:', 'ultrastore' ); ?> <span><?php comment_author_link(); ?></span> <?php edit_comment_link( esc_html__( '(Edit)', 'ultrastore' ), '<span class="edit-link">', '</span>' ); ?></p>
			</article>
		<?php
				break;
			default :
			// Proceed with normal comments.
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment-container">

				<div class="comment-avatar">
					<?php echo get_avatar( $comment, apply_filters( 'ultrastore_comment_avatar_size', 80 ) ); ?>
					<span class="name"><?php echo get_comment_author_link(); ?></span>
					<?php echo ultrastore_comment_author_badge(); ?>
				</div>

				<div class="comment-body">
					<div class="comment-wrapper">

						<div class="comment-head">
							<?php
								$edit_comment_link = '';
								if ( get_edit_comment_link() )
									$edit_comment_link = sprintf( esc_html__( '&middot; %1$sEdit%2$s', 'ultrastore' ), '<a href="' . get_edit_comment_link() . '" title="' . esc_attr__( 'Edit Comment', 'ultrastore' ) . '">', '</a>' );

								printf( '<span class="date"><a href="%1$s"><time datetime="%2$s">%3$s</time></a> %4$s</span>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									/* translators: 1: date, 2: time */
									sprintf( esc_html__( '%1$s at %2$s', 'ultrastore' ), get_comment_date(), get_comment_time() ),
									$edit_comment_link
								);
							?>
						</div><!-- comment-head -->

						<div class="comment-content comment-entry">
							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'ultrastore' ); ?></p>
							<?php endif; ?>
							<?php comment_text(); ?>
							<span class="reply">
								<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'ultrastore' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
							</span><!-- .reply -->
						</div><!-- .comment-content -->

					</div>
				</div>

			</article><!-- #comment-## -->
		<?php
			break;
		endswitch; // end comment_type check
	}
endif;

if ( ! function_exists( 'ultrastore_comment_author_badge' ) ) :
	/**
	 * Custom badge for post author comment
	 */
	function ultrastore_comment_author_badge() {

		// Set up empty variable
		$output = '';

		// Get comment classes
		$classes = get_comment_class();

		if ( in_array( 'bypostauthor', $classes ) ) {
			$output = '<span class="author-badge">' . esc_html__( 'Author', 'ultrastore' ) . '</span>';
		}

		// Display the badge
		return apply_filters( 'ultrastore_comment_author_badge', $output );
	}
endif;

if ( ! function_exists( 'ultrastore_footer_text' ) ) :
	/**
	 * Footer Text
	 */
	function ultrastore_footer_text() {

		// Get the customizer data
		$default = '&copy; Copyright ' . date( 'Y' ) . ' - <a href="' . esc_url( home_url() ) . '">' . esc_attr( get_bloginfo( 'name' ) ) . '</a>. All Rights Reserved. Designed & Developed by <a href="https://www.theme-junkie.com/">Theme Junkie</a>';
		$footer_text = get_theme_mod( 'copyrights_text', $default );

		// Display the data
		echo '<p class="copyright">' . wp_kses_post( $footer_text ) . '</p>';

	}
endif;

if ( ! function_exists( 'ultrastore_back_to_top' ) ) :
	/**
	 * Back to top button.
	 */
	function ultrastore_back_to_top() {

		// Get the customizer data.
		if ( true == get_theme_mod( 'backtotop', true )  ) : ?>

			<a href="#" class="back-to-top" title="<?php esc_html_e( 'Back to top', 'ultrastore' ); ?>">
				<i class="icon-arrow-up" aria-hidden="true"></i>
			</a>

		<?php endif; ?>

	<?php
	}
endif;

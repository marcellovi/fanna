<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="event-date"><?php echo tribe_get_start_date(); ?></div>
	<div class="event-city"><?php echo tribe_get_city(); ?></div>
	<div class="event-venue"><?php echo tribe_get_venue() ?></div>
	<div class="event-link">
		<a href="<?php the_permalink(); ?>" class="button"><?php esc_html_e( 'View Event', 'ultrastore' ); ?></a>
	</div>

</div>

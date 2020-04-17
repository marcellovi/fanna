<?php
	$search = get_theme_mod( 'search_icon', true );
	$text   = get_theme_mod( 'custom_text_header' );

	if ( true == $search ) :
?>
	<div class="search-icon">
		<a href="#search-overlay" class="search-toggle">
			<i class="icon-search"></i>
		</a>
	</div>
<?php else : ?>
	<div class="custom-text">
		<?php echo wp_kses_post( $text ); ?>
	</div>
<?php endif; ?>

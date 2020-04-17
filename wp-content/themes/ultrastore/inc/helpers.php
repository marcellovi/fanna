<?php
/**
 * Helper functions
 */

if ( ! function_exists( 'ultrastore_pingback_header' ) ) :
	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 */
	function ultrastore_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', bloginfo( 'pingback_url' ), '">';
		}
	}

	add_action( 'wp_head', 'ultrastore_pingback_header' );

endif;

if ( ! function_exists( 'ultrastore_is_elementor_activated' ) ) :
	/**
	 * Check if Elementor is active
	 */
	function ultrastore_is_elementor_activated() {
		return defined( 'ELEMENTOR_VERSION' );
	}
endif;

if ( ! function_exists( 'ultrastore_is_tj_extras_activated' ) ) :
	/**
	 * TJ Extras plugin activation checker.
	 */
	function ultrastore_is_tj_extras_activated() {
		return class_exists( 'TJ_Extras' ) ? true : false;
	}
endif;

if ( ! function_exists( 'ultrastore_is_woocommerce_activated' ) ) :
	/**
	 * Check if WooCommerce is active
	 */
	function ultrastore_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
endif;

if ( ! function_exists( 'ultrastore_is_yww_activated' ) ) {
	/**
	 * Check if YITH WooCommerce Wishlist is activated.
	 */
	function ultrastore_is_yww_activated() {
		return class_exists( 'YITH_WCWL' ) ? true : false;
	}
}

if ( ! function_exists( 'ultrastore_is_quick_view_activated' ) ) {
	/**
	 * Check if Woo Smart Quick View is activated.
	 */
	function ultrastore_is_quick_view_activated() {
		return class_exists( 'WPcleverWoosq' ) ? true : false;
	}
}

if ( ! function_exists( 'ultrastore_is_smart_compare_activated' ) ) {
	/**
	 * Check if Woo Smart Smart Compare is activated.
	 */
	function ultrastore_is_smart_compare_activated() {
		return class_exists( 'WPcleverWooscp' ) ? true : false;
	}
}

if ( ! function_exists( 'ultrastore_body_classes' ) ) :
	/**
	 * Adds classes to the body tag
	 */
	function ultrastore_body_classes( $classes ) {

		// Vars
		$post_layout  = ultrastore_post_layout();
		$container    = get_theme_mod( 'container_style', 'full-width' );
		$post_style   = get_theme_mod( 'post_style', 'grid' );

		// RTL
		if ( is_rtl() ) {
			$classes[] = 'rtl';
		}

		// Main class
		$classes[] = 'ultrastore-theme';

		// Container style
		$classes[] = $container . '-container';

		// Sidebar enabled
		if ( 'left-sidebar' == $post_layout
			|| 'right-sidebar' == $post_layout ) {
			$classes[] = 'has-sidebar';
		}

		// Content layout
		if ( $post_layout ) {
			$classes[] = $post_layout;
		}

		// Has featured image.
		if ( is_singular() && has_post_thumbnail() ) {
			$classes[] = 'has-featured-image';
		}

		// Content style
		if ( is_home()
			|| is_category()
			|| is_tag()
			|| is_date()
			|| is_author() ) {
			$classes[] = 'post-style-' . $post_style;
		}

		// Return classes
		return $classes;

	}

	add_filter( 'body_class', 'ultrastore_body_classes' );

endif;

if ( ! function_exists( 'ultrastore_post_layout' ) ) :
	/**
	 * Returns correct post layout.
	 */
	function ultrastore_post_layout() {

		// Define variables
		$class  = 'right-sidebar';
		$meta   = get_post_meta( get_the_ID(), 'tj_extras_post_layout', true );

		// Check meta first to override and return (prevents filters from overriding meta)
		if ( is_singular() && $meta ) {
			return $meta;
		}

		// Singular Page
		if ( is_page() ) {

			// Attachment
			if ( is_attachment() && wp_attachment_is_image() ) {
				$class = 'full-width';
			}

			// All other pages
			else {
				$class = get_theme_mod( 'page_layout', 'right-sidebar' );
			}

		}

		// Home
		elseif ( is_home()
			|| is_category()
			|| is_tag()
			|| is_date()
			|| is_author() ) {
			$class = get_theme_mod( 'post_layout', 'right-sidebar' );
		}

		// Singular Post
		elseif ( is_singular( 'post' ) ) {
			$class = get_theme_mod( 'post_layout', 'right-sidebar' );
		}

		// Library and Elementor template
		elseif ( is_singular( 'tj_library' )
    			|| is_singular( 'elementor_library' ) ) {
			$class = 'full-width';
		}

		// 404 page
		elseif ( is_404() ) {
			$class = 'full-width-narrow';
		}

		// Event archive page
		elseif ( is_post_type_archive( 'tribe_events' ) ) {
			$class = 'full-width';
		}

		// All else
		else {
			$class = 'right-sidebar';
		}

		// Class should never be empty
		if ( empty( $class ) ) {
			$class = 'right-sidebar';
		}

		// Apply filters and return
		return apply_filters( 'ultrastore_post_layout_class', $class );

	}

endif;

if ( ! function_exists( 'ultrastore_get_sidebar' ) ) :
	/**
	 * Returns the correct sidebar region.
	 */
	function ultrastore_get_sidebar( $sidebar = 'primary' ) {

		// Return the correct sidebar name & add useful hook
		$sidebar = apply_filters( 'ultrastore_get_sidebar', $sidebar );

		// Check meta option after filter so it always overrides
		if ( $meta = get_post_meta( get_the_ID(), 'tj_extras_sidebar', true ) ) {
			$sidebar = $meta;
		}

		// WooCommerce
		if ( ultrastore_is_woocommerce_activated() ) {
			if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
				$sidebar = 'woo_sidebar';
			}
		}

		// Never show empty sidebar
		if ( ! is_active_sidebar( $sidebar ) ) {
			$sidebar = 'primary';
		}

		return $sidebar;
	}
endif;

if ( ! function_exists( 'ultrastore_header' ) ) {
	/**
	 * Header template
	 */
	function ultrastore_header() {

		$header = get_theme_mod( 'header_style', 'default' );
		$meta   = get_post_meta( get_the_ID(), 'tj_extras_custom_header_template', true );

		// Check meta option first.
		if ( $meta && is_singular() ) {
			ultrastore_get_custom_header();
		} elseif ( 'default' != $header ) {
			ultrastore_get_custom_header();
		} else {
			get_template_part( 'partials/header/header' );
		}

	}

	add_action( 'ultrastore_header', 'ultrastore_header' );

}

if ( ! function_exists( 'ultrastore_get_custom_header' ) ) :
	/**
	 * Get custom header template
	 */
	function ultrastore_get_custom_header() {

		// Sets up empty variable
		$id = '';

		// Get the template ID
		$id = get_theme_mod( 'header_template', '0' );

		// Get the template ID from metabox
		if ( $meta = get_post_meta( get_the_ID(), 'tj_extras_custom_header_template', true ) ) {
			$id = $meta;
		}

		if ( $id ) {
			$args = array(
				'post_type' => 'tj_library',
				'p'         => $id
			);
			$loop = new WP_Query ($args );

			while ( $loop->have_posts() ) : $loop->the_post();
				global $post;
				the_content();
			endwhile;

			wp_reset_postdata();
		}

	}

endif;

if ( ! function_exists( 'ultrastore_footer' ) ) {
	/**
	 * Footer template
	 */
	function ultrastore_footer() {

		$footer = get_theme_mod( 'footer_style', 'default' );
		$meta   = get_post_meta( get_the_ID(), 'tj_extras_custom_footer_template', true );

		// Check meta option first.
		if ( $meta && is_singular() ) {
			ultrastore_get_custom_footer();
		} elseif ( 'default' != $footer ) {
			ultrastore_get_custom_footer();
		} else {
			get_template_part( 'sidebar', 'footer' );
		}

	}

	add_action( 'ultrastore_footer', 'ultrastore_footer' );

}

if ( ! function_exists( 'ultrastore_get_custom_footer' ) ) :
	/**
	 * Get custom footer template
	 */
	function ultrastore_get_custom_footer(){

		// Sets up empty variable
		$id = '';

		// Get the template ID
		$id = get_theme_mod( 'footer_template', '0' );

		// Get the template ID from metabox
		if ( $meta = get_post_meta( get_the_ID(), 'tj_extras_custom_footer_template', true ) ) {
			$id = $meta;
		}

		if ( $id ){
			$args = array(
				'post_type' => 'tj_library',
				'p'=> $id
			);
			$loop = new WP_Query ($args );

			while ( $loop->have_posts() ) : $loop->the_post();
				global $post;
				the_content();
			endwhile;

			wp_reset_postdata();
		}

	}

endif;

if ( ! function_exists( 'ultrastore_archive_header' ) ) :
	/**
	 * Archive header informations.
	 */
	function ultrastore_archive_header() {
		?>

		<?php if ( is_archive() && !is_search() ) : ?>
			<div class="archive-header">
				<div class="archive-content">
					<?php if ( is_author() ) echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
					<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
				</div>
			</div><!-- .archive-header -->
		<?php endif; ?>

		<?php if ( is_search() ) : ?>
			<div class="archive-header">
				<div class="archive-content">
					<span class="browse"><?php esc_html_e( 'Search Results for', 'ultrastore' ); ?></span>
					<h1 class="archive-title"><?php echo get_search_query(); ?></h1>
				</div>
			</div><!-- .archive-header -->
		<?php endif; ?>

		<?php if ( ultrastore_is_woocommerce_activated() ) : ?>
			<?php if ( is_cart() ) : ?>
				<div class="archive-header">
					<h1 class="archive-title"><?php esc_html_e( 'Cart', 'ultrastore' ); ?></h1>
				</div><!-- .archive-header -->
			<?php endif; ?>

			<?php if ( is_wc_endpoint_url( 'order-received' ) ) : ?>
				<div class="archive-header">
					<h1 class="archive-title"><?php esc_html_e( 'Order Received', 'ultrastore' ); ?></h1>
				</div><!-- .archive-header -->
			<?php endif; ?>

			<?php if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) : ?>
				<div class="archive-header">
					<h1 class="archive-title"><?php esc_html_e( 'Checkout', 'ultrastore' ); ?></h1>
				</div><!-- .archive-header -->
			<?php endif; ?>

			<?php if ( is_account_page() ) : ?>
				<div class="archive-header">
					<?php the_title( '<h1 class="archive-title">', '</h1>' ); ?>
				</div><!-- .archive-header -->
			<?php endif; ?>
		<?php endif; ?>

	<?php
	}

	add_action( 'ultrastore_header', 'ultrastore_archive_header', 10 );

endif;

if ( ! function_exists( 'ultrastore_post_classes' ) ) :
	/**
	 * Adds custom classes to the array of post classes.
	 */
	function ultrastore_post_classes( $classes ) {

		// Replace hentry class with entry.
		$classes[] = 'entry';

		return $classes;
	}

	add_filter( 'post_class', 'ultrastore_post_classes' );

endif;

if ( ! function_exists( 'ultrastore_remove_hentry' ) ) :
	/**
	 * Remove 'hentry' from post_class()
	 */
	function ultrastore_remove_hentry( $class ) {
		$class = array_diff( $class, array( 'hentry' ) );
		return $class;
	}

	add_filter( 'post_class', 'ultrastore_remove_hentry' );

endif;

if ( ! function_exists( 'ultrastore_excerpt_more' ) ) :
	/**
	 * Change the excerpt more string.
	 */
	function ultrastore_excerpt_more( $more ) {
		return '&hellip;';
	}

	add_filter( 'excerpt_more', 'ultrastore_excerpt_more' );

endif;

if ( ! function_exists( 'ultrastore_custom_excerpt_length' ) ) :
	/**
	 * Filter the excerpt length.
	 */
	function ultrastore_custom_excerpt_length( $length ) {

		// Sets default
		$length = 28;

		// Get the user settings
		$setting = get_theme_mod( 'excerpt', 28 );
		if ( 28 != $setting ) {
			$length = $setting;
		}

		return $length;
	}

	add_filter( 'excerpt_length', 'ultrastore_custom_excerpt_length', 999 );

endif;

if ( ! function_exists( 'ultrastore_extend_archive_title' ) ) :
	/**
	 * Extend archive title
	 */
	function ultrastore_extend_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_post_type_archive( 'product' ) ) {
			$default = get_theme_mod( 'shop_title', esc_attr__( 'Products', 'ultrastore' ) );
			$title = $default;
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}
		return $title;
	}

	add_filter( 'get_the_archive_title', 'ultrastore_extend_archive_title' );

endif;

if ( ! function_exists( 'ultrastore_customize_tag_cloud' ) ) :
	/**
	 * Customize tag cloud widget
	 */
	function ultrastore_customize_tag_cloud( $args ) {
		$args['largest']  = 13;
		$args['smallest'] = 13;
		$args['unit']     = 'px';
		$args['number']   = 20;
		return $args;
	}

	add_filter( 'widget_tag_cloud_args', 'ultrastore_customize_tag_cloud' );

endif;

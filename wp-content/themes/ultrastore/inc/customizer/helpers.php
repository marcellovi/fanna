<?php
/**
 * Theme Customizer Helpers
 */

if ( ! function_exists( 'ultrastore_library' ) ) :
	/**
	 * Helper to load custom library
	 */
	function ultrastore_library( $return = NULL ) {

		// Return library templates array
		if ( 'library' == $return ) {
			$templates 		= array( '&mdash; '. esc_html__( 'Select', 'ultrastore' ) .' &mdash;' );
			$get_templates 	= get_posts( array( 'post_type' => 'tj_library', 'numberposts' => -1, 'post_status' => 'publish' ) );

		    if ( ! empty ( $get_templates ) ) {
		    	foreach ( $get_templates as $template ) {
					$templates[ $template->ID ] = $template->post_title;
			    }
			}

			return $templates;
		}

	}
endif;

if ( ! function_exists( 'ultrastore_primary_colors' ) ) :
	/**
	 * Selectors for primary color
	 */
	function ultrastore_primary_colors( $return ) {

		$colors = array(
			'a:hover',
			'a:visited:hover',
			'.menu-primary-items a:hover',
			'.menu-primary-items .sub-menu a:hover',
			'.more-link',
			'.more-link:visited',
			'.contact-info-widget.default i',
			'.entry-meta a:hover',
			'.entry-meta a:visited:hover',
			'.sidebar-footer a:hover',
			'.tag-links a:hover',
			'.post-pagination .post-detail a:hover',
			'.author-bio .description .name a:hover',
			'.author-bio .author-social-links a:hover',
			'.tj-custom-links li a:hover',
			'.tj-custom-links li a:hover:before'
		);

		$backgrounds = array(
			'button',
			'input[type="button"]',
			'input[type="reset"]',
			'input[type="submit"]',
			'.button',
			'.menu-primary-items li.btn a',
			'.contact-info-widget li.skype a',
			'.author-badge'
		);

		$borders = array(
			'.menu-primary-items .sub-menu li:hover',
			'.more-link'
		);

		// Return array
		if ( 'colors' == $return ) {
			return $colors;
		} elseif ( 'backgrounds' == $return ) {
			return $backgrounds;
		} elseif ( 'borders' == $return ) {
			return $borders;
		}

	}
endif;

if ( ! function_exists( 'ultrastore_heading_selector' ) ) :
	/**
	 * Heading selector
	 */
	function ultrastore_heading_selector() {

		$headings = array(
			'h1',
			'h1 a',
			'h1 a:visited',
			'h2',
			'h2 a',
			'h2 a:visited',
			'h3',
			'h3 a',
			'h3 a:visited',
			'h4',
			'h4 a',
			'h4 a:visited',
			'h5',
			'h5 a',
			'h5 a:visited',
			'h6',
			'h6 a',
			'h6 a:visited'
		);

		return $headings;
	}
endif;

if ( ! function_exists( 'ultrastore_button_selector' ) ) :
	/**
	 * Button selector
	 */
	function ultrastore_button_selector() {

		$buttons = array(
			'button',
			'input[type="button"]',
			'input[type="reset"]',
			'input[type="submit"]',
			'.button',
			'.contact-info-widget li.skype a'
		);

		return $buttons;
	}
endif;

if ( ! function_exists( 'ultrastore_forms_selector' ) ) :
	/**
	 * Form selector
	 */
	function ultrastore_forms_selector() {

		$forms = array(
			'form input[type="text"]',
			'form input[type="password"]',
			'form input[type="email"]',
			'form input[type="url"]',
			'form input[type="date"]',
			'form input[type="month"]',
			'form input[type="time"]',
			'form input[type="datetime"]',
			'form input[type="datetime-local"]',
			'form input[type="week"]',
			'form input[type="number"]',
			'form input[type="search"]',
			'form input[type="tel"]',
			'form input[type="color"]',
			'form select',
			'form textarea'
		);

		return $forms;
	}
endif;

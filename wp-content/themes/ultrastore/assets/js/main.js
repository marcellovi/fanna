( function ( $ ) {

	/**
	 * Responsive video
	 */
	function responsiveVideo() {
		$( '.entry, .widget' ).fitVids();
	}

	/**
	 * Search pop up
	 */
	function searchPopup() {
		$( '.search-toggle' ).magnificPopup( {
			type: 'inline',
			mainClass: 'popup-fade',
			closeOnBgClick: false,
			closeBtnInside: false,
			callbacks: {
				open: function () {
					setTimeout( function () {
						$( '.search-popup input' ).focus();
					}, 1000 );
				}
			}
		} );
	}

	/**
	 * Back to top
	 */
	function backToTop() {
		if ( $( '.back-to-top' ).length ) {
			var scrollTrigger = 100,
				backToTop = function () {
					var scrollTop = $( window ).scrollTop();
					if ( scrollTop > scrollTrigger ) {
						$( '.back-to-top' ).addClass( 'show' );
					} else {
						$( '.back-to-top' ).removeClass( 'show' );
					}
				};

			backToTop();
			$( window ).on( 'scroll', function () {
				backToTop();
			} );

			$( '.back-to-top' ).on( 'click', function ( e ) {
				e.preventDefault();
				$( 'html, body' ).animate( {
					scrollTop: 0
				}, 700 );
			} );

		}
	}

	/**
	 * Mobile nav
	 */
	function mobileNav() {

		var $site = $( '.site' );

		$( '.menu-toggle' ).on( 'click', function ( e ) {
			e.preventDefault();

			if ( $site.hasClass( 'show-mobile-nav' ) ) {
				$site.removeClass( 'show-mobile-nav' );
			} else {
				$site.addClass( 'show-mobile-nav' );
			}
		} )

	}

	/**
	 * Page loader
	 */
	function loader() {
		setTimeout( function () {
			$( '.page-loading' ).fadeOut( 'fast', function () {} );
		}, 100 );
	}

	// Document ready
	$( function () {
		responsiveVideo();
		searchPopup();
		backToTop();
		mobileNav();
		loader();
	} );

}( jQuery ) );

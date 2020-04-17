'use strict';

jQuery( document ).ready( function( $ ) {
	var woosq_products = [],
		woosq_ids = [];
	$( '.woosq-btn' ).each( function() {
		var product_id = $( this ).attr( 'data-id' );
		if ( - 1 === $.inArray( product_id, woosq_ids ) ) {
			woosq_ids.push( product_id );
			woosq_products.push( {src: woosq_vars.ajax_url + '?product_id=' + product_id} );
		}
	} );

	$( 'body' ).on( 'click touch', '.woosq-btn', function( e ) {
		var product_id = $( this ).attr( 'data-id' );
		if ( - 1 === $.inArray( product_id, woosq_ids ) ) {
			woosq_ids.push( product_id );
			woosq_products.push( {src: woosq_vars.ajax_url + '?product_id=' + product_id} );
		}

		var effect = $( this ).attr( 'data-effect' );
		var index = woosq_get_key( woosq_products, 'src', woosq_vars.ajax_url + '?product_id=' + product_id );
		$.magnificPopup.open( {
			items: woosq_products,
			type: 'ajax',
			mainClass: 'mfp-woosq',
			removalDelay: 160,
			gallery: {
				enabled: true,
			},
			ajax: {
				settings: {
					type: 'GET',
					data: {
						action: 'woosq_quickview'
					}
				}
			},
			callbacks: {
				beforeOpen: function() {
					if ( typeof effect !== typeof undefined && effect !== false ) {
						this.st.mainClass = 'mfp-woosq ' + effect;
					} else {
						this.st.mainClass = 'mfp-woosq ' + woosq_vars.effect;
					}
				},
				ajaxContentAdded: function() {
					var form_variation = $( '#woosq-popup' ).find( '.variations_form' );
					form_variation.each( function() {
						$( this ).wc_variation_form();
					} );
					$( '#woosq-popup .summary-content' ).perfectScrollbar( {theme: 'wpc'} );
					// slick slider
					$( '#woosq-popup .thumbnails' ).on( 'init', function( event, slick, direction ) {
						// check to see if there are one or less slides
						if ( !(
							$( '#woosq-popup .thumbnails .slick-slide' ).length > 1
						) ) {
							// remove arrows
							$( '.slick-dots' ).hide();
						}
					} );
					$( '#woosq-popup .thumbnails' ).slick( {
						slidesToShow: 1,
						slidesToScroll: 1,
						dots: true,
						arrows: true,
					} );
					$( document.body ).trigger( 'woosq_loaded', [product_id] );
				},
				afterClose: function() {
					$( document.body ).trigger( 'woosq_close', [product_id] );
				}
			}
		}, index );
		$( document.body ).trigger( 'woosq_open', [product_id] );
		e.preventDefault();
	} );
} );

function woosq_get_key( array, key, value ) {
	for ( var i = 0; i < array.length; i ++ ) {
		if ( array[i][key] === value ) {
			return i;
		}
	}
	return - 1;
}
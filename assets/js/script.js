( function ( $, window ) {
	let fomatNumberTyping = () => {
		$( '.crm-action .formatable' ).on( 'keyup', function(e) {

			let n = parseFloat( $(this).val().replace( /\D/g, '' ), 10 );

			if ( ! isNaN( n ) ) {
				$(this).val( n.toLocaleString() );
				$(this).attr( 'data-number', n )
			}

		} );
	}

	function formatNumber( n, x, s, c, number ) {
		var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
			num = number.toFixed( Math.max( 0, ~~n ) );
		return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
	}

	fomatNumberTyping();


	let scriptJS = {
		init: function() {
			// scriptJS.listJS();
		},
		listJS: function() {
			var options = {
				valueNames: [ 'product__id', 'searchable' ],
				page: 10,
				pagination: [
					{ item: '<li class="rounded-md focus:outline-none focus:shadow-outline-purple"><a class="page px-3 py-1" href="#"></a></li>', }
				]
			};
			if ( $( 'tbody.list' ).html().trim() !== '' ) {
				var itemList = new List( 'crm-table', options );
			}
		},
		showToast: function( title ) {
			const toast =
			`<div class="toast">
				<div class="img-toast">
					<span class="dashicons dashicons-yes-alt"></span>
				</div>
				<p class="title">${title}</p>
			</div>`;

			$( 'body' ).append( toast ).fadeTo( 3000, 1, () => {
				$( '.toast' ).remove();
			} );
		},
		onShowPopup: function(e) {
			e.stopPropagation();
			let popup = $(this).attr( 'data-popup' );
			$( `#${popup}` ).addClass( 'current' );
		},

		onClosePopup: function(e) {
			if( $( e.target ).parents( '.modal-dialog' ).length === 0 || e.target.classList.contains( 'btn-close' ) ) {
				$( '.modal' ).removeClass( 'current' );
			}
		}
	}
	scriptJS.init();
	// $( document ).ready( function() {
	// 	scriptJS.init();
	// } );

	// $( window ).on( 'load', function() {
	// 	scriptJS.init();
	// } );

	// Export cart object.
	window.scriptJS = scriptJS;
} )( jQuery, window );
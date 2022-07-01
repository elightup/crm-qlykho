( function ( $, window ) {
	let fomatNumberTyping = () => {
		$( '.crm-action input[type="number"]' ).on( 'keyup', function() {
			// let n = parseInt( $(this).val().replace( /\D/g, '' ), 10 );
			// let n = $( this ).val().replace( /\D/g, '' );
			let n = formatNumber( 0, 3, '.', ',', parseFloat( $( this ).val() ) );

			console.log('n', n);
			$( this ).val( n );
		} );
	}

	function formatNumber( n, x, s, c, number ) {
		var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
			num = number.toFixed( Math.max( 0, ~~n ) );
		return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
	}

	// fomatNumberTyping();


	let scriptJS = {
		init: function() {
			scriptJS.listJS();
		},
		listJS: function() {
			var options = {
				valueNames: [ 'searchable' ],
				page: 10,
				pagination: [
					{ item: '<li class="rounded-md focus:outline-none focus:shadow-outline-purple"><a class="page px-3 py-1" href="#"></a></li>', }
				]
			};
			if ( $( 'tbody.list' ).html().trim() !== '' ) {
				var itemList = new List( 'crm-table', options );
			}
		},
		showPopup: function( title ) {
			const toast =
			`<div class="toast">
				<div class="img-toast">
					<span class="dashicons dashicons-yes-alt"></span>
				</div>
				<p class="title">${title}</p>
			</div>`;

			$( 'body' ).append( toast ).fadeTo( 2000, 1, () => {
				$( '.toast' ).remove();
			} );
		},
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
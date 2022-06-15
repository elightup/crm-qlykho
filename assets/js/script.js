jQuery( function ( $ ) {
	let listJS = () => {
		var options = {
			valueNames: [ 'searchable' ],
			page: 10,
			pagination: [
				{ item: '<li class="rounded-md focus:outline-none focus:shadow-outline-purple"><a class="page px-3 py-1" href="#"></a></li>', }
			]
		};

		var itemList = new List( 'crm-table', options );
	};

	listJS();
} );
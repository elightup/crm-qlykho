jQuery( function ( $ ) {
	let listJS = () => {
		var options = {
			valueNames: [ 'product__name' ],
			page: 10,
			pagination: [
				{ item: '<li class="rounded-md focus:outline-none focus:shadow-outline-purple"><a class="page px-3 py-1" href="#"></a></li>', }
			]
		};
		var optionsProduct = {
			valueNames: [ 'name_kho' ],
			page: 10,
			pagination: [
				{
					item: '<li class="rounded-md focus:outline-none focus:shadow-outline-purple"><a class="page px-3 py-1" href="#"></a></li>',
				}
			]
		};
		//console.log( optroduct );

		var productList = new List( 'crm-table', options );
		var productKho = new List( 'crm-table', optionsProduct );
	};

	listJS();
} );
jQuery( function ( $ ) {
	const $d = $( document );
	$( '.add_product_kho' ).on( 'click', function ( e ) {
		e.preventDefault();
		var newElem = $( this ).closest( '.crm-action' ).find( '.add-product' ).last().clone().appendTo( '.add-product__inner' );
		$( '.hidden-itemname' ).each( function ( index ) {
			newElem.children( '.hidden-itemname' ).children( 'option[value="' + $( this ).val() + '"]' ).remove();
		} );
	} );

	let kho = {
		init: function () {
			kho.clickSave();
			kho.editButton();
			kho.removeButton();
			kho.clearButton();
		},
		htmlLayout: function ( data ) {
			//console.log( 'data', data );

			return `
			<tr class="text-gray-700 dark:text-gray-400" data-kho="${ data.id }">
				<td class="px-4 py-3">${ data.id }</td>
				<td data-name-kho="${ data.ten }" class="name_kho px-4 py-3">${ data.ten }</td>
				<td data-user="${ data.user }" data-name-user="${ data.user_name }" class="name_user px-4 py-3">${ data.user_name }</td>
				<td data-email="${ data.email }" class="email_user px-4 py-3">${ data.email }</td>
				<td data-phone="${ data.phone }" class="phone_user px-4 py-3">${ data.phone }</td>
				<td class="action px-4 py-3">
					<div class="flex items-center space-x-4 text-sm">
						<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
							</svg>
						</button>
						<button data-kho="${ data.id }" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</button>
						<a data-kho="${ data.id }" href="${ data.href }&id=${ data.id }" class="button-view flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="View">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
							</svg>
						</a>
					</div>
				</td>
			</tr>
			`;
		},
		clickSave: function () {
			$d.on( 'click', '.btn_add_kho', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				console.log( 'id_kho2', id_kho );
				let href = $( '.action_btn' ).data( 'href' );
				let ten = $( 'input[name=ten]' ),
					user = $( 'select[name="user_name"] option:selected' );

				if ( user.val() === '' ) {
					user_name = '';
				} else {
					user_name = user.text();
				}
				if ( $( this ).hasClass( 'edit' ) ) {
					let data_kho = {
						id: id_kho,
						ten: ten.val(),
						user: user.val(),
						user_name: user_name,
						href: href,
						phone: user.attr( 'phone' ),
						email: user.attr( 'email' ),
					};
					//console.log( 'before', data_kho );
					kho.edit( data_kho );

				} else {
					kho.add( ten, user, href );
				}
			} );
		},
		add: function ( ten, user, href ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'them_kho',
				ten: ten.val(),
				user: user.val(),
				user_name: user.text(),
				phone: user.attr( 'phone' ),
				email: user.attr( 'email' ),
				href: href,
			}, response => {
				if ( !response.success ) {
					$( '.message-error' ).remove();
					$( '.crm-action' ).append( '<p class="message-error text-red-600">' + response.data + '</p>' );
					return;
				}
				console.log( response.data );
				let data_kho = {
					id: response.data,
					ten: ten.val(),
					user: user.val(),
					user_name: user.text(),
					phone: user.attr( 'phone' ),
					email: user.attr( 'email' ),
					href: href,
				};
				$( '.data-list' ).prepend( kho.htmlLayout( data_kho ) );
				kho.showPopup();
				$( 'input[name="ten"]' ).val( '' );
				$( 'select[name="user_name"] option:selected' ).val( '' );
				$( 'select[name="user_name"] option:selected' ).text( 'Ch???n user' );
				$( '.message-error' ).remove();
			} );
		},
		clearButton: function () {
			$d.on( 'click', '.btn-clear-kho', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				$( '.title-action-kho' ).text( 'Th??m th??ng tin kho' );
				kho.clearInput();
			} );
		},
		edit: function ( data_kho ) {
			console.log( 'edit', data_kho );
			$.post( ProductParams.ajaxUrl, {
				action: 'edit_kho',
				id: data_kho.id,
				ten_kho: data_kho.ten,
				user: data_kho.user,
				user_name: data.user_name,
			}, response => {
				if ( !response.success ) {
					//console.log( 'ss' );
					$( '.message-error' ).remove();
					$( '.crm-action' ).append( '<p class="message-error text-red-600">' + response.data + '</p>' );
					return;
				}
				kho.showPopupEdit();
				$( '.title-action-kho' ).text( 'Th??m th??ng tin kho' );
				$( 'input[name="ten"]' ).val( '' );
				$( 'select[name="user_name"] option:selected' ).val( '' );
				$( '.btn_add_kho' ).removeClass( 'edit' );
				$( '.btn_add_kho' ).text( 'Th??m' );
				$( '.btn_add_kho' ).removeAttr( 'data-kho' );
				let tr = $( 'tr[data-kho=' + data_kho.id + ']' );
				tr.replaceWith( kho.htmlLayout( data_kho ) );
			} );
		},
		showPopup: function () {
			const toast =
				`<div class="toast">
				<p class="title">???? th??m Kho th??nh c??ng</p>
				<div class="img-toast">
					<span class="dashicons dashicons-yes-alt"></span>
				</div>
			</div>`;

			$( 'body' ).append( toast ).fadeTo( 2000, 1, () => {
				$( '.toast' ).remove();
			} );
		},
		showPopupEdit: function () {
			const toast =
				`<div class="toast">
				<p class="title">S???a kho th??nh c??ng</p>
				<div class="img-toast">
					<span class="dashicons dashicons-yes-alt"></span>
				</div>
			</div>`;

			$( 'body' ).append( toast ).fadeTo( 2000, 1, () => {
				$( '.toast' ).remove();
			} );
		},
		editButton: function () {
			$d.on( 'click', '.data-list .button-edit', function ( e ) {

				let parent = $( this ).parents( 'tr' ),
					id_kho = parent.data( 'kho' ),
					ten = parent.find( '.name_kho' ),
					email = parent.find( '.email_user' ),
					phone = parent.find( '.phone_user' ),
					id_user = parent.find( '.name_user' );
				$( 'input[name="ten"]' ).val( ten.text() );
				$( 'select[name="user_name"] option:selected' ).val( id_user.data( 'user' ) );
				$( 'select[name="user_name"] option:selected' ).text( id_user.data( 'name-user' ) );
				$( 'select[name="user_name"] option:selected' ).attr( 'phone', phone.data( 'phone' ) );
				$( 'select[name="user_name"] option:selected' ).attr( 'email', email.data( 'email' ) );
				$( '.message-error' ).remove();
				$( '.btn_add_kho' ).addClass( 'edit' );
				$( '.btn_add_kho' ).text( 'L??u' );
				$( '.btn_add_kho' ).attr( 'data-kho', id_kho );
				$( '.btn-clear-kho' ).removeClass( 'disabled' );
				$( '.btn-clear-kho' ).removeAttr( 'disabled' );
				$( '.btn-clear-kho' ).attr( 'data-kho', id_kho );
				$( '.title-action-kho' ).text( 'S???a th??ng tin kho' );
			} );
		},
		remove: function ( id ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'xoa_kho',
				id_kho: id,
			}, response => {
				if ( !response.success ) {
					return;
				}
				$( '.action_user' ).html( response.data );
				let tr = $( 'tr[data-kho=' + id + ']' );
				tr.remove();
				kho.clearInput();
			} );
		},
		removeButton: function () {
			$d.on( 'click', '.data-list .action .button-remove', function ( e ) {
				let parent = $( this ).parents( 'tr' ),
					id_kho = parent.data( 'kho' );

				$( '.confirm-remove' ).addClass( 'confirmed' );
				$( '.confirm-remove' ).attr( 'data-id', id_kho );
				kho.confirmRemove( id_kho );
			} );
		},
		confirmRemove: function () {
			$d.on( 'click', '.confirm-remove', function () {
				let id_kho = $( this ).attr( 'data-id' );
				kho.remove( id_kho );
			} );
		},
		clearInput: function () {
			let ten = $( 'input[name=ten]' ),
				user = $( 'select[name="user_name"]' );
			ten.val( '' );
			user.val( '' );
		},
	};


	let product_kho = {
		init: function () {
			product_kho.clickSave();
			product_kho.editButton();
			product_kho.removeButton();
			product_kho.clearButton();
			product_kho.searchButton();
		},
		htmlLayout: function ( data ) {
			products = data.products;
			inner = ``;
			$.each( products, function ( i, val ) {
				inner = inner + `
				<tr class="text-gray-700 dark:text-gray-400" product-id="${ products[ i ][ 'id_product' ] }" >
					<td class="px-4 py-3">#${ products[ i ][ 'id_product' ] }</td>
					<td product-name="${ products[ i ][ 'name_sp' ] }" class="name_product px-4 py-3">${ products[ i ][ 'name_sp' ] }</td>
					<td number-history="${ products[ i ][ 'number_history' ] }" class="number-history px-4 py-3">${ products[ i ][ 'number_history' ] }</td>
					<td product-number="${ products[ i ][ 'number' ] }" class="product-number px-4 py-3">${ products[ i ][ 'number' ] }</td>

					<td class="action px-4 py-3">
						<div class="flex items-center space-x-4 text-sm">
							<button data-kho="${ data.id_kho }" class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
								<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
									<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
								</svg>
							</button>
							<button data-kho="${ data.id_kho }" @click="openList" class="remove-product flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
								<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>
					</td>
				</tr>
				`;
			} );
			return inner;
		},
		edit: function ( data_sp ) {
			console.log( 'edit' );
			let date = GetTodayDate();
			$.post( ProductParams.ajaxUrl, {
				action: 'edit_product_kho',
				id_kho: data_sp.id_kho,
				date: date,
				products: data_sp.products,
			}, response => {
				if ( !response.success ) {
					return;
				}
				$( '.add-product__inner' ).html( response.data );
				$( '.add_product_kho[data-kho=' + data_sp.id_kho + ']' ).removeClass( 'disabled' );
				$( '.add_product_kho[data-kho=' + data_sp.id_kho + ']' ).removeAttr( "disabled" );
				$( '.title-action' ).text( 'Th??ng tin s???n ph???m' );
				let tr = $( 'tr[product-id=' + data_sp.id_sp + ']' );
				tr.replaceWith( product_kho.htmlLayout( data_sp ) );
			} );
		},
		addsp: function ( id_kho, products ) {
			let date = GetTodayDate();
			//console.log( 'date', date );
			if ( products.length !== 0 ) {
				$.post( ProductParams.ajaxUrl, {
					action: 'them_product_kho',
					id_kho: id_kho,
					date: date,
					products: products,
				}, response => {
					if ( !response.success ) {
						$( '.crm-action' ).append( '<p class="message-error">' + response.data + '</p>' );
						return;
					}
					let data_sp_kho = {
						id: response.data,
						products: products,
						id_kho: id_kho,
					};
					$( '.add-product__inner' ).html( response.data );
					$( '.data-list' ).prepend( product_kho.htmlLayout( data_sp_kho ) );
					product_kho.showPopup();
					$( '.message-error' ).remove();
				} );
			}
		},
		clickSave: function () {
			$d.on( 'click', '.save_product', function () {
				var id_kho = $( this ).attr( 'data-kho' );
				var products = [];
				$( this ).closest( '.crm-action' ).find( ".add-product" ).each( function ( index ) {
					var id_product = $( this ).find( 'select[name="product_name"] option:selected' ).val();
					var name_sp = $( this ).find( 'select[name="product_name"] option:selected' ).text();
					var number = $( this ).find( '#number_product' ).val();
					console.log( 'number', number );
					var variable = $( '.save_product' ).attr( 'number-history' );
					if ( variable === undefined ) {
						number_history = number;
					} else {
						number_history = variable;
					}
					if ( id_product !== '' ) {
						var product = {
							'id_product': id_product,
							'name_sp': name_sp,
							'number': number,
							'number_history': number_history,
						};
						products.push( product );
					}
				} );
				//console.log( 'products', products );
				if ( $( this ).hasClass( 'edit' ) ) {

					let data_sp = {
						id_kho: id_kho,
						products: products,
						id_sp: products[ 0 ][ 'id_product' ],
					};
					product_kho.edit( data_sp );
				} else {
					product_kho.addsp( id_kho, products );
				}
			} );
		},
		editButton: function () {
			$d.on( 'click', '.data-list .button-edit', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				let parent = $( this ).parents( 'tr' ),
					id_product = parent.attr( 'product-id' ),
					name_product = parent.find( '.name_product' ),
					number_product = parent.find( '.product-number' ),
					number_history = parent.find( '.number-history' );

				$( 'input[name="number_product"]' ).val( number_product.attr( 'product-number' ) );
				$( 'select[name="product_name"] option:selected' ).text( name_product.attr( 'product-name' ) );
				$( 'select[name="product_name"] option:selected' ).val( id_product );
				$( 'select[name="product_name"]' ).attr( "disabled", true );
				$( '.save_product' ).addClass( 'edit' );
				$( '.save_product' ).attr( 'data-id', id_product );
				$( '.save_product' ).attr( 'number-history', number_history.attr( 'number-history' ) );
				$( '.add_product_kho[data-kho=' + id_kho + ']' ).addClass( 'disabled' );
				$( '.add_product_kho[data-kho=' + id_kho + ']' ).attr( "disabled", true );
				$( '.title-action' ).text( 'S???a th??ng tin s???n ph???m' );
				$( '.btn-clear[data-kho=' + id_kho + ']' ).removeClass( 'disabled' );
				$( '.btn-clear[data-kho=' + id_kho + ']' ).removeAttr( "disabled" );
			} );
		},
		clearButton: function () {
			$d.on( 'click', '.btn-clear', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				$( 'input[name="number_product"]' ).val( '' );
				$( 'select[name="product_name"] option:selected' ).text( 'Ch???n s???n ph???m' );
				$( 'select[name="product_name"] option:selected' ).val( '' );
				$( '.add_product_kho[data-kho=' + id_kho + ']' ).removeClass( 'disabled' );
				$( '.add_product_kho[data-kho=' + id_kho + ']' ).removeAttr( "disabled" );
				$( 'select[name="product_name"]' ).removeAttr( "disabled" );
				$( '.save_product' ).removeClass( 'edit' );
				$( '.save_product' ).removeAttr( 'data-id' );
				$( '.save_product' ).removeAttr( 'number-history' );
			} );
		},
		remove: function ( id_product, id_kho ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'xoa_sp_kho',
				id_sp: id_product,
				id_kho: id_kho,
			}, response => {
				if ( !response.success ) {
					return;
				}
				$( '.add-product__inner' ).html( response.data );
				let tr = $( 'tr[product-id=' + id_product + ']' );
				tr.remove();
			} );
		},
		removeButton: function () {
			$d.on( 'click', '.remove-product', function () {
				var id_kho = $( this ).attr( 'data-kho' );
				let parent = $( this ).parents( 'tr' ),
					id_product = parent.attr( 'product-id' );
				$( '.confirm-remove' ).addClass( 'confirmed' );
				$( '.confirm-remove' ).attr( 'data-id', id_product );
				$( '.confirm-remove' ).attr( 'data-kho', id_kho );
				product_kho.confirmRemove();
			} );
		},
		confirmRemove: function () {
			$d.on( 'click', '.confirm-remove', function () {
				let id_kho = $( this ).attr( 'data-kho' ),
					id_product = $( this ).attr( 'data-id' );
				product_kho.remove( id_product, id_kho );
			} );
		},
		showPopup: function () {
			const toast =
				`<div class="toast">
				<p class="title">???? th??m s???n ph???m th??nh c??ng</p>
				<div class="img-toast">
					<span class="dashicons dashicons-yes-alt"></span>
				</div>
			</div>`;

			$( 'body' ).append( toast ).fadeTo( 2000, 1, () => {
				$( '.toast' ).remove();
			} );
		},
		handleSearch: function ( id_kho, start_date, end_date ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'search_products',
				start_date: start_date,
				end_date: end_date,
				id_kho: id_kho,
			}, response => {
				if ( !response.success ) {
					return;
				}
				$( '.data-list' ).html( response.data );
			} );
		},
		searchButton: function () {
			$( '.submit-search' ).on( 'click', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				let start_date = $( '#start_date' ).val(),
					end_date = $( '#end_date' ).val();
				var tdate = new Date();
				var dd = tdate.getDate(); //yields day
				var MM = tdate.getMonth(); //yields month
				var yyyy = tdate.getFullYear(); //yields year
				var date = yyyy + "-" + ( MM + 1 ) + "-" + ( dd + 1 );
				var lastMonth = yyyy + "-" + ( MM ) + "-" + dd;
				if ( end_date === '' ) {
					end_date = date;
				}
				if ( start_date === '' ) {
					start_date = lastMonth;
				}
				product_kho.handleSearch( id_kho, start_date, end_date );
			} );
		}
	};

	kho.init();
	product_kho.init();
} );
function GetTodayDate() {
	var tdate = new Date();
	var dd = tdate.getDate(); //yields day
	var MM = tdate.getMonth(); //yields month
	var yyyy = tdate.getFullYear(); //yields year
	var h = tdate.getHours();
	var p = tdate.getMinutes();
	var s = tdate.getSeconds();
	var currentDate = yyyy + "-" + ( MM + 1 ) + "-" + dd + " " + h + ":" + p + ":" + s;

	return currentDate;
}

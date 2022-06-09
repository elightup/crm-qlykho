jQuery( function ( $ ) {
	const $d = $( document );
	$( '.add_product_kho' ).on( 'click', function ( e ) {
		e.preventDefault();
		$( this ).closest( '.modal-body' ).find( '.add-product' ).last().clone().appendTo( '.modal-body__product' );
	} );

	let kho = {
		init: function () {
			kho.onSave();
			kho.editButton();
			kho.removeButton();
		},
		htmlLayout: function ( data ) {
			return `
			<tr class="text-gray-700 dark:text-gray-400" data-kho="${ data.id }">
				<td class="px-4 py-3">#${ data.id }</td>
				<td data-name-kho="${ data.ten }" class="px-4 py-3">${ data.ten }</td>
				<td data-user="${ data.user }" data-name-user="${ data.user_name }" class="name_user px-4 py-3">${ data.user_name }</td>
				<td class="email_user px-4 py-3"></td>
				<td class="phone_user px-4 py-3"></td>
				<td class="action px-4 py-3">
					<div class="flex items-center space-x-4 text-sm">
						<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
							</svg>
						</button>
						<button data-kho="${ data.id }" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</button>
						<button data-kho="<?= esc_attr( $warehouse->id ) ?>" class="button-view flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="View">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
							</svg>
						</button>
					</div>
				</td>
			</tr>
			`;
		},
		onSave: function () {
			$d.on( 'click', '.btn_add_kho', function () {
				let ten = $( 'input[name=ten]' ),
					user = $( 'select[name="user_name"] option:selected' ),
					user_name = $( 'select[name="user_name"] option:selected' );
				if ( $( this ).hasClass( 'edit' ) ) {
					let data_kho = {
						id: $( this ).data( 'id' ),
						ten: ten.val(),
						user: user.val(),
						user_name: user_name.text(),
					};
					kho.edit( data_kho );
				} else {
					kho.add( ten, user, user_name );
				}
			} );
		},
		add: function ( ten, user, user_name ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'them_kho',
				ten: ten.val(),
				user: user.val(),
				user_name: user_name.text(),
			}, response => {
				console.log( data.ten );
				if ( !response.success ) {
					$( '.crm-action' ).append( '<p class="message-error">' + response.data + '</p>' );
					return;
				}
				let data_kho = {
					id: response.data,
					ten: ten.val(),
					user: user.val(),
					user_name: user_name.text(),
				};
				$( '.data-list' ).prepend( kho.htmlLayout( data_kho ) );
				kho.showPopup();
				ten.val( '' );
				user.val( '' );
				user_name.text( 'Chọn user' );
				$( '.message-error' ).remove();
			} );
		},
		edit: function ( data_kho ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'edit_kho',
				id: data_kho.id,
				ten_kho: data_kho.ten,
				user: data_kho.user,
				user_name: data.user_name,
			}, response => {
				if ( !response.success ) {
					return;
				}
				let tr = $( 'tr[data-kho=' + data_kho.id + ']' );
				tr.replaceWith( kho.htmlLayout( data_kho ) );
			} );
		},
		showPopup: function () {
			const toast =
				`<div class="toast">
				<p class="title">Đã thêm Kho thành công</p>
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
					id_user = parent.find( '.name_user' );
				$( 'input[name="ten"]' ).val( ten.text() );
				$( 'select[name="user_name"] option:selected' ).val( id_user.data( 'user' ) );
				$( 'select[name="user_name"] option:selected' ).text( id_user.data( 'name-user' ) );

				$( '.btn_add_kho' ).addClass( 'edit' );
				$( '.btn_add_kho' ).attr( 'data-id', id_kho );
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
				console.log( id_kho );
				kho.remove( id_kho );
			} );
		},
		clearInput: function () {
			let ten = $( 'input[name=ten]' ),
				user = $( 'select[name="user_name"] option:selected' ),
				user_name = $( 'select[name="user_name"] option:selected' );
			ten.val( '' );
			user.val( '' );
			user_name.text();
		},
	};


	let product_kho = {
		init: function () {
			product_kho.onSave();
			product_kho.editButton();
			product_kho.removeButton();
		},
		htmlLayout: function ( data ) {
			products = data.products;
			inner = ``;
			$.each( products, function ( i, val ) {
				inner = inner + `
				<div class="modal-body__inner">
					<div class="modal-body__id px-4 py-3">#${ products[ i ][ 'id_product' ] }</div>
					<div data-name="${ products[ i ][ 'name_sp' ] }" class="product__name px-4 py-3">${ products[ i ][ 'name_sp' ] }</div>
					<div data-number="${ products[ i ][ 'number' ] }" class="product__number px-4 py-3">${ products[ i ][ 'number' ] }</div>
					<div class="modal-body__action px-4 py-3">
						<div class="flex items-center space-x-4 text-sm">
							<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
								<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
									<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
								</svg>
							</button>
							<button data-kho="<?= esc_attr( $warehouse->id ) ?>" @click="openList" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
								<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>
					</div>
				</div>
				`;
			} );
			return inner;
		},
		edit: function ( data_sp ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'edit_product_kho',
				id_kho: data_sp.id_kho,
				products: data_sp.products,
			}, response => {
				if ( !response.success ) {
					return;
				}
				$( '.modal-body__product' ).html( response.data );
				$( '.add_product_kho[data-kho=' + data_sp.id_kho + ']' ).removeClass( 'disabled' );
				$( '.add_product_kho[data-kho=' + data_sp.id_kho + ']' ).removeAttr( "disabled" );
				let tr = $( '.modal-body__inner[data-product=' + data_sp.id_sp + ']' );
				tr.replaceWith( product_kho.htmlLayout( data_sp ) );
			} );
		},
		addsp: function ( id_kho, products ) {
			console.log( products );
			if ( products.length !== 0 ) {
				$.post( ProductParams.ajaxUrl, {
					action: 'them_product_kho',
					id_kho: id_kho,
					products: products,
				}, response => {
					if ( !response.success ) {
						$( '.crm-action' ).append( '<p class="message-error">' + response.data + '</p>' );
						return;
					}
					let data_sp_kho = {
						id: response.data,
						products: products,
					};
					$( '.modal-body__product' ).html( response.data );
					$( '.modal-body__content[data-kho=' + id_kho + ']' ).append( product_kho.htmlLayout( data_sp_kho ) );
					name_sp = $( this ).find( '#product_name option:selected' ).text( 'Chọn sản phẩm' );
					number = $( this ).find( '#number_product' ).val( '' );
					$( '.message-error' ).remove();
				} );
			}
		},
		onSave: function () {
			$d.on( 'click', '.save_product', function () {
				var id_kho = $( this ).attr( 'data-kho' );
				var products = [];
				$( this ).closest( '.modal-body' ).find( ".add-product" ).each( function ( index ) {
					var id_product = $( this ).find( 'select[name="product_name"] option:selected' ).val();
					console.log( 'id_product', id_product );
					var name_sp = $( this ).find( 'select[name="product_name"] option:selected' ).text();
					var number = $( this ).find( '#number_product' ).val();
					if ( id_product !== '' ) {
						var product = {
							'id_product': id_product,
							'name_sp': name_sp,
							'number': number
						};
						products.push( product );
					}
				} );
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
			$( '.modal-body .edit-product' ).on( 'click', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				//console.log( id_kho );
				let parent = $( this ).parents( '.modal-body__inner' ),
					id_product = parent.data( 'product' ),
					name_product = parent.find( '.product__name' ),
					number_product = parent.find( '.product__number' );
				//console.log( name_product.data( 'name' ) );
				$( 'input[name="number_product"]' ).val( number_product.data( 'number' ) );
				$( 'select[name="product_name"] option:selected' ).text( name_product.data( 'name' ) );
				$( 'select[name="product_name"] option:selected' ).val( id_product );
				$( '.save_product' ).addClass( 'edit' );
				$( '.save_product' ).attr( 'data-id', id_product );
				$( '.add_product_kho[data-kho=' + id_kho + ']' ).addClass( 'disabled' );
				$( '.add_product_kho[data-kho=' + id_kho + ']' ).attr( "disabled", true );
			} );
		},
		remove: function ( id, id_kho ) {
			console.log( id_kho );
			$.post( ProductParams.ajaxUrl, {
				action: 'xoa_sp_kho',
				id_sp: id,
				id_kho: id_kho,
			}, response => {
				if ( !response.success ) {
					return;
				}
				$( '.add-product' ).html( response.data );
				let tr = $( '.modal-body__content[data-kho=' + id_kho + '] .modal-body__inner[data-product=' + id + ']' );
				tr.remove();
			} );
		},
		removeButton: function () {
			$( '.modal-body .remove-product' ).on( 'click', function ( e ) {
				var id_kho = $( this ).closest( '.modal' ).attr( 'data-modal' );
				let parent = $( this ).parents( '.modal-body__inner' ),
					id_product = parent.data( 'product' );
				$( '.confirm-remove' ).addClass( 'confirmed' );
				$( '.confirm-remove' ).attr( 'data-id', id_product );
				$( '.confirm-remove' ).attr( 'data-kho', id_kho );
				product_kho.confirmRemove( id_product, id_kho );
			} );
		},
		confirmRemove: function () {
			$( '.confirm-remove' ).on( 'click', function () {
				let id_kho = $( this ).attr( 'data-kho' );
				let id_product = $( this ).attr( 'data-id' );
				product_kho.remove( id_product, id_kho );
			} );
		}
	};

	function modal_popup() {
		$( '.data-list .button-view' ).on( 'click', function () {
			var modal_id = $( this ).attr( 'data-kho' );

			$( '.data-list .button-view' ).removeClass( 'current' );
			$( '.modal' ).removeClass( 'current' );

			$( this ).addClass( 'current' );
			$( "#modal_" + modal_id ).addClass( 'current' );;
		} );
		$( '.btn-close' ).on( 'click', function () {
			$( '.modal' ).removeClass( 'current' );
		} );
	}


	kho.init();
	product_kho.init();
	modal_popup();
} );


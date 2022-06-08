jQuery( function( $ ) {
	const $d = $( document );
	let product = {
		init: function() {
			product.onSave();
			product.editButton();
			product.removeButton();
		},
		htmlLayout: function(data) {
			let gia_niem_yet    = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_niem_yet ) ),
			    gia_ban_le      = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_ban_le ) ),
			    gia_ban_buon    = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_ban_buon ) ),
			    thongso_kythuat = data.thongso_kythuat.substr( 0, 40 );
			return `
			<tr class="text-gray-700 dark:text-gray-400" data-product="${data.id}">
				<td class="px-4 py-3">#${data.id}</td>
				<td data-link-image="${data.hinhanh}" class="product__thumbnail px-4 py-3">
					<div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
						<img class="object-cover w-full h-full rounded-full border-0" src="${data.hinhanh}">
					</div>
				</td>
				<td class="product__name">${data.ten_sp}</td>
				<td class="product__gia-niem-yet px-4 py-3" data-gia-niem-yet="${data.gia_niem_yet}">${gia_niem_yet} ₫</td>
				<td class="product__gia-ban-le px-4 py-3" data-gia-ban-le="${data.gia_ban_le}">${gia_ban_le} ₫</td>
				<td class="product__gia-ban-buon px-4 py-3" data-gia-ban-buon="${data.gia_ban_buon}">${gia_ban_buon} ₫</td>
				<td class="product-thongso px-4 py-3">${thongso_kythuat} ...</td>
				<td class="px-4 py-3">
					<div class="flex items-center space-x-4 text-sm">
						<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
							</svg>
						</button>
						<button data-product="${data.id}" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</button>
					</div>
				</td>
			</tr>
			`;
		},

		add: function( ten, gia_niem_yet, gia_ban_le, gia_ban_buon, thongso, hinh_anh ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'them_san_pham',
				ten: ten.val(),
				gia_niem_yet: gia_niem_yet.val(),
				gia_ban_le: gia_ban_le.val(),
				gia_ban_buon: gia_ban_buon.val(),
				thongso: thongso.val(),
				hinh_anh: hinh_anh.val(),
			}, response => {
				if ( ! response.success ) {
					$( '.message-error' ).remove();
					$( '.crm-action' ).append( '<p class="message-error text-xs text-red-600 dark:text-red-400">' + response.data + '</p>' );
					return;
				}
				let data_sp = {
					id              : response.data,
					ten_sp          : ten.val(),
					gia_niem_yet    : gia_niem_yet.val(),
					gia_ban_le      : gia_ban_le.val(),
					gia_ban_buon    : gia_ban_buon.val(),
					thongso_kythuat : thongso.val(),
					hinhanh         : hinh_anh.val(),
				}
				$( '.data-list' ).prepend( product.htmlLayout( data_sp ) );
				product.showPopup();
				product.clearInput();

				ten.focus();

				$( '.message-error' ).remove();
			} );
		},
		edit: function( data_sp ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'edit_san_pham',
				id: data_sp.id,
				ten_sp: data_sp.ten_sp,
				gia_niem_yet: data_sp.gia_niem_yet,
				gia_ban_le: data_sp.gia_ban_le,
				gia_ban_buon: data_sp.gia_ban_buon,
				thongso_kythuat: data_sp.thongso_kythuat,
				hinh_anh: data_sp.hinhanh,
			}, response => {
				if ( ! response.success ) {
					return;
				}
				// product.showPopup();

				let tr = $( 'tr[data-product='+ data_sp.id +']' );
				tr.replaceWith( product.htmlLayout( data_sp ) );
			} );
		},
		remove: function( id ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'remove_san_pham',
				id: id,
			}, response => {
				if ( ! response.success ) {
					return;
				}

				let tr = $( 'tr[data-product='+ id +']' );
				tr.remove();
			} );
		},

		clearInput: function() {
			let ten          = $( 'input[name=ten]' ),
				gia_niem_yet = $( 'input[name=gia_niem_yet]' ),
				gia_ban_le   = $( 'input[name=gia_ban_le]' ),
				gia_ban_buon = $( 'input[name=gia_ban_buon]' ),
				thongso      = $( 'textarea[name=thong_so_ky_thuat]' ),
				hinh_anh     = $( 'input[name=hinh_anh]' );

			ten.val( '' );
			gia_niem_yet.val( '' );
			gia_ban_le.val( '' );
			gia_ban_buon.val( '' );
			thongso.val( '' );
			hinh_anh.val( '' );
		},

		onSave: function() {
			$d.on( 'click', '.btn_add_product', function() {
				let ten          = $( 'input[name=ten]' ),
					gia_niem_yet = $( 'input[name=gia_niem_yet]' ),
					gia_ban_le   = $( 'input[name=gia_ban_le]' ),
					gia_ban_buon = $( 'input[name=gia_ban_buon]' ),
					thongso      = $( 'textarea[name=thong_so_ky_thuat]' ),
					hinh_anh     = $( 'input[name=hinh_anh]' );

				if ( $(this).hasClass( 'edit' ) ) {
					let data_sp = {
						id              : $(this).attr( 'data-id' ),
						ten_sp          : ten.val(),
						gia_niem_yet    : gia_niem_yet.val(),
						gia_ban_le      : gia_ban_le.val(),
						gia_ban_buon    : gia_ban_buon.val(),
						thongso_kythuat : thongso.val(),
						hinhanh         : hinh_anh.val(),
					}
					product.edit( data_sp );
				} else {
					product.add( ten, gia_niem_yet, gia_ban_le, gia_ban_buon, thongso, hinh_anh );
				}
			} );
		},

		editButton: function() {
			$d.on( 'click', '.data-list .button-edit',  function() {
				let parent       = $(this).parents( 'tr' ),
					id_product   = parent.data( 'product' ),
					ten          = parent.find( '.product__name' ),
					gia_niem_yet = parent.find( '.product__gia-niem-yet' ),
					gia_ban_le   = parent.find( '.product__gia-ban-le' ),
					gia_ban_buon = parent.find( '.product__gia-ban-buon' ),
					thong_so     = parent.find( '.product__thongso' ),
					hinh_anh     = parent.find( '.product__thumbnail' );

				$( 'input[name="ten"]' ).val( ten.text() );
				$( 'input[name="gia_niem_yet"]' ).val( gia_niem_yet.data( 'gia-niem-yet' ) );
				$( 'input[name="gia_ban_le"]' ).val( gia_ban_le.data( 'gia-ban-le' ) );
				$( 'input[name="gia_ban_buon"]' ).val( gia_ban_buon.data( 'gia-ban-buon' ) );
				$( 'input[name="hinh_anh"]' ).val( hinh_anh.data( 'link-image' ) );

				$( '.btn_add_product' ).addClass( 'edit' );
				$( '.btn_add_product' ).attr( 'data-id', id_product );
			} );
		},

		removeButton: function() {
			$d.on( 'click', '.data-list .button-remove', function() {
				let parent     = $(this).parents( 'tr' ),
					id_product = parent.data( 'product' );

				$( '.confirm-remove' ).addClass( 'confirmed' );
				$( '.confirm-remove' ).attr( 'data-id', id_product );
				product.confirmRemove( id_product );
			} );
		},

		confirmRemove: function() {
			$d.on( 'click', '.confirm-remove', function() {
				let id_product = $(this).attr( 'data-id' );

				product.remove( id_product );
				product.clearInput();

			} );
		},

		showPopup: function() {
			const toast =
			`<div class="toast">
				<p class="title">Đã thêm sản phẩm thành công</p>
				<div class="img-toast">
					<span class="dashicons dashicons-yes-alt"></span>
				</div>
			</div>`;

			$( 'body' ).append( toast ).fadeTo( 2000, 1, () => {
				$( '.toast' ).remove();
			} );
		}
	};

	product.init();
} );
function formatNumber( n, x, s, c, number ) {
	var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
		num = number.toFixed( Math.max( 0, ~~n ) );
	return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
}
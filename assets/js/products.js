( function( $, scriptJS ) {
	const $d = $( document );
	let productList = {
		init: function() {
			productList.clickSave();
			productList.editButton();
			productList.removeButton();
			productList.clearButton();
			productList.showListKho();
		},
		htmlLayout: function(data) {
			let gia_niem_yet = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_niem_yet ) ),
				gia_ban_le   = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_ban_le ) ),
				gia_ban_buon = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_ban_buon ) );
			return `
			<tr class="text-gray-700 dark:text-gray-400" data-product="${data.id}">
				<td class="px-4 py-3">${data.id}</td>
				<td data-link-image="${data.hinh_anh}" class="product__thumbnail px-4 py-3">
					<div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
						<img class="object-cover w-full h-full rounded-full border-0" src="${data.hinh_anh}">
					</div>
				</td>
				<td class="product__name searchable px-4 py-3">

					<span>${data.ten}</span>
					<div>
						<span class="popup-kho" data-popup="product-${data.id}">Xem kho</span>
					</div>
				</td>
				<td class="product__gia-niem-yet px-4 py-3 text-right" data-gia-niem-yet="${data.gia_niem_yet}">${gia_niem_yet}</td>
				<td class="product__gia-ban-le px-4 py-3 text-right" data-gia-ban-le="${data.gia_ban_le}">${gia_ban_le}</td>
				<td class="product__gia-ban-buon px-4 py-3 text-right" data-gia-ban-buon="${data.gia_ban_buon}">${gia_ban_buon}</td>
				<td class="product__thongso px-4 py-3">
					<p class="hidden">
						${data.thong_so}
					</p>
					${data.thong_so.substr( 0, 40 )} ...
				</td>
				<td class="product__hang-san-xuat px-4 py-3 hidden">${data.hang_san_xuat}</td>
				<td class="product__xuat-xu px-4 py-3 hidden">${data.xuat_xu}</td>
				<td class="px-4 py-3">
					<div class="flex items-center space-x-4 text-sm">
						<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
							</svg>
						</button>
						<button data-product="${data.id}" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</button>
					</div>
				</td>
			</tr>
			`;
		},

		add: function( product ) {
			// const { ten, gia_niem_yet, gia_ban_le, gia_ban_buon, thong_so, hang_san_xuat, xuat_xu, hinh_anh } = product;
			$( '.btn_add_product' ).prop( 'disabled', true );

			$.post( ProductParams.ajaxUrl, {
				action : 'them_san_pham',
				...product
			}, response => {
				$( '.btn_add_product' ).prop( 'disabled', false );

				if ( ! response.success ) {
					$( '.message-error' ).remove();
					$( '.crm-action' ).append( '<p class="message-error text-xs text-red-600 dark:text-red-400">' + response.data + '</p>' );
					return;
				}
				let data_sp = {
					id : response.data,
					...product
				}

				$( '.data-list' ).prepend( productList.htmlLayout( data_sp ) );

				scriptJS.showPopup( 'Đã thêm sản phẩm thành công' );

				productList.clearInput();

				$( '.message-error' ).remove();
			} );
		},
		edit: function( product ) {
			$.post( ProductParams.ajaxUrl, {
				action: 'edit_san_pham',
				...product
			}, response => {
				if ( ! response.success ) {
					$( '.message-error' ).remove();
					$( '.crm-action' ).append( '<p class="message-error text-xs text-red-600 dark:text-red-400">' + response.data + '</p>' );
					return;
				}
				scriptJS.showPopup( 'Đã sửa sản phẩm thành công' );
				productList.clearInput();

				let tr = $( 'tr[data-product='+ product.id +']' );
				tr.replaceWith( productList.htmlLayout( product ) );

				$( '.message-error' ).remove();
				$( '.crm-action h2' ).text( 'Thêm sản phẩm' );
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

				scriptJS.showPopup( 'Đã xóa sản phẩm thành công' );

				let tr = $( 'tr[data-product='+ id +']' );
				tr.remove();
			} );
		},

		clearInput: function() {
			$( '.deleteable' ).val( '' );
			$( '.btn_add_product' ).removeClass( 'edit' );
		},

		clickSave: function() {
			$d.on( 'click', '.btn_add_product', function() {
				let ten           = $( 'input[name=ten]' ),
				    gia_niem_yet  = $( 'input[name=gia_niem_yet]' ),
				    gia_ban_le    = $( 'input[name=gia_ban_le]' ),
				    gia_ban_buon  = $( 'input[name=gia_ban_buon]' ),
				    thong_so      = $( 'textarea[name=thong_so]' ),
				    hang_san_xuat = $( 'input[name=hang_san_xuat]' ),
				    xuat_xu       = $( 'input[name=xuat_xu]' ),
				    hinh_anh      = $( 'input[name=hinh_anh]' );

				let data_sp = {
					ten          : ten.val(),
					gia_niem_yet : gia_niem_yet.val(),
					gia_ban_le   : gia_ban_le.val(),
					gia_ban_buon : gia_ban_buon.val(),
					thong_so     : thong_so.val(),
					hang_san_xuat: hang_san_xuat.val(),
					xuat_xu      : xuat_xu.val(),
					hinh_anh     : hinh_anh.val(),
				}
				if ( $(this).hasClass( 'edit' ) ) {
					data_sp = {
						id : $(this).attr( 'data-id' ),
						...data_sp
					}
					productList.edit( data_sp );
				} else {
					productList.add( data_sp );
				}
			} );
		},

		editButton: function() {
			$d.on( 'click', '.data-list .button-edit',  function() {
				let parent        = $(this).parents( 'tr' ),
				    id_product    = parent.data( 'product' ),
				    ten           = parent.find( '.product__name > span' ),
				    gia_niem_yet  = parent.find( '.product__gia-niem-yet' ),
				    gia_ban_le    = parent.find( '.product__gia-ban-le' ),
				    gia_ban_buon  = parent.find( '.product__gia-ban-buon' ),
				    thong_so      = parent.find( '.product__thongso p' ),
				    hang_san_xuat = parent.find( '.product__hang-san-xuat' ),
				    xuat_xu       = parent.find( '.product__xuat-xu' ),
				    hinh_anh      = parent.find( '.product__thumbnail' );

				$( 'input[name="ten"]' ).val( ten.text() );
				$( 'input[name="gia_niem_yet"]' ).val( gia_niem_yet.data( 'gia-niem-yet' ) );
				$( 'input[name="gia_ban_le"]' ).val( gia_ban_le.data( 'gia-ban-le' ) );
				$( 'input[name="gia_ban_buon"]' ).val( gia_ban_buon.data( 'gia-ban-buon' ) );
				$( 'textarea[name="thong_so"]' ).val( thong_so.html().trim() );
				$( 'input[name="hang_san_xuat"]' ).val( hang_san_xuat.text() );
				$( 'input[name="xuat_xu"]' ).val( xuat_xu.text() );
				$( 'input[name="hinh_anh"]' ).val( hinh_anh.data( 'link-image' ) );

				$( '.crm-action h2' ).text( 'Sửa sản phẩm' );
				$( '.btn_add_product' ).text( 'Lưu' );

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
				productList.confirmRemove( id_product );
			} );
		},

		clearButton: function() {
			$d.on( 'click', '.btn_clear_product', function() {
				productList.clearInput();
				$( '.crm-action h2' ).text( 'Thêm sản phẩm' );
				$( '.btn_add_product' ).text( 'Thêm' );
			} );
		},

		confirmRemove: function() {
			$d.on( 'click', '.confirm-remove', function() {
				let id_product = $(this).attr( 'data-id' );

				productList.remove( id_product );
				productList.clearInput();

			} );
		},

		showListKho: function() {
			$d.on( 'click', '.popup-kho', function() {
				let popup = $(this).attr( 'data-popup' );
				$( `#${popup}` ).addClass( 'current' );
			} );
			$d.on( 'click', '.btn-close', function() {
				$(this).parents( '.modal' ).removeClass( 'current' );
			} )
		}
	};

	productList.init();
} )( jQuery, scriptJS );

function formatNumber( n, x, s, c, number ) {
	var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
		num = number.toFixed( Math.max( 0, ~~n ) );
	return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
}
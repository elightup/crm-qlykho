jQuery( function( $ ) {
	const $d = $( document );
	let order = {
		init: function() {
			order.onClickAddSanPham();
			order.changeSanPham();
			order.clickSave();
		},
		htmlLayout: function(data) {
			let tong_tien = formatNumber( 0, 3, '.', ',', parseFloat( data.tong_tien ) );
			return `
			<tr class="text-gray-700 dark:text-gray-400" data-order="${data.id}">
				<td class="px-4 py-3">#${data.id}</td>
				<td class="px-4 py-3">
					${data.name_user}
				</td>
				<td class="px-4 py-3">
					${data.ngay}
				</td>
				<td data-tong-tien="${data.tong_tien}" class="px-4 py-3 text-right">
					${tong_tien}
				</td>
				<td class="px-4 py-3">
					Đã lên đơn
				</td>

				<td class="px-4 py-3">
					<div class="flex items-center space-x-4 text-sm">
						<button class="button-detail flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
							</svg>
						</button>
						<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
							</svg>
						</button>
						<button data-order="${data.id}" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</button>
					</div>
				</td>
			</tr>
			`;
		},

		onClickAddSanPham: function() {
			$( '.add_product_kho' ).on( 'click', function ( e ) {
				e.preventDefault();
				$( '.clone-product' ).last().clone().appendTo( '.table-product' );
			} );
		},

		changeSanPham: function() {
			$d.on( 'change', '#product__name', function() {
				let parent = $( this ).parent();
				let optionSelected = $(this).find( 'option:selected' );
				let gia_niem_yet   = formatNumber( 0, 3, '.', ',', parseFloat( optionSelected.attr( 'data-price' ) ) );
				let soLuong        = optionSelected.attr( 'data-soluong' ) ? formatNumber( 0, 3, '.', ',', parseFloat( optionSelected.attr( 'data-soluong' ) ) ) : 0;

				parent.siblings( '.product-price' ).text( gia_niem_yet );
				parent.siblings( '.product-number' ).find( 'input' ).val( soLuong );
				parent.siblings( '.product-number' ).find( 'input' ).attr( 'max', soLuong );
			} );
		},

		add: function( data_order ) {
			const { tong_tien, id_user } = data_order;
			$.post( ProductParams.ajaxUrl, {
				action   : 'them_don',
				tong_tien: tong_tien,
				id_user  : id_user,
			}, response => {
				if ( ! response.success ) {
					$( '.message-error' ).remove();
					$( '.crm-action' ).append( '<p class="message-error text-xs text-red-600 dark:text-red-400">' + response.data + '</p>' );
					return;
				}
				let data_order = {
					id       : response.data.id_order,
					tong_tien: tong_tien,
					name_user: response.data.name_user,
					ngay     : response.data.ngay,
				}
				$( '.data-list' ).prepend( order.htmlLayout( data_order ) );
				// productList.showPopup( 'Đã thêm sản phẩm thành công' );
				// productList.clearInput();

				// ten.focus();

				// $( '.message-error' ).remove();
			} );
		},

		clickSave: function() {
			$d.on( 'click', '.add_order', function() {
				let tong_tien = $( '.product-total' ),
					id_user   = $( '#user_name' );

				let data_order = {
					tong_tien: tong_tien.text(),
					id_user  : id_user.find(":selected").val(),
				}
				console.log('data_order', data_order);
				if ( $(this).hasClass( 'edit' ) ) {
					order.edit( data_order );
				} else {
					order.add( data_order );
				}
			} );
		},
	};

	order.init();
} );

function formatNumber( n, x, s, c, number ) {
	var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
		num = number.toFixed( Math.max( 0, ~~n ) );
	return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
}
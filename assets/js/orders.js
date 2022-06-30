( function( $, scriptJS ) {
	const $d = $( document );
	let order = {
		init: function() {
			order.onClickAddSanPham();
			order.changeSanPham();
			order.clickSave();
			order.showListKho();
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
			$d.on( 'click', '.add_product_order', function() {
				$( '.clone-product' ).last().clone().appendTo( '.table-product' );
			} );
		},

		changeSanPham: function() {
			$d.on( 'change', '#product__name', function() {
				let parent         = $( this ).parent();

				parent.siblings( '.product-number' ).find( 'input' ).val( 1 );

				let optionSelected = $(this).find( 'option:selected' ),
					max_number     = optionSelected.attr( 'data-soluong' ),
					price          = optionSelected.attr( 'data-price' ),
					soLuong        = parent.siblings( '.product-number' ).find( 'input' ).val(),
					gia_niem_yet   = formatNumber( 0, 3, '.', ',', parseFloat( price ) ),
					sub_total      = formatNumber( 0, 3, '.', ',', parseFloat( price * soLuong ) );

				parent.siblings( '.product-price' ).text( gia_niem_yet );
				parent.siblings( '.product-sub-total' ).text( sub_total );
				parent.siblings( '.product-sub-total' ).attr( 'data-sub-total', price * soLuong );
				parent.siblings( '.product-number' ).find( 'input' ).attr( 'max', max_number );
				parent.siblings( '.product-number' ).find( 'button' ).attr( 'data-popup', `product-${optionSelected.val()}` );


				let total = 0;
				$( '.product-sub-total' ).each( function () {
					total += parseInt( $( this ).attr( 'data-sub-total' ) );
				} );

				$( '.product-total span' ).text( formatNumber( 0, 3, '.', ',', total ) );
				$( '.product-total' ).attr( 'data-total', total );
			} );
			$d.on( 'input', '.product-number input', function() {
				let parent         = $( this ).parent(),
					optionSelected = parent.siblings( '.product-name' ).find( 'option:selected' ),
					price          = optionSelected.attr( 'data-price' ),
					sub_total      = formatNumber( 0, 3, '.', ',', parseFloat( price * $(this).val() ) );
				if ( price )  {
					parent.siblings( '.product-sub-total' ).text( sub_total );
					parent.siblings( '.product-sub-total' ).attr( 'data-sub-total', price * $(this).val() );
				}


				let total = 0;
				$( '.product-sub-total' ).each( function () {
					total += parseInt( $( this ).attr( 'data-sub-total' ) );
				} );

				$( '.product-total span' ).text( formatNumber( 0, 3, '.', ',', total ) );
				$( '.product-total' ).attr( 'data-total', total );
			} );
		},

		add: function( data_order ) {
			const { product, tong_tien, id_user } = data_order;
			$.post( ProductParams.ajaxUrl, {
				action   : 'them_don',
				product  : product,
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
				scriptJS.showPopup( 'Đã thêm sản phẩm thành công' );
				// productList.clearInput();


				// $( '.message-error' ).remove();
			} );
		},

		clickSave: function() {
			$d.on( 'click', '.add_order', () => {
				let tong_tien = $( '.product-total' ),
					id_user   = $( '#user_name' );


				let product = {};
				$( '.clone-product' ).each( function () {
					let optionSelected = $(this).find( '.product-name option:selected' ),
					product_id     = optionSelected.val(),
					quantity       = $(this).find( '.product-number input' ).val(),
					price          = optionSelected.attr( 'data-price' );

					let kho =[];
					$( `#product-${product_id} tbody tr` ).each( function() {
						kho.push( {
							id: $( this ).find( '.ten-kho' ).attr( 'data-kho' ),
							quantity: $( this ).find( '.chon-kho input' ).val()
						} );
					} );

					product[product_id] = {
						quantity : quantity,
						price    : price,
						warehouse: kho,
					}
				} );

				let data_order = {
					product: product,
					tong_tien: tong_tien.attr( 'data-total' ),
					id_user  : id_user.find( ":selected" ).val()
				}

				order.add( data_order );

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

	order.init();
} )( jQuery, scriptJS );

function formatNumber( n, x, s, c, number ) {
	var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
		num = number.toFixed( Math.max( 0, ~~n ) );
	return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
}
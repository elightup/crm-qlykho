( function( $, scriptJS ) {
	const $d = $( document );

	var options = {
		valueNames: [
			'product__id',
			{ attr: 'src', name: 'product__thumbnail_img' },
			'name',
			{ attr: 'data-popup', name: 'popup' },
			'product_gia_niem_yet',
			{ attr: 'data-gia-niem-yet', name: 'data_gia_niem_yet' },
			'product_gia_ban_le',
			{ attr: 'data-gia-ban-le', name: 'data_gia_ban_le' },
			'product_gia_ban_buon',
			{ attr: 'data-gia-ban-buon', name: 'data_gia_ban_buon' },
			'thongso_full',
			'thongso_excerpt',
			'hang_san_xuat',
			'xuat_xu'
		],
		page: 10,
		pagination: [
			{ item: '<li class="rounded-md focus:outline-none focus:shadow-outline-purple"><a class="page px-3 py-1" href="#"></a></li>', }
		]
	};
	if ( $( 'tbody.list' ).html().trim() !== '' ) {
		var itemList = new List( 'crm-table', options );
	}

	let productList = {
		init: function() {
			productList.clickSave();
			productList.editButton();
			productList.removeButton();
			productList.clearButton();
			productList.showListKho();
		},

		htmlLayoutPopup: function(id) {
			return `
			<div id="product-${id}" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Danh sách Kho</h5>
							<button type="button" class="btn-close dashicons dashicons-no" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p class="mt-4">Sản phẩm này chưa có ở kho nào</p>
						</div>
					</div>
				</div>
			</div>
			`;
		},

		valueListJS: function( product ) {
			let gia_niem_yet = formatNumber( 0, 3, '.', ',', parseFloat( product.gia_niem_yet ) ),
				gia_ban_le   = formatNumber( 0, 3, '.', ',', parseFloat( product.gia_ban_le ) ),
				gia_ban_buon = formatNumber( 0, 3, '.', ',', parseFloat( product.gia_ban_buon ) ),
				excerpt      = product.thong_so.split( ' ' ).splice( 0, 10 ).join( ' ' ) + '...';

			return {
				product__id           : product.id,
				product__thumbnail_img: product.hinh_anh,
				name                  : product.ten,
				popup				  : `product-${product.id}`,
				product_gia_niem_yet  : gia_niem_yet,
				data_gia_niem_yet     : product.gia_niem_yet,
				product_gia_ban_le    : gia_ban_le,
				data_gia_ban_le       : product.gia_ban_le,
				product_gia_ban_buon  : gia_ban_buon,
				data_gia_ban_buon     : product.gia_ban_buon,
				thongso_full          : product.thong_so,
				thongso_excerpt       : excerpt,
				hang_san_xuat         : product.hang_san_xuat,
				xuat_xu               : product.xuat_xu,
			}
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

				scriptJS.showToast( 'Đã thêm sản phẩm thành công' );

				itemList.add( productList.valueListJS( data_sp ) );
				itemList.sort( 'product__id', {
					order: 'desc'
				} )
				itemList.update();

				$( '.product-modal' ).prepend( productList.htmlLayoutPopup( response.data ) );
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
				scriptJS.showToast( 'Đã sửa sản phẩm thành công' );
				productList.clearInput();


				var item = itemList.get( 'product__id', product.id )[0];
				item.values( productList.valueListJS( product ) );
				itemList.update();

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

				scriptJS.showToast( 'Đã xóa sản phẩm thành công' );

				let popup = $( `#product-${id}` );
				popup.remove();

				itemList.remove( 'product__id', id );
				itemList.update();
			} );
		},

		clearInput: function() {
			$( '.deleteable' ).val( '' );
			$( '.btn_add_product' ).removeClass( 'edit' );
			$( '.message-error' ).remove();
		},

		clickSave: function() {
			$d.on( 'click', '.btn_add_product', function() {
				$( '.message-error' ).remove();

				let data_sp = {
					ten          : $( 'input[name=ten]' ).val(),
					gia_niem_yet : $( 'input[name=gia_niem_yet]' ).attr( 'data-number' ),
					gia_ban_le   : $( 'input[name=gia_ban_le]' ).attr( 'data-number' ),
					gia_ban_buon : $( 'input[name=gia_ban_buon]' ).attr( 'data-number' ),
					thong_so     : $( 'textarea[name=thong_so]' ).val(),
					hang_san_xuat: $( 'input[name=hang_san_xuat]' ).val(),
					xuat_xu      : $( 'input[name=xuat_xu]' ).val(),
					hinh_anh     : $( 'input[name=hinh_anh]' ).val(),
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
				$( '.message-error' ).remove();

				let parent        = $(this).parents( 'tr' ),
				    id_product    = parent.find( '.product__id' ).text(),
				    ten           = parent.find( '.name' ),
				    gia_niem_yet  = parent.find( '.product__gia-niem-yet' ),
				    gia_ban_le    = parent.find( '.product__gia-ban-le' ),
				    gia_ban_buon  = parent.find( '.product__gia-ban-buon' ),
				    thong_so      = parent.find( '.product__thongso p' ),
				    hang_san_xuat = parent.find( '.product__hang-san-xuat' ),
				    xuat_xu       = parent.find( '.product__xuat-xu' ),
				    hinh_anh      = parent.find( '.product__thumbnail' );

				$( 'input[name="ten"]' ).val( ten.text().trim() );

				$( 'input[name="gia_niem_yet"]' ).val( gia_niem_yet.html().trim() );
				$( 'input[name="gia_niem_yet"]' ).attr( 'data-number', gia_niem_yet.attr( 'data-gia-niem-yet' ) );
				$( 'input[name="gia_ban_le"]' ).val( gia_ban_le.html().trim() );
				$( 'input[name="gia_ban_le"]' ).attr( 'data-number', gia_ban_le.attr( 'data-gia-ban-le' ) );
				$( 'input[name="gia_ban_buon"]' ).val( gia_ban_buon.html().trim() );
				$( 'input[name="gia_ban_buon"]' ).attr( 'data-number', gia_ban_buon.attr( 'data-gia-ban-buon' ) );

				$( 'textarea[name="thong_so"]' ).val( thong_so.html().trim() );
				$( 'input[name="hang_san_xuat"]' ).val( hang_san_xuat.text() );
				$( 'input[name="xuat_xu"]' ).val( xuat_xu.text() );
				$( 'input[name="hinh_anh"]' ).val( hinh_anh.find( 'img' ).attr( 'src' ) );

				$( '.crm-action h2' ).text( 'Sửa sản phẩm' );
				$( '.btn_add_product' ).text( 'Lưu' );

				$( '.btn_add_product' ).addClass( 'edit' );
				$( '.btn_add_product' ).attr( 'data-id', id_product );
			} );
		},

		removeButton: function() {
			$d.on( 'click', '.data-list .button-remove', function() {
				let parent     = $(this).parents( 'tr' ),
					id_product = parent.find( '.product__id' ).text();

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
			$d.on( 'click', '.popup-kho', scriptJS.onShowPopup );
			$d.on( 'click', '.btn-close, #wpwrap', scriptJS.onClosePopup );
		},
	};

	productList.init();
} )( jQuery, scriptJS );

function formatNumber( n, x, s, c, number ) {
	var re = '\\d(?=(\\d{' + ( x || 3 ) + '})+' + ( n > 0 ? '\\D' : '$' ) + ')',
		num = number.toFixed( Math.max( 0, ~~n ) );
	return ( c ? num.replace( '.', c ) : num ).replace( new RegExp( re, 'g' ), '$&' + ( s || ',' ) );
}
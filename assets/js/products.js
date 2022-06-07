jQuery( function( $ ) {
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
			<tr data-product="${data.id}">
				<td>#${data.id}</td>
				<td data-link-image="${data.hinhanh}" class="product__thumbnail">
					<img src="${data.hinhanh}">
				</td>
				<td class="product__name">${data.ten_sp}</td>
				<td data-gia-niem-yet="${data.gia_niem_yet}">${gia_niem_yet} ₫</td>
				<td data-gia-ban-le="${data.gia_ban_le}">${gia_ban_le} ₫</td>
				<td data-gia-ban-buon="${data.gia_ban_buon}">${gia_ban_buon} ₫</td>
				<td class="product-thongso">${thongso_kythuat} ...</td>
				<td>
					<span class="dashicons dashicons-edit" title="Sửa"></span>
					<span class="dashicons dashicons-no" title="Xóa"></span>
				</td>
			</tr>
			`;
		},
		onSave: function() {
			$( '.btn_add_product' ).on( 'click', function() {
				let ten          = $( 'input[name=ten]' ),
					gia_niem_yet = $( 'input[name=gia_niem_yet]' ),
					gia_ban_le   = $( 'input[name=gia_ban_le]' ),
					gia_ban_buon = $( 'input[name=gia_ban_buon]' ),
					thongso      = $( 'textarea[name=thong_so_ky_thuat]' ),
					hinh_anh     = $( 'input[name=hinh_anh]' );

				if ( $(this).hasClass( 'edit' ) ) {
					let data_sp = {
						id              : $(this).data( 'id' ),
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
					$( '.crm-action' ).append( '<p class="message-error">' + response.data + '</p>' );
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

				ten.val( '' );
				gia_niem_yet.val( '' );
				gia_ban_le.val( '' );
				gia_ban_buon.val( '' );
				thongso.val( '' );
				hinh_anh.val( '' );
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

		editButton: function() {
			$( '.data-list .dashicons-edit' ).on( 'click', function() {
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
			$( '.data-list .dashicons-no' ).on( 'click', function() {
				let parent     = $(this).parents( 'tr' ),
					id_product = parent.data( 'product' );

				product.remove( id_product );
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
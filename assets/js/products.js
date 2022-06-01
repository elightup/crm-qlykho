jQuery( function( $ ) {
	let product = {
		init: function() {
			product.add();
		},
		htmlLayout: function(data) {
			let gia_niem_yet = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_niem_yet ) ),
				gia_ban_le = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_ban_le ) ),
				gia_ban_buon = formatNumber( 0, 3, '.', ',', parseFloat( data.gia_ban_buon ) ),
				thongso_kythuat = data.thongso_kythuat.substr( 0, 40 );
			return `
			<tr>
				<td>#${data.id}</td>
				<td><img src="${data.hinhanh}" class="product-thumbnail"></td>
				<td>${data.ten_sp}</td>
				<td>${gia_niem_yet} ₫</td>
				<td>${gia_ban_le} ₫</td>
				<td>${gia_ban_buon} ₫</td>
				<td class="product-thongso">${thongso_kythuat} ...</td>
				<td>
					<span class="dashicons dashicons-edit" title="Sửa"></span>
					<span class="dashicons dashicons-no" title="Xóa"></span>
				</td>
			</tr>
			`;
		},
		add: function() {
			$( '.btn_add_product' ).on( 'click', function() {
				var ten          = $( 'input[name=ten]' ),
					gia_niem_yet = $( 'input[name=gia_niem_yet]' ),
					gia_ban_le   = $( 'input[name=gia_ban_le]' ),
					gia_ban_buon = $( 'input[name=gia_ban_buon]' ),
					thongso      = $( 'textarea[name=thong_so_ky_thuat]' ),
					hinh_anh     = $( 'input[name=hinh_anh]' );
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
				} );
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
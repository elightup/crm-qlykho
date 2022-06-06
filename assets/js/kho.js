jQuery( function ( $ ) {

	$( '.add_product_kho' ).on( 'click', function ( e ) {
		e.preventDefault();
		$( this ).closest( '.modal-body' ).find( '.add-product' ).last().clone().appendTo( '.modal-body__product' );
	} );

	let kho = {
		init: function () {
			kho.add();
		},
		htmlLayout: function ( data ) {
			return `
			<tr>
				<td>#${ data.id }</td>
				<td>${ data.ten_kho }</td>
				<td>${ data.user_name }</td>
				<td></td>
				<td></td>
				<td>
					<span class="dashicons dashicons-edit" title="Sửa"></span>
					<span class="dashicons dashicons-no" title="Xóa"></span>
				</td>
			</tr>
			`;
		},
		add: function () {
			$( '.btn_add_kho' ).on( 'click', function () {
				var ten = $( 'input[name=ten]' ),
					user = $( 'select[name="user_name"] option:selected' ),
					user_name = $( 'select[name="user_name"] option:selected' );
				$.post( ProductParams.ajaxUrl, {
					action: 'them_kho',
					ten: ten.val(),
					user: user.val(),
					user_name: user_name.text(),
				}, response => {
					if ( !response.success ) {
						$( '.crm-action' ).append( '<p class="message-error">' + response.data + '</p>' );
						return;
					}
					let data_kho = {
						id: response.data,
						ten_kho: ten.val(),
						user: user.val(),
						user_name: user_name.text(),
					};
					$( '.data-list' ).prepend( kho.htmlLayout( data_kho ) );
					kho.showPopup();

					$( '.message-error' ).remove();
				} );
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
		}
	};

	let product_kho = {
		init: function () {
			product_kho.addsp();
		},
		htmlLayout: function ( data ) {
			return `
			<div class="modal-body__inner">
				<div class="modal-body__id">#${ data.products[ 0 ][ 'id_product' ] }</div>
				<div class="modal-body__name">${ data.products[ 0 ][ 'name_sp' ] }</div>
				<div class="modal-body__name">${ data.products[ 0 ][ 'number' ] }</div>
				<div class="modal-body__name">
					<span class="dashicons dashicons-edit" title="Sửa"></span>
					<span class="dashicons dashicons-no" title="Xóa"></span>
					<span class="dashicons dashicons-saved" title="Lưu"></span>
				</div>
			</div>
			`;
		},
		addsp: function () {
			$( '.save_product' ).on( 'click', function () {
				var id_kho = $( this ).closest( '.modal-body' ).find( '#idkho' ).val();
				var products = [];
				$( this ).closest( '.modal-body' ).find( ".add-product" ).each( function ( index ) {
					var id_product = $( this ).find( '#product_name' ).val();
					var name_sp = $( this ).find( '#product_name option:selected' ).text();
					var number = $( this ).find( '#number_product' ).val();
					var product = {
						'id_product': id_product,
						'name_sp': name_sp,
						'number': number
					};
					products.push( product );
				} );

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
					$( '.modal-body__content' ).append( product_kho.htmlLayout( data_sp_kho ) );
					//product_kho.showPopup();

					$( '.message-error' ).remove();
				} );
			} );
		},

	};

	kho.init();
	product_kho.init();
} );


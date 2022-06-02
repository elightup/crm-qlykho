jQuery( function ( $, rwmb ) {
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

					//ten.val( '' );
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


	kho.init();
} );

function get_user_name( user ) {
	var name = '<?php ?>';
};
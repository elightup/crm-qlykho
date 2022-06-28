<?php
namespace CRM\SanPham;

class Ajax {
	public function __construct() {
		$actions = [
			'them_san_pham',
			'edit_san_pham',
			'remove_san_pham',
		];

		foreach ( $actions as $action ) {
			add_action( 'wp_ajax_' . $action, [ $this, 'ajax_' . $action ] );
		}

	}

	public function ajax_them_san_pham() {
		$data = [
			'ten'          => isset( $_POST['ten'] ) ? $_POST['ten'] : '',
			'gia_niem_yet' => isset( $_POST['gia_niem_yet'] ) ? $_POST['gia_niem_yet'] : '',
			'gia_ban_le'   => isset( $_POST['gia_ban_le'] ) ? $_POST['gia_ban_le'] : '',
			'gia_ban_buon' => isset( $_POST['gia_ban_buon'] ) ? $_POST['gia_ban_buon'] : '',
			'thong_so'     => isset( $_POST['thong_so'] ) ? $_POST['thong_so'] : '',
			'hinh_anh'     => isset( $_POST['hinh_anh'] ) ? $_POST['hinh_anh'] : '',
		];
		if ( strlen( trim( $data['ten'] ) ) <= 0 || empty( $data['gia_niem_yet'] ) || empty( $data['gia_ban_le'] ) || empty( $data['gia_ban_buon'] ) ) {
			wp_send_json_error( 'Thông tin sản phẩm trống. Bạn hãy nhập đủ thông tin ' );
		}

		$id_product = $this->them_san_pham( $data );
		wp_send_json_success( $id_product );
	}

	public function them_san_pham( $data ) {
		global $wpdb;
		$wpdb->insert(
			'san_pham',
			[
				'ten'          => $data['ten'],
				'gia_niem_yet' => $data['gia_niem_yet'],
				'gia_ban_le'   => $data['gia_ban_le'],
				'gia_ban_buon' => $data['gia_ban_buon'],
				'thong_so'     => esc_textarea( $data['thong_so'] ),
				'hinh_anh'     => $data['hinh_anh'],
			]
		);
		$product_id = $wpdb->insert_id;
		return $product_id;
	}

	public function ajax_edit_san_pham() {
		$id   = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$data = [
			'ten'          => isset( $_POST['ten'] ) ? $_POST['ten'] : '',
			'gia_niem_yet' => isset( $_POST['gia_niem_yet'] ) ? $_POST['gia_niem_yet'] : '',
			'gia_ban_le'   => isset( $_POST['gia_ban_le'] ) ? $_POST['gia_ban_le'] : '',
			'gia_ban_buon' => isset( $_POST['gia_ban_buon'] ) ? $_POST['gia_ban_buon'] : '',
			'thong_so'     => isset( $_POST['thong_so'] ) ? $_POST['thong_so'] : '',
			'hinh_anh'     => isset( $_POST['hinh_anh'] ) ? $_POST['hinh_anh'] : '',
		];
		if ( strlen( trim( $data['ten'] ) ) <= 0 || empty( $data['gia_niem_yet'] ) || empty( $data['gia_ban_le'] ) || empty( $data['gia_ban_buon'] ) ) {
			wp_send_json_error( 'Thông tin sản phẩm trống. Bạn hãy nhập đủ thông tin ' );
		}

		$this->edit_san_pham( $id, $data );
		wp_send_json_success();
	}

	public function edit_san_pham( $id, $data ) {
		global $wpdb;
		$wpdb->update(
			'san_pham',
			[
				'ten'          => $data['ten'],
				'gia_niem_yet' => $data['gia_niem_yet'],
				'gia_ban_le'   => $data['gia_ban_le'],
				'gia_ban_buon' => $data['gia_ban_buon'],
				'thong_so'     => esc_textarea( $data['thong_so'] ),
				'hinh_anh'     => $data['hinh_anh'],
			],
			[ 'id' => $id ]
		);
	}

	public function ajax_remove_san_pham() {
		$id = isset( $_POST['id'] ) ? $_POST['id'] : '';
		if ( empty( $id ) ) {
			wp_send_json_error( 'Có lỗi xảy ra' );
		}

		$this->remove_san_pham( $id );
		wp_send_json_success();
	}

	public function remove_san_pham( $id ) {
		global $wpdb;
		$wpdb->delete(
			'san_pham',
			[ 'id' => $id ]
		);
	}
}

<?php
namespace CRM\SanPham;

class Ajax {
	public function __construct() {
		$actions = [
			'them_san_pham',
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
			'gia_niem_yet' => isset( $_POST['gia_niem_yet'] ) ? $_POST['gia_niem_yet'] : '',
			'thongso'      => isset( $_POST['thongso'] ) ? $_POST['thongso'] : '',
			'hinh_anh'     => isset( $_POST['hinh_anh'] ) ? $_POST['hinh_anh'] : '',
		];
		if ( empty( $data['ten'] || $data['gia_niem_yet'] || $data['gia_ban_buon'] ) ) {
			wp_send_json_error( 'Thông tin sản phẩm trống. Bạn hãy nhập đủ thông tin ' );
		}

		$id_product = $this->them_san_pham( $data );
		wp_send_json_success( $id_product );
	}

	public function them_san_pham( $data ) {
		global $wpdb;
		$wpdb->insert(
			'sanpham',
			[
				'ten_sp'          => $data['ten'],
				'gia_niem_yet'    => $data['gia_niem_yet'],
				'gia_ban_le'      => $data['gia_ban_le'],
				'gia_ban_buon'    => $data['gia_ban_buon'],
				'thongso_kythuat' => $data['thongso'],
				'hinhanh'         => $data['hinh_anh'],
			]
		);
		$product_id = $wpdb->insert_id;
		return $product_id;
	}
}

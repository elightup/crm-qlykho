<?php
namespace CRM\DonHang;

class Ajax {
	public function __construct() {
		$actions = [
			'them_don',
			'edit_don',
			'remove_don',
		];

		foreach ( $actions as $action ) {
			add_action( 'wp_ajax_' . $action, [ $this, 'ajax_' . $action ] );
		}

	}
	public function ajax_them_don() {
		$data = [
			'product'   => isset( $_POST['product'] ) ? $_POST['product'] : '',
			'tong_tien' => isset( $_POST['tong_tien'] ) ? $_POST['tong_tien'] : '',
			'id_user'   => isset( $_POST['id_user'] ) ? $_POST['id_user'] : '',
		];
		if ( empty( $data['id_user'] ) ) {
			wp_send_json_error( 'Thông tin trống. Bạn hãy nhập đủ thông tin ' );
		}

		$user   = get_userdata( $data['id_user'] );
		$result = [
			'id_order'  => $this->them_don( $data ),
			'name_user' => $user->display_name,
			'ngay'      => current_time( 'mysql' ),
		];
		wp_send_json_success( $result );
	}

	public function them_don( $data ) {
		global $wpdb;
		$wpdb->insert(
			'don_hang',
			[
				'san_pham'   => json_encode( $data['product'] ),
				'tong_tien'  => (int) $data['tong_tien'],
				'id_user'    => $data['id_user'],
				'ngay'       => current_time( 'mysql' ),
				'trang_thai' => 'Báo giá',
			]
		);
		$order_id = $wpdb->insert_id;
		return $order_id;
	}

	public function ajax_remove_don() {
		$id = isset( $_POST['id'] ) ? $_POST['id'] : '';
		if ( empty( $id ) ) {
			wp_send_json_error( 'Có lỗi xảy ra' );
		}

		$this->remove_don( $id );
		wp_send_json_success();
	}

	public function remove_don( $id ) {
		global $wpdb;
		$wpdb->delete(
			'don_hang',
			[ 'id' => $id ]
		);
	}
}



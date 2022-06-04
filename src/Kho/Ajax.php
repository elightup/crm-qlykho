<?php
namespace CRM\Kho;

class Ajax {
	public function __construct() {
		$actions = [
			'them_kho',
			'them_product_kho',
		];

		foreach ( $actions as $action ) {
			add_action( 'wp_ajax_' . $action, [ $this, 'ajax_' . $action ] );
		}
	}

	public function ajax_them_kho() {
		$data = [
			'ten'  => isset( $_POST['ten'] ) ? $_POST['ten'] : '',
			'user' => isset( $_POST['user'] ) ? $_POST['user'] : '',
		];

		$id_kho = $this->them_kho( $data );
		wp_send_json_success( $id_kho );
	}
	public function ajax_them_product_kho() {
		$data = isset( $_POST['products'] ) ? $_POST['products'] : '';
		var_dump( $data );
		$id_sp_kho = $this->them_sp_kho( $data );
		wp_send_json_success( $id_sp_kho );
	}

	public function them_kho( $data ) {
		global $wpdb;
		$wpdb->insert(
			'kho',
			[
				'ten_kho' => $data['ten'],
				'id_user' => $data['user'],
			]
		);
		$kho_id = $wpdb->insert_id;
		return $kho_id;
	}

	// public function them_sp_kho( $data ) {
	// global $wpdb;
	// $wpdb->insert(
	// 'sanpham_kho',
	// [
	// 'ten_kho' => $data['ten'],
	// 'id_user' => $data['user'],
	// ]
	// );
	// $sp_kho = $wpdb->insert_id;
	// return $sp_kho;
	// }

}

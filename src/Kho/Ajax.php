<?php
namespace CRM\Kho;

class Ajax {
	public function __construct() {
		$actions = [
			'them_kho',
			'them_product_kho',
			'xoa_kho',
			'xoa_sp_kho',
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
		$data      = [
			'id_kho'   => isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '',
			'products' => isset( $_POST['products'] ) ? $_POST['products'] : '',
		];
		$id_sp_kho = $this->them_sp_kho( $data );
		wp_send_json_success( $id_sp_kho );
	}
	public function ajax_xoa_kho() {
		global $wpdb;
		$id_kho = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$wpdb->delete( 'kho', array( 'id' => $id_kho ) );
	}
	public function ajax_xoa_sp_kho() {
		global $wpdb;
		$id_sp = isset( $_POST['id_sp'] ) ? $_POST['id_sp'] : '';
		$wpdb->delete( 'sanpham_kho', array( 'idSanPham' => $id_sp ) );
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

	public function them_sp_kho( $data ) {
		global $wpdb;
		$products = $data['products'];
		foreach ( $products as $product ) {
			$wpdb->insert(
				'sanpham_kho',
				[
					'idKho'     => $data['id_kho'],
					'idSanPham' => $product['id_product'],
					'soLuong'   => $product['number'],
				]
			);
		}
		$kho_id = $wpdb->insert_id;
		return $kho_id;
	}

}

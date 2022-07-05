<?php

namespace CRM\DonHang;

class AdminList {
	protected $table;

	public function init() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu() {
		add_menu_page(
			'Đơn hàng',
			'Đơn hàng',
			'administrator',
			'orders',
			[ $this, 'render' ],
			'dashicons-cart',
			30
		);
	}

	public function render() {
		if ( empty( $_GET['id'] ) ) {
			include CRM_DIR . 'views/orders.php';
		} else {
			include CRM_DIR . 'views/orderDetail.php';
		}
	}
}

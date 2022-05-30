<?php

namespace CRM\SanPham;

class AdminList {
	protected $table;

	public function init() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu() {
		add_menu_page(
			'Sản phẩm',
			'Sản phẩm',
			'administrator',
			'products',
			[ $this, 'render' ],
			'dashicons-cart',
			30
		);
	}

	public function render() {
		include CRM_DIR . 'templates/product.php';
	}
}

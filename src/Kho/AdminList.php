<?php

namespace CRM\Kho;

class AdminList {
	protected $table;

	public function init() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu() {
		add_menu_page(
			'Kho',
			'Kho',
			'administrator',
			'kho',
			[ $this, 'render' ],
			'dashicons-admin-home',
			30
		);
	}

	public function render() {
		include CRM_DIR . 'templates/kho.php';
	}
}

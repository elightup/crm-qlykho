<?php
namespace CRM\SanPham;

class Index {
	public function __construct() {
		( new AdminList() )->init();
		new Ajax;
	}
}

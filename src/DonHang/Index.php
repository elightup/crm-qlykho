<?php
namespace CRM\DonHang;

class Index {
	public function __construct() {
		( new AdminList() )->init();
		new Ajax;
	}
}

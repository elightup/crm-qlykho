<?php
namespace CRM\Kho;

class Index {
	public function __construct() {
		( new AdminList() )->init();
		new Ajax;
		new Export;
	}
}

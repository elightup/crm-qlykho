<?php
namespace CRM;

class Schema {
	public function __construct() {
		$this->create_tables();
	}

	public function create_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql          = '
		CREATE TABLE sanpham (
			`id` mediumint unsigned NOT NULL auto_increment,
			`ten_sp` varchar(50) NOT NULL,
			`gia_niem_yet` int unsigned NOT NULL,
			`gia_ban_le` int unsigned NOT NULL,
			`gia_ban_buon` int unsigned NOT NULL,
			`thongso_kythuat` text,
			`hinhanh` text,
			PRIMARY KEY  (`id`)
		);
		';
		$sql_kho      = '
			CREATE TABLE kho (
				`id` mediumint unsigned NOT NULL auto_increment,
				`ten_kho` varchar(50) NOT NULL,
				`id_user` mediumint NOT NULL,
				PRIMARY KEY  (`id`)
			);
		';
		$sql_spkho    = '
			CREATE TABLE sanpham_kho (
				`id` mediumint unsigned NOT NULL auto_increment,
				`idKho` mediumint NOT NULL,
				`idSanPham` mediumint NOT NULL,
				`soLuong` mediumint NOT NULL,
				PRIMARY KEY  (`id`)
			);
		';
		$sql_donhang  = '
			CREATE TABLE donhang (
				`id` mediumint unsigned NOT NULL auto_increment,
				`san_pham` mediumint NOT NULL,
				`id_khachhang` mediumint NOT NULL,
				`tong_tien` mediumint NOT NULL,
				`date` datetime NOT NULL,
				`status` varchar(50) NOT NULL,
				PRIMARY KEY  (`id`)
			);
		';
		$sql_nhap_kho = '
		CREATE TABLE nhap_kho (
			`id` mediumint unsigned NOT NULL auto_increment,
			`id_san_pham` mediumint NOT NULL,
			`so_luong` mediumint NOT NULL,
			`id_kho` mediumint NOT NULL,
			`date` datetime NOT NULL,
			PRIMARY KEY  (`id`)
		);
		';

		dbDelta( $sql );
		dbDelta( $sql_kho );
		dbDelta( $sql_spkho );
		dbDelta( $sql_donhang );
		dbDelta( $sql_nhap_kho );
	}
}

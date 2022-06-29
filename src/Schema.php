<?php
namespace CRM;

class Schema {
	public function __construct() {
		$this->create_tables();
	}

	public function create_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql              = '
		CREATE TABLE san_pham (
			`id` mediumint unsigned NOT NULL auto_increment,
			`ten` varchar(50) NOT NULL,
			`gia_niem_yet` int unsigned NOT NULL,
			`gia_ban_le` int unsigned NOT NULL,
			`gia_ban_buon` int unsigned NOT NULL,
			`thong_so` text,
			`hang_san_xuat` text,
			`xuat_xu` text,
			`hinh_anh` text,
			PRIMARY KEY  (`id`)
		) CHARACTER SET utf8m4;
		';
		$sql_kho          = '
			CREATE TABLE kho (
				`id` mediumint unsigned NOT NULL auto_increment,
				`ten` varchar(50) NOT NULL,
				`id_user` mediumint NOT NULL,
				PRIMARY KEY  (`id`)
			);
		';
		$sql_san_pham_kho = '
			CREATE TABLE san_pham_kho (
				`id` mediumint unsigned NOT NULL auto_increment,
				`idKho` mediumint NOT NULL,
				`idSanPham` mediumint NOT NULL,
				`soLuong` mediumint NOT NULL,
				PRIMARY KEY  (`id`)
			);
		';
		$sql_don_hang     = '
			CREATE TABLE don_hang (
				`id` mediumint unsigned NOT NULL auto_increment,
				`san_pham` longtext,
				`id_user` mediumint NOT NULL,
				`tong_tien` mediumint NOT NULL,
				`ngay` datetime NOT NULL,
				`trang_thai` varchar(50) NOT NULL,
				PRIMARY KEY  (`id`)
			);
		';
		$sql_nhap_kho     = '
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
		dbDelta( $sql_san_pham_kho );
		dbDelta( $sql_don_hang );
		dbDelta( $sql_nhap_kho );
	}
}

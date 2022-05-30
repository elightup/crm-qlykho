<?php
/**
 * Plugin Name: Phần mềm quản lý kho
 * Plugin URI:  https://titanweb.vn
 * Description: Giải pháp quản lý kho tối ưu.
 * Version:     1.0.0
 * Author:      TitanWeb
 * Author URI:  https://titanweb.vn
 */

namespace CRM;

// Prevent loading this file directly.
defined( 'ABSPATH' ) || die;

require 'vendor/autoload.php';

define( 'CRM_DIR', plugin_dir_path( __FILE__ ) );
define( 'CRM_URL', plugin_dir_url( __FILE__ ) );

if ( is_admin() ) {
	new Menu;
	new Schema;
	new Assets;
	( new SanPham\AdminList() )->init();
}

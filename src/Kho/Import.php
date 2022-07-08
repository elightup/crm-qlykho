<?php
namespace CRM\Kho;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import {

	/**
	 * Class contructor
	 *
	 * @since 0.1
	 **/
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_action( 'init', array( $this, 'generate_import' ) );
	}

	/**
	 * Add administration menus
	 *
	 * @since 0.1
	 **/
	public function add_admin_pages() {
		add_submenu_page( 'kho', 'Import', 'Import', 'install_plugins', 'import_kho', [ $this, 'show' ], 30 );
	}


	/**
	 * Process content of CSV file
	 *
	 * @since 0.1
	 **/
	public function generate_import() {
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		if ( isset( $_POST['submit'] ) && $page == 'import_kho' ) {
			if ( isset( $_FILES['upload'] ) ) {
				// sbd_create();

				$file_name = $_FILES['upload']['name'];
				$file_tmp  = $_FILES['upload']['tmp_name'];
				$path      = WP_CONTENT_DIR . '/uploads/' . $file_name;
				move_uploaded_file( $file_tmp, $path );

				$template_path = $path;
				if ( file_exists( $template_path ) ) {
					$objPHPExcel = IOFactory::load( $template_path );
					$data        = $objPHPExcel->getActiveSheet()->toArray();
				}
				wp_insert_rows_pt( $data, 'san_pham_kho' );

				echo 'Bạn đã upload thành công';
			} else {
				echo 'Chưa có file nào được chọn';
			}
		}
	}


	/**
	 * Content of the settings page
	 *
	 * @since 0.1
	 **/
	public function show() {
		?>
		<div class="wrap">
			<h2>Import sản phẩm vào kho</h2>
			<main id="main" class="site-main container" role="main">
				<form action="" method="post" enctype="multipart/form-data">
					<input class="form-control" type="file" name="upload">
					<input type="submit" name="submit" value="Upload" class="btn btn-success">
				</form>
			</main><!-- #main -->
		</div><!-- #wrap -->
		<?php
	}
}

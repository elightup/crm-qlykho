<?php
namespace CRM\Kho;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
	}

	public function add_admin_menu() {
		$page = add_submenu_page(
			'kho',
			'Nhập',
			'Nhập',
			'install_plugins',
			'import_kho',
			[ $this, 'render_admin_page' ],
			30
		);
		add_action( "load-$page", [ $this, 'import' ] );
		// add_action( "admin_print_styles-$page", [ $this, 'enqueue_assets' ] );
	}

	public function render_admin_page() {
		?>
		<div class="wrap">
			<h2>Nhập sản phẩm vào kho</h2>
			<p>Chọn file Excel chứa danh sách sản phẩm và nhấn nút <strong>Upload</strong></p>

			<form action="" method="post" enctype="multipart/form-data">
				<input class="form-control" type="file" name="upload">
				<input type="submit" name="submit" value="Upload" class="btn btn-success">
			</form>
		</div>
		<?php
	}

	/**
	 * Import an Excel file.
	 **/
	public function import() {
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		if ( $page !== 'import_kho' ) {
			return;
		}
		if ( empty( $_POST['submit'] ) ) {
			return;
		}
		if ( ! empty( $_FILES['upload']['error'] ) ) {
			return;
		}

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
	}
}

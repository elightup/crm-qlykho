<?php
namespace CRM\Kho;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export {

	/**
	 * Class contructor
	 *
	 * @since 0.1
	 **/
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_action( 'init', array( $this, 'generate_xlsx' ) );
	}


	/**
	 * Add administration menus
	 *
	 * @since 0.1
	 **/
	public function add_admin_pages() {
		add_submenu_page( 'kho', 'Export', 'Export', 'install_plugins', 'export_kho', [ $this, 'show' ], 30 );
	}

	/**
	 * Process content of CSV file
	 *
	 * @since 0.1
	 **/

	public function generate_xlsx() {
		$check = ( isset( $_POST['kho'] ) );
		if ( ! isset( $_POST['_wpnonce-export-kho'] ) || ! $check ) {
			return;
		}

		check_admin_referer( 'export-kho', '_wpnonce-export-kho' );

		$file = 'danh-sach-san-pham-' . date( 'd-m-Y' ) . '.xlsx';

		/**
		* Generate .xlsx file using PHP_XLSXWriter class
		* @link https://github.com/mk-j/PHP_XLSXWriter
		*/

		// Create new PHPExcel object
		$spreadsheet = new Spreadsheet();
		$sheet       = $spreadsheet->getActiveSheet();

		// Add some data
		$sheet->setCellValue( 'A1', 'STT' )
		->setCellValue( 'B1', 'Tên kho' )
		->setCellValue( 'C1', 'Tên sản phẩm' )
		->setCellValue( 'D1', 'Số lượng đầu kỳ' )
		->setCellValue( 'E1', 'Số lượng Cuối kỳ' );

		global $wpdb;
		$kho          = $_POST['kho'];
		$id_kho       = '(' . implode( ',', $kho ) . ')';
		$start_date   = $_POST['start_date'];
		$end_date     = $_POST['end_date'];
		$sql          = 'SELECT DISTINCT id_san_pham FROM nhap_kho WHERE id_kho in ' . $id_kho . ' AND `date` BETWEEN CAST( "' . $start_date . '" AS DATE ) AND CAST( "' . $end_date . '" AS DATE )';
		$products_kho = $wpdb->get_results( $sql );
		$candidates   = [];
		foreach ( $products_kho as $product ) {
			$id_sp        = $product->id_san_pham;
			$sql          = 'SELECT * FROM san_pham WHERE id = ' . $id_sp . ' ORDER BY id DESC';
			$san_pham     = $wpdb->get_results( $sql );
			$name_sp      = $san_pham[0]->ten;
			$sql1         = 'SELECT soLuong FROM san_pham_kho WHERE idSanPham = ' . $id_sp . ' AND idKho in ' . $id_kho;
			$sp_kho       = $wpdb->get_results( $sql1 );
			$so_luong_con = $sp_kho[0]->soLuong;
			$sql2         = 'SELECT so_luong FROM nhap_kho WHERE id_san_pham = ' . $id_sp . ' AND id_kho in ' . $id_kho . ' AND `date` BETWEEN CAST( "' . $start_date . '" AS DATE ) AND CAST( "' . $end_date . '" AS DATE )';
			$spKho        = $wpdb->get_results( $sql2 );
			$sl_dau       = $spKho[0]->so_luong;
			$sql          = 'SELECT * FROM kho WHERE id in ' . $id_kho;
			$warehouses   = $wpdb->get_results( $sql );
			$name_kho     = $warehouses[0]->ten;
			$candidates[] = [
				'name_product' => $name_sp,
				'sl_dau'       => $sl_dau,
				'sl_con'       => $so_luong_con,
				'name_kho'     => $name_kho,
			];
		}

		$row = 1;
		foreach ( $candidates as $candidate ) {

			$row ++;
			// Add some data
			$sheet->setCellValue( 'A' . $row, $row - 1 )
			->setCellValue( 'B' . $row, $candidate['name_kho'] )
			->setCellValue( 'C' . $row, $candidate['name_product'] )
			->setCellValue( 'D' . $row, $candidate['sl_dau'] )
			->setCellValue( 'E' . $row, $candidate['sl_con'] );
		}
		// var_dump( $sheet );
		// die();

		$writer = new Xlsx( $spreadsheet );
		ob_end_clean();

		// Redirect output to a client’s web browser( Excel2007 )
		header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
		header( 'Content-Disposition: attachment;filename="' . $file . '"' );
		header( 'Cache-Control: max-age=0' );
		// if you 're serving to IE 9, then the following may be needed
		header( 'Cache - Control: max - age = 1' );

		// If you're serving to IE over SSL, then the following may be needed
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); // always modified
		header( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
		header( 'Pragma: public' ); // HTTP/1.0

		$writer->save( 'php://output' );
		exit;

	}

	/**
	 * Content of the settings page
	 *
	 * @since 0.1
	 **/
	public function show() {
		?>
		<div class="wrap">
			<h2>Xuất danh sách sản phẩm ra file excel</h2>
			<form method="post" action="" enctype="multipart/form-data" novalidate>
			<?php wp_nonce_field( 'export-kho', '_wpnonce-export-kho' ); ?>
				<div class="option_choose" style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
					<div id="action-address" style="width: 20%;">
						<label>Chọn kho:</label><br>
						<select name="kho[]" id="kho" multiple="multiple" style="width: 90%">
						<?php
						global $wpdb;
						$sql        = 'SELECT * FROM kho ORDER BY id DESC';
						$warehouses = $wpdb->get_results( $sql );
						foreach ( $warehouses as $warehouse ) :
							$id_kho   = $warehouse->id;
							$name_kho = $warehouse->ten;
							?>
							<option value="<?= esc_attr( $id_kho );?>"><?= esc_html( $name_kho );?></option>
							<?php
						endforeach;
						?>
						</select>
					</div>
					<div class="start_date" style="width: 20%;">
						<label>Ngày bắt đầu:</label><br>
						<input type="date" class="date" id="start_date" name="start_date" style="width: 90%">
					</div>
					<div class="end_date" style="width: 20%;">
						<label>Ngày kết thúc:</label><br>
						<input type="date" class="date" id="end_date" name="end_date" style="width: 90%">
					</div>

				</div>
				<p class="submit">
					<input type="submit" class="button-primary" value="Export"/>
				</p>
			</form>
		</div>
		<?php
	}
}

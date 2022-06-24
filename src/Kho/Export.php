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
		// add_action( 'init', array( $this, 'generate_xlsx' ) );
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

	// public function generate_xlsx() {
	// $check = ( isset( $_POST['school-address'] ) || isset( $_POST['school-class'] ) );
	// if ( ! isset( $_POST['_wpnonce-export-users'] ) || ! $check ) {
	// return;
	// }

	// check_admin_referer( 'export-users', '_wpnonce-export-users' );

	// $file = 'danh-sach-thi-sinh-' . date( 'd-m-Y' ) . '.xlsx';

	// **
	// * Generate .xlsx file using PHP_XLSXWriter class
	// * @link https://github.com/mk-j/PHP_XLSXWriter
	// */

	// Create new PHPExcel object
	// $spreadsheet = new Spreadsheet();
	// $sheet       = $spreadsheet->getActiveSheet();

	// Add some data
	// $sheet->setCellValue( 'A1', 'STT' )
	// ->setCellValue( 'B1', 'Tên' )
	// ->setCellValue( 'C1', 'Lớp' )
	// ->setCellValue( 'D1', 'Số vote' );

	// global $wpdb;
	// $school_address = $_POST['school-address'];
	// $school_class   = $_POST['school-class'];
	// $term           = [];
	// if ( $school_class === null ) {
	// $term = $school_address;
	// } elseif ( $school_address === null ) {
	// $term = $school_class;
	// } else {
	// $term = array_merge( $school_address, $school_class );
	// }
	// $start_vote = $_POST['start_vote'];
	// $end_vote   = $_POST['end_vote'];
	// $date_query = array(
	// 'relation' => 'AND',
	// array(
	// 'before'    => $end_vote,
	// 'after'     => $start_vote,
	// 'inclusive' => true,
	// ),
	// );
	// $args       = array(
	// 'post_type'      => 'competition',
	// 'posts_per_page' => -1,
	// 'tax_query'      => [
	// 'relation' => 'AND',
	// [
	// 'taxonomy' => 'area-competition',
	// 'field'    => 'slug',
	// 'terms'    => $term,
	// ],
	// ],
	// 'date_query'     => $date_query,
	// );
	// $query_args = get_posts( $args );
	// $candidates = [];
	// foreach ( $query_args as $query_arg ) {
	// $post_id = $query_arg->ID;
	// $vote    = (int) get_post_meta( $post_id, 'voted_number_3', true );
	// $name    = $query_arg->post_title;
	// $terms   = get_the_terms( $post_id, 'area-competition' );
	// foreach ( $terms as $term ) {
	// if ( $term->parent !== 0 ) {
	// $class = $term->name;
	// }
	// }
	// $candidates[] = [
	// 'name'  => $name,
	// 'votes' => $vote,
	// 'class' => $class,
	// ];
	// }
	// usort( $candidates, function ( $vote_a, $vote_b ) {
	// return $vote_b['votes'] - $vote_a['votes'];
	// } );
	// $row = 1;
	// foreach ( $candidates as $candidate ) {

	// $row ++;
	// Add some data
	// $sheet->setCellValue( 'A' . $row, $row - 1 )
	// ->setCellValue( 'B' . $row, $candidate['name'] )
	// ->setCellValue( 'C' . $row, $candidate['class'] )
	// ->setCellValue( 'D' . $row, $candidate['votes'] );
	// }

	// $writer = new Xlsx( $spreadsheet );
	// ob_end_clean();

	// Redirect output to a client’s web browser (Excel2007)
	// header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
	// header( 'Content-Disposition: attachment;filename="' . $file . '"' );
	// header( 'Cache-Control: max-age=0' );
	// If you're serving to IE 9, then the following may be needed
	// header( 'Cache-Control: max-age=1' );

	// If you're serving to IE over SSL, then the following may be needed
	// header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
	// header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); // always modified
	// header( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
	// header( 'Pragma: public' ); // HTTP/1.0

	// $writer->save( 'php://output' );
	// exit;

	// }

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
						<select name="address-users[]" id="number-users" multiple="multiple" style="width: 90%">
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

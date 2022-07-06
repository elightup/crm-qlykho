<?php
namespace CRM\Kho;

class Index {
	public function __construct() {
		( new AdminList() )->init();
		new Ajax;
		new Export;
		new Import;
	}

}

function wp_insert_rows_pt( $row_arrays = array(), $wp_table_name ) {
	global $wpdb;
	$wp_table_name = esc_sql( $wp_table_name );
	// Setup arrays for Actual Values, and Placeholders.
	$values        = array();
	$place_holders = array();
	$query         = '';
	$query_columns = '';
	$query        .= "INSERT INTO {$wp_table_name} (";
	foreach ( $row_arrays as $count => $row_array ) {
		foreach ( $row_array as $key => $value ) {
			if ( $count == 0 ) {
				if ( $query_columns ) {
					$query_columns .= ',' . $key . '';
				} else {
					$query_columns .= '' . $key . '';
				}
			}

			$values[] = $value;

			if ( isset( $place_holders[ $count ] ) ) {
				$place_holders[ $count ] .= ", '%s'";
			} else {
				$place_holders[ $count ] .= "( '%s'";
			}
		}
				// mind closing the GAP.
				$place_holders[ $count ] .= ')';
	}
	$query .= 'idKho, idSanPham, soLuong ) VALUES ';
	$query .= implode( ', ', $place_holders );
	if ( $wpdb->query( $wpdb->prepare( $query, $values ) ) ) {
		return true;
	} else {
		return false;
	}
}

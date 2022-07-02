<?php
namespace CRM\Kho;

class Ajax {
	public function __construct() {
		$actions        = [
			'them_kho',
			'edit_kho',
			'xoa_kho',
			'them_product_kho',
			'xoa_sp_kho',
			'edit_product_kho',
			'search_products',
		];
		$this->base_url = admin_url( 'edit.php?page=kho' );

		foreach ( $actions as $action ) {
			add_action( 'wp_ajax_' . $action, [ $this, 'ajax_' . $action ] );
		}
	}

	public function ajax_them_kho() {
		global $wpdb;
		$data       = [
			'ten'  => isset( $_POST['ten'] ) ? $_POST['ten'] : '',
			'user' => isset( $_POST['user'] ) ? $_POST['user'] : '',
		];
		$sql        = 'SELECT * FROM kho ORDER BY id DESC';
		$warehouses = $wpdb->get_results( $sql );
		foreach ( $warehouses as $warehouse ) {
			$ten_kho = $warehouse->ten;
			if ( $ten_kho === $data['ten'] ) {
				wp_send_json_error( 'Bạn đã có kho này. Vui lòng nhập lại ' );
			}
		}
		if ( empty( $data['ten'] ) ) {
			wp_send_json_error( 'Thông tin kho trống. Bạn hãy nhập đủ thông tin ' );
		}
		$id_kho = $this->them_kho( $data );
		ob_start();
		$this->get_user( $data );
		$result = ob_get_clean();
		wp_send_json_success( $id_kho, $result );
	}
	public function ajax_xoa_kho() {
		global $wpdb;
		$id_kho = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$wpdb->delete( 'kho', array( 'id' => $id_kho ) );
		$wpdb->delete( 'san_pham_kho', array( 'idKho' => $id_kho ) );
		$wpdb->delete( 'nhap_kho', array( 'id_kho' => $id_kho ) );
		ob_start();
		$sql        = 'SELECT * FROM kho ORDER BY id DESC';
		$warehouses = $wpdb->get_results( $sql );
		?>
		<label for="user">User:</label>
		<select name="user_name" id="user_name">
			<option value=""><?= esc_html( 'Chọn user' );?></option>
			<?php
			$users = get_users();
			foreach ( $users as $user ) :
				$user_id   = $user->ID;
				$user_name = $user->display_name;
				$hidden    = '';
				foreach ( $warehouses as $key => $warehouse ) :
					$warehouse_user = (int) $warehouse->id_user;
					if ( $user_id === $warehouse_user ) {
						$hidden = 'hidden';
					}
				endforeach;
				?>
				<option value="<?= esc_attr( $user_id );?>" <?= esc_attr( $hidden );?>><?= esc_html( $user->display_name );?></option>
				<?php
			endforeach;
			?>
		</select>
		<?php
		$result = ob_get_clean();
		wp_send_json_success( $result );
	}
	public function ajax_edit_kho() {
		$id   = isset( $_POST['id'] ) ? $_POST['id'] : '';
		$data = [
			'ten_kho'   => isset( $_POST['ten_kho'] ) ? $_POST['ten_kho'] : '',
			'user'      => isset( $_POST['user'] ) ? $_POST['user'] : '',
			'user_name' => isset( $_POST['user_name'] ) ? $_POST['user_name'] : '',
		];
		if ( empty( $data['ten_kho'] ) ) {
			wp_send_json_error( 'Thông tin kho trống. Bạn hãy nhập đủ thông tin ' );
		}
		$this->edit_kho( $id, $data );
		wp_send_json_success();
	}
	public function edit_kho( $id, $data ) {
		global $wpdb;
		$wpdb->update(
			'kho',
			[
				'ten'     => $data['ten_kho'],
				'id_user' => $data['user'],
			],
			[ 'id' => $id ]
		);
	}
	public function get_user( $data ) {
		echo 'ssss';
	}
	public function them_kho( $data ) {
		global $wpdb;
		$wpdb->insert(
			'kho',
			[
				'ten'     => $data['ten'],
				'id_user' => $data['user'],
			]
		);
		$kho_id = $wpdb->insert_id;
		return $kho_id;
	}

	public function ajax_them_product_kho() {
		$id_kho = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$data   = [
			'id_kho'   => isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '',
			'date'     => isset( $_POST['date'] ) ? $_POST['date'] : '',
			'products' => isset( $_POST['products'] ) ? $_POST['products'] : '',
		];
		$this->them_sp_kho( $data );
		$this->them_nhap_kho( $data );
		ob_start();
		$this->changed_products( $id_kho );
		$result = ob_get_clean();
		wp_send_json_success( $result );
	}
	public function ajax_edit_product_kho() {
		$id_kho = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$data   = [
			'id_kho'   => isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '',
			'date'     => isset( $_POST['date'] ) ? $_POST['date'] : '',
			'products' => isset( $_POST['products'] ) ? $_POST['products'] : '',
		];
		$this->edit_sp_kho( $data );
		$this->them_nhap_kho( $data );
		ob_start();
		$this->changed_products( $id_kho );
		$result = ob_get_clean();
		wp_send_json_success( $result );
	}
	public function ajax_xoa_sp_kho() {
		global $wpdb;
		$id_sp  = isset( $_POST['id_sp'] ) ? $_POST['id_sp'] : '';
		$id_kho = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$wpdb->delete( 'san_pham_kho', array(
			'idSanPham' => $id_sp,
			'idKho'     => $id_kho,
		) );
		$wpdb->delete( 'nhap_kho', array(
			'id_san_pham' => $id_sp,
			'id_kho'      => $id_kho,
		) );
		ob_start();
		$this->changed_products( $id_kho );
		$result = ob_get_clean();
		wp_send_json_success( $result );
	}
	public function ajax_search_products() {
		ob_start();
		global $wpdb;
		$start_date   = isset( $_POST['start_date'] ) ? $_POST['start_date'] : '';
		$end_date     = isset( $_POST['end_date'] ) ? $_POST['end_date'] : '';
		$id_kho       = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$sql          = 'SELECT DISTINCT id_san_pham FROM nhap_kho WHERE id_kho = ' . $id_kho . ' AND `date` BETWEEN CAST( "' . $start_date . '" AS DATE ) AND CAST( "' . $end_date . '" AS DATE )';
		$products_kho = $wpdb->get_results( $sql );
		foreach ( $products_kho as $product_kho ) :
			$idsp     = $product_kho->id_san_pham;
			$sql      = 'SELECT * FROM san_pham WHERE id = ' . $idsp . ' ORDER BY id DESC';
			$products = $wpdb->get_results( $sql );
			$sql1     = 'SELECT soLuong FROM san_pham_kho WHERE idSanPham = ' . $idsp . ' AND idKho = ' . $id_kho;
			$kho      = $wpdb->get_results( $sql1 );
			$sql2     = 'SELECT so_luong FROM nhap_kho WHERE id_san_pham = ' . $idsp . ' AND id_kho = ' . $id_kho . ' AND `date` BETWEEN CAST( "' . $start_date . '" AS DATE ) AND CAST( "' . $end_date . '" AS DATE )';
			$spKho    = $wpdb->get_results( $sql2 );
			?>
			<tr class="text-gray-700 dark:text-gray-400" product-id="<?= esc_attr( $idsp );?>" >
				<td class="px-4 py-3">#<?= esc_html( $idsp ) ?></td>
				<td product-name="<?= esc_attr( $products[0]->ten );?>" class="name_product searchable px-4 py-3"><?= esc_attr( $products[0]->ten );?></td>
				<td number-history="<?= esc_attr( $spKho[0]->so_luong );?>" class="number-history px-4 py-3"><?= esc_html( $spKho[0]->so_luong ) ?></td>
				<td product-number="<?= esc_attr( $kho[0]->soLuong );?>" class="product-number px-4 py-3"><?= esc_html( $kho[0]->soLuong ) ?></td>

				<td class="action px-4 py-3">
					<div class="flex items-center space-x-4 text-sm">
						<button data-kho="<?= esc_attr( $id_kho ) ?>" class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
							</svg>
						</button>
						<button data-kho="<?= esc_attr( $id_kho ) ?>" @click="openList" class="remove-product flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
							<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</button>
					</div>
				</td>
			</tr>
			<?php
		endforeach;

		$result = ob_get_clean();
		wp_send_json_success( $result );
	}

	public function them_sp_kho( $data ) {
		global $wpdb;
		$products = $data['products'];
		foreach ( $products as $product ) {
			$wpdb->insert(
				'san_pham_kho',
				[
					'idKho'     => $data['id_kho'],
					'idSanPham' => $product['id_product'],
					'soLuong'   => $product['number'],
				]
			);
		}
		$kho_id = $wpdb->insert_id;
		return $kho_id;
	}
	public function them_nhap_kho( $data ) {
		global $wpdb;
		$products = $data['products'];
		foreach ( $products as $product ) {
			$wpdb->insert(
				'nhap_kho',
				[
					'id_san_pham' => $product['id_product'],
					'so_luong'    => $product['number'],
					'id_kho'      => $data['id_kho'],
					'date'        => $data['date'],
				]
			);
		}
		$id = $wpdb->insert_id;
		return $id;
	}
	public function edit_sp_kho( $data ) {
		global $wpdb;
		$products = $data['products'];
		foreach ( $products as $product ) {
			$wpdb->update(
				'san_pham_kho',
				[
					'idSanPham' => $product['id_product'],
					'soLuong'   => $product['number'],
				],
				[ 'idSanPham' => $product['id_product'] ]
			);
		}
	}
	public function changed_products( $id ) {
		global $wpdb;
		$sql_kho  = 'SELECT * FROM san_pham_kho WHERE idKho = ' . $id;
		$products = $wpdb->get_results( $sql_kho );
		?>
		<div class="add-product">
			<select name="product_name" id="product_name" class="rwmb">
				<option value="" selected disabled hidden>Chọn sản phẩm</option>
				<?php
				$sql     = 'SELECT * FROM san_pham ORDER BY id DESC';
				$sanpham = $wpdb->get_results( $sql );
				foreach ( $sanpham as $sp ) :
					$id_sp  = $sp->id;
					$hidden = '';
					foreach ( $products as $product ) {
						$idproduct = $product->idSanPham;
						if ( $id_sp === $idproduct ) {
							$hidden = 'hidden';
						}
					}
					?>
					<option value="<?= esc_attr( $sp->id );?>" <?= esc_attr( $hidden );?>><?= esc_html( $sp->ten );?></option>
					<?php
				endforeach;
				?>
			</select>
			<input type="number" name="number_product" id="number_product" class="rwmb">
		</div>
		<?php
	}
}

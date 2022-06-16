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
		wp_send_json_success( $id_kho );
	}
	public function ajax_xoa_kho() {
		global $wpdb;
		$id_kho = isset( $_POST['id_kho'] ) ? $_POST['id_kho'] : '';
		$wpdb->delete( 'kho', array( 'id' => $id_kho ) );
		$wpdb->delete( 'san_pham_kho', array( 'idKho' => $id_kho ) );
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
			wp_send_json_error( 'Thông tin sản phẩm trống. Bạn hãy nhập đủ thông tin ' );
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
			'idSanPham' => $id_sp,
			'idKho'     => $id_kho,
		) );
		ob_start();
		$this->changed_products( $id_kho );
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
				$sql     = 'SELECT * FROM sanpham ORDER BY id DESC';
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
					<option value="<?= esc_attr( $sp->id );?>" <?= esc_attr( $hidden );?>><?= esc_html( $sp->ten_sp );?></option>
					<?php
				endforeach;
				?>
			</select>
			<input type="number" name="number_product" id="number_product" class="rwmb">
		</div>
		<?php
	}
}

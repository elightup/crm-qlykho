<?php
	global $wpdb;
	$sql        = 'SELECT * FROM kho ORDER BY id DESC';
	$warehouses = $wpdb->get_results( $sql );
?>

<div class="wrap">

	<div class="crm-content">
		<div class="crm-table">
			<h2>Danh sách Kho</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Mã Kho</th>
						<th>Tên Kho</th>
						<th>Tên User</th>
						<th>Email User</th>
						<th>Số điện thoại</th>
					</tr>
				</thead>
				<tbody class="data-list">
					<?php
					foreach ( $warehouses as $key => $warehouse ) :
						$user      = get_user_by( 'id', $warehouse->id_user );
						$user_id   = $user->ID;
						$user_meta = get_user_meta( $user_id, 'user_phone', true );
						$id_kho    = $warehouse->id;
						$sql_kho   = 'SELECT * FROM sanpham_kho WHERE idKho = ' . $id_kho;
						$products  = $wpdb->get_results( $sql_kho );
						?>
						<tr>
							<td data-bs-toggle="modal" data-bs-target="#modal_<?= esc_attr( $warehouse->id );?>">#<?= esc_html( $warehouse->id ) ?></td>
							<td data-bs-toggle="modal" data-bs-target="#modal_<?= esc_attr( $warehouse->id );?>"><?= esc_html( $warehouse->ten_kho ) ?></td>
							<td><?= esc_html( $user->display_name ) ?></td>
							<td><?= esc_html( $user->user_email ) ?></td>
							<td><?= esc_html( $user_meta ) ?></td>
							<td>
								<span class="dashicons dashicons-edit" title="Sửa"></span>
								<span class="dashicons dashicons-no" title="Xóa"></span>
							</td>
						</tr>
						<?php include CRM_DIR . 'src/kho/modal.php'; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<fieldset class="crm-action">
			<legend><h2>Thông tin kho</h2></legend>
			<div class="action_input">
				<div class="action_input-item">
					<label for="ten">Tên kho:</label>
					<input type="text" id="ten" name="ten" autofocus />
				</div>
				<div class="action_input-item">
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
				</div>
			</div>
			<div class="action_btn">
				<button class="btn btn_add_kho">Lưu</button>
			</div>
		</fieldset>
	</div>
</div>

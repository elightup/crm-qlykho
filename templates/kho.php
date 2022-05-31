<?php
	global $wpdb;
	$sql        = 'SELECT * FROM kho';
	$warehouses = $wpdb->get_results( $sql );
?>
<div class="wrap">
	<div class="crm_content product">
		<div class='crm_title'>
			<h2>Danh sách Kho</h2>
			<button class="btn btn_add_product">Thêm kho</button>
		</div>
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
					$user       = get_user_by( 'id', $warehouse->id_user );
					$user_id    = $user->ID;
					$user_meta  = get_user_meta( $user_id );
					$user_phone = $user_meta['user_phone'][0];
					?>
					<tr>
						<td>#<?= esc_html( $warehouse->id ) ?></td>
						<td><?= esc_html( $warehouse->ten_kho ) ?></td>
						<td><?= esc_html( $user->display_name ) ?></td>
						<td><?= esc_html( $user->user_email ) ?></td>
						<td><?= esc_html( $user_phone ) ?></td>

						<td>
							<span class="dashicons dashicons-edit" title="Sửa"></span>
							<span class="dashicons dashicons-no" title="Xóa"></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

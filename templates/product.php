<?php
	global $wpdb;
	$sql      = 'SELECT * FROM sanpham';
	$products = $wpdb->get_results( $sql );
?>
<div class="wrap">
	<div class="crm_content product">
		<div class='crm_title'>
			<h2>Danh sách sản phẩm</h2>
			<button class="btn btn_add_product">Thêm sản phẩm</button>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Mã Sản phẩm</th>
					<th>Tên Sản phẩm</th>
					<th>Giá 1</th>
					<th>Giá 2</th>
					<th>Giá 3</th>
					<th>Thông số kỹ thuật</th>
					<th>Hình ảnh</th>
				</tr>
			</thead>
			<tbody class="data-list">
				<?php foreach ( $products as $key => $product ) : ?>
					<tr>
						<td>#<?= esc_html( $product->id ) ?></td>
						<td><?= esc_html( $product->ten_sp ) ?></td>
						<td><?= esc_html( $product->gia_1 ) ?></td>
						<td><?= esc_html( $product->gia_2 ) ?></td>
						<td><?= esc_html( $product->gia_3 ) ?></td>
						<td><?= esc_html( $product->thongso_kythuat ) ?></td>
						<td><?= esc_html( $product->hinhanh ) ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="popup_khach_hang">

		</div>
	</div>
</div>

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
					<th>Hình ảnh</th>
					<th>Tên Sản phẩm</th>
					<th>Giá niêm yết</th>
					<th>Giá bán lẻ</th>
					<th>Giá bán buôn</th>
					<th>Thông số kỹ thuật</th>
					<th>Hành động</th>
				</tr>
			</thead>
			<tbody class="data-list">
				<?php foreach ( $products as $key => $product ) : ?>
					<tr>
						<td>#<?= esc_html( $product->id ) ?></td>
						<td>
							<img src="<?= esc_url( $product->hinhanh ) ?>" alt="" class="product-thumbnail">
						</td>
						<td><?= esc_html( $product->ten_sp ) ?></td>
						<td>
							<?= esc_html( number_format( $product->gia_niem_yet, 0, ',', '.' ) . ' ₫' ) ?>
						</td>
						<td><?= esc_html( number_format( $product->gia_ban_le, 0, ',', '.' ) . ' ₫' ) ?></td>
						<td><?= esc_html( number_format( $product->gia_ban_buon, 0, ',', '.' ) . ' ₫' ) ?></td>
						<td class="product-thongso"><?= esc_html( wp_trim_words( $product->thongso_kythuat, 15 ) ) ?></td>
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

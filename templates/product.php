<?php
	global $wpdb;
	$sql      = 'SELECT * FROM sanpham ORDER BY id DESC';
	$products = $wpdb->get_results( $sql );
?>
<div class="wrap">
	<!-- <div class='crm-title'>
		<h2>Danh sách sản phẩm</h2>
		<button class="btn btn_add_product">Thêm sản phẩm</button>
	</div> -->
	<div class="crm-content">
		<div class="crm-table">
			<h2>Danh sách sản phẩm</h2>
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
		<fieldset class="crm-action">
			<legend><h2>Thông tin sản phẩm</h2></legend>
			<div class="action_input">
				<div class="action_input-item">
					<label for="ten">Tên sản phẩm:</label>
					<input type="text" id="ten" name="ten" autofocus />
				</div>
				<div class="action_input-item">
					<label for="gia_niem_yet">Giá niêm yết:</label>
					<input type="text" id="gia_niem_yet" name="gia_niem_yet" />
				</div>
				<div class="action_input-item">
					<label for="gia_ban_le">Giá bán lẻ:</label>
					<input type="text" id="gia_ban_le" name="gia_ban_le" />
				</div>
				<div class="action_input-item">
					<label for="gia_ban_buon">Giá bán buôn:</label>
					<input type="text" id="gia_ban_buon" name="gia_ban_buon" />
				</div>
				<div class="action_input-item">
					<label for="thong_so_ky_thuat">Thông số kỹ thuật:</label>
					<textarea id="thong_so_ky_thuat" name="thong_so_ky_thuat" rows="5"></textarea>
				</div>
				<div class="action_input-item">
					<label for="hinh_anh">Link hình ảnh:</label>
					<input type="text" id="hinh_anh" name="hinh_anh" />
				</div>
			</div>
			<div class="action_btn">
				<button class="btn btn_add_product">Lưu</button>
			</div>
		</fieldset>
	</div>
</div>

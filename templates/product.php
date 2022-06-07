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
						<tr data-product="<?= esc_attr( $product->id ) ?>">
							<td>#<?= esc_html( $product->id ) ?></td>
							<td data-link-image="<?= esc_attr( $product->hinhanh ) ?>" class="product__thumbnail">
								<img src="<?= esc_url( $product->hinhanh ) ?>">
							</td>
							<td class="product__name"><?= esc_html( $product->ten_sp ) ?></td>
							<td data-gia-niem-yet="<?= esc_attr( $product->gia_niem_yet ) ?>" class="product__gia-niem-yet">
								<?= esc_html( number_format( $product->gia_niem_yet, 0, ',', '.' ) . ' ₫' ) ?>
							</td>
							<td data-gia-ban-le="<?= esc_attr( $product->gia_ban_le ) ?>" class="product__gia-ban-le"><?= esc_html( number_format( $product->gia_ban_le, 0, ',', '.' ) . ' ₫' ) ?></td>
							<td data-gia-ban-buon="<?= esc_attr( $product->gia_ban_buon ) ?>" class="product__gia-ban-buon"><?= esc_html( number_format( $product->gia_ban_buon, 0, ',', '.' ) . ' ₫' ) ?></td>
							<td class="product__thongso"><?= esc_html( wp_trim_words( $product->thongso_kythuat, 15 ) ) ?></td>
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
					<label for="ten">Tên sản phẩm <span class="action-required">*</span></label>
					<input type="text" id="ten" name="ten" autofocus />
				</div>
				<div class="action_input-item">
					<label for="gia_niem_yet">Giá niêm yết <span class="action-required">*</span></label>
					<input type="number" id="gia_niem_yet" name="gia_niem_yet" />
				</div>
				<div class="action_input-item">
					<label for="gia_ban_le">Giá bán lẻ <span class="action-required">*</span></label>
					<input type="number" id="gia_ban_le" name="gia_ban_le" />
				</div>
				<div class="action_input-item">
					<label for="gia_ban_buon">Giá bán buôn <span class="action-required">*</span></label>
					<input type="number" id="gia_ban_buon" name="gia_ban_buon" />
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

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
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danh sách sản phẩm</h2>
			<table class="table table-striped w-full overflow-hidden rounded-lg shadow-xs">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="px-4 py-3">Mã Sản phẩm</th>
						<th class="px-4 py-3">Hình ảnh</th>
						<th class="px-4 py-3">Tên Sản phẩm</th>
						<th class="px-4 py-3">Giá niêm yết</th>
						<th class="px-4 py-3">Giá bán lẻ</th>
						<th class="px-4 py-3">Giá bán buôn</th>
						<th class="px-4 py-3">Thông số kỹ thuật</th>
						<th class="px-4 py-3">Hành động</th>
					</tr>
				</thead>
				<tbody class="data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php foreach ( $products as $key => $product ) : ?>
						<tr class="text-gray-700 dark:text-gray-400" data-product="<?= esc_attr( $product->id ) ?>">
							<td class="px-4 py-3">#<?= esc_html( $product->id ) ?></td>
							<td data-link-image="<?= esc_attr( $product->hinhanh ) ?>" class="product__thumbnail px-4 py-3">
								<div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
									<img class="object-cover w-full h-full rounded-full border-0" src="<?= esc_url( $product->hinhanh ) ?>">
								</div>
							</td>
							<td class="product__name"><?= esc_html( $product->ten_sp ) ?></td>
							<td data-gia-niem-yet="<?= esc_attr( $product->gia_niem_yet ) ?>" class="product__gia-niem-yet px-4 py-3">
								<?= esc_html( number_format( $product->gia_niem_yet, 0, ',', '.' ) . ' ₫' ) ?>
							</td>
							<td data-gia-ban-le="<?= esc_attr( $product->gia_ban_le ) ?>" class="product__gia-ban-le px-4 py-3"><?= esc_html( number_format( $product->gia_ban_le, 0, ',', '.' ) . ' ₫' ) ?></td>
							<td data-gia-ban-buon="<?= esc_attr( $product->gia_ban_buon ) ?>" class="product__gia-ban-buon px-4 py-3"><?= esc_html( number_format( $product->gia_ban_buon, 0, ',', '.' ) . ' ₫' ) ?></td>
							<td class="product__thongso px-4 py-3"><?= esc_html( wp_trim_words( $product->thongso_kythuat, 15 ) ) ?></td>
							<td class="px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
									</button>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<fieldset class="crm-action">
			<legend><h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Thông tin sản phẩm</h2></legend>
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
				<button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple btn_add_product">Lưu</button>
			</div>
		</fieldset>
	</div>
</div>

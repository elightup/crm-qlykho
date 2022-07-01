<?php foreach ( $products as $product ) :
	$warehouses = $wpdb->get_results( $wpdb->prepare(
		'SELECT * FROM san_pham_kho as sk INNER JOIN kho as k
	 	 ON sk.idKho = k.id
		 WHERE sk.idSanPham=%d
		 ORDER BY sk.id DESC',
		$product->id
	) );
	?>
<div id="product-<?= esc_attr( $product->id ) ?>" class="modal fade">
	<div class="modal-dialog" style="max-width: 385px">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Danh sách Kho</h5>
				<button type="button" class="btn-close dashicons dashicons-no" aria-label="Close"></button>
			</div>
			<div class="modal-body" style="padding: 0 0 20px;">
				<?php if ( $warehouses ) : ?>
				<table>
					<thead>
						<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
							<th class="px-4 py-3">Tên kho</th>
							<th class="px-4 py-3 text-right">Còn</th>
							<th class="px-4 py-3 text-right">Lấy</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php foreach ( $warehouses as $warehouse ) : ?>
					<tr class="text-gray-700 dark:text-gray-400">
						<td class="px-4 py-3 ten-kho" data-kho="<?= esc_attr( $warehouse->id ) ?>">
							<?= esc_html( $warehouse->ten ) ?>
						</td>
						<td class="px-4 py-3 text-right">
							<?= esc_html( $warehouse->soLuong ) ?>
						</td>
						<td class="px-4 py-3 text-right chon-kho">
							<input type="number" name="choose" style="width: 80px;" max="<?= esc_attr( $warehouse->soLuong ) ?>">
						</td>
					</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<div class="flex justify-end mt-4 px-4">
					<button class="btn-close px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg">
						Lưu
					</button>
				</div>
				<?php else : ?>
					<p class="mt-4 px-4 py-3">Sản phẩm này chưa có ở kho nào</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>

<?php foreach ( $products as $product ) :
	$warehouses = $wpdb->get_results( $wpdb->prepare(
		'SELECT * FROM sanpham_kho as sk INNER JOIN kho as k
	 	 ON sk.idKho = k.id
		 WHERE sk.idSanPham=%d
		 ORDER BY sk.id DESC',
		$product->id
	) );
	?>
<div id="product-<?= esc_attr( $product->id ) ?>" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Danh sách Kho</h5>
				<button type="button" class="btn-close dashicons dashicons-no" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php if ( $warehouses ) : ?>
				<table>
					<thead>
						<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
							<th class="px-4 py-3">Mã kho</th>
							<th class="px-4 py-3">Tên kho</th>
							<th class="px-4 py-3">Số lượng</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php foreach ( $warehouses as $warehouse ) : ?>
					<tr class="text-gray-700 dark:text-gray-400">
						<td class="px-4 py-3">
							#<?= esc_html( $warehouse->id ) ?>
						</td>
						<td class="px-4 py-3">
							<?= esc_html( $warehouse->ten_kho ) ?>
						</td>
						<td class="px-4 py-3">
							<?= esc_html( $warehouse->soLuong ) ?>
						</td>
					</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>

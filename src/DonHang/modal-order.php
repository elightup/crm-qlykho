<?php foreach ( $orders as $order ) : ?>
<div id="order-<?= esc_attr( $order->id ) ?>" class="modal fade">
	<div class="modal-dialog" style="max-width: 1200px">
		<div class="modal-content">
			<div class="modal-header bg-blue-600">
				<h5 class="modal-title text-white">Thông tin Đơn hàng</h5>
				<button type="button" class="btn-close dashicons dashicons-no text-white" aria-label="Close"></button>
			</div>
			<div class="modal-body" style="padding-top: 20px;">
				<h5 class="text-xl font-semibold mb-4 flex">Thông tin khách hàng</h5>
				<div class="mb-8 md:w-1/2">
					<?php
					$user = get_user_by( 'id', $order->id_user );
					?>
					<div class="user-info__item flex mb-2">
						<div class="user-info__label font-semibold">
							Tên khách hàng:
						</div>
						<div class="user-info__value">
							<?= esc_html( $user->data->display_name ); ?>
						</div>
					</div>
					<div class="user-info__item flex mb-2">
						<div class="user-info__label font-semibold">
							Số điện thoại:
						</div>
						<div class="user-info__value">
							<?php
							$user_phone = get_user_meta( $order->id_user, 'user_phone', true );
							echo esc_html( $user_phone );
							?>
						</div>
					</div>
					<div class="user-info__item flex mb-2">
						<div class="user-info__label font-semibold">
							Địa chỉ:
						</div>
						<div class="user-info__value">
						<?php
							$user_address = get_user_meta( $order->id_user, 'address', true );
							echo esc_html( $user_address );
						?>
						</div>
					</div>
				</div>
				<h5 class="text-xl font-semibold mt-8 mb-4 flex">Thông tin đơn hàng</h5>
				<table class="table table-striped w-full overflow-hidden shadow-xs mb-8">
					<thead>
						<tr class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
							<th class="px-4 py-3">Mã đơn</th>
							<th class="px-4 py-3 whitespace-no-wrap">Ngày lên đơn</th>
							<th class="px-4 py-3 whitespace-no-wrap">Sản phẩm</th>
							<th class="px-4 py-3 whitespace-no-wrap text-right">Tổng tiền</th>
							<th class="px-4 py-3 whitespace-no-wrap">Trạng thái</th>
						</tr>
					</thead>
					<tbody class="list data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
						<tr class="text-gray-700 dark:text-gray-400" data-order="<?= esc_attr( $order->id ) ?>">
							<td class="px-4 py-3">#<?= esc_html( $order->id ) ?></td>
							<td class="px-4 py-3">
								<?= esc_html( $order->ngay ) ?>
							</td>
							<td class="px-4 py-3">
								<?php
								$list_product = json_decode( $order->san_pham );
								foreach ( $list_product as $product_id => $product ) {
									$product_name = $wpdb->get_col( $wpdb->prepare(
										'SELECT ten FROM san_pham
										 WHERE id=%d',
										$product_id
									) );

									echo '<b>' . $product->quantity . ':</b> ' . $product_name[0] . '<br>';
								}
								?>
							</td>
							<td data-tong-tien="<?= esc_attr( $order->tong_tien ) ?>" class="px-4 py-3 text-right">
								<?= esc_html( number_format( $order->tong_tien, 0, ',', '.' ) ) ?>
							</td>
							<td class="px-4 py-3">
								<?= esc_html( $order->trang_thai ) ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php endforeach; ?>

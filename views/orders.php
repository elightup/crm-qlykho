<?php
	global $wpdb;
?>
<div class="wrap">
	<div class="crm-title">
		<h2 class="text-lg px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Lên đơn</h2>
	</div>
	<div class="crm-content">
		<div class="px-4 py-3 mb-8">
			<?php
			$products = $wpdb->get_results( 'SELECT * FROM san_pham ORDER BY id DESC' );
			require CRM_DIR . '/src/DonHang/modal-kho.php';
			?>
			<table class="table table-add-order w-full overflow-hidden rounded-lg shadow-xs mb-4">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="px-4 py-3">Sản phẩm</th>
						<th class="px-4 py-3 text-right">Giá</th>
						<th class="px-4 py-3 whitespace-no-wrap">Số lượng</th>
						<th class="px-4 py-3 whitespace-no-wrap">Chiết khấu</th>
						<th class="px-4 py-3 whitespace-no-wrap text-right">Tổng tiền</th>
						<th class="px-4 py-3 whitespace-no-wrap text-right"></th>
					</tr>
				</thead>
				<tbody class="table-product bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<tr class="clone-product text-gray-700 dark:text-gray-400">
						<td class="px-4 py-3 product-name">
							<select name="product_name" id="product__name" class="rwmb">
								<option value="" selected hidden>Chọn sản phẩm</option>
								<?php
								$sql     = 'SELECT * FROM san_pham ORDER BY id DESC';
								$sanpham = $wpdb->get_results( $sql );
								foreach ( $sanpham as $sp ) :
									$number = $wpdb->get_col( $wpdb->prepare(
										'SELECT SUM(soLuong) FROM san_pham_kho
										 WHERE idSanPham=%d',
										$sp->id
									) );
									$number = $number[0] ? $number[0] : '';
									?>
									<option data-soluong="<?= esc_attr( $number ); ?>" data-price="<?= esc_attr( $sp->gia_niem_yet ); ?>" value="<?= esc_attr( $sp->id );?>"><?= esc_html( $sp->ten );?></option>
									<?php
								endforeach;
								?>
							</select>
						</td>
						<td class="px-4 py-3 product-price text-right clearable"></td>
						<td class="px-4 py-3 product-number">
							<input class="" type="number" min="0" value="0" style="width: 5rem">
							<button class="popup-kho px-4 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple btn_add_product"
								data-popup="">
								Chọn kho
							</button>
						</td>
						<td class="px-4 py-3"></td>
						<td class="px-4 py-3 product-sub-total clearable text-right"></td>
						<td class="px-4 py-3 text-red-600 remove-product-order">
							<span class="dashicons dashicons-no"></span>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="mb-4 text-right product-total text-lg font-medium">Tổng: <span class="clearable"></span></div>
			<div class="flex justify-between">
				<div>
					<button class="add-order px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
						Lên đơn
					</button>
					<button class="clear-order px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
						Nhập lại
					</button>
				</div>
				<button class="add-product-order px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
					Thêm sản phẩm
				</button>
			</div>
		</div>
		<div class="px-4 py-3 mb-8 mx-1">
			<fieldset class="crm-action" style="margin: 0">
				<legend><h2 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Khách hàng</h2></legend>
				<div class="action_input">
					<div class="action_input-item action_user">
						<select name="user_name" id="user_name">
							<option value=""><?= esc_html( 'Chọn user' );?></option>
							<?php
							$users = get_users();
							foreach ( $users as $user ) :
								$user_id   = $user->ID;
								$user_name = $user->display_name;
								?>
								<option value="<?= esc_attr( $user_id );?>"><?= esc_html( $user_name );?></option>
								<?php
							endforeach;
							?>
						</select>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="crm-content mt-8 table-order" x-data="data()">
		<div id="crm-table" class="crm-table">
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-700">Danh sách đơn hàng</h2>
			<?php
			$sql    = 'SELECT * FROM don_hang ORDER BY id DESC';
			$orders = $wpdb->get_results( $sql );
			require CRM_DIR . '/src/DonHang/modal-order.php';
			?>
			<table class="table table-striped w-full overflow-hidden rounded-lg shadow-xs">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="px-4 py-3">Mã đơn</th>
						<th class="px-4 py-3">Tên khách hàng</th>
						<th class="px-4 py-3 whitespace-no-wrap">Ngày lên đơn</th>
						<th class="px-4 py-3 whitespace-no-wrap text-right">Tổng tiền</th>
						<th class="px-4 py-3 whitespace-no-wrap">Trạng thái</th>
						<th class="px-4 py-3">Hành động</th>
					</tr>
				</thead>
				<tbody class="list data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php foreach ( $orders as $order ) : ?>
						<tr class="text-gray-700 dark:text-gray-400" data-order="<?= esc_attr( $order->id ) ?>">
							<td class="px-4 py-3">#<?= esc_html( $order->id ) ?></td>
							<td class="px-4 py-3">
								<?php
								$user = get_user_by( 'id', $order->id_user );
								echo esc_html( $user->data->display_name );
								?>
							</td>
							<td class="px-4 py-3">
								<?= esc_html( $order->ngay ) ?>
							</td>
							<td data-tong-tien="<?= esc_attr( $order->tong_tien ) ?>" class="px-4 py-3 text-right">
								<?= esc_html( number_format( $order->tong_tien, 0, ',', '.' ) ) ?>
							</td>
							<td class="px-4 py-3">
								<?= esc_html( $order->trang_thai ) ?>
							</td>

							<td class="px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button class="button-detail popup-kho px-2 py-2 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" data-popup="order-<?= esc_attr( $order->id ) ?>">
										<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
										</svg>
									</button>
									<button class="button-edit px-2 py-2 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-order="<?= esc_attr( $order->id ) ?>" @click="openModal" class="button-remove px-2 py-2 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
									</button>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					<div
						x-show="isModalOpen"
						x-transition:enter="transition ease-out duration-150"
						x-transition:enter-start="opacity-0"
						x-transition:enter-end="opacity-100"
						x-transition:leave="transition ease-in duration-150"
						x-transition:leave-start="opacity-100"
						x-transition:leave-end="opacity-0"
						class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
						style="display: none"
					>
						<!-- Modal -->
						<div
							x-show="isModalOpen"
							x-transition:enter="transition ease-out duration-150"
							x-transition:enter-start="opacity-0 transform translate-y-1/2"
							x-transition:enter-end="opacity-100"
							x-transition:leave="transition ease-in duration-150"
							x-transition:leave-start="opacity-100"
							x-transition:leave-end="opacity-0  transform translate-y-1/2"
							@click.away="closeModal"
							@keydown.escape="closeModal"
							class="form-popup-remove w-full text-center px-6 py-4 relative overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-l"
							id="modal"
						>
							<!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
							<header class="flex justify-end absolute right-1">
								<button
								class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
								aria-label="close"
								@click="closeModal"
								>
								<svg
									class="w-4 h-4"
									fill="currentColor"
									viewBox="0 0 20 20"
									role="img"
									aria-hidden="true"
								>
									<path
									d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
									clip-rule="evenodd"
									fill-rule="evenodd"
									></path>
								</svg>
								</button>
							</header>
							<!-- Modal body -->
							<div class="mb-6">
								<!-- Modal title -->
								<p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Xác nhận</p>
								<!-- Modal description -->
								<p class="text-sm text-gray-700 dark:text-gray-400">
									Bạn có chắc chắn muốn xóa đơn hàng này
								</p>
							</div>
							<footer
								class="flex flex-col items-center justify-center px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
							>
								<button
								@click="closeModal"
								class="px-5 py-3 text-sm w-24 font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
								>
								Hủy
								</button>
								<button
								:class="isModalOpen ? 'confirmed' : ''"
								@click="closeModal"
								class="confirm-remove px-5 py-3 w-24 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg sm:px-4 sm:py-2 focus:outline-none focus:shadow-outline-purple"
								>
								Xác nhận
								</button>
							</footer>
						</div>
					</div>
				</tbody>
			</table>
			<span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
				<ul class="pagination inline-flex items-center mt-4"></ul>
			</span>
		</div>
	</div>
</div>

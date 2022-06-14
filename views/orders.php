<?php
	global $wpdb;
	$sql    = 'SELECT * FROM don_hang ORDER BY id DESC';
	$orders = $wpdb->get_results( $sql );
?>
<div class="wrap">
	<div class="crm-content" x-data="data()">
		<div id="crm-table" class="crm-table">
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danh sách đơn hàng</h2>
			<table class="table table-striped w-full overflow-hidden rounded-lg shadow-xs">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="px-4 py-3">Mã</th>
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
									<button class="button-detail flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
										<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
										</svg>
									</button>
									<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-order="<?= esc_attr( $order->id ) ?>" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
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
							class="form-popup-remove w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
							id="modal"
						>
							<!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
							<header class="flex justify-end">
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
							<div class="mt-4 mb-6">
								<!-- Modal title -->
								<p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Xác nhận</p>
								<!-- Modal description -->
								<p class="text-sm text-gray-700 dark:text-gray-400">
									Bạn có chắc chắn muốn xóa đơn hàng này
								</p>
							</div>
							<footer
								class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
							>
								<button
								@click="closeModal"
								class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
								>
								Hủy
								</button>
								<button
								:class="isModalOpen ? 'confirmed' : ''"
								@click="closeModal"
								class="confirm-remove w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 focus:outline-none focus:shadow-outline-purple"
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
		<!-- <fieldset class="crm-action">
			<legend><h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Thêm sản phẩm</h2></legend>
			<div class="action_input">
				<div class="action_input-item">
					<label for="ten">Tên sản phẩm <span class="action-required">*</span></label>
					<input class="deleteable" type="text" id="ten" name="ten" autofocus />
				</div>
				<div class="action_input-item">
					<label for="gia_niem_yet">Giá niêm yết <span class="action-required">*</span></label>
					<input class="deleteable" type="number" id="gia_niem_yet" name="gia_niem_yet" />
				</div>
				<div class="action_input-item">
					<label for="gia_ban_le">Giá bán lẻ <span class="action-required">*</span></label>
					<input class="deleteable" type="number" id="gia_ban_le" name="gia_ban_le" />
				</div>
				<div class="action_input-item">
					<label for="gia_ban_buon">Giá bán buôn <span class="action-required">*</span></label>
					<input class="deleteable" type="number" id="gia_ban_buon" name="gia_ban_buon" />
				</div>
				<div class="action_input-item">
					<label for="thong_so_ky_thuat">Thông số kỹ thuật:</label>
					<textarea class="deleteable" id="thong_so_ky_thuat" name="thong_so_ky_thuat" rows="5"></textarea>
				</div>
				<div class="action_input-item">
					<label for="hinh_anh">Link hình ảnh:</label>
					<input class="deleteable" type="text" id="hinh_anh" name="hinh_anh" />
				</div>
			</div>
			<div class="action_btn">
				<button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple btn_add_product">Thêm</button>
				<button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple btn_clear_product">Hủy</button>
			</div>
		</fieldset> -->
	</div>
</div>

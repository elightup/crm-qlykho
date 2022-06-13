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
	<div class="crm-content" x-data="data()">
		<div id="crm-table" class="crm-table">
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danh sách sản phẩm</h2>
			<div class="form-search lg:mr-32 mb-4">
				<div
				class="relative w-full max-w-xl mr-6 focus-within:text-purple-500"
				>
				<div class="absolute inset-y-0 flex items-center pl-2">
					<svg
					class="w-4 h-4"
					aria-hidden="true"
					fill="currentColor"
					viewBox="0 0 20 20"
					>
					<path
						fill-rule="evenodd"
						d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
						clip-rule="evenodd"
					></path>
					</svg>
				</div>
				<input
					class="search w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
					type="text"
					placeholder="Tìm kiếm sản phẩm"
				/>
				</div>
			</div>

			<table class="table table-striped w-full overflow-hidden rounded-lg shadow-xs">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="px-4 py-3">Mã</th>
						<th class="px-4 py-3 whitespace-no-wrap">Hình ảnh</th>
						<th class="px-4 py-3">Tên Sản phẩm</th>
						<th class="px-4 py-3 whitespace-no-wrap text-right">Giá niêm yết</th>
						<th class="px-4 py-3 whitespace-no-wrap text-right">Giá bán lẻ</th>
						<th class="px-4 py-3 whitespace-no-wrap text-right">Giá bán buôn</th>
						<th class="px-4 py-3">Thông số kỹ thuật</th>
						<th class="px-4 py-3">Hành động</th>
					</tr>
				</thead>
				<tbody class="list data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php foreach ( $products as $key => $product ) : ?>
						<tr class="text-gray-700 dark:text-gray-400" data-product="<?= esc_attr( $product->id ) ?>">
							<td class="px-4 py-3">#<?= esc_html( $product->id ) ?></td>
							<td data-link-image="<?= esc_attr( $product->hinhanh ) ?>" class="product__thumbnail px-4 py-3">
								<div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
									<img class="object-cover w-full h-full rounded-full border-0" src="<?= esc_url( $product->hinhanh ) ?>">
								</div>
							</td>
							<td class="product__name px-4 py-3"><?= esc_html( $product->ten_sp ) ?></td>
							<td data-gia-niem-yet="<?= esc_attr( $product->gia_niem_yet ) ?>" class="product__gia-niem-yet px-4 py-3 text-right">
								<?= esc_html( number_format( $product->gia_niem_yet, 0, ',', '.' ) ) ?>
							</td>
							<td data-gia-ban-le="<?= esc_attr( $product->gia_ban_le ) ?>" class="product__gia-ban-le px-4 py-3 text-right">
								<?= esc_html( number_format( $product->gia_ban_le, 0, ',', '.' ) ) ?>
							</td>
							<td data-gia-ban-buon="<?= esc_attr( $product->gia_ban_buon ) ?>" class="product__gia-ban-buon px-4 py-3 text-right">
								<?= esc_html( number_format( $product->gia_ban_buon, 0, ',', '.' ) ) ?>
							</td>
							<td class="product__thongso px-4 py-3"><?= esc_html( wp_trim_words( $product->thongso_kythuat, 15 ) ) ?></td>
							<td class="px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-product="<?= esc_attr( $product->id ) ?>" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
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
									Bạn có chắc chắn muốn xóa sản phẩm này
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
		<fieldset class="crm-action">
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
		</fieldset>
	</div>
</div>
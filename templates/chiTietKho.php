<?php
global $wpdb;
$id_kho   = $_GET['id'];
$sql      = 'SELECT * FROM sanpham_kho WHERE idKho = ' . $id_kho . ' ORDER BY id DESC';
$products = $wpdb->get_results( $sql );
?>
<div class="wrap">
	<div class="crm-list" x-data="data()">
		<div id="crm-table" class="crm-table">
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danh sách sản phẩm trong kho</h2>
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
						<th class="px-4 py-3">Mã sản phẩm</th>
						<th class="px-4 py-3">Tên sản phẩm</th>
						<th class="px-4 py-3">Số lượng</th>
						<th class="px-4 py-3">Hành động</th>
					</tr>
				</thead>
				<tbody class="list data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php
					foreach ( $products as $product ) :
						$idsp     = $product->idSanPham;
						$sql_sp   = 'SELECT * FROM sanpham WHERE id = ' . $idsp;
						$san_pham = $wpdb->get_results( $sql_sp );
						$numbersp = $product->soLuong;
						?>
						<tr class="text-gray-700 dark:text-gray-400" product-id="<?= esc_attr( $idsp );?>" >
							<td class="px-4 py-3">#<?= esc_html( $idsp ) ?></td>
							<td product-name="<?= esc_attr( $san_pham[0]->ten_sp );?>" class="name_product product__name px-4 py-3"><?= esc_attr( $san_pham[0]->ten_sp );?></td>
							<td product-number="<?= esc_attr( $numbersp );?>" class="product-number px-4 py-3"><?= esc_html( $numbersp ) ?></td>

							<td class="action px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button data-kho="<?= esc_attr( $id_kho ) ?>" class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-kho="<?= esc_attr( $id_kho ) ?>" @click="openList" class="remove-product flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
									</button>
								</div>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php require CRM_DIR . 'src/Kho/list.php'; ?>
				</tbody>
			</table>
			<span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
				<ul class="pagination inline-flex items-center mt-4"></ul>
			</span>
		</div>
		<fieldset class="crm-action">
			<legend><h2 class="title-action mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Thêm sản phẩm vào kho</h2></legend>
			<div class="action_input">
				<div class="add-product__wrap">
					<label for="ten" >Sản phẩm: <span class="action-required">*</span></label>
					<label for="ten" >Số lượng:</label>
				</div>
				<div class="add-product__inner">
					<div class="add-product">
						<select name="product_name" id="product__name" class="rwmb">
							<option value="" selected hidden>Chọn sản phẩm</option>
							<?php
							$sql     = 'SELECT * FROM sanpham ORDER BY id DESC';
							$sanpham = $wpdb->get_results( $sql );
							foreach ( $sanpham as $sp ) :
								$id_sp  = $sp->id;
								$hidden = '';
								foreach ( $products as $product ) {
									$idproduct = $product->idSanPham;
									if ( $id_sp === $idproduct ) {
										$hidden = 'hidden';
									}
								}
								?>
								<option value="<?= esc_attr( $sp->id );?>" <?= esc_attr( $hidden );?>><?= esc_html( $sp->ten_sp );?></option>
								<?php
							endforeach;
							?>
						</select>
						<input type="number" name="number_product" id="number_product" class="rwmb">
					</div>
				</div>
			</div>
			<div class="action_btn">
				<button data-kho="<?= esc_attr( $id_kho );?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple add_product_kho">Thêm</button>
				<button data-kho="<?= esc_attr( $id_kho );?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple save_product">Lưu</button>
				<button data-kho="<?= esc_attr( $id_kho );?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple btn-clear disabled" disabled="disabled">Hủy</button>
			</div>
		</fieldset>
	</div>
</div>

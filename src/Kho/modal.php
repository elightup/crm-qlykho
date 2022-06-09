<div class="modal fade" data-kho="<?= esc_attr( $warehouse->id );?>" id="modal_<?= esc_attr( $warehouse->id );?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Danh sách sản phẩm <?= esc_html( $warehouse->ten_kho ) ?></h5>
				<button type="button" class="btn-close dashicons dashicons-no" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" data-kho="<?= esc_attr( $warehouse->id ) ?>">
				<div class="modal-body__top text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800 ">
					<div class="modal-body__id px-4 py-3">Mã sản phẩm</div>
					<div class="modal-body__name px-4 py-3">Tên sản phẩm</div>
					<div class="modal-body__name px-4 py-3">Số lượng</div>
					<div class="modal-body__name px-4 py-3">Hành động</div>
				</div>
				<div class="modal-body__content" data-kho="<?= esc_attr( $warehouse->id );?>">
					<?php
					$i = 1;
					foreach ( $products as $product ) :
						$idsp     = $product->idSanPham;
						$sql_sp   = 'SELECT * FROM sanpham WHERE id = ' . $idsp;
						$san_pham = $wpdb->get_results( $sql_sp );
						$numbersp = $product->soLuong;
						?>
						<div class="modal-body__inner" data-product="<?= esc_attr( $idsp );?>">
							<div class="modal-body__id px-4 py-3">#<?= esc_html( $idsp );?></div>
							<div data-name="<?= esc_attr( $san_pham[0]->ten_sp );?>" class="product__name px-4 py-3">
								<?php
								echo esc_html( $san_pham[0]->ten_sp );
								?>
							</div>
							<div data-number="<?= esc_attr( $numbersp );?>" class="product__number px-4 py-3"><?= esc_html( $numbersp );?></div>
							<div class="modal-body__action px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button data-kho="<?= esc_attr( $warehouse->id ) ?>" class="edit-product flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-kho="<?= esc_attr( $warehouse->id ) ?>" @click="openList" class="remove-product flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
									</button>
								</div>
							</div>
						</div>
						<?php
						$i++;
					endforeach;
					?>
				</div>
				<div class="modal-body__product">
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
				<div class="modal-body__add">
					<button data-kho="<?= esc_attr( $warehouse->id );?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple add_product_kho">Thêm</button>
					<button data-kho="<?= esc_attr( $warehouse->id );?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple save_product">Lưu</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_<?= esc_attr( $warehouse->id );?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Danh sách sản phẩm <?= esc_html( $warehouse->ten_kho ) ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="modal-body__top">
					<div class="modal-body__id">STT</div>
					<div class="modal-body__name">Tên sản phẩm</div>
					<div class="modal-body__name">Số lượng</div>
					<div class="modal-body__name"></div>
				</div>
				<div class="modal-body__content">
					<?php
					$i = 1;
					foreach ( $products as $product ) :
						$idsp     = $product->idSanPham;
						$sql_sp   = 'SELECT * FROM sanpham WHERE id = ' . $idsp;
						$san_pham = $wpdb->get_results( $sql_sp );
						$numbersp = $product->soLuong;
						?>
						<div class="modal-body__inner">
							<div class="modal-body__id"><?= esc_html( $san_pham[0]->id )?></div>
							<div class="modal-body__name">
								<?php
								echo esc_html( $san_pham[0]->ten_sp );
								?>
							</div>
							<div class="modal-body__name"><?= esc_html( $numbersp );?></div>
							<div class="modal-body__name">
								<span class="dashicons dashicons-edit" title="Sửa"></span>
								<span class="dashicons dashicons-no" title="Xóa"></span>
								<span class="dashicons dashicons-saved" title="Lưu"></span>
							</div>
						</div>
						<?php
						$i++;
					endforeach;
					?>
				</div>
				<div class="modal-body__product">
					<input type="text" name="idkho" id="idkho" value="<?= esc_attr( $warehouse->id );?>" hidden>
					<div class="add-product">
						<select name="product_name" id="product_name" class="rwmb">
							<?php
							$sql     = 'SELECT * FROM sanpham ORDER BY id DESC';
							$sanpham = $wpdb->get_results( $sql );
							foreach ( $sanpham as $sp ) :
								?>
								<option value="<?= esc_attr( $sp->id );?>"><?= esc_html( $sp->ten_sp );?></option>
								<?php
							endforeach;
							?>
						</select>
						<input type="number" name="number_product" id="number_product" class="rwmb">
					</div>
				</div>
				<div class="modal-body__add">
					<button class="btn add_product_kho">Thêm</button>
					<button class="btn save_product">Lưu</button>
				</div>
			</div>
		</div>
	</div>
</div>

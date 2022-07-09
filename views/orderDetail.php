<?php
global $wpdb;
$actual_link = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if ( isset( $_POST['submit'] ) ) {

	$id          = $_GET['id'];
	$total_price = $_POST['total-price'];
	$trang_thai  = $_POST['trang-thai'];
	$user_id     = $_POST['user_name'];
	$data        = json_decode( filter_input( INPUT_POST, 'data-sp' ), true );

	$wpdb->update(
		'don_hang',
		[
			'san_pham'   => json_encode( $data ),
			// 'ngay'       => current_time( 'mysql' ),
			'tong_tien'  => (int) $total_price,
			'id_user'    => $user_id,
			'trang_thai' => $trang_thai,
		],
		[ 'id' => $id ]
	);
}

$order = $wpdb->get_results( $wpdb->prepare(
	'SELECT * FROM don_hang
	 WHERE id=%d',
	$_GET['id']
) );
$order = $order[0];
?>
<div class="wrap">
	<div class="crm-list detail-order" x-data="data()">
		<div id="" class="crm-table">
			<form action="" method="POST" id="form-update-order" class="form-update-order">
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
							<select name="user_name" id="">
								<?php
								$users = get_users();
								foreach ( $users as $user ) :
									$user_id   = $user->ID;
									$user_name = $user->display_name;
									?>
									<option value="<?= esc_attr( $user_id ) ?>" <?php selected( $user_id, $order->id_user ) ?> ><?= esc_html( $user_name );?></option>
									<?php
								endforeach;
								?>
							</select>
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
							<th class="px-4 py-3 whitespace-no-wrap text-right">Tổng tiền</th>
							<th class="px-4 py-3 whitespace-no-wrap">Trạng thái</th>
						</tr>
					</thead>
					<tbody class="list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
						<tr class="text-gray-700 dark:text-gray-400" data-order="<?= esc_attr( $order->id ) ?>">
							<td class="px-4 py-3">#<?= esc_html( $order->id ) ?></td>
							<td class="px-4 py-3" name="order-date">
								<?= esc_html( $order->ngay ) ?>
							</td>
							<td data-tong-tien="<?= esc_attr( $order->tong_tien ) ?>" class="px-4 py-3 text-right total-price">
								<?= esc_html( number_format( $order->tong_tien, 0, ',', '.' ) ) ?>
							</td>
							<input type="hidden" name="total-price" value="<?= esc_attr( $order->tong_tien ) ?>">
							<td class="px-4 py-3">
								<select name="trang-thai" id="">
									<option value="Đã lên đơn" <?php selected( 'Đã lên đơn', $order->trang_thai ); ?>>Đã lên đơn</option>
									<option value="Báo giá" <?php selected( 'Báo giá', $order->trang_thai ); ?>>Báo giá</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<h5 class="text-xl font-semibold mt-8 mb-4 flex">Danh sách sản phẩm</h5>
				<table class="table table-striped w-full overflow-hidden shadow-xs mb-8">
					<thead>
						<tr class="text-xs font-semibold tracking-wide text-left text-gray-700 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
							<th class="px-4 py-3 whitespace-no-wrap">Tên sản phẩm</th>
							<th class="px-4 py-3 whitespace-no-wrap">Số lượng</th>
							<th class="px-4 py-3 whitespace-no-wrap">Kho</th>
							<th class="px-4 py-3 whitespace-no-wrap text-right">Giá</th>
							<th class="px-4 py-3 whitespace-no-wrap text-right">Tổng tiền</th>
							<th class="px-4 py-3">Hành động</th>
						</tr>
					</thead>
					<tbody class="data-list list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
						<?php
						$list_product = json_decode( $order->san_pham );
						if ( $list_product ) :
							foreach ( $list_product as $product_id => $product ) :
								$san_pham = $wpdb->get_results( $wpdb->prepare(
									'SELECT * FROM san_pham
										WHERE id=%d',
									$product_id
								) );
								?>
								<tr class="text-gray-700 dark:text-gray-400" data-product="<?= esc_attr( $product_id ) ?>">
									<td class="px-4 py-3"><?= esc_html( $san_pham[0]->ten ) ?></td>
									<td class="px-4 py-3">
									<?= esc_html( $product->quantity ) ?>
									</td>
									<td class="px-4 py-3">
									<?php
									foreach ( $product->warehouse as $warehouse ) {
										$warehouse_name = $wpdb->get_col( $wpdb->prepare(
											'SELECT ten FROM kho
												WHERE id=%d',
											$warehouse->id
										) );

										echo '<b>' . $warehouse->quantity . ':</b> ' . $warehouse_name[0] . '<br>';
									}
									?>
									</td>
									<td data-tong-tien="<?= esc_attr( $order->tong_tien ) ?>" class="px-4 py-3 text-right">
										<?= esc_html( number_format( $product->price, 0, ',', '.' ) ) ?>
									</td>
									<td class="px-4 py-3 text-right">
										<?php
										$total = $product->quantity * $product->price;
										echo esc_html( number_format( $total, 0, ',', '.' ) );
										?>
									</td>
									<td class="action px-4 py-3">
										<div class="flex items-center space-x-4 text-sm">
											<!-- <div class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
												<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
													<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
												</svg>
											</div> -->
											<div @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
												<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
													<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
												</svg>
											</div>
										</div>
									</td>
								</tr>
								<?php
							endforeach;
						endif;
						?>
						<?php
						$product = $wpdb->get_results( $wpdb->prepare(
							'SELECT san_pham FROM don_hang
							 WHERE id=%d',
							$_GET['id']
						) );
						$product = $product[0]->san_pham ? json_decode( $product[0]->san_pham, true ) : [];
						?>
						<input type="hidden" value="<?= esc_attr( json_encode( $product ) ) ?>" name="data-sp">

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
									<div
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
									</div>
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
									<div
									@click="closeModal"
									class="px-5 py-3 text-sm w-24 font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
									>
									Hủy
									</div>
									<div
									:class="isModalOpen ? 'confirmed' : ''"
									@click="closeModal"
									class="confirm-remove-product px-5 py-3 w-24 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg sm:px-4 sm:py-2 focus:outline-none focus:shadow-outline-purple"
									>
									Xác nhận
									</div>
								</footer>
							</div>
						</div>
					</tbody>
				</table>
				<input type="submit" name="submit" value="Cập nhật" class="update-order px-4 py-2 font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-lg" style="cursor: pointer">
				<!-- <button class="update-order px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
					Cập nhật
				</button> -->
			</form>
		</div>
		<?php
		$products = $wpdb->get_results( 'SELECT * FROM san_pham ORDER BY id DESC' );
		require CRM_DIR . '/src/DonHang/modal-kho.php';
		?>
		<fieldset class="crm-action">
			<legend><h2 class="title-action mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Thêm sản phẩm vào đơn</h2></legend>
			<div class="action_input">
				<div class="add-product__wrap">
					<label for="ten" >Sản phẩm: <span class="action-required">*</span></label>
					<label for="ten" >Số lượng:</label>
				</div>
				<div class="add-product__inner">
					<div class="add-product">
						<div class="product-name">
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
									<option value="<?= esc_attr( $sp->id ); ?>" data-soluong="<?= esc_attr( $number ); ?>" data-price="<?= esc_attr( $sp->gia_niem_yet ); ?>" >
										<?= esc_html( $sp->ten ); ?>
									</option>
									<?php
								endforeach;
								?>
							</select>
						</div>
						<div class="product-number">
							<input type="number" name="number_product" id="number_product" min="0" value="0" style="width: 5rem;">
							<button class="popup-kho px-4 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple btn_add_product"
								data-popup="">
								Chọn kho
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="action_btn">
				<button class="add-product-detail px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
					Thêm
				</button>
				<button class="clear-product px-4 py-2 font-medium text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 focus:outline-none focus:shadow-outline-purple">
					Nhập lại
				</button>
			</div>
		</fieldset>
	</div>
</div>

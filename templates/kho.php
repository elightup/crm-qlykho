<?php
	global $wpdb;
	$sql        = 'SELECT * FROM kho ORDER BY id DESC';
	$warehouses = $wpdb->get_results( $sql );
?>

<div class="wrap">

	<div class="crm-content" x-data="data()">
		<div class="crm-table">
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danh sách Kho</h2>
			<table class="table table-striped w-full overflow-hidden rounded-lg shadow-xs">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="px-4 py-3">Mã Kho</th>
						<th class="px-4 py-3">Tên Kho</th>
						<th class="px-4 py-3">Tên User</th>
						<th class="px-4 py-3">Email User</th>
						<th class="px-4 py-3">Số điện thoại</th>
						<th class="px-4 py-3">Hành động</th>
					</tr>
				</thead>
				<tbody class="data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php
					foreach ( $warehouses as $key => $warehouse ) :
						$user      = get_user_by( 'id', $warehouse->id_user );
						$user_id   = $user->ID;
						$user_meta = get_user_meta( $user_id, 'user_phone', true );
						$id_kho    = $warehouse->id;
						$sql_kho   = 'SELECT * FROM sanpham_kho WHERE idKho = ' . $id_kho;
						$products  = $wpdb->get_results( $sql_kho );
						?>
						<tr class="text-gray-700 dark:text-gray-400" data-kho="<?= esc_attr( $warehouse->id );?>" >
							<td class="px-4 py-3">#<?= esc_html( $warehouse->id ) ?></td>
							<td data-name-kho="<?= esc_attr( $warehouse->ten_kho );?>" class="name_kho px-4 py-3"><?= esc_html( $warehouse->ten_kho ) ?></td>
							<td data-user="<?= esc_attr( $warehouse->id_user );?>" data-name-user="<?= esc_attr( $user->display_name ) ?>" class="name_user px-4 py-3"><?= esc_html( $user->display_name ) ?></td>
							<td class="email_user px-4 py-3"><?= esc_html( $user->user_email ) ?></td>
							<td class="phone_user px-4 py-3"><?= esc_html( $user_meta ) ?></td>
							<td class="action px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-kho="<?= esc_attr( $warehouse->id ) ?>" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
									</button>
									<button data-kho="<?= esc_attr( $warehouse->id ) ?>" class="button-view flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="View">
										<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
										</svg>
									</button>
								</div>
							</td>
						</tr>
						<?php include CRM_DIR . 'src/Kho/modal.php'; ?>
						<?php endforeach; ?>
						<?php require CRM_DIR . 'src/Kho/list.php'; ?>
						<?php require CRM_DIR . 'src/Kho/popup.php'; ?>
				</tbody>
			</table>
		</div>
		<fieldset class="crm-action">
			<legend><h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Thông tin kho</h2></legend>
			<div class="action_input">
				<div class="action_input-item">
					<label for="ten" >Tên kho: <span class="action-required">*</span></label>
					<input type="text" id="ten" name="ten" autofocus />
				</div>
				<div class="action_input-item action_user">
					<label for="user">User: <span class="action-required">*</span></label>
					<select name="user_name" id="user_name">
						<option value=""><?= esc_html( 'Chọn user' );?></option>
						<?php
						$users = get_users();
						foreach ( $users as $user ) :
							$user_id   = $user->ID;
							$user_name = $user->display_name;
							$hidden    = '';
							foreach ( $warehouses as $key => $warehouse ) :
								$warehouse_user = (int) $warehouse->id_user;
								if ( $user_id === $warehouse_user ) {
									$hidden = 'hidden';
								}
							endforeach;
							?>
							<option value="<?= esc_attr( $user_id );?>" <?= esc_attr( $hidden );?>><?= esc_html( $user->display_name );?></option>
							<?php
						endforeach;
						?>
					</select>
				</div>
			</div>
			<div class="action_btn">
				<button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple btn_add_kho">Lưu</button>
			</div>
		</fieldset>
	</div>
</div>

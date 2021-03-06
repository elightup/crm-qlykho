<?php
	global $wpdb;
	$sql        = 'SELECT * FROM kho ORDER BY id DESC';
	$warehouses = $wpdb->get_results( $sql );
?>

<div class="wrap">

	<div class="crm-content" x-data="data()">
		<div id="crm-table" class="crm-table">
			<h2 class="mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Danh sách Kho</h2>
			<div class="form-search lg:mr-32 mb-4">
				<div class="relative w-full max-w-xl mr-6 focus-within:text-purple-500">
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
					placeholder="Tìm kiếm kho"
				/>
				</div>
			</div>
			<table class="table table-striped w-full overflow-hidden rounded-lg shadow-xs">
				<thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
						<th class="ma_khos px-4 py-3 col-1">Mã Kho</th>
						<th class="px-4 py-3">Tên Kho</th>
						<th class="px-4 py-3">Tên User</th>
						<th class="px-4 py-3">Email User</th>
						<th class="phone_users px-4 py-3 col-2">Số điện thoại</th>
						<th class="px-4 py-3">Hành động</th>
					</tr>
				</thead>
				<tbody class="list data-list bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
					<?php
					foreach ( $warehouses as $warehouse ) :
						$user_id = $warehouse->id_user;
						if ( $user_id !== '0' ) {
							$user = get_user_by( 'id', $user_id );

							$user_name = $user->display_name;
							$usermail  = $user->user_email;
						} else {
							$user_name = '';
							$usermail  = '';
						}
						$user_meta = get_user_meta( $user_id, 'user_phone', true );
						$id_kho    = $warehouse->id;
						?>
						<tr class="text-gray-700 dark:text-gray-400" data-kho="<?= esc_attr( $warehouse->id );?>" >
							<td class="ma_kho px-4 py-3 col-1" ><?= esc_html( $warehouse->id ) ?></td>
							<td data-name-kho="<?= esc_attr( $warehouse->ten );?>" class="name_kho searchable px-4 py-3"><?= esc_html( $warehouse->ten ) ?></td>
							<td data-user="<?= esc_attr( $user_id );?>" data-name-user="<?= esc_attr( $user_name ) ?>" class="name_user px-4 py-3"><?= esc_html( $user_name ) ?></td>
							<td data-email="<?= esc_attr( $usermail ) ?>" class="email_user px-4 py-3"><?= esc_html( $usermail ) ?></td>
							<td data-phone="<?= esc_attr( $user_meta ) ?>" class="phone_user px-4 py-3 col-2"><?= esc_html( $user_meta ) ?></td>
							<td class="action px-4 py-3">
								<div class="flex items-center space-x-4 text-sm">
									<button data-kho="<?= esc_attr( $warehouse->id ) ?>" class="button-edit flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										</svg>
									</button>
									<button data-kho="<?= esc_attr( $warehouse->id ) ?>" @click="openModal" class="button-remove flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
										<svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										</svg>
									</button>
									<a data-kho="<?= esc_attr( $warehouse->id ) ?>" href="<?= admin_url( 'edit.php?page=kho' ) . '&id=' . $id_kho;?>" class="button-view flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-gray-500 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="View">
										<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
										</svg>
									</a>
								</div>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php require CRM_DIR . 'src/Kho/popup.php'; ?>
				</tbody>
			</table>
			<span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
				<ul class="pagination inline-flex items-center mt-4"></ul>
			</span>
		</div>
		<fieldset class="crm-action">
			<legend><h2 class="title-action-kho mt-4 mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Thêm thông tin kho</h2></legend>
			<div class="action_input">
				<div class="action_input-item">
					<label for="ten" >Tên kho: <span class="action-required">*</span></label>
					<input type="text" id="ten" name="ten" autofocus />
				</div>
				<div class="action_input-item action_user">
					<label for="user">User:</label>
					<select name="user_name" id="user_name">
						<option value="" phone="" email=""><?= esc_html( 'Chọn user' );?></option>
						<?php
						$users = get_users();
						foreach ( $users as $user ) :
							$user_id    = $user->ID;
							$user_name  = $user->display_name;
							$user_email = $user->user_email;
							$user_phone = get_user_meta( $user_id, 'user_phone', true );
							$hidden     = '';
							foreach ( $warehouses as $key => $warehouse ) :
								$warehouse_user = (int) $warehouse->id_user;
								if ( $user_id === $warehouse_user ) {
									$hidden = 'hidden';
								}
							endforeach;
							?>
							<option value="<?= esc_attr( $user_id );?>" phone="<?= esc_attr( $user_phone );?>" email="<?= esc_attr( $user_email );?>" <?= esc_attr( $hidden );?>><?= esc_html( $user->display_name );?></option>
							<?php
						endforeach;
						?>
					</select>
				</div>
			</div>
			<div class="action_btn" data-href="<?= admin_url( 'edit.php?page=kho' );?>">
				<button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple btn_add_kho">Thêm</button>
				<button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple btn-clear-kho">Hủy</button>
			</div>
		</fieldset>
	</div>
</div>

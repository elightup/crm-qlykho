<?php
namespace CRM;

class Field {
	public function __construct() {
		// add_action( 'admin_menu', array( $this, 'remove_menus' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'your_prefix_function_name' ) );
	}

	public function your_prefix_function_name( $meta_boxes ) {
		$prefix = '';

		$meta_boxes[] = [
			'title'  => __( 'Thông tin user', 'your-text-domain' ),
			'id'     => 'thong-tin-user',
			'type'   => 'user',
			'fields' => [
				[
					'name' => __( 'Họ tên', 'your-text-domain' ),
					'id'   => $prefix . 'ho_ten',
					'type' => 'text',
				],
				[
					'name' => __( 'Số điện thoại', 'your-text-domain' ),
					'id'   => $prefix . 'user_phone',
					'type' => 'text',
				],
				[
					'name' => __( 'Địa chỉ', 'your-text-domain' ),
					'id'   => $prefix . 'address',
					'type' => 'text',
				],
			],
		];

		return $meta_boxes;
	}
}

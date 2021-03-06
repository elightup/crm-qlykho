<?php
namespace CRM;

class Assets {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_style' ] );
	}

	public static function enqueue_style() {
		// wp_enqueue_style( 'crm_popup', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', '' );
		wp_enqueue_style( 'taiwind', CRM_URL . 'assets/css/tailwind.output.css', '', filemtime( CRM_DIR . 'assets/css/tailwind.output.css' ) );
		wp_enqueue_style( 'crm', CRM_URL . 'assets/css/style.css', '', filemtime( CRM_DIR . 'assets/css/style.css' ) );

		// wp_enqueue_script( 'script_popup', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', [], true );
		wp_enqueue_style( 'crm-style', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', [], '4.1' );
		wp_enqueue_script( 'crm_select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', [], '4.1', true );
		wp_enqueue_script( 'alpine', 'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js', [], '2', true );
		wp_enqueue_script( 'alpine_init', CRM_URL . 'assets/js/init-alpine.js', [ 'alpine' ], filemtime( CRM_DIR . 'assets/js/init-alpine.js' ), true );
		wp_enqueue_script( 'focus_trap_script', CRM_URL . 'assets/js/focus-trap.js', [], filemtime( CRM_DIR . 'assets/js/focus-trap.js' ), true );
		wp_enqueue_script( 'list-js', 'https://cdn.jsdelivr.net/npm/list.js@2.3.1/dist/list.min.js', [], '2.3.1', true );
		wp_enqueue_script( 'script', CRM_URL . 'assets/js/script.js', [ 'jquery' ], '1.0', true );

		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		if ( is_admin() && $page != '' ) {
			wp_enqueue_script( 'crm', CRM_URL . 'assets/js/' . $page . '.js', [], filemtime( CRM_DIR . 'assets/js/' . $page . '.js' ), true );
			wp_localize_script( 'crm', 'ProductParams', [
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			] );
		}
	}
}

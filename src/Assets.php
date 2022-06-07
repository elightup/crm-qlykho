<?php
namespace CRM;

class Assets {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_style' ] );
	}

	public static function enqueue_style() {
		// wp_enqueue_style( 'crm_popup', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', '' );
		wp_enqueue_style( 'crm_style', CRM_URL . 'assets/css/style.css', '', filemtime( CRM_DIR . 'assets/css/style.css' ) );
		wp_enqueue_style( 'taiwind_style', CRM_URL . 'assets/css/tailwind.output.css', '', filemtime( CRM_DIR . 'assets/css/tailwind.output.css' ) );

		// wp_enqueue_script( 'script_popup', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', [], true );
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		if ( is_admin() && $page != '' ) {
			wp_enqueue_script( 'crm_script', CRM_URL . 'assets/js/' . $page . '.js', [], filemtime( CRM_DIR . 'assets/js/' . $page . '.js' ), true );
			wp_localize_script( 'crm_script', 'ProductParams', [
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			] );
		}
	}
}

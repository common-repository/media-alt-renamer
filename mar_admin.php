<?php

//include "common/meow_admin.php";

class Meow_AltRenamer_Admin {

	public function __construct() {
		//parent::__construct();
		//add_action( 'admin_menu', array( $this, 'app_menu' ) );
		//add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	function admin_notices() {
	}

	function admin_enqueue_scripts() {
		wp_register_style( 'meowapps-admin-css', $this->common_url( 'meow-admin.css' ) );
		wp_enqueue_style( 'meowapps-admin-css' );
	}

	function common_url( $file ) {
		return plugin_dir_url( __FILE__ ) . ( '\/meow-common\/' . $file );
	}

}

?>

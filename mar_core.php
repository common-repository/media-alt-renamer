<?php

class Meow_AltRenamer_Core {

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/*
		INIT
	*/

	function init() {

		add_action( 'wp_ajax_mar_rename', array( $this, 'wp_ajax_mar_rename' ) );

		if ( is_admin() ) {
			include( 'mar_library.php' );
			new Meow_AltRenamer_Library;
			include( 'mar_admin.php' );
			new Meow_AltRenamer_Admin;
		}
	}

	/*
		CORE
	*/

	function log( $data ) {
		if ( !get_option( 'mar_debuglogs', false ) )
			return;
		$fh = fopen( trailingslashit( plugin_dir_path( __FILE__ ) ) . '/media-alt-renamer.log', 'a' );
		$date = date( "Y-m-d H:i:s" );
		fwrite( $fh, "$date: {$data}\n" );
		fclose( $fh );
	}

	function wp_ajax_mar_rename() {
		$id = intval( $_POST['id'] );
		$text = $_POST['text'];
		// $nonce = $_POST[ '_wpnonce_mar_rename' ];
		// if ( empty( $_POST ) || !wp_verify_nonce( $nonce, 'mar_rename-' . $id ) )
		// 	wp_send_json_error( __( 'Not authorized.' ) );
		$previous = get_post_meta( $id, '_wp_attachment_image_alt', true );
		update_post_meta( $id, '_wp_attachment_image_alt', $text );
		$this->log( "ALT set to $text (originally $previous) for Media $id." );
		do_action( 'mar_alt_renamed', $id, $text, $previous );
		wp_send_json_success( $text );
	}
}

?>

<?php

class Meow_AltRenamer_Library {

	public function __construct() {
		global $pagenow;
		if ( 'upload.php' == $pagenow ) {
			add_filter( 'manage_media_columns', array( $this, 'manage_media_columns' ) );
			add_action( 'manage_media_custom_column', array( $this, 'manage_media_custom_column' ), 10, 2 );
			add_action( 'admin_footer', array( $this, 'footer' ) );
		}
	}

	function admin_enqueue_scripts() {
		wp_register_style( 'meowapps-admin-css', $this->common_url( 'meow-admin.css' ) );
		wp_enqueue_style( 'meowapps-admin-css' );
	}

	function manage_media_columns( $cols ) {
		$cols["AltRenamer"] = "ALT text";
		return $cols;
	}

	function manage_media_custom_column( $column_name, $id ) {
		if ( $column_name != 'AltRenamer' )
			return;
			$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
			?>
			<div class="mar-media">

				<input style="width: 100%; font-size: 11px; transition: width 0.3s;" class="mar-alt-text"
					type="text" mar-origin="<?php echo $alt; ?>" value="<?php echo $alt; ?>">
				</input>
				<div style="width: 18%; display: none; float: right;" class="button button-primary meow-button-xs">
					<span class="dashicons dashicons-edit"></span>
				</div

			</div>
			<?php
			// <label><small style="margin: 0px 6px; color: gray;">PROGRESS: 0%</small></label>
	}

	function footer() {

		?>
		<script>
		(function($) {

			function update_alt_text(id, text) {
				var id = id;
				var row = $('tr[id="post-' + id + '"]');
				$.ajax( ajaxurl, {
					type: 'POST',
					data: { action: 'mar_rename', id: id, text: text },
					dataType: 'json'
				}).always( function() {
					row.find('.meow-button-xs').removeClass('updating-message');
					row.find('.mar-alt-text').prop( "disabled", false );
				}).done(function(x) {
					if (!x.success) {
						alert(x.data);
					}
					else {
						row.find('.mar-alt-text').attr('mar-origin', text);
						row.find('.meow-button-xs').css('display', 'none');
						row.find('.mar-alt-text').css('width', '100%');
					}
				}).fail( function() {
					alert("Error");
				});
			}

			$(document).ready(function($) {

				$('.mar-media .button').click(function(e) {
					var id = $(this).parents('tr').attr('id').replace('post-', '');
					var row = $('tr[id="post-' + id + '"]');
					var text = row.find('.mar-alt-text').val();
					row.find('.meow-button-xs').addClass('updating-message');
					row.find('.mar-alt-text').prop( "disabled", true );
					update_alt_text(id, text);
				});

				$('.mar-alt-text').keydown(function(e) {
					if (e.which == 13) {
						e.preventDefault();
						var id = $(this).parents('tr').attr('id').replace('post-', '');
						var row = $('tr[id="post-' + id + '"]');
						var text = row.find('.mar-alt-text').val();
						row.find('.meow-button-xs').addClass('updating-message');
						row.find('.mar-alt-text').prop( "disabled", true );
						update_alt_text(id, text);
					}
				});

				$('.mar-alt-text').keyup(function(e) {
					if ($(this).val() === $(this).attr('mar-origin')) {
						$(this).parent().find('.meow-button-xs').css('display', 'none');
						$(this).css('width', '100%');
					}
					else {
						var current = $(this);
						$(this).css('width', '80%')
						.one("transitionend",
						 function(e){
							 if ($(this).val() === $(this).attr('mar-origin'))
							 	return;
							 $(this).parent().find('.meow-button-xs').css('display', 'inherit');
						 });
					}
				});

			});
		})( jQuery );
		</script>
		<?php
	}
}

?>

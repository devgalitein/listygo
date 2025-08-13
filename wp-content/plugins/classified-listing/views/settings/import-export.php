<div class="rtcl-admin-wrap rtcl-import-export-wrapper">
	<div class="rtcl-admin-header">
		<h2 class="rtcl-header-title"><?php esc_html_e( "Export Import Settings", 'classified-listing' ); ?></h2>
	</div>
	<?php
	$active_tab = isset( $_GET['tab'] ) && $_GET['tab'] ? esc_attr( $_GET['tab'] ) : 'export';
	?>
	<div class="rtcl-admin-settings-wrap">
		<h2 class="nav-tab-wrapper">
			<a href="?page=rtcl-import-export&tab=export"
			   class="nav-tab <?php echo $active_tab == 'export' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( "Export", 'classified-listing' ); ?></a>
			<a href="?page=rtcl-import-export&tab=import"
			   class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( "Import", 'classified-listing' ); ?></a>
		</h2>

		<?php
		if ( $active_tab == 'import' ) {
			require_once RTCL_PATH . 'views/settings/import.php';
		} elseif ( $active_tab == 'export' ) {
			require_once RTCL_PATH . 'views/settings/export.php';
		}
		?>
	</div>
</div><!-- /.wrap -->
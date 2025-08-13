<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.6
 */

add_editor_style( 'style-editor.css' );

if ( !isset( $content_width ) ) {
	$content_width = 1200;
}

class Listygo_Main {
	public $theme   = 'listygo';
	public $action  = 'listygo_theme_init';

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action( 'admin_notices',     array( $this, 'plugin_update_notices' ) );
		$this->includes();
	}
	public function load_textdomain(){
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}
	
	public function includes(){
		do_action( $this->action );
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/includes.php';
		require_once get_template_directory() . '/inc/customizer/typography/fonts-list.php';
	}

	public function plugin_update_notices() {
		$plugins = array();

		if ( defined( 'LISTYGO_CORE' ) ) {
			if ( version_compare( LISTYGO_CORE, '1.0.22', '<' ) ) {
				$plugins[] = 'Listygo Core';
			}
		}

		foreach ( $plugins as $plugin ) {
			$notice = '<div class="error"><p>' . sprintf( __( "Please update plugin <b><i>%s</b></i> to the latest version otherwise some functionalities will not work properly. You can update it from <a href='%s'>here</a>", 'listygo' ), $plugin, menu_page_url( 'listygo-install-plugins', false ) ) . '</p></div>';
			echo wp_kses( $notice, 'alltext_allow' );
		}
	}

}
new Listygo_Main;


<?php
/*
Plugin Name: Listygo Core
Plugin URI: https://www.radiustheme.com
Description: Listygo Core Plugin for Listygo Directory Theme
Version: 1.0.22
Author: RadiusTheme
Text Domain: listygo-core
Domain Path: /languages
Author URI: https://www.radiustheme.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'LISTYGO_CORE' ) ) {
	$plugin_data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
	define( 'LISTYGO_CORE',              $plugin_data['version'] );
	define( 'LISTYGO_CORE_SCRIPT_VER',   ( WP_DEBUG ) ? time() : LISTYGO_CORE );
	define( 'LISTYGO_CORE_THEME_PREFIX', 'listygo' );
	define( 'LISTYGO_CORE_CPT', 		 'listygo' );	
	define( 'LISTYGO_CORE_BASE_URL', plugin_dir_url( __FILE__ ) );
	define( 'LISTYGO_CORE_BASE_DIR', plugin_dir_path( __FILE__ ) );
}

require_once LISTYGO_CORE_BASE_DIR . 'demo-users/user-importer.php';

class Listygo_Core {

	public $plugin  = 'listygo-core';
	public $action  = 'listygo_theme_init';
	protected static $instance;

	public function __construct() {
		add_action( 'plugins_loaded',       array( $this, 'load_textdomain' ), 20 );
		add_action( 'plugins_loaded',       array( $this, 'demo_importer' ), 17 );
		add_action( 'after_setup_theme', array( $this, 'after_theme_loaded' ), 15 );
		add_action( 'rdtheme_social_share', array( $this, 'social_share' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'listygo_core_enqueue_scripts' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'listygo_core_enqueue_scripts' ), 20 );
		add_action( 'plugins_loaded',       array( $this, 'php_version_check' ));
		if ( isset( $_GET['export_user'] ) && $_GET['export_user'] == 1 ) {
			Listygo_Core_Demo_User_Import::export_users();
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function listygo_core_enqueue_scripts() {
		wp_enqueue_style( 'listygo-core', LISTYGO_CORE_BASE_URL . 'assets/css/listygo-core.css' );
		wp_enqueue_script( 'listygo-core', LISTYGO_CORE_BASE_URL . 'assets/js/listygo-core.js', array( 'jquery' ), '', true );
	}
	
	public function after_theme_loaded() {
		if ( defined( 'RT_FRAMEWORK_VERSION' ) ) {
			require_once LISTYGO_CORE_BASE_DIR . 'inc/post-types.php'; // Post Types
			require_once LISTYGO_CORE_BASE_DIR . 'inc/rt-posts.php'; // Post Fields
			require_once LISTYGO_CORE_BASE_DIR . 'inc/post-meta.php'; // Post Meta
			require_once LISTYGO_CORE_BASE_DIR . 'widgets/init.php'; // Widgets
		}
		if ( class_exists('Listygo_Main') && did_action( 'elementor/loaded' ) ) {
			require_once LISTYGO_CORE_BASE_DIR . 'elementor/init.php'; // Elementor
		}
	}

	public function social_share( $sharer ){
		include LISTYGO_CORE_BASE_DIR . 'inc/social-share.php';
	}

	public function load_textdomain() {
	    load_plugin_textdomain( $this->plugin, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	public function demo_importer() {
		require_once LISTYGO_CORE_BASE_DIR . 'inc/demo-importer.php';
		require_once LISTYGO_CORE_BASE_DIR . 'inc/demo-importer-ocdi.php';
	}	

	public function php_version_check(){
		if ( version_compare(phpversion(), '7.2', '<') ){
			add_action( 'admin_notices', [ $this, 'php_version_message' ] );
		}
		if ( version_compare(phpversion(), '7.2', '>') ){
			require_once LISTYGO_CORE_BASE_DIR . 'optimization/__init__.php';
		}
	}

	public function php_version_message(){
		$class = 'notice notice-error settings-error';
		$message = sprintf( __( 'The %1$sListygo Optimization%2$s requires %1$sphp 7.2+%2$s. Please consider updating php version and know more about it <a href="https://wordpress.org/about/requirements/" target="_blank">here</a>.', 'listygo-core' ), '<strong>', '</strong>' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ));
	}

}
Listygo_Core::instance();

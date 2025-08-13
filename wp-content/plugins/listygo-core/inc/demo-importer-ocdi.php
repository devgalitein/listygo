<?php
/**
 * @author  RadiusTheme
 * @since   1.2
 * @version 1.2
 */
/*------------------------------------------------------------------------------------------------------------------*/
/* Listygo Demo Import
/*------------------------------------------------------------------------------------------------------------------*/

namespace radiustheme\Listygo_Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Demo_Importer_OCDI {

	public function __construct() {
		add_filter( 'pt-ocdi/import_files',          array( $this, 'demo_config' ) );
		add_filter( 'pt-ocdi/before_content_import', array( $this, 'before_import' ) );
		add_filter( 'pt-ocdi/after_import',          array( $this, 'after_import' ) );
		add_filter( 'pt-ocdi/disable_pt_branding',   '__return_true' );
		add_action( 'init',                          array( $this, 'rewrite_flush_check' ) );
	}

	public function demo_config() {

		$demos_array = array(
			'demo1' => array(
				'title' => __( 'Home One', 'listygo-core' ),
				'page'  => __( 'Home One', 'listygo-core' ),
				'screenshot' => plugins_url( 'screenshots/01.jpg', dirname(__FILE__) ),
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/listygo/',
			),
			'demo2' => array(
				'title' => __( 'Home Two', 'listygo-core' ),
				'page'  => __( 'Home Two', 'listygo-core' ),
				'screenshot' => plugins_url( 'screenshots/02.jpg', dirname(__FILE__) ),
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/listygo/home-two/',
			),
			'demo3' => array(
				'title' => __( 'Home Three', 'listygo-core' ),
				'page'  => __( 'Home Three', 'listygo-core' ),
				'screenshot' => plugins_url( 'screenshots/03.jpg', dirname(__FILE__) ),
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/listygo/home-three/',
			),
			'demo4' => array(
				'title' => __( 'Home Four', 'listygo-core' ),
				'page'  => __( 'Home Four', 'listygo-core' ),
				'screenshot' => plugins_url( 'screenshots/04.jpg', dirname(__FILE__) ),
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/listygo/home-four/',
			),
			'demo5' => array(
				'title' => __( 'Home Five', 'listygo-core' ),
				'page'  => __( 'Home Five', 'listygo-core' ),
				'screenshot' => plugins_url( 'screenshots/05.jpeg', dirname(__FILE__) ),
				'preview_link' => 'https://radiustheme.com/demo/wordpress/themes/listygo/home-five/',
			),
		);

		$config = array();
		$import_path  = trailingslashit( get_template_directory() ) . 'sample-data/';

		foreach ( $demos_array as $key => $demo ) {
			$config[] = array(
				'import_file_id'               => $key,
				'import_page_name'             => $demo['page'],
				'import_file_name'             => $demo['title'],
				'local_import_file'            => $import_path . 'contents.xml',
				'local_import_widget_file'     => $import_path . 'widgets.wie',
				'local_import_customizer_file' => $import_path . 'customizer.dat',
				'import_preview_image_url'   => $demo['screenshot'],
				'preview_url'                => $demo['preview_link'],
			);
		}

		return $config;
	}

	public function before_import( $selected_import ) {
		// Delete My Account and Checkout page
		$myaccount = get_page_by_title( 'My Account' );
		$checkout  = get_page_by_title( 'Checkout' );

		if ( $myaccount ) {
			wp_delete_post( $myaccount->ID, true );
		}

		if ( $checkout ) {
			wp_delete_post( $checkout->ID, true );
		}
	}

	public function after_import( $selected_import ) {
		$this->assign_menu( $selected_import['import_file_id'] );
		$this->assign_frontpage( $selected_import );
		$this->update_permalinks();
		$this->update_rtcl_options();
		update_option( 'listygo_ocdi_importer_rewrite_flash', true );
	}

	private function assign_menu( $demo ) {
		$primary  = get_term_by( 'name', 'Main Menu', 'nav_menu' );
		$cr_menu = get_term_by( 'name', 'Copyright Footer Menu', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', array(
			'primary'  => $primary->term_id,
			'crmenu' => $cr_menu->term_id,
		));
	}

	private function assign_frontpage( $selected_import ) {
		$blog_page  = get_page_by_title( 'Blog' );
		$front_page = get_page_by_title( $selected_import['import_page_name'] );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front',  $front_page->ID );
		update_option( 'page_for_posts', $blog_page->ID );
	}

	private function update_permalinks() {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	private function update_rtcl_options() {
		$listings     = get_page_by_title( 'All Ads' );
		$listing_form = get_page_by_title( 'Post an Ad' );
		$myaccount    = get_page_by_title( 'My Account' );
		$checkout     = get_page_by_title( 'Checkout' );

		$data = array(
			'rtcl_general_settings'    => array(
				'listings_per_page'            => 9,
				'default_view'                 => 'grid',
				'currency_position'            => 'left',
			),
			'rtcl_moderation_settings' => array(
				'listing_duration'             => 0,
				'new_listing_threshold'        => 155,
				'listing_bump_up_label'        => '',
				'display_options'              => array(
					'category',
					'location',
					'date',
					'price',
					'views',
					'excerpt',
					'new',
					'popular'
				),
				'display_options_detail'       => array(
					'location',
					'date',
					'user',
					'price',
					'views',
					'top',
					'featured',
					'new',
					'popular'
				),
				'has_comment_form'             => 'yes',
				'enable_review_rating'         => 'yes',
				'enable_update_rating'         => 'yes',
			),
			'rtcl_advanced_settings'           => array(
				'permalink'                    => 'listing',

				'listings'                     => $listings->ID,
				'listing_form'                 => $listing_form->ID,
				'myaccount'                    => $myaccount->ID,
				'checkout'                     => $checkout->ID,
			),
		);

		foreach ( $data as $key => $value ) {
			$defaults = get_option( $key, array() );
			$args = wp_parse_args( $value , $defaults );
			update_option( $key, $args );
		}
	}

	public function rewrite_flush_check() {
		if ( get_option( 'listygo_ocdi_importer_rewrite_flash' ) == true  ) {
			flush_rewrite_rules();
			delete_option( 'listygo_ocdi_importer_rewrite_flash' );
		}
	}
}

new Demo_Importer_OCDI;

<?php

namespace RtclPro\Controllers\Hooks;

use Rtcl\Helpers\Functions;

class TemplateLoader {
	/**
	 * Is ClassifiedListing support defined?
	 *
	 * @var boolean
	 */
	private static $theme_support = false;

	static function init() {
		self::$theme_support = Functions::is_enable_template_support();
		if ( self::$theme_support ) {
			add_filter( 'comments_template', [ __CLASS__, 'comments_template_loader' ] );
		}
		if ( Functions::get_listing_details_disable_settings() ) {
			add_action( 'template_redirect', [ __CLASS__, 'disable_listing_details_page' ] );
		} else {
			if ( ! is_user_logged_in() ) {
				add_filter( 'template_include', [ __CLASS__, 'listing_details_restriction' ], 999 );
			}
		}
	}

	public static function disable_listing_details_page() {
		Functions::disable_listing_details_page_view();
	}

	public static function listing_details_restriction( $template ) {
		if ( ! Functions::is_listing() ) {
			return $template;
		}

		$single_listing_logged_in = Functions::get_option_item( 'rtcl_single_listing_settings', 'single_listing_logged_in', false, 'checkbox' );

		if ( ! $single_listing_logged_in ) {
			return $template;
		}

		Functions::listing_details_for_logged_in_users();
	}

	/**
	 * Load comments template.
	 *
	 * @param string $template template to load.
	 *
	 * @return string
	 */
	public static function comments_template_loader( $template ) {

		if ( get_post_type() !== rtcl()->post_type ) {
			return $template;
		}
		$file       = 'single-rtcl_listing-reviews.php';
		$checkFiles = array(
			$file,
			"classified-listing/$file"
		);

		if ( $template_file = locate_template( $checkFiles ) ) {
			return $template_file;
		} else {
			$file = trailingslashit( rtclPro()->plugin_path() ) . 'templates/single-rtcl_listing-reviews.php';
			if ( file_exists( $file ) ) {
				return $file;
			}

			return $template;
		}
	}

}
<?php


namespace Rtcl\Traits\Functions;


use Rtcl\Helpers\Functions;

trait SettingsTrait {
	static function get_privacy_policy_page_id() {
		$page_id = Functions::get_option_item( 'rtcl_account_settings', 'page_for_privacy_policy', 0 );

		return apply_filters( 'rtcl_privacy_policy_page_id', 0 < $page_id ? absint( $page_id ) : 0 );
	}

	static function get_terms_and_conditions_page_id() {
		$page_id = Functions::get_option_item( 'rtcl_account_settings', 'page_for_terms_and_conditions', 0 );

		return apply_filters( 'rtcl_terms_and_conditions_page_id', 0 < $page_id ? absint( $page_id ) : 0 );
	}

	public static function get_listings_default_view() {
		$default_view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );

		return apply_filters( 'rtcl_archive_listings_default_view', $default_view );
	}

	public static function get_listings_per_row() {
		$per_row = wp_parse_args(
			Functions::get_option_item( 'rtcl_archive_listing_settings', 'listings_per_row' ),
			[
				'desktop' => 3,
				'tablet'  => 2,
				'mobile'  => 1,
			]
		);

		return apply_filters( 'rtcl_archive_listings_grid_view_per_row', $per_row );
	}

	public static function get_listing_details_disable_settings() {
		$disable_single = Functions::get_option_item( 'rtcl_single_listing_settings', 'disable_single_listing', false, 'checkbox' );

		return apply_filters( 'rtcl_single_listing_disable_option', $disable_single );
	}

	public static function get_base_template() {
		$base_template = Functions::get_option_item( 'rtcl_advanced_settings', 'template_base', 'rtcl_template' );

		return apply_filters( 'rtcl_listing_base_template', $base_template );
	}

}

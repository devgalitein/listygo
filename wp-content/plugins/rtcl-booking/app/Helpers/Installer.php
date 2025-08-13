<?php

namespace RtclBooking\Helpers;

class Installer {
	public static function activate() {

		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'rtcl_booking_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'rtcl_booking_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		self::create_tables();

		delete_transient( 'rtcl_booking_installing' );

		do_action( 'rtcl_flush_rewrite_rules' );
	}

	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( self::get_table_schema() );
	}

	/**
	 * @return array
	 */
	static function get_table_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$booking_meta_table_name = $wpdb->prefix . "rtcl_booking_meta";
		$booking_info_table_name = $wpdb->prefix . "rtcl_booking_info";
		$table_schema            = [];

		if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $booking_info_table_name ) ) !== $booking_info_table_name ) {
			$table_schema[] = "CREATE TABLE $booking_info_table_name (
                          id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                          listing_id int(10) UNSIGNED NOT NULL,
                          user_id int(10) UNSIGNED NOT NULL,
                          listing_owner_id int(10) UNSIGNED NOT NULL,
                          price double NOT NULL,
                          quantity int(10) UNSIGNED NOT NULL,
                          details longtext,
                          booking_date varchar(191) DEFAULT NULL,
                          time_slot	varchar(191) DEFAULT NULL,
                          requested_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          status varchar(191) NOT NULL,
                          PRIMARY KEY (id)
                        ) $collate;";
		}

		if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $booking_meta_table_name ) ) !== $booking_meta_table_name ) {
			$table_schema[] = "CREATE TABLE $booking_meta_table_name (
                      id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                      listing_id int(10) UNSIGNED NOT NULL,
                      meta_key varchar(191) NOT NULL,
                      meta_value longtext DEFAULT NULL,
                      PRIMARY KEY (id)
                      ) $collate;";
		}

		return $table_schema;
	}

	public static function deactivate() {
		do_action( 'rtcl_flush_rewrite_rules' );
	}
}
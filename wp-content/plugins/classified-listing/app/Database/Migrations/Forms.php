<?php
/* phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared */

namespace Rtcl\Database\Migrations;

use Rtcl\Abstracts\Migration;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FormPreDefined;

class Forms extends Migration {

	public static function migrate() {
		global $wpdb;

		$charsetCollate = $wpdb->get_charset_collate();

		$table = $wpdb->prefix . 'rtcl_forms';
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table ) {
			$sql = "CREATE TABLE $table (
			  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			  `title` VARCHAR(255) NOT NULL,
			  `slug` VARCHAR(255) NOT NULL,
			  `status` VARCHAR(45) NULL DEFAULT 'draft',
			  `fields` json NULL,
			  `sections` json NULL,
			  `translations` json NULL,
			  `settings` json NULL,
			  `type` VARCHAR(45) NULL,
			  `default` tinyint(1) NOT NULL DEFAULT 0,
			  `created_by` INT NULL,
			  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
			  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)) $charsetCollate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			dbDelta( $sql );
		}

		// Create new form if table is empty
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) ) {
			$row = $wpdb->get_var( "SELECT COUNT(*) from $table" );
			if ( !$row ) {
				$formData = FormPreDefined::sample();
				Form::query()->insert( $formData );
			}
		}
	}
}
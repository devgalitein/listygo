<?php

namespace Rtcl\Database;

use Rtcl\Database\Migrations\Forms;
use Rtcl\Database\Migrations\Session;

class DbMigration {

	public static function run() {
		Session::migrate();
		Forms::migrate();
		do_action( 'rtcl_run_db_migration' );
	}
}
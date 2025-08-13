<?php

namespace RtclPro\Controllers\Hooks;

use Rtcl\Helpers\Functions;

class MenuHooks {

	public static function init() {
		if ( Functions::get_option_item( 'rtcl_chat_settings', 'enable', false, 'checkbox' ) ) {
			add_action( 'admin_menu', [ __CLASS__, 'add_manage_chat_menu' ], 60 );
			add_action( 'in_admin_header', [ __CLASS__, 'remove_all_notices' ], 999 );
		}
	}

	/**
	 * Remove admin notices
	 */
	public static function remove_all_notices() {
		$screen = get_current_screen();

		if ( isset( $screen->base ) && 'classified-listing_page_rtcl-manage-chat' == $screen->base ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	public static function add_manage_chat_menu() {
		add_submenu_page(
			'rtcl-admin',
			__( 'Manage Chat', 'classified-listing-pro' ),
			__( 'Manage Chat', 'classified-listing-pro' ),
			'manage_rtcl_options',
			'rtcl-manage-chat',
			[ __CLASS__, 'manage_chat_list' ]
		);
	}

	public static function manage_chat_list() { ?>
		<div id="rtcl-manage-chat-app"></div>
		<?php
	}
}
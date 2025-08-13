<?php

namespace RtclStore\Controllers\Hooks;

use Rtcl\Helpers\Functions;

class MenuHooks {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_store_category_menu' ] );
		add_filter( 'parent_file', [ __CLASS__, 'fix_store_category_menu_new_edit_highlight' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_membership_menu' ], 51 );
		add_action( 'in_admin_header', [ __CLASS__, 'remove_all_notices' ], 999 );
	}

	public static function fix_store_category_menu_new_edit_highlight( $parent_file ) {
		global $submenu_file, $current_screen;

		if ( $current_screen->taxonomy == rtclStore()->category ) {
			$submenu_file = 'edit-tags.php?taxonomy=store_category&post_type=store';
			$parent_file  = 'edit.php?post_type=' . rtcl()->post_type;
		}

		return $parent_file;
	}

	/**
	 * Remove admin notices
	 */
	public static function remove_all_notices() {
		$screen = get_current_screen();

		if ( isset( $screen->base ) && 'classified-listing_page_rtcl-membership' == $screen->base ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	public static function add_store_category_menu() {
		if ( ! Functions::get_option_item( 'rtcl_membership_settings', 'enable_store', false, 'checkbox' ) ) {
			return;
		}
		$store_label = apply_filters( 'rtcl_store_category_label', __( "Store Categories", "classified-listing-store" ) );
		add_submenu_page(
			'edit.php?post_type=' . rtcl()->post_type,
			$store_label,
			$store_label,
			'manage_rtcl_options',
			add_query_arg( [
				'taxonomy'  => rtclStore()->category,
				'post_type' => rtclStore()->post_type
			], 'edit-tags.php' ), false );
	}

	public static function add_membership_menu() {
		add_submenu_page(
			'rtcl-admin',
			__( 'Membership', 'classified-listing-store' ),
			__( 'Membership', 'classified-listing-store' ),
			'manage_rtcl_options',
			'rtcl-membership',
			[ __CLASS__, 'manage_membership_list' ]
		);
	}

	public static function manage_membership_list() { ?>
        <div class="rtcl-admin-wrap">
            <div class="rtcl-admin-header">
                <h2 class="rtcl-header-title"><?php esc_html_e( "Manage membership", "classified-listing-store" ) ?></h2>
            </div>
            <div class="rtcl-admin-settings-wrap">
                <div id="rtcl-membership-app"></div>
            </div>
        </div>
		<?php
	}
}
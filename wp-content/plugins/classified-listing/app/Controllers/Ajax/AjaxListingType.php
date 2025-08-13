<?php

namespace Rtcl\Controllers\Ajax;

use Rtcl\Helpers\Functions;

class AjaxListingType {

	function __construct() {
		add_action( 'wp_ajax_rtcl_ajax_add_listing_type', [ $this, 'rtcl_ajax_add_listing_type' ] );
		add_action( 'wp_ajax_rtcl_ajax_delete_listing_type', [ $this, 'rtcl_ajax_delete_listing_type' ] );
		add_action( 'wp_ajax_rtcl_ajax_update_listing_type', [ $this, 'rtcl_ajax_update_listing_type' ] );
		add_action( 'wp_ajax_rtcl_ajax_sort_ad_types', [ $this, 'sort_ad_types' ] );
	}

	function sort_ad_types() {
		$success = false;
		$message = '';
		if ( wp_verify_nonce( isset( $_REQUEST[ rtcl()->nonceId ] ) ? $_REQUEST[ rtcl()->nonceId ] : null, rtcl()->nonceText ) ) {
			$types    = $_POST['types'] ?? [];
			$ad_types = [];
			foreach ( $types as $id => $name ) {
				$id              = Functions::sanitize_title_with_underscores( $id );
				$ad_types[ $id ] = sanitize_text_field( $name );
			}
			if ( ! empty( $ad_types ) ) {
				update_option( rtcl()->get_listing_types_option_id(), $ad_types );
				$success = true;
				$message = __( 'Successfully updated.', 'classified-listing' );
			}
		} else {
			$message = __( 'Session expired.', 'classified-listing' );
		}

		wp_send_json(
			[
				'success' => $success,
				'message' => $message
			]
		);
	}

	function rtcl_ajax_add_listing_type() {
		$success = false;
		$message = esc_html__( 'Type field is required', 'classified-listing' );
		$data    = [];
		if ( wp_verify_nonce( isset( $_REQUEST[ rtcl()->nonceId ] ) ? $_REQUEST[ rtcl()->nonceId ] : null, rtcl()->nonceText ) ) {
			$type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
			if ( $type ) {
				$id    = Functions::sanitize_title_with_underscores( $type );
				$types = Functions::get_listing_types();
				if ( isset( $types[ $id ] ) ) {
					$message = __( 'This type already exist.', 'classified-listing' );
				} else {
					$types[ $id ] = $type;
					update_option( rtcl()->get_listing_types_option_id(), $types );
					do_action( 'rtcl_after_save_ad_type', $type, $_POST );
					$data    = [
						'id'   => $id,
						'name' => $type
					];
					$success = true;
					$message = __( 'Successfully added.', 'classified-listing' );
				}
			}
		} else {
			$message = __( 'Session expired.', 'classified-listing' );
		}

		wp_send_json(
			[
				'success' => $success,
				'message' => $message,
				'data'    => $data
			]
		);
	}

	function rtcl_ajax_update_listing_type() {
		$success = false;
		$message = __( 'Type id and name is required', 'classified-listing' );
		$data    = [
			'id'   => '',
			'name' => ''
		];
		if ( wp_verify_nonce( isset( $_REQUEST[ rtcl()->nonceId ] ) ? $_REQUEST[ rtcl()->nonceId ] : null, rtcl()->nonceText ) ) {
			$old_id = isset( $_POST['old_id'] ) ? sanitize_text_field( $_POST['old_id'] ) : '';
			$id     = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
			$name   = isset( $_POST['name'] ) ? sanitize_text_field( stripslashes( $_POST['name'] ) ) : '';
			if ( $old_id && $id && $name ) {
				$old_id = Functions::sanitize_title_with_underscores( $old_id );
				$id     = Functions::sanitize_title_with_underscores( $id );
				$types  = Functions::get_listing_types();
				if ( ! empty( $types ) ) {
					$old_exist = false;
					if ( isset( $types[ $old_id ] ) ) {
						$data      = [
							'id'   => $old_id,
							'name' => $types[ $old_id ]
						];
						$old_exist = true;
					}
					if ( apply_filters( 'rtcl_listing_type_update_no_change', true ) && $old_id === $id && $types[ $old_id ] === $name ) {
						$message = __( 'No change found.', 'classified-listing' );
					} elseif ( $old_id === $id && $old_exist ) {
						$types[ $id ] = $name;
						$data         = [
							'id'   => $id,
							'name' => $name
						];
						$success      = true;
						update_option( rtcl()->get_listing_types_option_id(), $types );
						do_action( 'rtcl_after_save_ad_type', $id, $_POST );
						$message = __( 'Successfully updated.', 'classified-listing' );
					} elseif ( $old_id !== $id && $old_exist && $types[ $id ] ) {
						$message = __( 'This type is already exist.', 'classified-listing' );
					} elseif ( $old_id !== $id && $old_exist ) {
						$new_types = [];
						foreach ( $types as $typeId => $type ) {
							if ( $typeId == $old_id ) {
								$new_types[ $id ] = $name;
								$data             = [
									'id'   => $id,
									'name' => $name
								];
							} else {
								$new_types[ $typeId ] = $type;
							}
						}
						update_option( rtcl()->get_listing_types_option_id(), $new_types );
						do_action( 'rtcl_after_save_ad_type', $id, $_POST );
						$success = true;
						$message = __( 'Successfully updated', 'classified-listing' );
					} else {
						$message = __( 'Unknown Error!', 'classified-listing' );
					}
				} else {
					$message = __( 'No types found.', 'classified-listing' );
				}
			}
		} else {
			$message = __( 'Session expired.', 'classified-listing' );
		}

		wp_send_json(
			[
				'success' => $success,
				'message' => $message,
				'data'    => $data
			]
		);
	}

	function rtcl_ajax_delete_listing_type() {
		$success = false;
		$message = __( 'Type id is required', 'classified-listing' );
		$data    = [];
		if ( wp_verify_nonce( isset( $_REQUEST[ rtcl()->nonceId ] ) ? $_REQUEST[ rtcl()->nonceId ] : null, rtcl()->nonceText ) ) {
			$id      = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
			
			if ( $id ) {
				$types = Functions::get_listing_types();
				if ( isset( $types[ $id ] ) ) {
					unset( $types[ $id ] );
					update_option( rtcl()->get_listing_types_option_id(), $types );
					do_action( 'rtcl_after_delete_ad_type', $id );
					$success = true;
					$message = __( 'Successfully deleted', 'classified-listing' );
				} else {
					$message = __( 'No type found to delete', 'classified-listing' );
				}
			}
		} else {
			$message = __( 'Session expired.', 'classified-listing' );
		}

		wp_send_json(
			[
				'success' => $success,
				'message' => $message,
				'data'    => $data
			]
		);
	}
}
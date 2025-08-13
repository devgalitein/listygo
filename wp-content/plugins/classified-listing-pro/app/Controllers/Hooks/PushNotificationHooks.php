<?php

namespace RtclPro\Controllers\Hooks;

use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use RtclPro\Helpers\PNHelper;
use RtclPro\Models\PushNotification;

class PushNotificationHooks {

	public static function init() {
		add_action( 'transition_post_status', [ __CLASS__, 'notify_device_for_listing_status_changes' ], 99, 3 );
		add_action( 'wp_insert_post', [ __CLASS__, 'notify_device_for_listing_order_created' ], 10, 3 );

		// Send News letter push notifications
		add_action( 'wp_ajax_rtcl_pro_send_news_letter_push_notification', [ __CLASS__, 'rtcl_pro_send_news_letter_push_notification' ] );
		add_action( 'rtcl_pro_cron_send_newsletter_pn', [ __CLASS__, 'process_send_newsletter_pn' ] );
	}

	public static function rtcl_pro_send_news_letter_push_notification() {
		if ( !Fns::verify_pro_nonce() ) {
			wp_send_json_error( esc_html__( 'Session expired!!', 'classified-listing-pro' ) );
		}
		$title = esc_html( Functions::request( 'title' ) );
		$body = esc_html( Functions::request( 'body' ) );
		if ( !$title ) {
			wp_send_json_error( esc_html__( 'Title is required!', 'classified-listing-pro' ) );
		}
		if ( !$body ) {
			wp_send_json_error( esc_html__( 'Body is required!', 'classified-listing-pro' ) );
		}
		$title = mb_substr( $title, 0, 60 );
		$body = mb_substr( $body, 0, 230 );
		$data = [ 'title' => $title, 'body' => $body, 'offset' => 0 ];
		update_option( 'rtcl_pro_cron_send_newsletter_pn_data', $data );
		wp_schedule_single_event( time() + 10, 'rtcl_pro_cron_send_newsletter_pn' );

		wp_send_json_success( esc_html__( 'Notification scheduled successfully', 'classified-listing-pro' ) );
	}

	public static function process_send_newsletter_pn() {
		$data = get_option( 'rtcl_pro_cron_send_newsletter_pn_data', [] );
		if ( empty( $data ) || !is_array( $data ) ) {
			delete_option( 'rtcl_pro_cron_send_newsletter_pn_data' );
			return;
		}
		$offset = absint( $data['offset'] );
		$limit = 99;
		global $wpdb;
		$pn = new PushNotification();
		$table = $wpdb->prefix . $pn->get_table_name();
		$pushTokens = $wpdb->get_col(
			$wpdb->prepare( "SELECT push_token FROM {$table} LIMIT %d OFFSET %d", $limit, $offset )
		);
		if ( empty( $pushTokens ) ) {
			delete_option( 'rtcl_pro_cron_send_newsletter_pn_data' );
			return;
		}
		$pn->setPushTokens( $pushTokens );
		try {
			$pn->notify_news_letter( $data );
		} catch ( \Exception $e ) {
			delete_option( 'rtcl_pro_cron_send_newsletter_pn_data' );
			return;
		}
		// Schedule next batch
		$data['offset'] = $offset + $limit;
		update_option( 'rtcl_pro_cron_send_newsletter_pn_data', $data );
		wp_schedule_single_event( time() + 5, 'rtcl_pro_cron_send_newsletter_pn' );
	}

	public static function notify_device_for_listing_status_changes( $new_status, $old_status, $post ) {
		if ( rtcl()->post_type !== $post->post_type ) {
			return;
		}
		$listing = rtcl()->factory->get_listing( $post );
		$pn = new PushNotification();

		if ( 'publish' == $new_status ) {
			$pn->notify_user( PNHelper::EVENT_LISTING_APPROVED, [
				'user_id' => $listing->get_author_id(),
				'object'  => $listing
			] );
			return;
		}
		if ( 'rtcl-expired' == $new_status ) {
			$pn->notify_user( PNHelper::EVENT_LISTING_EXPIRED, [
				'user_id' => $listing->get_author_id(),
				'object'  => $listing
			] );
		}
	}

	public static function notify_device_for_listing_order_created( $post_id, $post, $update ) {
		if ( $update || rtcl()->post_type_pricing !== $post->post_type || rtcl()->post_type !== $post->post_type ) {
			return;
		}
		$pn = new PushNotification();
		if ( rtcl()->post_type !== $post->post_type ) {
			$listing = rtcl()->factory->get_listing( $post );
			$pn->notify_admin( PNHelper::EVENT_LISTING_CREATED, [
				'object' => $listing
			] );
		}
		if ( rtcl()->post_type_payment !== $post->post_type ) {
			$order = rtcl()->factory->get_order( $post );
			$pn->notify_admin( PNHelper::EVENT_ORDER_CREATED, [
				'object' => $order
			] );
		}
	}
}

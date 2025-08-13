<?php

namespace RtclPro\Controllers;

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use Rtcl\Helpers\Utility;
use RtclPro\Helpers\Fns;

class ScriptController {
	static private $suffix;
	static private $version;
	static private $ajaxurl;

	public static function init() {
		self::$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		self::$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : RTCL_PRO_VERSION;
		self::$ajaxurl = admin_url( 'admin-ajax.php' );
		if ( $current_lang = apply_filters( 'rtcl_ajaxurl_current_lang', null, self::$ajaxurl ) ) {
			self::$ajaxurl = add_query_arg( 'lang', $current_lang, self::$ajaxurl );
		}
		add_filter( 'rtcl_public_root_var', [ __CLASS__, 'public_root_var' ], 10, 2 );
		add_action( 'admin_init', [ __CLASS__, 'register_admin_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'load_admin_script_setting_page' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'register_script' ] );
	}

	public static function register_admin_scripts() {
		wp_register_script( 'rtcl-pro-admin', rtclPro()->get_assets_uri( "js/admin.min.js" ), [
			'jquery',
			'rtcl-common'
		], self::$version, true );

		wp_localize_script( 'rtcl-pro-admin', 'RtclPro', apply_filters( 'rtcl_pro_admin_localize_vars', [
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			rtclPro()->nonceId => wp_create_nonce( rtclPro()->nonceText ),
		] ) );

		wp_register_style( 'rtcl-pro-admin', rtclPro()->get_assets_uri( "css/admin.min.css" ), [ 'rtcl-admin' ] );
	}

	public static function register_script() {
		wp_register_script( 'photoswipe', rtclPro()->get_assets_uri( "vendor/photoswipe/photoswipe.min.js" ), '', '4.1.3' );
		wp_register_script( 'photoswipe-ui-default', rtclPro()->get_assets_uri( "vendor/photoswipe/photoswipe-ui-default.min.js" ), [ 'photoswipe' ], '4.1.3' );
		wp_register_script( 'zoom', rtclPro()->get_assets_uri( "vendor/zoom/jquery.zoom.min.js" ), [ 'jquery' ], '1.7.21' );
		wp_register_style( 'photoswipe', rtclPro()->get_assets_uri( "vendor/photoswipe/photoswipe.css" ), '', self::$version );
		wp_register_style( 'photoswipe-default-skin', rtclPro()->get_assets_uri( "vendor/photoswipe/default-skin/default-skin.css" ), [ 'photoswipe' ],
			self::$version );

		$depsScript = [ 'jquery', 'rtcl-common', 'rtcl-public' ];

		wp_register_script( 'jquery-payment', rtclPro()->get_assets_uri( "vendor/jquery.payment.min.js" ), [ 'jquery' ], '3.0.0' );

		wp_register_script( 'rtcl-credit-card-form', rtclPro()->get_assets_uri( "js/credit-card-form.min.js" ), [
			'jquery-payment',
			'rtcl-validator'
		],
			self::$version );

		if ( Fns::is_enable_chat() ) {
			/**
			 * If user is logged in send beacon every 15 minutes to set online status
			 * If user is logged in then check the Chat notification every 5 second
			 */

			wp_register_script( 'pusher-js', rtclPro()->get_assets_uri( "vendor/pusher.min.js" ), '', '8.4.0', true );
			wp_register_script( 'rtcl-user-chat', rtclPro()->get_assets_uri( "js/rtcl-user-chat.min.js" ), [
				'jquery',
				'pusher-js'
			], self::$version, true );
			wp_register_script( 'rtcl-chat', rtclPro()->get_assets_uri( "js/rtcl-chat.min.js" ), [
				'jquery',
				'pusher-js'
			], self::$version, true );

			$chat_data = Fns::getChatLocalizedData();
			$chat_settings = Functions::get_option( 'rtcl_chat_settings' );
			if ( Functions::is_listing() ) {
				global $listing, $post;
				if ( !$listing && $post ) {
					$listing = rtcl()->factory->get_listing( $post->ID );
				}
				if ( $listing ) {
					$chat_data['listing'] = [
						'id'        => absint( $listing->get_id() ),
						'title'     => $listing->get_the_title(),
						'url'       => $listing->get_the_permalink(),
						'images'    => Functions::get_listing_images( $listing->get_id() ),
						'amount'    => $listing->get_price_html(),
						'raw_price' => $listing->get_price(),
						'location'  => $listing->get_locations(),
						'category'  => $listing->get_categories(),
					];
				}
			}

			if ( !empty( $chat_settings['pusher_enable'] ) && $chat_settings['pusher_enable'] === 'yes' ) {
				$chat_data['pusher'] = [
					'app_key'     => !empty( $chat_settings['pusher_app_key'] ) ? $chat_settings['pusher_app_key'] : null,
					'app_cluster' => !empty( $chat_settings['pusher_app_cluster'] ) ? $chat_settings['pusher_app_cluster'] : null
				];
			}

			if ( get_current_user_id() ) {
				$user_details = get_userdata( get_current_user_id() );
				if ( !empty( $user_details ) ) {
					$pp_id = absint( get_user_meta( $user_details->ID, '_rtcl_pp_id', true ) );
					$image_url = $pp_id ? wp_get_attachment_image_url( $pp_id ) : get_avatar_url( $user_details->ID );
					$chat_data['logged_in_user_info'] = [
						'id'              => $user_details->ID,
						'name'            => $user_details->display_name,
						'profile_picture' => $image_url,
					];
				}
			}

			wp_localize_script( 'rtcl-chat', 'rtcl_chat', apply_filters( 'rtcl_localize_chat_data', $chat_data ) );
			wp_localize_script( 'rtcl-user-chat', 'rtcl_chat', apply_filters( 'rtcl_localize_chat_data', $chat_data ) );
			if ( is_singular( rtcl()->post_type ) ) {
				wp_enqueue_script( 'rtcl-chat' );
			}

			global $wp;

			if ( Functions::is_account_page() ) {
				if ( isset( $wp->query_vars['chat'] ) ) {
					wp_enqueue_script( 'rtcl-user-chat' );
				}
			}
			$depsScript[] = 'pusher-js';
		}

		wp_register_script( 'rtcl-pro-public', rtclPro()->get_assets_uri( "js/public" . self::$suffix . ".js" ), $depsScript, self::$version, true );
		wp_register_style( 'rtcl-pro-public', rtclPro()->get_assets_uri( "css/public.min.css" ), [ 'rtcl-public' ], self::$version );
		if ( !empty( $chat_data ) && in_array( 'pusher-js', $depsScript ) ) {
			$chat_data['refresh_unread_count'] = apply_filters( 'rtcl_localize_chat_data_refresh_unread_count', 1 );
			wp_localize_script( 'rtcl-pro-public', 'rtcl_chat', apply_filters( 'rtcl_localize_chat_data', $chat_data ) );
		}

		if ( is_singular( rtcl()->post_type ) ) {
			wp_enqueue_style( 'photoswipe-default-skin' );
			add_action( 'wp_footer', [ __CLASS__, 'photoswipe_placeholder' ] );
		}

		wp_enqueue_style( 'rtcl-pro-public' );
		wp_enqueue_script( 'rtcl-pro-public' );
	}


	public static function photoswipe_placeholder() {
		Functions::get_template( 'listing/photoswipe', [], '', rtclPro()->get_plugin_template_path() );
	}

	public static function load_admin_script_setting_page( $hook ) {
		if ( !empty( $_GET['page'] ) && $_GET['page'] == 'rtcl-settings' ) {
			wp_enqueue_script( 'rtcl-pro-admin' );
			wp_enqueue_style( 'rtcl-pro-admin' );
		}

		if ( $hook == 'classified-listing_page_rtcl-manage-chat' ) {
			wp_enqueue_style( 'rtcl-pro-admin' );
			wp_enqueue_script( 'rtcl-admin-chat', rtclPro()->get_assets_uri( "js/rtcl-chat-admin.min.js" ), [ 'jquery' ], self::$version, true );
			$chatData = Fns::getChatLocalizedData();
			$chatData['manage_admin'] = 1;
			$chatData['lang']['admin_title'] = esc_html__( 'Manage Chat', 'classified-listing-pro' );
			wp_localize_script( 'rtcl-admin-chat', 'rtcl_chat', $chatData );
		}
	}

	public static function public_root_var( $rootVar, $options ) {
		if ( is_array( $options ) && !empty( $options ) ) {
			if ( !empty( $options['top'] ) ) {
				$rootVar .= '--rtcl-badge-top-bg-color:' . $options['top'] . ' !important;';
			}
			if ( !empty( $options['top_text'] ) ) {
				$rootVar .= '--rtcl-badge-top-color:' . $options['top_text'] . ' !important;';
			}
			if ( !empty( $options['popular'] ) ) {
				$rootVar .= '--rtcl-badge-popular-bg-color:' . $options['popular'] . ' !important;';
			}
			if ( !empty( $options['popular_text'] ) ) {
				$rootVar .= '--rtcl-badge-popular-color:' . $options['popular_text'] . ' !important;';
			}
			if ( !empty( $options['bump_up'] ) ) {
				$rootVar .= '--rtcl-badge-bump_up-bg-color:' . $options['bump_up'] . ' !important;';
			}
			if ( !empty( $options['bump_up_text'] ) ) {
				$rootVar .= '--rtcl-badge-bump_up-color:' . $options['bump_up_text'] . ' !important;';
			}
		}

		return $rootVar;
	}

}
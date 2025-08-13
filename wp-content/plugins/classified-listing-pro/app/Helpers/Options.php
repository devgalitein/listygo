<?php

namespace RtclPro\Helpers;

use Rtcl\Helpers\Link;
use Rtcl\Resources\Options as RtclOptions;

class Options {


	public static function get_registered_only_options() {
		$options = [
			'listing_seller_information' => esc_html__( 'Listing seller information', 'classified-listing-pro' )
		];

		return apply_filters( 'rtcl_registered_only_options', $options );
	}


	/**
	 * @return mixed|void
	 */
	static function get_app_redirect_list() {

		$list = [
			'home'      => esc_html__( "Home", "classified-listing-pro" ),
			'my_ads'    => esc_html__( "My ads", "classified-listing-pro" ),
			'promotion' => esc_html__( "Promotion", "classified-listing-pro" )
		];

		return apply_filters( 'rtcl_pro_get_app_redirect_list', $list );

	}

	/**
	 * @return array|object
	 * @deprecated
	 * @use Rtcl\Resources\Options::is_enable_map()
	 */
	static function get_radius_search_options() {
		_deprecated_function( __METHOD__, '2.0.9', 'Rtcl\Resources\Options::is_enable_map()' );

		return RtclOptions::radius_search_options();
	}

	static function widget_search_style_options() {
		$options = [
			'popup'      => esc_html__( 'Popup', 'classified-listing-pro' ),
			'suggestion' => esc_html__( 'Auto Suggestion', 'classified-listing-pro' ),
			'dependency' => esc_html__( 'Dependency Selection', 'classified-listing-pro' ),
			'standard'   => esc_html__( 'Standard', 'classified-listing-pro' )
		];

		return apply_filters( 'rtcl_pro_widget_search_style_options', $options );
	}

	static function get_listings_view_options() {
		$options = [
			'list' => esc_html__( "List", 'classified-listing-pro' ),
			'grid' => esc_html__( "Grid", 'classified-listing-pro' )
		];

		return apply_filters( 'rtcl_pro_listings_view_options', $options );
	}

	public static function chat_admin_settings() {
		$options = [
			'ls_section'                            => [
				'title'       => esc_html__( 'Chat Settings', 'classified-listing-pro' ),
				'type'        => 'section',
				'description' => wp_kses( sprintf( __( 'Regenerate Chat Table <a href="%s" onClick="return confirm(\'Do you really want to Confirm this booking\')">Click Here.</a> <span style="color:red">This will remove all chat history.</span>',
					'classified-listing-pro' ), add_query_arg( [
					rtcl()->nonceId              => wp_create_nonce( rtcl()->nonceText ),
					'rtcl_regenerate_chat_table' => ''
				], Link::get_current_url() ) ), [
					'a'    => [
						'href'    => [],
						'onClick' => []
					],
					'span' => [
						'style' => [ 'color' ]
					]
				] ),
			],
			'enable'                                => [
				'title'       => esc_html__( 'Enable Chat', 'classified-listing-pro' ),
				'label'       => esc_html__( 'Enable', 'classified-listing-pro' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'Enable chat option', 'classified-listing-pro' ),
			],
			'unread_message_email'                  => [
				'title'       => esc_html__( 'Unread Message Email', 'classified-listing-pro' ),
				'label'       => esc_html__( 'Enable', 'classified-listing-pro' ),
				'type'        => 'checkbox',
				'description' => wp_kses(
					__( 'Enable email for unread message trace to receiver, if receiver at offline.', 'classified-listing-pro' ),
					[
						'span' => [
							'style' => true
						]
					]
				)
			],
			'remove_inactive_conversation_duration' => [
				'title'       => esc_html__( 'Delete Inactive Conversation (in days)', 'classified-listing-pro' ),
				'type'        => 'number',
				'default'     => 30,
				'description' => wp_kses(
					__( 'Auto remove inactive conversation which are last active in given days ago <span style="color: red">(Leave it blank to alive conversation forever)</span>.',
						'classified-listing-pro' ),
					[
						'span' => [
							'style' => true
						]
					]
				)
			],
			'bad_words'                             => [
				'title'       => esc_html__( 'Bad Words', 'classified-listing-pro' ),
				'type'        => 'textarea',
				'description' => __( 'Add bad word by comma separated. Example:', 'classified-listing-pro' )
				                 . '<span style="font-size:80%"> porn,murder</span>',
			],
			'pusher_section'                        => [
				'title'       => esc_html__( 'Pusher Integration', 'classified-listing-pro' ),
				'type'        => 'section',
				'description' => __( 'Go to Pusher and create account <a href="https://pusher.com/" target="_blank">https://pusher.com/</a>',
					'classified-listing-pro' ),
			],
			'pusher_enable'                         => [
				'title' => esc_html__( 'Enable Pusher', 'classified-listing-pro' ),
				'label' => esc_html__( 'Enable', 'classified-listing-pro' ),
				'type'  => 'checkbox',
			],
			'pusher_app_id'                         => [
				'title'       => esc_html__( 'App ID', 'classified-listing-pro' ),
				'type'        => 'text',
				'description' => ''
			],
			'pusher_app_key'                        => [
				'title'       => esc_html__( 'Key', 'classified-listing-pro' ),
				'type'        => 'text',
				'description' => ''
			],
			'pusher_app_secret'                     => [
				'title'       => esc_html__( 'Secret', 'classified-listing-pro' ),
				'type'        => 'text',
				'description' => ''
			],
			'pusher_app_cluster'                    => [
				'title'       => esc_html__( 'Cluster', 'classified-listing-pro' ),
				'type'        => 'text',
				'description' => ''
			]
		];

		return apply_filters( 'rtcl_chat_settings_options', $options );
	}

}
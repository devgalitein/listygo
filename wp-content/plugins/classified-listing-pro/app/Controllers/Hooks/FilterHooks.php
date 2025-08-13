<?php

namespace RtclPro\Controllers\Hooks;

use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Models\Listing;
use Rtcl\Models\RtclCFGField;
use Rtcl\Resources\Options as FreeOptions;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;
use RtclPro\Emails\UnreadMessageEmail;
use RtclPro\Emails\UserVerifyLinkEmailToUser;
use RtclPro\Gateways\Authorize\GatewayAuthorize;
use RtclPro\Gateways\Stripe\GatewayStripe;
use RtclPro\Gateways\Stripe\lib\StripeAPI;
use RtclPro\Gateways\Stripe\lib\StripeException;
use RtclPro\Gateways\Stripe\lib\StripeLogger;
use RtclPro\Gateways\WooPayment\GatewayWooPayment;
use RtclPro\Helpers\Fns;
use RtclPro\Helpers\Options;
use RtclPro\Helpers\PNHelper;
use WP_Post;
use WP_Term;

class FilterHooks {
	public static function init() {
		add_filter( 'rtcl_register_settings_group', [ __CLASS__, 'app_tab_item' ] );
		add_filter( 'rtcl_settings_option_fields', [ __CLASS__, 'app_tab_options' ], 10, 2 );

		add_filter(
			'rtcl_location_type',
			function () {
				return Functions::location_type();
			}, 100 );
		add_filter( 'rtcl_listing_promotions', [ __CLASS__, 'add_promotions' ] );

		add_filter( 'rtcl_listing_extra_class', [ __CLASS__, 'mark_as_sold_class' ], 10, 2 );
		add_filter( 'rtcl_load_payment_gateways', [ __CLASS__, 'add_pro_payment_gateways' ] );

		add_filter( 'rtcl_email_services', [ __CLASS__, 'add_email_services' ] );

		add_filter( 'rtcl_shortcode_listings_attributes', [ __CLASS__, 'add_view_at_shortcode_listings_attributes' ] );
		add_filter( 'rtcl_listings_view_class', [ __CLASS__, 'add_view_class_at_loop' ] );

		// Scripts params
		add_filter( 'rtcl_localize_params_public', [ __CLASS__, 'add_public_script_localize_params' ] );

		add_filter( 'rtcl_widget_search_fields', [ __CLASS__, 'widget_search_fields' ] );
		add_filter( 'rtcl_widget_search_update_values', [ __CLASS__, 'widget_search_update_values' ], 10, 2 );
		add_filter( 'rtcl_widget_search_default_values', [ __CLASS__, 'widget_search_default_values' ] );
		add_filter( 'rtcl_widget_search_values', [ __CLASS__, 'rtcl_widget_search_values' ], 10, 3 );

		add_filter( 'rtcl_widget_filter_fields', [ __CLASS__, 'widget_filter_fields' ] );
		add_filter( 'rtcl_widget_filter_values', [ __CLASS__, 'rtcl_widget_filter_values' ], 10, 3 );
		add_filter( 'rtcl_widget_filter_update_values', [ __CLASS__, 'widget_filter_update_values' ], 10, 2 );
		add_filter( 'rtcl_widget_filter_default_values', [ __CLASS__, 'widget_filter_default_values' ], 10, 2 );
		add_filter( 'rtcl_listing_query_meta_query', [ __CLASS__, 'widget_filter_rating_query' ] );

		add_filter( 'rtcl_widget_listings_data', [ __CLASS__, 'rtcl_widget_shortcode_filter_listings_data' ] );
		add_filter( 'rtcl_filter_listings_shortcode_data', [
			__CLASS__,
			'rtcl_widget_shortcode_filter_listings_data'
		] );
		add_filter( 'rtcl_widget_listings_query_params', [ __CLASS__, 'widget_listings_query_params' ], 10, 2 );
		add_filter( 'rtcl_filter_listings_shortcode_query_params', [
			__CLASS__,
			'widget_listings_query_params',
		], 10, 2 );
		add_filter( 'rtcl_widget_listings_fields', [ __CLASS__, 'widget_listings_fields' ] );
		add_filter( 'rtcl_cf_attributes_for_field_html', [ __CLASS__, 'cf_attributes_for_field' ], 10, 2 );
		add_filter( 'rtcl_get_listing_label_class', [ __CLASS__, 'add_label_class' ], 10, 2 );

		add_filter( 'rtcl_registration_name_validation', [ __CLASS__, 'remove_validation_for_name' ], 10, 2 );
		add_filter( 'rtcl_registration_phone_validation', [ __CLASS__, 'remove_validation_for_phone' ], 20, 2 );

		add_filter( 'rtcl_my_account_endpoint', [ __CLASS__, 'add_chat_end_point' ] );
		add_filter( 'rtcl_account_default_menu_items', [ __CLASS__, 'add_chat_menu_item' ] );
		add_filter( 'rtcl_addons', [ __CLASS__, 'remove_classified_listing_pro' ] );
		add_filter( 'rtcl_single_listing_script_dependencies', [ __CLASS__, 'sl_script_dependency' ] );
		add_filter( 'rtcl_single_listing_localized_params', [ __CLASS__, 'add_sl_localized_params' ] );

		add_filter( 'rtcl_registration_need_auth_new_user', [
			__CLASS__,
			'rtcl_registration_need_auth_new_user'
		], 100, 2 );
		add_filter( 'rtcl_my_account_endpoint', [ __CLASS__, 'my_account_end_point_filter' ], 20 );
		add_filter( 'rtcl_is_enable_post_for_unregister', [ __CLASS__, 'is_enable_post_for_unregister' ], 100 );
		// GB Block Hooks
		add_filter( 'rtcl_gb_localize_script', [ __CLASS__, 'gb_block_pro_options' ], 10 );
		add_filter( 'rtcl_gb_category_box_data', [ __CLASS__, 'gb_listing_category_box_path' ], 10 );
		add_filter( 'rtcl_gb_listing_filter_data', [ __CLASS__, 'gb_listing_filter_path' ], 10 );
		add_filter( 'rtcl_gb_single_location_box_data', [ __CLASS__, 'gb_location_box_path' ], 10 );
		add_filter( 'rtcl_licenses', [ __CLASS__, 'license' ], 1 );
		add_filter( 'rtcl_top_listings_query_args', [ __CLASS__, 'top_listing_geo_query' ] );

		add_filter( 'rtcl_before_save_pricing_meta_data', [
			__CLASS__,
			'sanitize_pricing_meta_data_for_subscription'
		], 10, 2 );

		add_filter( 'rtcl_pricing_admin_options', [ __CLASS__, 'update_pricing_field' ], 15, 2 );
		add_action( 'admin_notices', [ __CLASS__, 'update_pricing_notice' ], 99 );
		//add_action( 'before_delete_post', [ __CLASS__, 'delete_subscription_data' ] );
		add_action( 'save_post_' . rtcl()->post_type_pricing, [ __CLASS__, 'before_save_pricing_meta_data' ], 5, 2 );
		add_filter( 'body_class', [ __CLASS__, 'add_payment_class_checkout_page' ] );

		// Ajax filter
		add_filter( 'rtcl_ajax_filter_before_query_modify_data', [ __CLASS__, 'ajax_filter_modify_data' ], 10, 3 );
		add_filter( 'rtcl_ajax_filter_load_data_query_args', [ __CLASS__, 'exclude_sold_out_listings' ], 10, 2 );
		add_filter( 'rtcl_ajax_filter_load_data_response', [ __CLASS__, 'ajax_filter_cf_items' ], 10, 3 );
		add_filter( 'rtcl_filter_form_items', [ __CLASS__, 'filter_form_items' ] );

		// Form builder
		add_filter( 'rtcl_fb_fields', [ __CLASS__, 'fb_fields' ] );
	}

	public static function fb_fields( $fields ) {
		$fields['repeater'] = [
			'element'          => 'repeater',
			'container_class'  => '',
			'id'               => '',
			'label'            => __( 'Repeater Field', 'classified-listing-pro' ),
			'label_placement'  => '',
			'help_message'     => '',
			'logics'           => '',
			'max_repeat_field' => '',
			'single_view'      => true,
			'archive_view'     => false,
			'fields'           => [
				[
					'element'       => 'text',
					'name'          => 'text_input',
					'default_value' => '',
					'label'         => __( 'Text Input', 'classified-listing-pro' ),
					'placeholder'   => '',
					'validation'    => [
						'required' => [
							'value'   => false,
							'message' => __( 'This field is required', 'classified-listing-pro' ),
						],
					]
				]
			],
			'editor'           => [
				'title'      => __( 'Repeat Field', 'classified-listing-pro' ),
				'icon_class' => 'rtcl-icon-ccw',
				'template'   => 'repeater',
			]
		];

		return $fields;
	}

	public static function filter_form_items( $items ) {
		$allForms = Form::query()->where( 'status', 'publish' )->order_by( 'title' )->get();
		$forms = [];
		if ( !empty( $allForms ) ) {
			foreach ( $allForms as $form ) {
				$forms[$form->id] = $form->title;
			}
		}
		$items['directory'] = [
			'icon'   => 'rtcl-icon-folder',
			'label'  => esc_html__( 'Directory', 'classified-listing-pro' ),
			'fields' => [
				[
					'label'    => esc_html__( 'Item Title', 'classified-listing-pro' ),
					'id'       => 'title',
					'default'  => __( 'Directory Filter', 'classified-listing-pro' ),
					'type'     => 'text',
					'required' => 1,
				],
				[
					'label'   => esc_html__( 'Field type', 'classified-listing-pro' ),
					'id'      => 'type',
					'default' => 'checkbox',
					'options' => [
						'checkbox' => esc_html__( 'Checkbox', 'classified-listing-pro' ),
						'radio'    => esc_html__( 'Radio', 'classified-listing-pro' ),
						'select'   => esc_html__( 'Dropdown', 'classified-listing-pro' ),
					],
					'type'    => 'select'
				],
				[
					'label'      => esc_html__( 'Default selected', 'classified-listing-pro' ),
					'id'         => 'selected',
					'validation' => 'absint',
					'options'    => $forms,
					'type'       => 'checkbox'
				]
			]
		];
		$items['cf'] = [
			'icon'   => 'rtcl-icon-code',
			'label'  => esc_html__( 'Custom fields', 'classified-listing-pro' ),
			'fields' => [
				[
					'label'    => esc_html__( 'Item Title', 'classified-listing-pro' ),
					'id'       => 'title',
					'default'  => __( 'Custom fields', 'classified-listing-pro' ),
					'type'     => 'text',
					'required' => 1,
				],
				[
					'label' => esc_html__( 'Fields Order', 'classified-listing-pro' ),
					'id'    => 'fields_order',
					'type'  => 'cf_fields_order',
				],
			]
		];
		$items['rating'] = [
			'icon'   => 'rtcl-icon-star',
			'label'  => esc_html__( 'Rating', 'classified-listing-pro' ),
			'fields' => [
				[
					'label'    => esc_html__( 'Item Title', 'classified-listing-pro' ),
					'id'       => 'title',
					'default'  => __( 'Rating', 'classified-listing-pro' ),
					'type'     => 'text',
					'required' => 1,
				],
			]
		];

		return $items;
	}

	public static function add_payment_class_checkout_page( $classes ) {
		if ( empty( rtcl()->session ) ) {
			rtcl()->initialize_session();
		}

		if ( rtcl()->session->get( 'rtcl_app_web_view' ) ) {
			$classes[] = 'rtcl-app-web-view';
		}

		return $classes;
	}

	public static function before_save_pricing_meta_data( $post_id, $post ) {
		if ( !isset( $_POST['post_type'] ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( !Functions::verify_nonce() ) {
			return $post_id;
		}

		$price = rtcl()->factory->get_pricing( $post->ID );
		if ( !$price || $price->getType() !== 'membership' || !Functions::get_option_item( 'rtcl_payment_settings', 'subscription', false, 'checkbox' ) ) {
			return $post_id;
		}
		$sProductId = get_post_meta( $post->ID, '_stripe_product_id', true );
		$sPricingId = get_post_meta( $post->ID, '_stripe_price_id', true );
		if ( !$sPricingId || !$sProductId ) {
			return $post_id;
		}

		wp_die( esc_html__( 'This pricing is linked with subscription, You are not allow to update this pricing any more, you can update product / pricing from stripe dashboard.',
			'classified-listing-pro' ) );
	}

	public static function delete_subscription_data( $post_id ) {
		if ( rtcl()->post_type_pricing !== get_post_type( $post_id ) ) {
			return;
		}

		$price = rtcl()->factory->get_pricing( $post_id );
		if ( !$price || $price->getType() !== 'membership' || !Functions::get_option_item( 'rtcl_payment_settings', 'subscription', false, 'checkbox' ) ) {
			return;
		}

		$sProductId = get_post_meta( $post_id, '_stripe_product_id', true );
		$sPricingId = get_post_meta( $post_id, '_stripe_price_id', true );
		if ( !$sPricingId || !$sProductId ) {
			return;
		}

		try {
			$stripe = new StripeAPI();
			$stripe->request( [], 'products/' . $sProductId, 'DELETE' );
			$stripe->request( [], 'prices/' . $sPricingId, 'DELETE' );
		} catch ( StripeException $e ) {
			StripeLogger::error( 'Error while deleting pricing id(' . $post_id . '): ' . $e->getMessage() );
		}

	}

	public static function update_pricing_notice() {
		global $post, $pagenow;
		if ( $pagenow != 'post.php' || empty( $post ) || get_post_type( $post->ID ) != rtcl()->post_type_pricing ) {
			return;
		}

		$price = rtcl()->factory->get_pricing( $post->ID );
		if ( !$price || $price->getType() !== 'membership' || !Functions::get_option_item( 'rtcl_payment_settings', 'subscription', false, 'checkbox' ) ) {
			return;
		}
		$sProductId = get_post_meta( $post->ID, '_stripe_product_id', true );
		$sPricingId = get_post_meta( $post->ID, '_stripe_price_id', true );
		if ( !$sPricingId || !$sProductId ) {
			return;
		}
		?>
		<div class="error rtcl-pricing-notice">
			<p style="color: red; font-size: 15px">
				<?php esc_html_e( 'This pricing is linked with subscription, You are not allow to update this pricing any more, you can update product / pricing from stripe dashboard.',
					'classified-listing-pro' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * @param array $data
	 * @param WP_Post $post
	 *
	 * @return array|void
	 */
	public static function update_pricing_field( $data, $post ) {

		$price = rtcl()->factory->get_pricing( $post->ID );
		if ( !$price || $price->getType() !== 'membership' || !Functions::get_option_item( 'rtcl_payment_settings', 'subscription', false, 'checkbox' ) ) {
			return $data;
		}
		$sProductId = get_post_meta( $post->ID, '_stripe_product_id', true );
		$sPricingId = get_post_meta( $post->ID, '_stripe_price_id', true );
		if ( $sPricingId && $sProductId ) {
			$description = __( 'Not allow to change from site. This is the membership product', 'classified-listing-pro' );
			$description = sprintf( '<div style="color: red;">%s</div>', $description );
			if ( !empty( $data['price'] ) ) {
				$data['price']['description'] = !empty( $data['price']['description'] ) ? $data['price']['description'] . $description : $description;
				if ( !empty( $data['price']['attr'] ) && is_array( $data['price']['attr'] ) ) {
					$data['price']['attr']['disabled'] = true;
				} else {
					$data['price']['attr'] = [ 'disabled' => true ];
				}
			}
			if ( !empty( $data['visible'] ) ) {
				$data['visible']['description'] = !empty( $data['visible']['description'] ) ? $data['visible']['description'] . $description : $description;
				if ( isset( $data['visible']['attr'] ) && is_array( $data['visible']['attr'] ) ) {
					$data['visible']['attr']['disabled'] = true;
				} else {
					$data['visible']['attr'] = [ 'disabled' => true ];
				}
			}
		}


		return $data;
	}


	/**
	 * @param array $data
	 * @param int $post_id
	 *
	 * @return array
	 */
	public static function sanitize_pricing_meta_data_for_subscription( $data, $post_id ) {

		$price = rtcl()->factory->get_pricing( $post_id );
		if ( !$price || $price->getType() !== 'membership' || !Functions::get_option_item( 'rtcl_payment_settings', 'subscription', false, 'checkbox' ) ) {
			return $data;
		}

		if ( empty( $data['price'] ) || empty( $data['visible'] ) ) {
			return $data;
		}

		if ( $data['price']['old'] == $data['price']['new'] && $data['visible']['old'] == $data['visible']['new'] ) {
			return $data;
		}
		$sProductId = get_post_meta( $post_id, '_stripe_product_id', true );
		$sPricingId = get_post_meta( $post_id, '_stripe_price_id', true );
		if ( $sPricingId && $sProductId ) {
			$data['price']['new'] = $data['price']['old'];
			$data['visible']['new'] = $data['visible']['old'];
		}

		return $data;
	}

	public static function top_listing_geo_query( $query_args ) {
		if ( Functions::is_enable_map() ) {
			$distance = !empty( $_GET['distance'] ) ? absint( $_GET['distance'] ) : 0;

			if ( $distance ) {
				$current_user_id = get_current_user_id();
				$lat = !empty( $_GET['center_lat'] ) ? trim( $_GET['center_lat'] ) : get_user_meta( $current_user_id, '_rtcl_latitude', true );
				$lan = !empty( $_GET['center_lng'] ) ? trim( $_GET['center_lng'] ) : get_user_meta( $current_user_id, '_rtcl_longitude', true );

				if ( $lat && $lan ) {
					$rs_data = FreeOptions::radius_search_options();
					$rtcl_geo_query = [
						'lat_field' => 'latitude',
						'lng_field' => 'longitude',
						'latitude'  => $lat,
						'longitude' => $lan,
						'distance'  => $distance,
						'units'     => $rs_data["units"]
					];
					$query_args['rtcl_geo_query'] = array_filter( apply_filters( 'rtcl_top_listing_query_geo_query', $rtcl_geo_query ) );
				}
			}
		}

		return $query_args;
	}

	public static function license( $licenses ) {
		$licenses[] = [
			'plugin_file' => RTCL_PRO_PLUGIN_FILE,
			'api_data'    => [
				'key_name'    => 'license_key',
				'status_name' => 'license_status',
				'action_name' => 'rtcl_manage_licensing',
				'product_id'  => 81839,
				'version'     => RTCL_PRO_VERSION,
			],
			'settings'    => [
				'title' => esc_html__( 'Main Plugin License Key', 'classified-listing-pro' ),
			],
		];

		return $licenses;
	}

	public static function gb_block_pro_options( $data ) {
		$data['single_location']['style_options'][] = [
			'value' => '3',
			'label' => __( 'Style 3', 'classified-listing-pro' ),
		];
		$data['category']['style_options'][] = [
			'value' => '2',
			'label' => __( 'Style 2', 'classified-listing-pro' ),
		];
		$data['listing']['grid_style_options'][] = [
			'value' => '2',
			'label' => __( 'Style 2', 'classified-listing-pro' ),
		];
		$data['listing']['grid_style_options'][] = [
			'value' => '3',
			'label' => __( 'Style 3', 'classified-listing-pro' ),
		];
		$data['listing']['grid_style_options'][] = [
			'value' => '4',
			'label' => __( 'Style 4', 'classified-listing-pro' ),
		];
		$data['listing']['grid_style_options'][] = [
			'value' => '5',
			'label' => __( 'Style 5', 'classified-listing-pro' ),
		];
		$data['listing']['list_style_options'][] = [
			'value' => '2',
			'label' => __( 'Style 2', 'classified-listing-pro' ),
		];
		$data['listing']['list_style_options'][] = [
			'value' => '3',
			'label' => __( 'Style 3', 'classified-listing-pro' ),
		];
		$data['listing']['list_style_options'][] = [
			'value' => '4',
			'label' => __( 'Style 4', 'classified-listing-pro' ),
		];
		$data['listing']['list_style_options'][] = [
			'value' => '5',
			'label' => __( 'Style 5', 'classified-listing-pro' ),
		];
		$data['location_type'] = 'geo' === Functions::location_type() ? 'geo' : 'local';

		return $data;
	}

	public static function gb_listing_category_box_path( $data ) {
		$style = !empty( $data['style'] ) ? $data['style'] : '1';
		if ( '1' !== $style ) {
			$data['default_template_path'] = rtclPro()->get_plugin_template_path();
		}

		return $data;
	}

	public static function gb_listing_filter_path( $data ) {
		$style = '1';
		$view = $data['view'];
		if ( 'list' === $view ) {
			$style = !empty( $data['style'] ) ? $data['style'] : '1';
		}
		if ( 'grid' === $view ) {
			$style = !empty( $data['style'] ) ? $data['style'] : '1';
		}
		if ( '1' != $style ) {
			$data['template'] = 'block/listing-ads/' . $view . '/style-' . $style;
			$data['default_template_path'] = rtclPro()->get_plugin_template_path();
		}

		return $data;
	}

	public static function gb_location_box_path( $data ) {
		$style = !empty( $data['style'] ) ? $data['style'] : '1';
		if ( $style == '3' ) {
			$data['template'] = 'block/single-location/style-' . $style;
			$data['default_template_path'] = rtclPro()->get_plugin_template_path();
		}

		return $data;
	}

	public static function app_tab_item( $tabs ) {
		$tabs['app'] = [
			'title'  => esc_html__( 'App', 'classified-listing-pro' ),
			'subtab' => []
		];

		return $tabs;
	}

	// Add App tab options
	public static function app_tab_options( $fields, $active_tab ) {
		if ( 'app' == $active_tab ) {
			$shedulerData = get_option( 'rtcl_pro_cron_send_newsletter_pn_data', [] );
			$fields = [
				'general'              => [
					'title' => esc_html__( 'General Settings', 'classified-listing-pro' ),
					'type'  => 'section',
				],
				'redirect_new_listing' => [
					'title'       => esc_html__( 'Redirect after New Listing', 'classified-listing-pro' ),
					'type'        => 'select',
					'default'     => 'home',
					'options'     => Options::get_app_redirect_list(),
					'description' => esc_html__( 'Redirect after successfully post a new listing', 'classified-listing-pro' )
				],
				'pn'                   => [
					'title' => esc_html__( 'App Push Notification settings', 'classified-listing-pro' ),
					'type'  => 'section',
				],
				'app_schema'           => [
					'title'       => esc_html__( 'App Schema', 'classified-listing-pro' ),
					'type'        => 'text',
					'placeholder' => esc_html__( 'myapp', 'classified-listing-pro' ),
					'description' => wp_kses(
						__( 'At your react native app folder -> app.json "expo": {"scheme": <b>"myapp"</b>}', 'classified-listing-pro' ),
						[
							'b' => [],
						]
					),
				],
				'pn_events'            => [
					'title'       => esc_html__( 'Allow Events', 'classified-listing-pro' ),
					'type'        => 'multi_checkbox',
					'options'     => PNHelper::getEventList(),
					'description' => esc_html__( 'Allow to handle data to app.', 'classified-listing-pro' ),
				],
				'news_letter_pn'       => [
					'title'       => esc_html__( 'Send business letter using push notification', 'classified-listing-pro' ),
					'type'        => 'section',
					'description' => __( 'Send push notification to app for all users ', 'classified-listing' ) . '<br />' .
						( !empty( $shedulerData ) ? '<span class="block p-4 text-sm text-green-800 rounded-lg bg-green-50 mt-2" role="alert">Processing existing newsletter news letter queue .....</span>'
							: '<button class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mt-2" id="rtcl-send-pn-btn"><i class="rtcl-icon-bell-alt mr-1"></i>' . __( 'Send Notification', 'classified-listing-pro' ) . '</button>' ),
				],
				'app_debugger'         => [
					'title'       => esc_html__( 'App Debugger', 'classified-listing-pro' ),
					'type'        => 'section',
					'description' => '',
				],
				'iap_disabled'         => [
					'title'       => esc_html__( 'Disable In App Purchase', 'classified-listing-pro' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Disable in app purchase, This will turn off membership and promotion feature from app.',
						'classified-listing-pro' ),
				],
				'iap_disabled_version' => [
					'title'       => esc_html__( 'App Version', 'classified-listing-pro' ),
					'type'        => 'text',
					'description' => '<span style="color: red">'
						. esc_html__( 'This field is required. Without this version number, the in app purchase option will not work. i.e. 1.x.x',
							'classified-listing-pro' ) . '</span>',
					'dependency'  => [
						'rules' => [
							'#rtcl_app_settings-iap_disabled' => [
								'type'  => '==',
								'value' => 'yes'
							]
						]
					]
				],
			];

			$fields = apply_filters( 'rtcl_app_settings_options', $fields );
		}

		return $fields;
	}


	/**
	 *
	 * @return mixed
	 */
	public static function is_enable_post_for_unregister() {
		return Functions::get_option_item( 'rtcl_account_settings', 'enable_post_for_unregister', false, 'checkbox' );
	}


	/**
	 * @param $endpoints
	 *
	 * @return mixed
	 */
	public static function my_account_end_point_filter( $endpoints ) {
		if ( Functions::get_option_item( 'rtcl_account_settings', 'user_verification', '', 'checkbox' ) ) {
			$endpoints['verify'] = Functions::get_option_item( 'rtcl_advanced_settings', 'myaccount_verify', 'verify' );
		}

		return $endpoints;
	}

	/**
	 * @param $auth
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public static function rtcl_registration_need_auth_new_user( $auth, $user_id ) {
		if ( Functions::get_option_item( 'rtcl_account_settings', 'user_verification', '', 'checkbox' ) && Fns::needs_validation( $user_id ) ) {
			return true;
		}

		return $auth;
	}

	/**
	 * @param array $dependencies
	 *
	 * @return array
	 */
	public static function sl_script_dependency( $dependencies ) {
		array_push( $dependencies, 'photoswipe-ui-default', 'zoom' );

		return $dependencies;
	}

	public static function add_sl_localized_params( $params ) {
		$params['zoom_enabled'] = apply_filters( 'rtcl_single_listing_zoom_enabled',
			!Functions::get_option_item( 'rtcl_single_listing_settings', 'disable_gallery_zoom', false, 'checkbox' ) );
		$params['photoswipe_enabled'] = apply_filters( 'rtcl_single_listing_photoswipe_enabled',
			!Functions::get_option_item( 'rtcl_single_listing_settings', 'disable_gallery_photoswipe', false, 'checkbox' ) );
		$params['photoswipe_options'] = apply_filters(
			'rtcl_single_listing_photoswipe_options',
			[
				'shareEl'               => false,
				'closeOnScroll'         => false,
				'history'               => false,
				'hideAnimationDuration' => 0,
				'showAnimationDuration' => 0,
			]
		);
		$params['zoom_options'] = apply_filters( 'rtcl_single_listing_zoom_options', [] );

		return $params;
	}

	public static function remove_classified_listing_pro( $addons ) {
		unset( $addons['classified_listing_pro'] );

		return $addons;
	}

	/**
	 * @param array $default_menu_items
	 *
	 * @return array
	 */
	public static function add_chat_menu_item( $default_menu_items ) {
		if ( Fns::is_enable_chat() ) {
			$position = array_search( 'favourites', array_keys( $default_menu_items ) );
			$newOptions = [ 'chat' => esc_html__( 'Chat', 'classified-listing-pro' ) ];

			if ( $position > -1 ) {
				Functions::array_insert( $default_menu_items, $position, $newOptions );
			} else {
				$default_menu_items = array_merge( $default_menu_items, $newOptions );
			}
		}

		return $default_menu_items;
	}


	/**
	 * @param array $endpoints
	 *
	 * @return array
	 */
	public static function add_chat_end_point( $endpoints ) {
		$endpoints['chat'] = Functions::get_option_item( 'rtcl_advanced_settings', 'myaccount_chat_endpoint', 'chat' );

		return $endpoints;
	}


	public static function remove_validation_for_name( $validation, $source ) {
		if ( 'api_social_login' === $source ) {
			$validation = false;
		}

		return $validation;
	}

	public static function remove_validation_for_phone( $validation, $source ) {
		if ( 'api_social_login' === $source ) {
			$validation = false;
		}

		return $validation;
	}

	/**
	 * @param array $class
	 * @param Listing $listing
	 */
	public static function add_label_class( $class, $listing ) {
		if ( $listing->get_meta( '_top' ) ) {
			$class[] = 'is-top';
		}
		if ( $listing->get_meta( '_bump_up' ) ) {
			$class[] = 'is-bump-up';
		}

		if ( Fns::is_popular( $listing ) ) {
			$class[] = 'is-popular';
		}

		if ( Fns::is_enable_top_listings() && Functions::get_loop_prop( 'as_top' ) ) {
			$class[] = 'as-top';
		}

		return $class;
	}

	/**
	 * @param array $attributes
	 * @param RtclCFGField $field
	 *
	 * @return array
	 */
	public static function cf_attributes_for_field( $attributes, $field ) {
		$rawConditions = is_a( $field, RtclCFGField::class ) ? $field->getConditions() : [];
		if ( !empty( $rawConditions ) && is_array( $rawConditions ) ) {
			$conditions = [];
			foreach ( $rawConditions as $rawGroup ) {
				$group = [];
				foreach ( $rawGroup as $rule ) {
					$rule['field'] = apply_filters( 'rtcl_wpml_cf_field_id', $rule['field'], $rule, $rawGroup, $field );
					$group[] = $rule;
				}
				$conditions[] = $group;
			}
			$attributes['data-rt-depends'] = $conditions;
		}

		return $attributes;
	}

	/**
	 * @param array $promotions
	 *
	 * @return array
	 */
	public static function add_promotions( $promotions ) {
		$top_label = Functions::get_option_item( 'rtcl_general_listing_label_settings', 'listing_top_label' );
		$bump_label = Functions::get_option_item( 'rtcl_general_listing_label_settings', 'listing_bump_up_label' );

		$promotions['_top'] = $top_label ?: esc_html__( "Top", "classified-listing-pro" );
		$promotions['_bump_up'] = $bump_label ?: esc_html__( "Bump Up", "classified-listing-pro" );

		return $promotions;
	}

	public static function widget_listings_fields( $fields ) {
		$fields['type']['options'] = [
			'featured_only' => esc_html__( 'Featured only', 'classified-listing-pro' ),
			'top_only'      => esc_html__( 'Top Only', 'classified-listing-pro' ),
			'feature_top'   => esc_html__( 'Featured and Top', 'classified-listing-pro' ),
			'all'           => esc_html__( 'All Type', 'classified-listing-pro' ),
		];
		$fields['view']['options']['map'] = esc_html__( 'Map', 'classified-listing-pro' );

		return $fields;
	}

	public static function rtcl_widget_shortcode_filter_listings_data( $data ) {
		if ( !empty( $data['instance']['view'] ) && $data['instance']['view'] === 'map' && !empty( $data['rtcl_query']->posts ) ) {
			wp_enqueue_script( 'rtcl-map' );
			$items = [];
			foreach ( $data['rtcl_query']->posts as $post_id ) {
				$listing = rtcl()->factory->get_listing( $post_id );
				$items[] = Functions::get_map_data( $listing );
			}

			$data['instance']['items'] = $items;
			$data['template'] = 'widgets/listings-map';
			$data['default_template_path'] = rtclPro()->get_plugin_template_path();
		}

		return $data;
	}

	public static function widget_listings_query_params( $params, $instance ) {
		$meta_queries = [];
		switch ( $instance['type'] ) {
			case 'top_only':
				$meta_queries[] = [
					'key'     => '_top',
					'value'   => 1,
					'compare' => '=',
				];
				break;

			case 'feature_top':
				$meta_queries[] = [
					'key'     => 'featured',
					'value'   => 1,
					'compare' => '=',
				];
				$meta_queries[] = [
					'key'     => '_top',
					'value'   => 1,
					'compare' => '=',
				];
				break;

			default:
				break;
		}
		if ( !empty( $meta_queries ) ) {
			if ( is_array( $params['meta_query'] ) && !empty( $params['meta_query'] ) ) {
				if ( count( $params['meta_query'] ) < 2 ) {
					$params['meta_query'] = array_merge( $params['meta_query'], $meta_queries, [ 'relation' => 'AND' ] );
				} else {
					$params['meta_query'] = array_merge( $params['meta_query'], $meta_queries );
				}
			} else {
				$params['meta_query'] = $meta_queries;
			}
		}

		if ( $instance['view'] === 'map' ) {
			$params['fields'] = 'ids';
		}

		return $params;
	}

	public static function widget_filter_fields( $fields ) {
		$new_fields = [
			'show_icon_image_for_category' => [
				'label' => esc_html__( 'Show category image / icon', 'classified-listing-pro' ),
				'type'  => 'checkbox',
			],
			'search_by_rating'             => [
				'label' => esc_html__( 'Search by Rating', 'classified-listing-pro' ),
				'type'  => 'checkbox',
			],
			'search_by_custom_fields'      => [
				'label' => esc_html__( 'Search by Custom Fields', 'classified-listing-pro' ),
				'type'  => 'checkbox',
			],
		];
		if ( FBHelper::isEnabled() ) {
			$allForms = Form::query()->select( 'id,title,`default`' )->where( 'status', 'publish' )->order_by( 'created_at', 'DESC' )->get();
			if ( !empty( $allForms ) ) {
				$directoryList = [
					''    => __( 'Default Directory', 'classified-listing-pro' ),
					'all' => __( 'All', 'classified-listing-pro' )
				];
				foreach ( $allForms as $form ) {
					$directoryList[$form->id] = $form->title . ( $form->default === 1 ? __( 'Default', 'classified-listing-pro' ) : '' );
				}
				$new_fields['directory'] = [
					'label'   => esc_html__( 'Select a directory', 'classified-listing-pro' ),
					'type'    => 'select',
					'options' => $directoryList
				];
			}
		}
		$target_key = 'search_by_listing_types';

		$position = array_search( $target_key, array_keys( $fields ) );
		if ( $position > -1 ) {
			Functions::array_insert( $fields, $position, $new_fields );
		} else {
			$fields = array_merge( $fields, $new_fields );
		}

		return $fields;
	}

	public static function rtcl_widget_filter_values( $data, $args, $instance ) {
		$custom_field_filter = '';
		if ( !empty( $instance['search_by_custom_fields'] ) ) {
			$filterTypes = [
				'text',
				'textarea',
				'number',
				'checkbox',
				'select',
				'radio',
				'date',
			];
			$filters = !empty( $_GET['filters'] ) ? $_GET['filters'] : [];
			$current_term = get_queried_object();

			// listings page category query available
			$category_query_var = get_query_var( '__cat' );
			if ( !empty( $category_query_var ) ) {
				$current_term = get_term_by( 'slug', $category_query_var, rtcl()->category );
			}

			if ( FBHelper::isEnabled() ) {
				$directory = empty( $instance['directory'] )
					? ''
					: ( $instance['directory'] === 'all' ? 'all'
						: ( is_numeric( $instance['directory'] ) ? absint( $instance['directory'] ) : '' ) );
				$directoryData = FBHelper::getDirectoryData( $directory );
				if ( !empty( $directoryData['custom'] ) ) {
					foreach ( $directoryData['custom'] as $custom_field ) {
						if ( empty( $custom_field['filterable'] ) || !in_array( $custom_field['element'], $filterTypes ) ) {
							continue;
						}
						$field = new FBField( $custom_field );
						$catIds = is_a( $current_term, WP_Term::class ) && $current_term->taxonomy === rtcl()->category ? $current_term->term_id : '';
						if ( !$field->isValidCategoryCondition( $catIds, $directoryData ) ) {
							continue;
						}
						$metaKey = $field->getMetaKey();
						$field_html = $isOpen = null;
						if ( 'number' == $field->getElement() ) {
							$fMinValue = !empty( $filters[$metaKey]['min'] ) ? esc_attr( $filters[$metaKey]['min'] ) : null;
							$fMaxValue = !empty( $filters[$metaKey]['max'] ) ? esc_attr( $filters[$metaKey]['max'] ) : null;
							$isOpen = $fMinValue || $fMaxValue ? ' is-open' : null;
							$field_html .= sprintf(
								'<div class="form-group row">
                                                                    <div class="col-md-6">
                                                                        <div class="ui-field">
                                                                            <input id="filters[%1$s][min]" name="filters[%1$s][min]" type="number" value="%2$s" class="ui-input form-control" placeholder="%3$s">									
                                                                        </div>											
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="ui-field">
                                                                            <input id="filters[%1$s][max]" name="filters[%1$s][max]" type="number" value="%4$s" class="ui-input form-control" placeholder="%5$s">
                                                                        </div>
                                                                    </div>
                                                                </div>',
								$metaKey,
								$fMinValue,
								esc_html__( 'Min.', 'classified-listing-pro' ),
								$fMaxValue,
								esc_html__( 'Max.', 'classified-listing-pro' )
							);
						} elseif ( 'date' == $field->getElement() ) {
							$value = !empty( $filters[$metaKey] ) ? esc_attr( $filters[$metaKey] ) : null;
							$isOpen = $value ? ' is-open' : null;
							$field_html .= sprintf(
								'<div class="form-group">
																<div class="ui-field">
																	<input id="filters[%1$s]" autocomplete="false" name="filters[%1$s]" type="text" value="%2$s" data-options="%4$s" class="ui-input form-control rtcl-date" placeholder="%3$s">									
																</div>	
														</div>',
								esc_attr( $metaKey ),
								esc_attr( $value ),
								esc_html__( 'Date', 'classified-listing-pro' ),
								htmlspecialchars(
									wp_json_encode(
										$field->getDateFieldOptions( [
											'singleDatePicker' => $field->getData( 'filterable_date_type' ) === 'single',
											'autoUpdateInput'  => false,
										] )
									)
								)
							);
						} elseif ( in_array( $field->getElement(), [ 'text', 'textarea' ], true ) ) {
							$values = !empty( $filters[$metaKey] ) ? esc_attr( $filters[$metaKey] ) : null;
							$isOpen = $values ? ' is-open' : null;
							$placeholder_text = sprintf( esc_html__( 'Search by %s', 'classified-listing-pro' ), $field->getLabel() );
							$field_html .= sprintf(
								'<div class="form-group">
                                                                    <input id="filters%1$s" name="filters[%1$s]" type="text" value="%2$s" class="ui-input form-control" placeholder="%3$s">
                                                                </div>',
								$metaKey,
								$values,
								apply_filters( 'rtcl_filter_custom_text_field_placeholder', $placeholder_text, $field )
							);
						} else {
							$cFieldHtml = '';
							$values = !empty( $filters[$metaKey] ) ? ( is_string( $filters[$metaKey] ) ? explode( ',', $filters[$metaKey] )
								: $filters[$metaKey] ) : [];
							$isOpen = is_array( $values ) && count( $values ) ? ' is-open' : null;
							$options = $field->getOptions();
							if ( !empty( $options ) ) {
								$cFieldHtml .= "<ul class='ui-link-tree is-collapsed'>";
								foreach ( $options as $option ) {
									$option = wp_parse_args( $option, [ 'value' => '', 'label' => '' ] );
									$_value = $option['value'];
									$_label = $option['label'];
									$checked = in_array( $_value, $values ) ? ' checked ' : '';
									$cFieldHtml .= "<li class='ui-link-tree-item {$field->getMetaKey()}-{$_value}'>";
									$cFieldHtml .= "<input id='filters{$metaKey}-values-{$_value}' name='filters[{$metaKey}][]' {$checked} value='{$_value}' type='checkbox' class='ui-checkbox filter-submit-trigger'>";
									$cFieldHtml .= "<a href='#' class='filter-submit-trigger'>" . esc_html( $_label ) . '</a>';
									$cFieldHtml .= '</li>';
								}
								$cFieldHtml .= '<li class="is-opener"><span class="rtcl-more"><i class="rtcl-icon rtcl-icon-plus-circled"></i><span class="text">'
									. esc_html__( 'Show More', 'classified-listing-pro' ) . '</span></span></li>';
								$cFieldHtml .= '</ul>';
							}
							$field_html .= apply_filters( 'rtcl_filter_widget_custom_field_html', $cFieldHtml, $field, $options, $filters );
						}

						$custom_field_filter .= sprintf(
							'<div class="rtcl-custom-field-filter rtcl-custom-field-filter-%s ui-accordion-item %s">
									                <a class="ui-accordion-title">
									                    <span>%s</span>
									                    <span class="ui-accordion-icon rtcl-icon rtcl-icon-anchor"></span>
									                </a>
									                <div class="ui-accordion-content">%s</div>
									            </div>%s',
							$field->getElement(),
							$isOpen,
							$field->getLabel(),
							$field_html,
							$directory ? sprintf( '<input type="hidden" name="directory" value="%s" />', $directory ) : ''
						);
					}
				}
			} else {

				if ( is_a( $current_term, WP_Term::class ) && rtcl()->category === $current_term->taxonomy ) {
					$c_ids = Functions::get_custom_field_ids( $current_term->term_id );
					if ( !empty( $c_ids ) ) {
						$i = 1;
						foreach ( $c_ids as $c_id ) {
							$field = rtcl()->factory->get_custom_field( $c_id );
							if ( !$field ) {
								continue;
							}
							if ( !empty( $conditions = $field->getConditions() ) && is_array( $conditions ) ) {
								$isCValid = true;
								foreach ( $conditions as $group ) {
									$isGValid = true;
									foreach ( $group as $rule ) {
										$isValid = true;
										$r_field_id = !empty( $rule['field'] ) ? absint( $rule['field'] ) : 0;
										$operator = !empty( $rule['operator'] ) ? $rule['operator'] : '';
										if ( !$r_field_id || !$operator ) {
											continue;
										}
										$r_field_id = apply_filters( 'rtcl_wpml_cf_field_id', $r_field_id, $rule, $group, $field );
										$r_value = !empty( $rule['value'] ) ? $rule['value'] : '';
										$d_value = !empty( $filters['_field_' . $r_field_id] ) ? $filters['_field_' . $r_field_id] : '';
										if ( $operator === '==empty' ) { // hasNoValue
											$isValid = empty( $d_value );
										} elseif ( $operator === '!=empty' ) { // hasValue  -- ANY value
											$isValid = !empty( $d_value );
										} elseif ( $operator === '==' ) { // equalTo
											if ( is_array( $d_value ) ) {
												$isValid = in_array( $r_value, $d_value );
											} else {
												$isValid = strtolower( $d_value ) == strtolower( $r_value );
											}
										} elseif ( $operator === '!=' ) { // notEqualTo
											if ( is_array( $d_value ) ) {
												$isValid = !in_array( $r_value, $d_value );
											} else {
												$isValid = strtolower( $d_value ) !== strtolower( $r_value );
											}
										} elseif ( $operator === '==pattern' && !empty( $r_value ) ) { // patternMatch
											if ( is_array( $d_value ) ) {
												$isPatternValid = false;
												foreach ( $d_value as $_ ) {
													preg_match( "/$r_value/", $_, $matches );
													if ( !empty( $matches ) ) {
														$isPatternValid = true;
														break;
													}
												}
												$isValid = $isPatternValid;
											} else {
												preg_match( "/$r_value/", $d_value, $matches );
												$isValid = !empty( $matches );
											}
										} elseif ( $operator === '==contains' ) { // contains
											if ( is_array( $d_value ) ) {
												$isContainsValid = false;
												foreach ( $d_value as $_ ) {
													if ( strpos( (string)$r_value, (string)$_ ) !== false ) {
														$isContainsValid = true;
														break;
													}
												}
												$isValid = $isContainsValid;
											} else {
												if ( empty( $d_value ) ) {
													$isValid = false;
												} else {
													$isValid = strpos( (string)$r_value, (string)$d_value ) !== false;
												}
											}
										}
										if ( !$isValid ) {
											$isGValid = false;
											break;
										}
									}
									if ( $isGValid ) {
										$isCValid = true;
										break;
									} else {
										$isCValid = false;
									}
								}
								if ( !$isCValid ) {
									continue;
								}
							}
							if ( in_array( $field->getType(), $filterTypes ) && $field->isSearchable() ) {
								$field_html = $isOpen = null;
								$metaKey = $field->getMetaKey();
								if ( $field->getType() == 'number' ) {
									$fMinValue = !empty( $filters[$metaKey]['min'] ) ? esc_attr( $filters[$metaKey]['min'] ) : null;
									$fMaxValue = !empty( $filters[$metaKey]['max'] ) ? esc_attr( $filters[$metaKey]['max'] ) : null;
									// $isOpen       = $fMinValue || $fMaxValue ? ' is-open' : null;
									$isOpen = ( ( $fMinValue || $fMaxValue )
										&& ( ( $fMinValue > 0 && $fMinValue != $field->getMin() )
											|| $fMaxValue != $field->getMax() ) ) ? 'is-open' : null;
									$min_settings = $field->getMin();
									$max_settings = !empty( $field->getMax() ) ? 'data-max=' . absint( $field->getMax() ) : '';
									$field_html .= sprintf(
										'<div class="form-group row">
                                                                    <div class="col-md-6">
                                                                        <div class="ui-field">
                                                                            <input id="filters[%1$s][min]" name="filters[%1$s][min]" type="number" value="%2$s" class="ui-input form-control" data-min="%6$s" placeholder="%3$s">									
                                                                        </div>											
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="ui-field">
                                                                            <input id="filters[%1$s][max]" name="filters[%1$s][max]" type="number" value="%4$s" class="ui-input form-control" %7$s placeholder="%5$s">
                                                                        </div>
                                                                    </div>
                                                                </div>',
										$metaKey,
										$fMinValue,
										esc_html__( 'Min.', 'classified-listing-pro' ),
										$fMaxValue,
										esc_html__( 'Max.', 'classified-listing-pro' ),
										absint( $min_settings ),
										$max_settings
									);
								} elseif ( $field->getType() == 'date' ) {
									$value = !empty( $filters[$metaKey] ) ? esc_attr( $filters[$metaKey] ) : null;
									$isOpen = $value ? ' is-open' : null;
									$field_html .= sprintf(
										'<div class="form-group">
																<div class="ui-field">
																	<input id="filters[%1$s]" autocomplete="false" name="filters[%1$s]" type="text" value="%2$s" data-options="%4$s" class="ui-input form-control rtcl-date" placeholder="%3$s">									
																</div>	
														</div>',
										esc_attr( $metaKey ),
										esc_attr( $value ),
										esc_html__( 'Date', 'classified-listing-pro' ),
										htmlspecialchars(
											wp_json_encode(
												$field->getDateFieldOptions(
													[
														'singleDatePicker' => $field->getDateSearchableType() === 'single',
														'autoUpdateInput'  => false,
													]
												)
											)
										)
									);
								} elseif ( in_array( $field->getType(), [ 'text', 'textarea' ], true ) ) {
									$values = !empty( $filters[$metaKey] ) ? esc_attr( $filters[$metaKey] ) : null;
									$isOpen = $values ? ' is-open' : null;
									// Suppor translatepress .
									$placeholder_text = sprintf( esc_html__( 'Search by %s', 'classified-listing-pro' ), $field->getLabel() );
									$field_html .= sprintf(
										'<div class="form-group">
                                                                    <input id="filters%1$s" name="filters[%1$s]" type="text" value="%2$s" class="ui-input form-control" placeholder="%3$s">
                                                                </div>',
										$metaKey,
										$values,
										apply_filters( 'rtcl_filter_custom_text_field_placeholder', $placeholder_text, $field )
									);
								} else {
									$custom_field_html = '';
									$values = !empty( $filters[$metaKey] ) ? $filters[$metaKey] : [];
									$isOpen = count( $values ) ? ' is-open' : null;
									$options = $field->getOptions();
									if ( !empty( $options['choices'] ) ) {
										$custom_field_html .= "<ul class='ui-link-tree is-collapsed'>";
										foreach ( $options['choices'] as $key => $option ) {
											$checked = in_array( $key, $values ) ? ' checked ' : '';
											$custom_field_html .= "<li class='ui-link-tree-item {$field->getMetaKey()}-{$key}'>";
											$custom_field_html .= "<input id='filters{$metaKey}-values-{$key}' name='filters[{$metaKey}][]' {$checked} value='{$key}' type='checkbox' class='ui-checkbox filter-submit-trigger'>";
											$custom_field_html .= "<a href='#' class='filter-submit-trigger'>" . esc_html( $option ) . '</a>';
											$custom_field_html .= '</li>';
										}
										$custom_field_html .= '<li class="is-opener"><span class="rtcl-more"><i class="rtcl-icon rtcl-icon-plus-circled"></i><span class="text">'
											. esc_html__(
												'Show More',
												'classified-listing-pro'
											) . '</span></span></li>';
										$custom_field_html .= '</ul>';
									}
									$field_html .= apply_filters( 'rtcl_filter_widget_custom_field_html', $custom_field_html, $field, $options, $filters );
								}

								$custom_field_filter .= apply_filters(
									'rtcl_widget_filter_custom_field_html',
									sprintf(
										'<div class="rtcl-custom-field-filter rtcl-custom-field-filter-%s ui-accordion-item %s">
									                <a class="ui-accordion-title">
									                    <span>%s</span>
									                    <span class="ui-accordion-icon rtcl-icon rtcl-icon-anchor"></span>
									                </a>
									                <div class="ui-accordion-content">%s</div>
									            </div>',
										$field->getType(),
										$isOpen,
										$field->getLabel(),
										$field_html
									),
									$field,
									$c_id,
									$filters,
									$field_html
								);
							}
						}
					}
				}
			}
		}
		$data['custom_field_filter'] = $custom_field_filter;

		if ( !empty( $instance['search_by_rating'] )
			&& Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox' )
		) {
			$filters = !empty( $_GET['filters'] ) ? $_GET['filters'] : [];
			$rating = !empty( $filters['rating'] ) ? $filters['rating'] : '';

			$rating_html = apply_filters(
				'rtcl_filter_widget_rating_list',
				[
					'5.0' => esc_html__( '5.0', 'classified-listing-pro' ),
					'4.5' => esc_html__( '4.5 & up', 'classified-listing-pro' ),
					'4.0' => esc_html__( '4.0 & up', 'classified-listing-pro' ),
					'3.5' => esc_html__( '3.5 & up', 'classified-listing-pro' ),
					'3.0' => esc_html__( '3.0 & up', 'classified-listing-pro' ),
				]
			);

			$rating_filter = '<ul class="ui-link-tree is-collapsed">';

			foreach ( $rating_html as $key => $text ) {
				$key = (float)$key;
				$width = ( $key / 5 ) * 100;
				$rating_filter .= '<li class="ui-link-tree-item' . esc_attr( $rating == $key ? ' selected' : '' ) . '" data-id="' . esc_attr( $key ) . '">
									<div class="star-rating"><span style="width:' . esc_attr( $width ) . '%"></span></div>
									<span class="rating-filter-label">' . esc_html( $text ) . '</span>
								</li>';
			}

			$rating_filter .= '</ul>';

			$rating_filter .= '<input type="hidden" name="filters[rating]" value="' . esc_attr( $rating ) . '"/>';

			$data['rating_filter'] = sprintf(
				'<div class="rtcl-rating-filter ui-accordion-item is-open">
								<a class="ui-accordion-title">
                                    <span>%s</span>
                                    <span class="ui-accordion-icon rtcl-icon rtcl-icon-anchor"></span>
                                </a>
                                <div class="ui-accordion-content">%s</div>
                            </div>',
				apply_filters( 'rtcl_widget_filter_rating_title', esc_html__( 'Ratings', 'classified-listing-pro' ) ),
				$rating_filter
			);
		}

		return $data;
	}

	public static function widget_filter_update_values( $instance, $new_instance ) {
		$instance['show_icon_image_for_category'] = !empty( $new_instance['show_icon_image_for_category'] ) ? 1 : 0;
		$instance['search_by_custom_fields'] = !empty( $new_instance['search_by_custom_fields'] ) ? 1 : 0;
		$instance['search_by_rating'] = !empty( $new_instance['search_by_rating'] ) ? 1 : 0;
		$instance['directory'] = !empty( $new_instance['directory'] ) ? ( $new_instance['directory'] === 'all' ? 'all'
			: absint( $new_instance['directory'] ) ) : '';

		return $instance;
	}

	public static function widget_filter_default_values( $defaults ) {
		$defaults['show_icon_image_for_category'] = 1;
		$defaults['search_by_custom_fields'] = 1;
		$defaults['search_by_rating'] = 0;

		return $defaults;
	}

	public static function widget_filter_rating_query( $meta_query ) {
		$filters = !empty( $_GET['filters'] ) ? $_GET['filters'] : [];
		if ( !empty( $filters['rating'] ) ) {
			$rating = (float)$filters['rating'];
			$meta_query[] = [
				'key'     => '_rtcl_average_rating',
				'value'   => $rating,
				'compare' => '>=',
			];
		}

		// Skip sold out listing
		if ( ( !empty( $filters ) || isset( $_GET['q'] ) ) && apply_filters( 'rtcl_filter_disappear_mark_as_sold', true ) ) {
			$meta_query[] = [
				'relation' => 'OR',
				[
					'key'     => '_rtcl_mark_as_sold',
					'compare' => 'NOT EXISTS' // doesn't work
				],
				[
					'key'     => '_rtcl_mark_as_sold',
					'value'   => '1',
					'compare' => '!='
				]
			];
		}

		return $meta_query;
	}

	/**
	 * @param array $args
	 * @param array $params
	 *
	 * @return array
	 */
	public static function exclude_sold_out_listings( $args, $params ): array {
		if ( !empty( $params ) && apply_filters( 'rtcl_filter_disappear_mark_as_sold', true ) ) {
			$query = [
				'relation' => 'OR',
				[
					'key'     => '_rtcl_mark_as_sold',
					'compare' => 'NOT EXISTS' // doesn't work
				],
				[
					'key'     => '_rtcl_mark_as_sold',
					'value'   => '1',
					'compare' => '!='
				]
			];
			if ( !empty( $args['meta_query'] ) ) {
				$args['meta_query'][] = $query;
			} else {
				$args['meta_query'] = $query;
			}
		}

		return $args;
	}

	public static function widget_search_fields( $fields ) {
		$fields['style']['options'] = Options::widget_search_style_options();
		$fields['style']['type'] = 'select';
		$new_fields = [
			'orientation' => [
				'label'   => esc_html__( 'Orientation', 'classified-listing-pro' ),
				'type'    => 'radio',
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'classified-listing-pro' ),
					'inline'   => esc_html__( 'inline', 'classified-listing-pro' ),
				],
			],
		];
		$position = array_search( 'style', array_keys( $fields ) );
		if ( $position > -1 ) {
			Functions::array_insert( $fields, $position, $new_fields );
		} else {
			$fields = array_merge( $fields, $new_fields );
		}

		return $fields;
	}

	public static function rtcl_widget_search_values( $data, $args, $instance ) {
		$data['style'] = isset( $instance['style'] ) && array_key_exists( $instance['style'], Options::widget_search_style_options() )
			? $instance['style'] : 'suggestion';
		$data['orientation'] = !empty( $instance['orientation'] ) ? $instance['orientation'] : 'inline';
		if ( get_query_var( '__loc' ) && $location = get_term_by( 'slug', get_query_var( '__loc' ), rtcl()->location ) ) {
			$data['selected_location'] = $location;
		}

		if ( get_query_var( '__cat' ) && $location = get_term_by( 'slug', get_query_var( '__cat' ), rtcl()->category ) ) {
			$data['selected_category'] = $location;
		}

		$data['active_count'] = $data['can_search_by_category'] + $data['can_search_by_location'] + $data['can_search_by_listing_types']
			+ $data['can_search_by_price'];

		$data['classes'] = [
			'rtcl',
			'rtcl-widget-search',
			'rtcl-widget-search-' . $data['orientation'],
			'rtcl-widget-search-style-' . $data['style'],
		];
		$data['instance'] = $instance;
		$data['args'] = $args;
		$data['data'] = $data;
		$data['template'] = 'widgets/search';
		$data['default_template_path'] = rtclPro()->get_plugin_template_path();

		return $data;
	}

	public static function widget_search_update_values( $instance, $new_instance ) {
		$instance['style'] = !empty( $new_instance['style'] ) && array_key_exists( $new_instance['style'], Options::widget_search_style_options() )
			? strip_tags( $new_instance['style'] ) : 'suggestion';
		$instance['orientation'] = isset( $new_instance['orientation'] ) && !empty( $new_instance['orientation'] ) ? strip_tags( $new_instance['orientation'] )
			: 'inline';

		return $instance;
	}

	public static function widget_search_default_values( $default_values ) {
		$default_values['style'] = 'popup';
		$default_values['orientation'] = 'inline';

		return $default_values;
	}

	public static function add_public_script_localize_params( $localize ) {
		$localize['has_map'] = Functions::has_map() == 'yes';
		$localize['online_status_seconds'] = (int)apply_filters( 'rtcl_user_online_status_seconds', 300 );
		$localize['online_status_offline_text'] = apply_filters( 'rtcl_user_offline_text', esc_html__( 'Offline Now', 'classified-listing-pro' ) );
		$localize['online_status_online_text'] = apply_filters( 'rtcl_user_online_text', esc_html__( 'Online Now', 'classified-listing-pro' ) );

		return $localize;
	}

	public static function add_view_class_at_loop( $class ) {
		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}

		return 'grid' === $view ? 'rtcl-grid-view' : 'rtcl-list-view';
	}

	public static function add_view_at_shortcode_listings_attributes( $atts ) {
		$atts['view'] = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );

		return $atts;
	}

	public static function add_email_services( $emailServices ) {
		$emailServices['Unread_Message_Email'] = new UnreadMessageEmail();
		$emailServices['User_Verify_Link_Email_To_User'] = new UserVerifyLinkEmailToUser();

		return $emailServices;
	}


	/**
	 * @param array $classes
	 * @param Listing $listing
	 *
	 * @return array
	 */
	public static function mark_as_sold_class( $classes, $listing ) {
		if ( Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
			if ( is_array( $classes ) ) {
				$classes[] = 'is-sold';
			}
		}

		return $classes;
	}

	public static function add_pro_payment_gateways( $gateways ) {
		$gateways[] = GatewayAuthorize::class;
		$gateways[] = GatewayStripe::class;

		if ( Functions::is_wc_activated() ) {
			$gateways[] = GatewayWooPayment::class;
		}

		return $gateways;
	}

	/**
	 * @param array $data
	 * @param array $params
	 * @param array $filterData
	 *
	 * @return array
	 */
	public static function ajax_filter_modify_data( $data, $params, $filterData ) {

		if ( !empty( $filterData['itemKeys'] ) && is_array( $filterData['itemKeys'] ) ) {
			$cf_meta_query = !empty( $data['args']['meta_query'] ) ? $data['args']['meta_query'] : [];

			if ( in_array( 'directory', $filterData['itemKeys'] ) && !empty( $params['directory'] ) ) {
				$inputDirectory = is_numeric( $params['directory'] )
					? [ absint( $params['directory'] ) ]
					: ( is_array( $params['directory'] ) ? array_filter( array_map( 'absint', $params['directory'] ) )
						: trim( sanitize_text_field( wp_unslash( $params['directory'] ) ) ) );
				$selectedValues = !empty( $inputDirectory ) ? ( is_string( $inputDirectory ) ? array_filter( array_map( 'absint',
					explode( ',', $inputDirectory ) ) ) : $inputDirectory ) : [];

				if ( !empty( $selectedValues ) ) {
					$allForms = Form::query()->select( 'id,title,`default`' )->where( 'status', 'publish' )->whereIn( 'id', $selectedValues )->get();
					if ( !empty( $allForms ) ) {
						$selected = [];
						foreach ( $allForms as $form ) {
							$selected[$form->id] = $form->title;
						}
						$cf_meta_query[] = [
							'key'     => '_rtcl_form_id',
							'value'   => array_keys( $selected ),
							'compare' => 'IN',
						];
						$data['active_filters'][] = [
							'id'       => 'directory',
							'itemId'   => 'directory',
							'label'    => __( "Directory", 'classified-listing-pro' ),
							'selected' => $selected
						];
					}
				}
			}

			if ( in_array( 'cf', $filterData['itemKeys'] ) ) {
				if ( FBHelper::isEnabled() ) {
					if ( empty( $params['directory'] ) || $params['directory'] === 'all' ) {
						$directory = 'all';
					} else {
						if ( is_array( $params['directory'] ) ) {
							$directory = array_filter( array_map( 'absint', $params['directory'] ) );
							$directory = !empty( $directory ) ? $directory : 0;
						} else {
							if ( is_numeric( $params['directory'] ) ) {
								$directory = absint( $params['directory'] );
							} else {
								$directory = explode( ',', $params['directory'] );
								if ( !empty( $directory ) ) {
									$directory = array_filter( array_map( 'absint', $directory ) );
									$directory = !empty( $directory ) ? $directory : 0;
								} else {
									$directory = 0;
								}
							}
						}
					}

					$cFields = FBHelper::getDirectoryCustomFields( $directory );

					if ( !empty( $cFields ) ) {
						foreach ( $params as $key => $values ) {
							if ( !str_starts_with( $key, 'cf_' ) ) {
								continue;
							}

							$fieldName = str_replace( 'cf_', '', $key );
							$field = $rawField = null;
							foreach ( $cFields as $_cField ) {
								if ( !empty( $_cField['name'] ) && $_cField['name'] === $fieldName ) {
									$rawField = $_cField;
									$field = new FBField( $_cField );
									break;
								}
							}

							if ( empty( $field ) || !$field->isFilterable() ) {
								continue;
							}

							$values = !empty( $values ) ? ( is_array( $values ) ? array_filter( array_map( function ( $param ) {
								return trim( sanitize_text_field( wp_unslash( $param ) ) );
							}, $values ) ) : trim( sanitize_text_field( wp_unslash( $values ) ) ) ) : '';
							$activeFilterSelected = [];
							if ( $field->getElement() === 'number' ) {
								$values = !empty( $values ) ? ( is_string( $values ) ? explode( ',', $values ) : $values ) : [];
								$values = !empty( $values ) ? array_filter( array_map( 'intval', $values ) ) : [];

								if ( $n = count( $values ) ) {
									if ( 2 === $n ) {
										$cf_meta_query[] = [
											'key'     => $fieldName,
											'value'   => $values,
											'type'    => 'NUMERIC',
											'compare' => 'BETWEEN',
										];
									} else {
										if ( !empty( $values[1] ) ) {
											$cf_meta_query[] = [
												'key'     => $fieldName,
												'value'   => $values[1],
												'type'    => 'NUMERIC',
												'compare' => '<=',
											];
										} else {
											$cf_meta_query[] = [
												'key'     => $fieldName,
												'value'   => $values[0],
												'type'    => 'NUMERIC',
												'compare' => '>=',
											];
										}
									}
									$activeFilterSelected = [ 'cf_' . $field->getMetaKey() => implode( ' - ', $values ) ];
								}
							} elseif ( in_array( $field->getElement(), [ 'checkbox', 'select', 'radio' ] ) ) {
								$values = is_array( $values ) ? $values : [ $values ];
								if ( count( $values ) > 1 ) {

									$sub_meta_queries = [ 'relation' => 'OR' ];

									foreach ( $values as $value ) {
										$sub_meta_queries[] = [
											'key'     => $fieldName,
											'value'   => sanitize_text_field( $value ),
											'compare' => '=',
										];
										$activeFilterSelected[$value] = $value;
									}

									$cf_meta_query[] = apply_filters( 'rtcl_cf_sub_meta_queries', $sub_meta_queries, $field );

								} else {
									$cf_meta_query[] = [
										'key'     => $fieldName,
										'value'   => sanitize_text_field( $values[0] ),
										'compare' => '=',
									];
									$activeFilterSelected = [ $values[0] => $values[0] ];
								}
							} elseif ( $field->getElement() === 'date' ) {
								$rawValues = !empty( $params['cf_' . $field->getMetaKey()] ) ? $params['cf_' . $field->getMetaKey()] : $values;
								$values = is_array( $rawValues ) ? implode( ',', $rawValues ) : $rawValues;
								$values = trim( sanitize_text_field( wp_unslash( $values ) ) );
								$search_type = $field->getDateFilterDateType();
								$dateFormatType = $field->getDateFormatType();
								$tempField = $rawField;
								if ( $search_type == 'range' ) {
									$tempField['date_type'] = 'range';
									$values = explode( ' - ', $values );
									$values = [
										'start' => $values[0] ?? ( $values['start'] ?? '' ),
										'end'   => $values[1] ?? ( $values['end'] ?? '' ),
									];
								} else {
									$tempField['date_type'] = 'single';
								}
								$dateValues = FBHelper::sanitizeFieldValue( $values, $tempField );
								if ( !empty( $dateValues ) ) {
									$dateFormat = $field->getData( 'date_format', 'Y-m-d' );
									$activeFilterSelected = [
										'cf_' . $field->getMetaKey() => is_array( $dateValues ) ? implode( ' - ', [
											wp_date( $dateFormat, strtotime( $dateValues['start'] ) ),
											wp_date( $dateFormat, strtotime( $dateValues['end'] ) )
										] ) : wp_date( $dateFormat, strtotime( $dateValues ) )
									];
									if ( $field->getDateType() == 'range' ) {
										$start_meta_key = $fieldName . '_' . 'start';
										$end_meta_key = $fieldName . '_' . 'end';

										if ( $search_type == 'single' ) {
											$start_date = $dateValues;
											$end_date = $dateValues;
										} else {
											$start_date = $dateValues['start'];
											$end_date = $dateValues['end'];
										}
										if ( $start_date && $end_date ) {
											$cf_meta_query[] = apply_filters(
												'rtcl_cf_date_range_meta_queries',
												[
													'relation' => 'AND',
													[
														'key'     => $start_meta_key,
														'value'   => $start_date,
														'compare' => '<=',
														'type'    => $dateFormatType,
													],
													[
														'key'     => $end_meta_key,
														'value'   => $end_date,
														'compare' => '>=',
														'type'    => $dateFormatType,
													],
												],
												$field,
												$values
											);
										}
									} else {
										if ( $search_type == 'range' ) {
											$start_date = $dateValues['start'];
											$end_date = $dateValues['end'];
											$cf_meta_query[] = [
												'key'     => $fieldName,
												'value'   => [ $start_date, $end_date ],
												'compare' => 'BETWEEN',
												'type'    => $dateFormatType,
											];
										} else {
											$cf_meta_query[] = [
												'key'     => $fieldName,
												'value'   => $dateValues,
												'compare' => '=',
												'type'    => $dateFormatType,
											];
										}
									}
								}
							} else {
								$values = is_array( $values ) ? $values[0] : $values;
								$operator = ( in_array(
									$field->getElement(),
									[
										'text',
										'textarea',
										'url',
									]
								) ) ? 'LIKE' : '=';
								$cf_meta_query[] = [
									'key'     => $fieldName,
									'value'   => sanitize_text_field( $values ),
									'compare' => $operator,
								];
								$activeFilterSelected = [ 'cf_' . $field->getMetaKey() => $values ];
							}

							$data['active_filters'][] = [
								'id'       => 'cf_' . $field->getMetaKey(),
								'itemId'   => 'cf_' . $field->getMetaKey(),
								'label'    => $field->getLabel(),
								'selected' => $activeFilterSelected
							];
						}
					}
				} else {
					foreach ( $params as $key => $values ) {
						if ( strpos( $key, 'cf_' ) !== 0 ) {
							continue;
						}
						$field_id = absint( str_replace( 'cf_', '', $key ) );
						$field = rtcl()->factory->get_custom_field( $field_id );
						if ( !$field ) {
							continue;
						}
						$key = '_field_' . $field_id;
						$activeFilterSelected = [];
						$values = !empty( $values ) ? ( is_array( $values ) ? array_filter( array_map( function ( $param ) {
							return trim( sanitize_text_field( wp_unslash( $param ) ) );
						}, $values ) ) : trim( sanitize_text_field( wp_unslash( $values ) ) ) ) : '';
						if ( $field->getType() === 'number' ) {
							$values = is_array( $values ) ? $values : [ $values ];
							if ( $n = count( $values ) ) {
								if ( 2 == $n ) {
									$cf_meta_query[] = [
										'key'     => $key,
										'value'   => array_map( 'intval', array_values( $values ) ),
										'type'    => 'NUMERIC',
										'compare' => 'BETWEEN',
									];
								} else {
									if ( empty( $values['min'] ) ) {
										$cf_meta_query[] = [
											'key'     => $key,
											'value'   => (int)$values['max'],
											'type'    => 'NUMERIC',
											'compare' => '<=',
										];
									} else {
										$cf_meta_query[] = [
											'key'     => $key,
											'value'   => (int)$values['min'],
											'type'    => 'NUMERIC',
											'compare' => '>=',
										];
									}
								}
								$activeFilterSelected = [ 'cf_' . $field_id => implode( ' - ', $values ) ];
							}
						} elseif ( in_array( $field->getType(), [ 'checkbox', 'select', 'radio' ] ) ) {
							$values = is_array( $values ) ? $values : [ $values ];
							$fieldOptions = $field->getOptions();

							if ( count( $values ) > 1 ) {
								$sub_meta_queries = [
									'relation' => 'OR',
								];

								foreach ( $values as $value ) {
									$sub_meta_queries[] = [
										'key'     => $key,
										'value'   => sanitize_text_field( $value ),
										'compare' => 'LIKE',
									];
									$activeFilterSelected[$value] = !empty( $fieldOptions['choices'][$value] ) ? $fieldOptions['choices'][$value]
										: $value;
								}

								$cf_meta_query[] = apply_filters( 'rtcl_cf_sub_meta_queries', $sub_meta_queries, $field );

							} else {
								$cf_meta_query[] = [
									'key'     => $key,
									'value'   => sanitize_text_field( $values[0] ),
									'compare' => 'LIKE',
								];
								$activeFilterSelected = [
									$values[0] => !empty( $fieldOptions['choices'][$values[0]] ) ? $fieldOptions['choices'][$values[0]] : $values[0]
								];
							}
						} elseif ( $field->getType() === 'date' ) {
							$values = is_array( $values ) ? $values[0] : $values;
							$activeFilterSelected = [ 'cf_' . $field_id => $values ];
							$date_type = $field->getDateType();
							$search_type = $field->getDateSearchableType();
							$type = $date_type == 'date_time' || $date_type == 'date_time_range' ? 'DATETIME' : 'DATE';
							if ( $date_type == 'date' || $date_type == 'date_time' ) {
								$meta_key = $field->getMetaKey();

								if ( $search_type == 'single' ) {
									$cf_meta_query[] = [
										'key'     => $meta_key,
										'value'   => $field->sanitize_date_field( $values, [ 'range' => false ] ),
										'compare' => '=',
										'type'    => $type,
									];
								} else {
									$dates = $field->sanitize_date_field( $values, [ 'range' => true ] );
									$start_date = $dates['start'];
									$end_date = $dates['end'];
									$cf_meta_query[] = [
										'key'     => $meta_key,
										'value'   => [ $start_date, $end_date ],
										'compare' => 'BETWEEN',
										'type'    => $type,
									];
								}
							} elseif ( $date_type == 'date_range' || $date_type == 'date_range_time' ) {
								$start_meta_key = $field->getDateRangeMetaKey( 'start' );
								$end_meta_key = $field->getDateRangeMetaKey( 'end' );

								if ( $search_type == 'single' ) {
									$start_date = $end_date = $field->sanitize_date_field( $values, [ 'range' => false ] );
									$end_date = $start_date ? gmdate( 'Y-m-d', strtotime( $start_date ) ) . ' 23:59:59' : '';
								} else {
									$dates = $field->sanitize_date_field( $values, [ 'range' => true ] );
									$start_date = $dates['start'];
									$end_date = $dates['end'];
								}
								if ( $start_date ) {
									$cf_meta_query[] = [
										'key'     => $start_meta_key,
										'value'   => $start_date,
										'compare' => '<=',
										'type'    => $type,
									];
								}
								if ( $end_date ) {
									$cf_meta_query[] = [
										'key'     => $end_meta_key,
										'value'   => $end_date,
										'compare' => '>=',
										'type'    => $type,
									];
								}
							}
						} else {
							$values = is_array( $values ) ? $values[0] : $values;
							$operator = ( in_array( $field->getType(), [
								'text',
								'textarea',
								'url',
							] ) ) ? 'LIKE' : '=';
							$cf_meta_query[] = [
								'key'     => $key,
								'value'   => sanitize_text_field( $values ),
								'compare' => $operator,
							];
							$activeFilterSelected = [ 'cf_' . $field_id => $values ];
						}

						$data['active_filters'][] = [
							'id'       => 'cf_' . $field_id,
							'itemId'   => 'cf_' . $field_id,
							'label'    => $field->getLabel(),
							'selected' => $activeFilterSelected
						];
					}
				}
			}

			if ( in_array( 'rating', $filterData['itemKeys'] )
				&& ( $rating = !empty( $params['filter_rating'] ) && is_numeric( $params['filter_rating'] ) ? $params['filter_rating'] + 0 : 0 )
			) {
				if ( !empty( $rating ) ) {
					$cf_meta_query[] = [
						'key'     => '_rtcl_average_rating',
						'value'   => $rating,
						'compare' => '>=',
					];
				}

				// Skip sold out listing
				if ( isset( $params['q'] ) && apply_filters( 'rtcl_filter_disappear_mark_as_sold', true ) ) {
					$cf_meta_query[] = [
						'relation' => 'OR',
						[
							'key'     => '_rtcl_mark_as_sold',
							'compare' => 'NOT EXISTS' // doesn't work
						],
						[
							'key'     => '_rtcl_mark_as_sold',
							'value'   => '1',
							'compare' => '!='
						]
					];
				}
				$data['active_filters'][] = [
					'id'       => 'filter_rating',
					'itemId'   => 'rating',
					'label'    => __( "Rating", 'classified-listing-pro' ),
					'selected' => [ $rating => $rating ]
				];
			}

			$data['args']['meta_query'] = $cf_meta_query;
		}

		return $data;
	}

	/**
	 * @param array $response
	 * @param array $params
	 * @param array $filterData
	 *
	 * @return array
	 */
	public static function ajax_filter_cf_items( $response, $params, $filterData ) {

		if ( !empty( $filterData['itemKeys'] ) && is_array( $filterData['itemKeys'] ) && in_array( 'cf', $filterData['itemKeys'] )
			&& empty( $filterData['initLoad'] )
		) {
			$response['cf_items'] = Fns::get_ajax_filter_cs_items( $params, $filterData );
		}

		return $response;
	}
}

<?php

namespace RtclPro\Controllers\Hooks;


use Rtcl\Helpers\Functions;
use Rtcl\Models\Payment;
use Rtcl\Models\Roles;
use RtclPro\Controllers\ChatController;
use RtclPro\Gateways\WooPayment\WooPayment;
use RtclPro\Helpers\Fns;
use WP_Post;
use WP_Query;

class ActionHooks {
	public static function init() {
		add_action( 'init', [ __CLASS__, "wc_payment_support" ] );
		add_action( 'rtcl_admin_settings_before_saved_account_settings', [
			__CLASS__,
			'apply_user_role_at_account_settings'
		], 10, 2 );

		add_action( 'rtcl_listing_overwrite_change', [ __CLASS__, 'update_promotions_at_save_post' ], 10, 2 );

		add_action( 'rtcl_save_pricing_meta_data', [ __CLASS__, 'save_pricing_meta_data' ], 10, 2 );

		add_action( 'rtcl_cron_move_listing_publish_to_expired', [
			__CLASS__,
			'remove_data_move_listing_publish_to_expired'
		] );
		add_action( 'rtcl_cron_hourly_scheduled_events', [ __CLASS__, 'cron_hourly_scheduled_actions' ] );

		add_action( 'rtcl_rest_checkout_process_success', [ __CLASS__, 'rest_checkout_process_mail' ] );

		add_action( 'clear_auth_cookie', [ __CLASS__, 'set_user_status_offline' ] );

		add_action( 'rtcl_shortcode_before_listings_loop', [ __CLASS__, 'add_map_data_support' ] );
		add_action( 'rtcl_shortcode_after_listings_loop', [ __CLASS__, 'remove_map_data_support' ] );
		
		add_action( 'rtcl_ajax_filter_query_before_loop', [ __CLASS__, 'ajax_filter_add_map_data_support' ] );
		add_action( 'rtcl_ajax_filter_query_after_loop', [ __CLASS__, 'ajax_filter_remove_map_data_support' ] );

		add_action( 'rtcl_listing_submit_box_misc_actions__bump_up', [ __CLASS__, 'add_bump_up_expired_date' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ __CLASS__, 'restrict_manage_posts' ], 12 );
		add_action( 'parse_query', [ __CLASS__, 'parse_query' ], 12 );
		add_action( 'rtcl_my_listings_meta_lists', [ __CLASS__, 'add_chat_count' ] );
		add_action( 'wp_head', [ __CLASS__, 'update_user_online_status' ], 99 );
	}

	public static function update_user_online_status() {
		$seconds = (int)apply_filters( 'rtcl_user_online_status_seconds', 300 );
		if ( $seconds && is_user_logged_in() ) {
			update_user_meta( get_current_user_id(), 'online_status', current_time( 'timestamp' ) + $seconds );
		}
	}

	public static function add_chat_count( $listings ) {
		if ( $listings && Fns::is_enable_chat() ) {
			$listing_id = $listings->get_id();
			$chat_count = ChatController::get_chat_count( $listing_id );
			?>
			<li>
				<svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M8.00009 7.90936C8.60258 7.90936 9.091 7.42094 9.091 6.81845C9.091 6.21596 8.60258 5.72754 8.00009 5.72754C7.3976 5.72754 6.90918 6.21596 6.90918 6.81845C6.90918 7.42094 7.3976 7.90936 8.00009 7.90936Z"
						fill="#797979"/>
					<path
						d="M4.36386 7.90936C4.96635 7.90936 5.45477 7.42094 5.45477 6.81845C5.45477 6.21596 4.96635 5.72754 4.36386 5.72754C3.76137 5.72754 3.27295 6.21596 3.27295 6.81845C3.27295 7.42094 3.76137 7.90936 4.36386 7.90936Z"
						fill="#797979"/>
					<path
						d="M8.00002 0.272949C3.58182 0.272949 0 3.20386 0 6.81843C0.0186718 7.69789 0.231432 8.56237 0.623068 9.35003C1.0147 10.1377 1.57554 10.8291 2.26547 11.3748L1.45453 14.8184L5.75636 13.0984C6.49131 13.2742 7.24431 13.3633 7.99998 13.3639C12.4182 13.3639 16 10.433 16 6.8184C16 3.20383 12.4182 0.272949 8.00002 0.272949ZM8.00002 12.6366C7.3006 12.6357 6.60371 12.5527 5.92364 12.3893C5.86877 12.3768 5.81263 12.3707 5.75636 12.3711C5.66424 12.3709 5.57293 12.3882 5.48726 12.422L2.48363 13.622L2.97091 11.5457C3.00251 11.4141 2.9968 11.2764 2.9544 11.1479C2.912 11.0194 2.8346 10.9053 2.73091 10.8184C2.1226 10.3407 1.6275 9.73436 1.2811 9.04281C0.934701 8.35126 0.745576 7.59164 0.727283 6.8184C0.727283 3.61114 4.00001 1.0002 8.00002 1.0002C12 1.0002 15.2727 3.61111 15.2727 6.8184C15.2727 10.0257 12 12.6366 8.00002 12.6366Z"
						fill="#797979"/>
					<path
						d="M11.6363 7.90936C12.2388 7.90936 12.7272 7.42094 12.7272 6.81845C12.7272 6.21596 12.2388 5.72754 11.6363 5.72754C11.0338 5.72754 10.5454 6.21596 10.5454 6.81845C10.5454 7.42094 11.0338 7.90936 11.6363 7.90936Z"
						fill="#797979"/>
				</svg>
				<?php
				/* translators:  click count */
				echo sprintf( _n( '%1$s Person', '%1$s Persons', $chat_count, 'classified-listing-pro' ),
					esc_html( number_format_i18n( $chat_count ) ) ); ?>
			</li>
			<?php
		}
	}

	public static function restrict_manage_posts() {
		global $typenow;
		if ( rtcl()->post_type == $typenow && Fns::is_enable_mark_as_sold() ) {
			$mark = isset( $_GET['rtcl_mark_as_sold'] ) ? esc_html( $_GET['rtcl_mark_as_sold'] ) : 'no';
			?>
			<label class="mark-as-sold-filter-field">
				<input type="checkbox" value="yes" name="rtcl_mark_as_sold" <?php checked( $mark, 'yes' ); ?>/>
				<?php echo apply_filters( 'rtcl_mark_as_sold_filter_text', __( 'Sold Out Listings', 'classified-listing-pro' ) ); ?>
			</label>
			<?php
		}
	}

	public static function parse_query( $query ) {

		global $pagenow, $post_type;

		if ( !( is_admin() and $query->is_main_query() ) ) {
			return $query;
		}

		if ( 'edit.php' == $pagenow && rtcl()->post_type == $post_type ) {
			// Set featured meta in query
			if ( isset( $_GET['rtcl_mark_as_sold'] ) ) {
				$query->query_vars['meta_key'] = '_rtcl_mark_as_sold';
				$query->query_vars['meta_value'] = 1;
			}
		}
	}

	public static function add_map_data_support( $attributes ) {
		if ( !empty( $attributes['map'] ) ) {
			global $rtcl_has_map_data;
			$rtcl_has_map_data = 1;
		}
	}

	public static function remove_map_data_support( $attributes ) {
		if ( isset( $attributes['map'] ) ) {
			global $rtcl_has_map_data;
			$rtcl_has_map_data = null;
		}
	}
	
	public static function ajax_filter_add_map_data_support(  ) {
		if ( !empty( $_GET['hasMap'] ) || !empty( $_POST['hasMap'] ) ) {
			global $rtcl_has_map_data;
			$rtcl_has_map_data = 1;
		}
	}
	
	public static function ajax_filter_remove_map_data_support(  ) {
		if ( !empty( $_GET['hasMap'] ) || !empty( $_POST['hasMap'] ) ) {
			global $rtcl_has_map_data;
			$rtcl_has_map_data = null;
		}
	}
	
	public static function set_user_status_offline() {
		update_user_meta( get_current_user_id(), 'online_status', 0 );
		delete_user_meta( get_current_user_id(), '_rtcl_conversation_status' );
	}


	/**
	 * @param Payment $payment
	 */
	static function rest_checkout_process_mail( $payment ) {
		if ( $payment && $payment->exists() ) {
			if ( Functions::get_option_item( 'rtcl_email_notifications_settings', 'notify_admin', 'order_created', 'multi_checkbox' ) ) {
				rtcl()->mailer()->emails['Order_Created_Email_To_Admin']->trigger( $payment->get_id(), $payment );
			}

			if ( Functions::get_option_item( 'rtcl_email_notifications_settings', 'notify_users', 'order_created', 'multi_checkbox' ) ) {
				rtcl()->mailer()->emails['Order_Created_Email_To_Customer']->trigger( $payment->get_id(), $payment );
			}
		}
	}

	/**
	 * @param integer $post_id
	 * @param array $request
	 */
	public static function save_pricing_meta_data( $post_id, $request ) {
		$syncData = [];
		if ( isset( $request['_top'] ) ) {
			update_post_meta( $post_id, '_top', 1 );
			$syncData['update']['_top'] = 1;
		} else {
			delete_post_meta( $post_id, '_top' );
			delete_post_meta( $post_id, '_top_expiry_date' );
			$syncData['delete'] = [ '_top', '_top_expiry_date' ];
		}
		if ( isset( $request['_bump_up'] ) ) {
			update_post_meta( $post_id, '_bump_up', 1 );
			$syncData['update']['_bump_up'] = 1;
		} else {
			delete_post_meta( $post_id, '_bump_up' );
			delete_post_meta( $post_id, '_bump_up_expiry_date' );
			$syncData['delete'][] = '_bump_up';
			$syncData['delete'][] = '_bump_up_expiry_date';
		}
		Functions::syncMLListingMeta( $post_id, $syncData );
	}

	public static function cron_hourly_scheduled_actions() {
		self::remove_expired_bump_up();
		self::remove_expired_top_listing();
		self::do_hourly_bump_up();
	}

	public static function remove_data_move_listing_publish_to_expired( $post_id ) {
		delete_post_meta( $post_id, '_top' );
		delete_post_meta( $post_id, '_top_expiry_date' );
		delete_post_meta( $post_id, '_bump_up' );
		delete_post_meta( $post_id, '_bump_up_expiry_date' );
		$syncData = [ 'delete' => [ '_top', '_top_expiry_date', '_bump_up', '_bump_up_expiry_date' ] ];
		Functions::syncMLListingMeta( $post_id, $syncData );
	}

	private static function do_hourly_bump_up() {
		// Define the query
		$args = apply_filters( 'rtcl_cron_do_hourly_bump_up_query_args', [
			'post_type'      => rtcl()->post_type,
			'posts_per_page' => 10,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'date_query'     => [
				'before' => current_time( 'Y-m-d' )
			],
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_bump_up_expiry_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '>',
					'type'    => 'DATETIME'
				],
				[
					'key'     => '_bump_up',
					'compare' => '=',
					'value'   => 1,
				]
			]
		] );

		$rtcl_query = new WP_Query( $args );
		if ( !empty( $rtcl_query->posts ) ) {
			foreach ( $rtcl_query->posts as $post_id ) {
				$post_date = current_time( 'mysql' );
				$post_date_gmt = get_gmt_from_date( current_time( 'mysql' ) );
				wp_update_post(
					[
						'ID'            => $post_id,
						'post_date'     => $post_date,
						'post_date_gmt' => $post_date_gmt
					]
				);
				$syncData = [ 'post_date_update' => [ 'post_date' => $post_date, 'post_date_gmt' => $post_date_gmt ] ];
				Functions::syncMLListingMeta( $post_id, $syncData );
				do_action( "rtcl_cron_do_hourly_bump_up_listing", $post_id );
			}
		}
	}

	private static function remove_expired_bump_up() {

		// Define the query
		$args = [
			'post_type'      => rtcl()->post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_bump_up_expiry_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<',
					'type'    => 'DATETIME'
				],
				[
					'key'     => '_bump_up',
					'compare' => '=',
					'value'   => 1,
				]
			]
		];


		$rtcl_query = new WP_Query( apply_filters( 'rtcl_cron_remove_expired_bump_up_query_args', $args ) );

		if ( !empty( $rtcl_query->posts ) ) {

			foreach ( $rtcl_query->posts as $post_id ) {
				delete_post_meta( $post_id, '_bump_up' );
				delete_post_meta( $post_id, '_bump_up_expiry_date' );
				$syncData = [ 'delete' => [ '_bump_up', '_bump_up_expiry_date' ] ];
				Functions::syncMLListingMeta( $post_id, $syncData );
				do_action( "rtcl_cron_remove_expired_bump_up_listing", $post_id ); // TODO : make task
			}
		}


	}

	private static function remove_expired_top_listing() {
		// Define the query
		$args = [
			'post_type'      => rtcl()->post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_top_expiry_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '<',
					'type'    => 'DATETIME'
				],
				[
					'key'     => '_top',
					'compare' => '=',
					'value'   => 1,
				]
			]
		];


		$rtcl_query = new WP_Query( apply_filters( 'rtcl_cron_remove_expired_top_listing_query_args', $args ) );

		if ( !empty( $rtcl_query->posts ) ) {

			foreach ( $rtcl_query->posts as $post_id ) {
				delete_post_meta( $post_id, '_top' );
				delete_post_meta( $post_id, '_top_expiry_date' );
				$syncData = [ 'delete' => [ '_top', '_top_expiry_date' ] ];
				Functions::syncMLListingMeta( $post_id, $syncData );
				do_action( "rtcl_cron_remove_expired_top_listing", $post_id );
			}
		}
	}

	/**
	 * @param integer $post_id
	 * @param array $request
	 *
	 * @throws \Exception
	 */
	public static function update_promotions_at_save_post( $post_id, $request ) {
		$syncData = [];
		// Top
		if ( isset( $request['_top'] ) ) {
			update_post_meta( $post_id, '_top', 1 );
			$syncData['update']['_top'] = 1;
		} else {
			delete_post_meta( $post_id, '_top' );
			delete_post_meta( $post_id, '_top_expiry_date' );
			$syncData['delete'][] = '_top';
			$syncData['delete'][] = '_top_expiry_date';
		}

		// Bump up
		if ( isset( $request['_bump_up'] ) ) {
			update_post_meta( $post_id, '_bump_up', 1 );
			$syncData['update']['_bump_up'] = 1;
			if ( isset( $request['_bump_up_expiry_date'] ) ) {
				$current_date = new \DateTime( current_time( 'mysql' ) );
				$bumpUp_expiry_date = new \DateTime( Functions::datetime( 'mysql', trim( $request['_bump_up_expiry_date'] ) ) );

				$oldBumpUpExpireDate = get_post_meta( $post_id, '_bump_up_expiry_date', true );
				$oldBumpUpExpireDate = $oldBumpUpExpireDate ? new \DateTime( Functions::datetime( 'mysql', trim( $oldBumpUpExpireDate ) ) ) : null;
				if ( $bumpUp_expiry_date && $bumpUp_expiry_date > $current_date ) {

					if ( $oldBumpUpExpireDate ) {
						if ( $bumpUp_expiry_date > $oldBumpUpExpireDate ) {
							$__bumpUp_expiry_date = $bumpUp_expiry_date->format( 'Y-m-d H:i:s' );
							update_post_meta( $post_id, '_bump_up_expiry_date', $__bumpUp_expiry_date );
							$syncData['update']['_bump_up_expiry_date'] = $__bumpUp_expiry_date;
						}
					} else {
						$__bumpUp_expiry_date = $bumpUp_expiry_date->format( 'Y-m-d H:i:s' );
						update_post_meta( $post_id, '_bump_up_expiry_date', $__bumpUp_expiry_date );
						$syncData['update']['_bump_up_expiry_date'] = $__bumpUp_expiry_date;
					}
				}
			} else {
				delete_post_meta( $post_id, '_bump_up_expiry_date' );
				$syncData['delete'][] = '_bump_up_expiry_date';
			}
		} else {
			delete_post_meta( $post_id, '_bump_up' );
			delete_post_meta( $post_id, '_bump_up_expiry_date' );
			$syncData['delete'][] = '_bump_up';
			$syncData['delete'][] = '_bump_up_expiry_date';
		}

		Functions::syncMLListingMeta( $post_id, $syncData );
	}

	/**
	 * @param int $isBumpUp
	 * @param WP_Post $post
	 */
	public static function add_bump_up_expired_date( $isBumpUp, $post ) {
		$expiredDate = get_post_meta( $post->ID, '_bump_up_expiry_date', true );
		?>
		<div class="misc-pub-section rtcl-overwrite-sub-item rtcl-overwrite-sub-item__bump_up">
			<label for="_bump_up_expiry_date"><?php _e( "Bump Up Expired at:", 'classified-listing-pro' ) ?></label>
			<input disabled type="text" class="rtcl-date" name="_bump_up_expiry_date"
				   value="<?php echo $expiredDate; ?>" data-options="<?php echo htmlspecialchars( wp_json_encode( [
				'singleDatePicker' => true,
				'minDate'          => date( 'Y-m-d h:i:s' ),
				'timePicker'       => true,
				"timePicker24Hour" => true,
				'showDropdowns'    => true,
				'autoUpdateInput'  => false,
				'locale'           => [
					'format' => 'YYYY-MM-DD hh:mm:ss'
				]
			] ) ) ?>"/>
		</div>
		<?php
	}


	public static function wc_payment_support() {
		if ( Fns::is_wc_payment_enabled() ) {
			new WooPayment();
		}
	}


	public static function apply_user_role_at_account_settings( $new_options, $old_options ) {

		if ( !empty( $new_options['allowed_core_permission_roles'] ) || !empty( $old_options['allowed_core_permission_roles'] ) ) {

			$new_roles = isset( $new_options['allowed_core_permission_roles'] ) && is_array( $new_options['allowed_core_permission_roles'] )
				? $new_options['allowed_core_permission_roles'] : [];
			$old_roles = isset( $old_options['allowed_core_permission_roles'] ) && is_array( $old_options['allowed_core_permission_roles'] )
				? $old_options['allowed_core_permission_roles'] : [];
			$add_roles = array_diff( $new_roles, $old_roles );
			$remove_roles = array_diff( $old_roles, $new_roles );
			if ( !empty( $add_roles ) ) {
				Roles::add_core_caps( $add_roles );
			}
			if ( !empty( $remove_roles ) ) {
				Roles::remove_code_caps( $remove_roles );
			}
		}

	}
}

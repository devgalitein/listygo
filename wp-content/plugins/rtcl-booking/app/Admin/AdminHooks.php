<?php

namespace RtclBooking\Admin;

use Rtcl\Helpers\Functions;
use Rtcl\Services\FormBuilder\FBHelper;
use RtclBooking\Helpers\Functions as BookingFunctions;

class AdminHooks {

	public static function init() {
		if ( ! FBHelper::isEnabled() ) {
			add_action( 'save_post', [ __CLASS__, 'save_booking_data' ], 10, 2 );
		}
		add_filter( 'rtcl_register_settings_group', [ __CLASS__, 'add_booking_tab_item_at_settings_tabs_list' ] );
		add_filter( 'rtcl_settings_option_fields', [ __CLASS__, 'add_booking_tab_options' ], 10, 2 );
		add_action( rtcl()->category . '_add_form_fields', array( __CLASS__, 'taxonomy_add_new_meta_field' ), 99 );
		add_action( rtcl()->category . '_edit_form_fields', array( __CLASS__, 'taxonomy_edit_meta_field' ), 99 );
		add_action( 'edited_' . rtcl()->category, [ __CLASS__, 'save_taxonomy_custom_meta' ], 10 );
		add_action( 'create_' . rtcl()->category, [ __CLASS__, 'save_taxonomy_custom_meta' ], 10 );
		add_action( 'wp_ajax_rtcl_booking_fields_listings', array( __CLASS__, 'booking_section_show_hide' ) );
	}

	public static function booking_section_show_hide() {
		$term_id   = isset( $_POST['term_id'] ) ? absint( $_POST['term_id'] ) : 0;
		$term_meta = esc_attr( get_term_meta( $term_id, "_rtcl_booking_disable", true ) );
		$response  = array(
			'hide' => false
		);
		if ( 'yes' === $term_meta ) {
			$response['hide'] = true;
		}
		wp_send_json( $response );
	}

	// add membership tab item
	public static function add_booking_tab_item_at_settings_tabs_list( $tabs ) {
		$tabs['booking'] = [
			'title'  => esc_html__( "Booking", "rtcl-booking" ),
			'subtab' => []
		];

		return $tabs;
	}

	public static function save_booking_data( $post_id, $post ) {
		if ( ! isset( $_POST['post_type'] ) ) {
			return $post_id;
		}

		if ( rtcl()->post_type != $post->post_type ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the logged in user has permission to edit this post
		if ( ! current_user_can( 'edit_' . rtcl()->post_type, $post_id ) ) {
			return $post_id;
		}

		if ( ! Functions::verify_nonce() ) {
			return $post_id;
		}

		if ( isset( $_POST['_rtcl_booking_active'] ) ) {
			$booking_type = sanitize_text_field( $_POST['_rtcl_listing_booking'] );

			BookingFunctions::delete_booking_meta( $post_id, '_rtcl_instant_booking' );
			BookingFunctions::delete_booking_meta( $post_id, '_rtcl_shs' );
			BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_active', '1' );
			BookingFunctions::update_booking_meta( $post_id, 'rtcl_listing_booking_type', $booking_type );

			if ( isset( $_POST['_rtcl_show_available_tickets'] ) ) {
				BookingFunctions::update_booking_meta( $post_id, '_rtcl_show_available_tickets', '1' );
			} else {
				BookingFunctions::delete_booking_meta( $post_id, '_rtcl_show_available_tickets' );
			}

			if ( isset( $_POST['_rtcl_instant_booking'] ) ) {
				BookingFunctions::update_booking_meta( $post_id, '_rtcl_instant_booking', '1' );
			}

			if ( isset( $_POST['_rtcl_booking_fee'] ) ) {
				BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_fee', floatval( $_POST['_rtcl_booking_fee'] ) );
			}

			if ( 'event' == $booking_type ) {
				if ( isset( $_POST['_rtcl_available_tickets'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_available_tickets', absint( $_POST['_rtcl_available_tickets'] ) );
				}
				if ( isset( $_POST['_rtcl_booking_allowed_ticket'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_allowed_ticket', absint( $_POST['_rtcl_booking_allowed_ticket'] ) );
				}
			} else if ( 'services' == $booking_type ) {
				if ( isset( $_POST['_rtcl_booking_max_guest'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_max_guest', absint( $_POST['_rtcl_booking_max_guest'] ) );
				}
				if ( ! empty( $_POST['_rtcl_booking_active'] ) && ! empty( $_POST['_rtcl_shs'] ) && is_array( $_POST['_rtcl_shs'] ) ) {
					$new_bhs = Functions::sanitize( $_POST['_rtcl_shs'], 'business_hours' );
					if ( ! empty( $new_bhs ) ) {
						BookingFunctions::update_booking_meta( $post_id, '_rtcl_shs', $new_bhs );
					}
				}
			} else if ( 'pre_order' == $booking_type ) {
				if ( isset( $_POST['_rtcl_booking_pre_order_available_volumn'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_pre_order_available_volumn',
						absint( $_POST['_rtcl_booking_pre_order_available_volumn'] ) );
				}
				if ( isset( $_POST['_rtcl_booking_pre_order_maximum'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_pre_order_maximum', absint( $_POST['_rtcl_booking_pre_order_maximum'] ) );
				}
				if ( isset( $_POST['_rtcl_booking_pre_order_available_date'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_pre_order_available_date',
						sanitize_text_field( $_POST['_rtcl_booking_pre_order_available_date'] ) );
				}
				if ( isset( $_POST['_rtcl_booking_pre_order_date'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_pre_order_date',
						sanitize_text_field( $_POST['_rtcl_booking_pre_order_date'] ) );
				}
			} else if ( 'rent' == $booking_type ) {
				if ( isset( $_POST['_rtcl_booking_disable_date'] ) ) {
					$unavailable_date = sanitize_text_field( $_POST['_rtcl_booking_disable_date'] );
					$unavailable_date = empty( $unavailable_date ) ? [] : explode( ',', $unavailable_date );
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_rent_unavailable_date', $unavailable_date );
				}
				if ( isset( $_POST['_rtcl_booking_rent_max_guest'] ) ) {
					BookingFunctions::update_booking_meta( $post_id, '_rtcl_booking_max_guest', absint( $_POST['_rtcl_booking_rent_max_guest'] ) );
				}
			}

		} else {
			BookingFunctions::delete_booking_meta( $post_id, '_rtcl_booking_active' );
		}
	}

	// Add booking tab options
	public static function add_booking_tab_options( $fields, $active_tab ) {
		if ( 'booking' == $active_tab ) {

			$available_payment_html = '';

			if ( class_exists( 'WooCommerce' ) ) {
				$payment_gateways = WC()->payment_gateways()->payment_gateways();
				ob_start();
				if ( $payment_gateways ) {
					foreach ( $payment_gateways as $payment_gateway ) {
						$title = sprintf(
							esc_html__( 'This payment is %s, please click the link beside to enable/disable.', 'rtcl-booking' ),
							$payment_gateway->enabled == 'yes' ? 'enabled' : 'disabled'
						);
						?>
                        <li>
                            <label>
                        <span title="<?php echo $title; ?>"
                              class="dashicons <?php echo $payment_gateway->enabled == 'yes' ? 'dashicons-yes' : 'dashicons-dismiss'; ?>"></span>
                                <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_' . $payment_gateway->id ); ?>"
                                   target="_blank"> <?php echo( $payment_gateway->method_title ); ?> </a>
                            </label>
                        </li>
						<?php
					}
				}
				$available_payment_html .= ob_get_clean();
			}

			$fields = [
				'general_section'                => [
					'title'       => esc_html__( 'General Settings', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => '',
				],
				'booking_enable'                 => [
					'title'       => esc_html__( 'Booking', 'rtcl-booking' ),
					'label'       => esc_html__( 'Enable', 'rtcl-booking' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Enable Booking option', 'rtcl-booking' ),
				],
				'booking_fee_label'              => [
					'title'   => esc_html__( 'Label for Reservation Fee', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Booking Fee", 'rtcl-booking' )
				],
				'instant_booking_label'          => [
					'title'   => esc_html__( 'Label for Instant Booking', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Instant Booking", 'rtcl-booking' )
				],
				'booking_button_text'            => [
					'title'   => esc_html__( 'Booking Button Text', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Book Now", 'rtcl-booking' )
				],
				'event_section'                  => [
					'title'       => esc_html__( 'Event Fields', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => '',
				],
				'disable_event_type'             => [
					'title'       => esc_html__( 'Event Type', 'rtcl-booking' ),
					'label'       => esc_html__( 'Disable', 'rtcl-booking' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Disable booking event type.', 'rtcl-booking' ),
				],
				'avaiable_ticket_label'          => [
					'title'   => esc_html__( 'Label for Available Tickets', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Available Tickets", 'rtcl-booking' )
				],
				'allowed_per_booking_label'      => [
					'title'   => esc_html__( 'Label for Tickets allowed per booking', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Tickets allowed per booking", 'rtcl-booking' )
				],
				'service_section'                => [
					'title'       => esc_html__( 'Service Fields', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => '',
				],
				'disable_service_type'           => [
					'title'       => esc_html__( 'Service Type', 'rtcl-booking' ),
					'label'       => esc_html__( 'Disable', 'rtcl-booking' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Disable booking service type.', 'rtcl-booking' ),
				],
				'max_guest_label'                => [
					'title'   => esc_html__( 'Label for Maximum Number of Guests', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Maximum Number of Guests", 'rtcl-booking' )
				],
				'schedule_label'                 => [
					'title'   => esc_html__( 'Label for Schedule', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Schedule", 'rtcl-booking' )
				],
				'pre_order_section'              => [
					'title'       => esc_html__( 'Pre-Order Fields', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => '',
				],
				'disable_pre_oder_type'          => [
					'title'       => esc_html__( 'Pre-Order Type', 'rtcl-booking' ),
					'label'       => esc_html__( 'Disable', 'rtcl-booking' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Disable booking pre-order type.', 'rtcl-booking' ),
				],
				'pre_order_date_label'           => [
					'title'   => esc_html__( 'Label for Pre-Order Date', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Pre-Order Date", 'rtcl-booking' )
				],
				'pre_order_available_date'       => [
					'title'   => esc_html__( 'Label for Available From', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Available From", 'rtcl-booking' )
				],
				'pre_order_available_volumn'     => [
					'title'   => esc_html__( 'Label for Available Volumn', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Available Volumn", 'rtcl-booking' )
				],
				'allowed_per_order_label'        => [
					'title'   => esc_html__( 'Label for Maximum Order per booking', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => esc_html__( "Maximum Order per booking", 'rtcl-booking' )
				],
				'rent_section'                   => [
					'title'       => esc_html__( 'Rent Fields', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => '',
				],
				'disable_rent_type'              => [
					'title'       => esc_html__( 'Rent Type', 'rtcl-booking' ),
					'label'       => esc_html__( 'Disable', 'rtcl-booking' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Disable booking rent type.', 'rtcl-booking' ),
				],
				'payment_section'                => [
					'title'       => esc_html__( 'Payment Options', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => '',
				],
				'enable_booking_payment'         => [
					'title'       => esc_html__( 'Payment', 'rtcl-booking' ),
					'label'       => esc_html__( 'Enable booking payment using WooCommerce.', 'rtcl-booking' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Add booking payment using WooCommerce payment methods.', 'rtcl-booking' ),
				],
				'available_payments'             => [
					'title'       => esc_html__( 'WooCommerce Payments', 'rtcl-booking' ),
					'type'        => 'html',
					'html'        => $available_payment_html ? sprintf( '<ul class="rtcl-woo-payments">%s</ul>', $available_payment_html ) : '',
					'description' => $available_payment_html
						? __( 'List of all available payment gateways installed and activated for WooCommerce. Click on a payment method to go to <strong>WooCommerce Payment</strong> settings.',
							'rtcl-booking' ) : __( 'Please, install and activate WooCommerce to workable booking payment.', 'rtcl-booking' ),
				],
				'booking_account_endpoints'      => [
					'title'       => esc_html__( 'Account endpoints', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => esc_html__( 'Endpoints are appended to your page URLs to handle specific actions on the accounts pages. They should be unique and can be left blank to disable the endpoint.',
						'rtcl-booking' ),
				],
				'myaccount_booking_endpoint'     => [
					'title'   => esc_html__( 'My Bookings', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => 'my-bookings'
				],
				'myaccount_all_booking_endpoint' => [
					'title'   => esc_html__( 'All Bookings', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => 'all-bookings'
				],
				'booking_endpoints'              => [
					'title'       => esc_html__( 'Booking endpoints', 'rtcl-booking' ),
					'type'        => 'section',
					'description' => esc_html__( 'Endpoints are appended to your page URLs to handle specific actions during the booking process. They should be unique.',
						'rtcl-booking' ),
				],
				'booking_confirmation_endpoint'  => [
					'title'   => esc_html__( 'Booking Confirmation', 'rtcl-booking' ),
					'type'    => 'text',
					'default' => 'booking-confirmation'
				],
			];

			$fields = apply_filters( 'rtcl_booking_settings_options', $fields );
		}

		return $fields;
	}

	public static function taxonomy_add_new_meta_field() {
		?>
        <div class="form-field rtcl-term-group-wrap">
            <label for="tag-rtcl-order"><?php _e( 'Booking', 'rtcl-booking' ); ?></label>
            <fieldset class="rtcl-checkbox-wrap">
                <label>
                    <input type="checkbox" name="_rtcl_booking_disable" value="yes"><?php _e( 'Disable booking fields on listing form.',
						'rtcl-booking' ); ?>
                </label>
            </fieldset>
        </div>
		<?php
	}

	public static function taxonomy_edit_meta_field( $term ) {
		$t_id      = $term->term_id;
		$term_meta = esc_attr( get_term_meta( $t_id, "_rtcl_booking_disable", true ) );
		?>
        <tr class="form-field rtcl-term-group-wrap">
            <th scope="row" valign="top">
                <label for="tag-rtcl-order"><?php _e( 'Booking', 'rtcl-booking' ); ?></label>
            </th>
            <td>
                <fieldset class="rtcl-checkbox-wrap">
                    <label>
                        <input type="checkbox" name="_rtcl_booking_disable" value="yes" <?php checked( $term_meta, 'yes',
							true ) ?>><?php _e( 'Disable booking fields on listing form.',
							'rtcl-booking' ); ?>
                    </label>
                </fieldset>
            </td>
        </tr>
		<?php
	}

	/**
	 * @param $term_id
	 */
	public static function save_taxonomy_custom_meta( $term_id ) {
		$disabled = ! empty( $_POST['_rtcl_booking_disable'] ) ? sanitize_text_field( $_POST['_rtcl_booking_disable'] ) : '';
		update_term_meta( $term_id, '_rtcl_booking_disable', $disabled );
	}

}
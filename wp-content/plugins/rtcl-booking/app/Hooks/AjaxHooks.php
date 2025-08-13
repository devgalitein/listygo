<?php

namespace RtclBooking\Hooks;

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use Rtcl\Helpers\Utility;
use RtclBooking\Helpers\Functions as BookingFunctions;

class AjaxHooks {

	public static function init() {
		add_action( 'wp_ajax_rtcl_booking_verification', [ __CLASS__, 'rtcl_booking_verification' ] );
		add_action( 'wp_ajax_rtcl_booking_confirmation', [ __CLASS__, 'rtcl_booking_confirmation' ] );
		add_action( 'wp_ajax_rtcl_booking_request_approve', [ __CLASS__, 'rtcl_booking_request_approve' ] );
		add_action( 'wp_ajax_rtcl_booking_request_reject', [ __CLASS__, 'rtcl_booking_request_reject' ] );
		add_action( 'wp_ajax_rtcl_booking_delete_data', [ __CLASS__, 'rtcl_booking_delete_data' ] );
		add_action( 'wp_ajax_rtcl_booking_request_cancel', [ __CLASS__, 'rtcl_booking_request_cancel' ] );
		add_action( 'wp_ajax_rtcl_booking_service_day_slots', [ __CLASS__, 'rtcl_booking_service_day_slots' ] );
		add_action( 'wp_ajax_nopriv_rtcl_booking_service_day_slots', [ __CLASS__, 'rtcl_booking_service_day_slots' ] );
		add_action( 'wp_ajax_rtcl_booking_pagination', [ __CLASS__, 'rtcl_booking_pagination' ] );
		add_action( 'wp_ajax_rtcl_booking_rent_fee_calculation', [ __CLASS__, 'booking_rent_fee_calculation' ] );
	}

	public static function booking_rent_fee_calculation() {
		$response = [
			'status' => true,
			'fee'    => 0
		];

		if ( isset( $_POST['post_id'] ) && isset( $_POST['booking_date'] ) ) {
			$post_id         = absint( $_POST['post_id'] );
			$booking_date    = $_POST['booking_date'];
			$per_day_fee     = BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_fee' );
			$days            = BookingFunctions::get_days_from_date_range( $booking_date );
			$response['fee'] = $per_day_fee * $days;
		}

		wp_send_json( $response );
	}

	public static function rtcl_booking_verification() {
		$msg              = '';
		$error            = false;
		$post_id          = absint( $_POST['post_id'] );
		$no_of_ticket     = isset( $_POST['no_of_ticket'] ) ? absint( $_POST['no_of_ticket'] ) : 0;
		$time_slot        = isset( $_POST['time_slot'] ) ? sanitize_text_field( $_POST['time_slot'] ) : '';
		$booking_date     = isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '';
		$available_ticket = (int) BookingFunctions::get_booking_meta( $post_id, '_rtcl_available_tickets' );
		$booking_type     = BookingFunctions::get_booking_meta( $post_id, 'rtcl_listing_booking_type' );

		$ticket_booked = BookingFunctions::get_booked_ticket( $post_id );

		// pre-order meta
		$available_volumn = BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_pre_order_available_volumn' );

		if ( 'services' === $booking_type ) {
			$max_guest = (int) BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_max_guest' );
			if ( $no_of_ticket > $max_guest ) {
				$msg   = apply_filters( 'rtcl_booking_max_allowed_message', sprintf( esc_html__( 'Maximum allowed guest %s', 'rtcl-booking' ), $max_guest ) );
				$error = true;
			}
			if ( empty( $time_slot ) ) {
				$msg   = apply_filters( 'rtcl_booking_time_slot_empty_message', esc_html__( 'Please, select a time slot.', 'rtcl-booking' ) );
				$error = true;
			}
		} else if ( 'event' === $booking_type ) {
			if ( $no_of_ticket ) {
				if ( $available_ticket <= $ticket_booked || ( $ticket_booked + $no_of_ticket ) > $available_ticket ) {
					$msg   = apply_filters( 'rtcl_booking_not_available_ticket_message', esc_html__( 'Ticket not available', 'rtcl-booking' ) );
					$error = true;
				}
			}
		} else if ( 'pre_order' === $booking_type ) {
			if ( $no_of_ticket ) {
				if ( $available_volumn <= $ticket_booked || ( $ticket_booked + $no_of_ticket ) > $available_volumn ) {
					$msg   = apply_filters( 'rtcl_booking_not_available_ticket_message', esc_html__( 'Item not available', 'rtcl-booking' ) );
					$error = true;
				}
			}
		}

		if ( 'rent' == $booking_type ) {
			$booking_date = isset( $_POST['booking_rent_date'] ) ? sanitize_text_field( $_POST['booking_rent_date'] ) : '';
			$no_of_ticket = isset( $_POST['no_of_guest'] ) ? absint( $_POST['no_of_guest'] ) : 0;
		}

		$response = [
			'error'   => $error,
			'message' => $msg
		];

		if ( ! $error && BookingFunctions::get_booking_confirmation_endpoint() ) {
			$confirmation_link = get_the_permalink( $post_id ) . BookingFunctions::get_booking_confirmation_endpoint();

			if ( BookingFunctions::is_enable_booking_payment() ) {
				$confirmation_link = wc_get_checkout_url();

				$item_data = [
					'_rtcl_booking_listing_id' => $post_id,
					'_rtcl_booking_date'       => $booking_date,
					'_rtcl_booking_time_slot'  => $time_slot
				];

				BookingFunctions::booking_add_to_woo( $post_id, $no_of_ticket, $item_data );
			}

			$confirmation_link = add_query_arg( [
				'guest'        => $no_of_ticket,
				'booking_date' => $booking_date,
				'time_slot'    => $time_slot
			], $confirmation_link );

			$response['redirect_url'] = $confirmation_link;

		}

		wp_send_json( $response );

	}

	public static function rtcl_booking_confirmation() {
		global $wpdb;
		$msg     = '';
		$success = false;

		$endpoint = Functions::get_option_item( 'rtcl_booking_settings', 'myaccount_booking_endpoint', 'my-bookings' );
		$redirect = Link::get_account_endpoint_url( $endpoint );

		if ( apply_filters( 'rtcl_booking_form_remove_nonce', false ) || Functions::verify_nonce() ) {
			$listing_id       = isset( $_POST['listing_id'] ) ? absint( $_POST['listing_id'] ) : '';
			$listing_owner_id = rtcl()->factory->get_listing( $listing_id )->get_owner_id();
			$user_id          = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : '';
			$name             = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
			$email            = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			$phone            = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
			$persons          = isset( $_POST['ticket_no'] ) ? sanitize_text_field( $_POST['ticket_no'] ) : '';
			$booking_date     = isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '';
			$time_slot        = isset( $_POST['time_slot'] ) ? sanitize_text_field( $_POST['time_slot'] ) : '';
			$fee              = isset( $_POST['ticket_fee'] ) ? sanitize_text_field( $_POST['ticket_fee'] ) : '';
			$message          = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
			$instant_booking  = (bool) BookingFunctions::get_booking_meta( $listing_id, '_rtcl_instant_booking' );
			$booking_status   = $instant_booking ? 'approved' : 'pending';

			$details = [
				'name'    => $name,
				'email'   => $email,
				'phone'   => $phone,
				'message' => $message
			];

			$booking_info_table = $wpdb->prefix . "rtcl_booking_info";

			$data = [
				'listing_id'       => $listing_id,
				'user_id'          => $user_id,
				'listing_owner_id' => $listing_owner_id,
				'quantity'         => $persons,
				'price'            => $fee,
				'details'          => serialize( $details ),
				'booking_date'     => $booking_date,
				'time_slot'        => $time_slot,
				'requested_at'     => current_time( 'mysql' ),
				'status'           => $booking_status,
			];

			$wpdb->insert( $booking_info_table, $data );

			if ( $wpdb->insert_id ) {
				Functions::add_notice( apply_filters( 'rtcl_booking_success_message', esc_html__( "Thank you for submitting booking request!", "rtcl-booking" ),
					$_REQUEST ) );
				$success = true;

				$transient_key = 'rtcl_rent_booked_dates_' . $booking_status . '_' . $listing_id;
				delete_transient( $transient_key );
				// send email to listing owner
				rtcl()->mailer()->emails['Booking_Request_Email']->trigger( $listing_id, [ 'name' => $name ] );
				// send approve email to user when enable instant booking
				if ( $instant_booking ) {
					rtcl()->mailer()->emails['Booking_Approved_Email']->trigger( $wpdb->insert_id, [ 'listing_id' => $listing_id ] );
				}
			}
		} else {
			Functions::add_notice( apply_filters( 'rtcl_booking_session_error_message', esc_html__( "Session Error !!", "rtcl-booking" ), $_REQUEST ),
				'error' );
		}

		$msg = Functions::get_notices( 'error' );
		if ( $success ) {
			$msg = Functions::get_notices( 'success' );
		}
		Functions::clear_notices(); // Clear all notice created by checking

		$response = [
			'success'      => $success,
			'message'      => $msg,
			'redirect_url' => $success ? esc_url( $redirect ) : ''
		];

		wp_send_json( $response );

	}

	public static function rtcl_booking_request_approve() {
		global $wpdb;
		$msg     = '';
		$success = false;

		if ( apply_filters( 'rtcl_booking_all_remove_nonce', false ) || Functions::verify_nonce() ) {
			$listing_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';
			$booking_id = isset( $_POST['booking_id'] ) ? absint( $_POST['booking_id'] ) : '';

			if ( $booking_id ) {
				$booking_info_table = $wpdb->prefix . "rtcl_booking_info";

				$data  = [
					'status'     => 'approved',
					'updated_at' => current_time( 'mysql' )
				];
				$where = [
					'id' => $booking_id
				];

				$updated = $wpdb->update( $booking_info_table, $data, $where );

				if ( $updated ) {
					$success       = true;
					$transient_key = 'rtcl_rent_booked_dates_approved_' . $listing_id;
					delete_transient( $transient_key );
					$msg = esc_html__( 'Approved booking request', 'rtcl-booking' );
					// send mail to user
					rtcl()->mailer()->emails['Booking_Approved_Email']->trigger( $booking_id, [ 'listing_id' => $listing_id ] );
				}
			}
		}

		$response = [
			'success' => $success,
			'message' => $msg
		];

		wp_send_json( $response );
	}

	public static function rtcl_booking_request_reject() {
		global $wpdb;
		$msg     = '';
		$success = false;

		if ( apply_filters( 'rtcl_booking_all_remove_nonce', false ) || Functions::verify_nonce() ) {
			$listing_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';
			$booking_id = isset( $_POST['booking_id'] ) ? absint( $_POST['booking_id'] ) : '';

			if ( $booking_id ) {
				$booking_info_table = $wpdb->prefix . "rtcl_booking_info";

				$data  = [
					'status'     => 'rejected',
					'updated_at' => current_time( 'mysql' )
				];
				$where = [
					'id' => $booking_id
				];

				$updated = $wpdb->update( $booking_info_table, $data, $where );

				if ( $updated ) {
					$success = true;
					delete_transient( 'rtcl_rent_booked_dates_approved_' . $listing_id );
					delete_transient( 'rtcl_rent_booked_dates_pending_' . $listing_id );
					$msg = esc_html__( 'Rejected booking request', 'rtcl-booking' );
					// send mail to user
					rtcl()->mailer()->emails['Booking_Rejected_Email']->trigger( $booking_id, [ 'listing_id' => $listing_id ] );
				}
			}
		}

		$response = [
			'success' => $success,
			'message' => $msg
		];

		wp_send_json( $response );
	}

	public static function rtcl_booking_delete_data() {
		global $wpdb;
		$msg     = '';
		$success = false;

		if ( apply_filters( 'rtcl_booking_all_remove_nonce', false ) || Functions::verify_nonce() ) {
			$booking_id = isset( $_POST['booking_id'] ) ? absint( $_POST['booking_id'] ) : '';

			if ( $booking_id ) {
				$booking_info_table = $wpdb->prefix . "rtcl_booking_info";

				$deleted = $wpdb->delete( $booking_info_table, [ 'id' => $booking_id ] );

				if ( $deleted ) {
					$success = true;
					$msg     = esc_html__( 'Deleted booking successfully', 'rtcl-booking' );
				}
			}
		}

		$response = [
			'success' => $success,
			'message' => $msg
		];

		wp_send_json( $response );
	}

	public static function rtcl_booking_request_cancel() {
		global $wpdb;
		$msg     = '';
		$success = false;

		if ( apply_filters( 'rtcl_booking_all_remove_nonce', false ) || Functions::verify_nonce() ) {
			$booking_id = isset( $_POST['booking_id'] ) ? absint( $_POST['booking_id'] ) : '';

			if ( $booking_id ) {
				$booking_info_table = $wpdb->prefix . "rtcl_booking_info";

				$data  = [
					'status'     => 'canceled',
					'updated_at' => current_time( 'mysql' )
				];
				$where = [
					'id' => $booking_id
				];

				$updated = $wpdb->update( $booking_info_table, $data, $where );


				if ( $updated ) {
					$success = true;
					$msg     = esc_html__( 'Canceled booking successfully', 'rtcl-booking' );
				}
			}
		}

		$response = [
			'success' => $success,
			'message' => $msg
		];

		wp_send_json( $response );
	}

	public static function rtcl_booking_service_day_slots() {

		if ( ! Functions::verify_nonce() ) {
			return false;
		}

		$listing_id   = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';
		$booking_date = isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '';

		$serviceHours = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_shs' );
		$serviceHours = maybe_unserialize( $serviceHours );

		$day   = date( 'D', strtotime( $booking_date ) );
		$slots = [];

		if ( ! empty( $serviceHours ) ) {
			$day   = strtolower( $day );
			$slots = $serviceHours[ BookingFunctions::get_day_index( $day ) ];
		}

		$options = '<option value="">' . esc_html__( 'Select time', 'rtcl-booking' ) . '</option>';
		$lists   = '<li class="slot-not-available">' . esc_html__( 'Slot not available', 'rtcl-booking' ) . '</li>';

		if ( isset( $slots['times'] ) ) {
			$lists = '';
			foreach ( $slots['times'] as $slot ) {
				$time_slot = Utility::formatTime( $slot['start'], null, 'H:i' ) . ' - ' . Utility::formatTime( $slot['end'], null, 'H:i' );
				$options   .= '<option value="' . esc_attr( $time_slot ) . '">' . esc_html( $time_slot ) . '</option>';
				$lists     .= '<li>' . esc_html( $time_slot ) . '</li>';
			}
		}
		?>
        <div id="service_slots" class="rtcl-form-group">
			<?php if ( ! empty( $slots['open'] ) && ! isset( $slots['times'] ) ):
				$lists = '<li class="slot-not-available">' . esc_html__( 'please, select specific time.', 'rtcl-booking' ) . '</li>';
				?>
                <label for="service_slot"><?php esc_html_e( 'Time', 'rtcl-booking' ); ?></label>
                <input type="text" id="service_slot" class="bhs-timepicker rtcl-form-control" name="service_slots"
                       autocomplete="off" required/>
			<?php else: ?>
                <label for="service_slot"><?php esc_html_e( 'Time Slot', 'rtcl-booking' ); ?></label>
                <select id="service_slot" class="rtcl-form-control" name="service_slots" required>
					<?php echo $options; ?>
                </select>
			<?php endif; ?>
            <div class="available-slots">
                <span><?php esc_html_e( 'Availabe Slots', 'rtcl-booking' ); ?></span>
                <ul>
					<?php echo wp_kses_post( $lists ); ?>
                </ul>
            </div>
        </div>
		<?php
		die();
	}

	public static function rtcl_booking_pagination() {
		global $wpdb;

		$user_id            = get_current_user_id();
		$booking_info_table = $wpdb->prefix . "rtcl_booking_info";

		$posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : 5;
		$page           = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;
		$pagination_for = isset( $_POST['pagination_for'] ) ? sanitize_text_field( $_POST['pagination_for'] ) : 'my';

		if ( $page > 1 ) {
			$offset = $page * $posts_per_page - $posts_per_page;
		} else {
			$offset = 0;
		}

		if ( 'all' == $pagination_for ) {
			$where      = "listing_owner_id='" . absint( $user_id ) . "'";
			$where_text = " WHERE " . $where;
			if ( ! empty( $status ) ) {
				$where_text .= " AND status='" . $status . "'";
			}
			$query = $wpdb->prepare( "SELECT * FROM {$booking_info_table} {$where_text} ORDER BY requested_at DESC LIMIT %d offset %d",
				[ $posts_per_page, $offset ] );
		} else {
			$query = $wpdb->prepare( "SELECT * FROM {$booking_info_table} WHERE user_id = %d ORDER BY requested_at DESC LIMIT %d offset %d",
				[ $user_id, $posts_per_page, $offset ] );
		}

		$results = $wpdb->get_results( $query );

		if ( ! empty( $results ) ) {
			foreach ( $results as $booking ) {
				$listing = rtcl()->factory->get_listing( $booking->listing_id );
				Functions::get_template( 'myaccount/booking-info',
					[
						'booking'     => $booking,
						'listing'     => $listing,
						'booking_for' => $pagination_for
					], '', rtclBooking()->get_plugin_template_path() );
			}
		}

		die();
	}

}
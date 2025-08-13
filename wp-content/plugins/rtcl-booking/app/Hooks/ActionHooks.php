<?php

namespace RtclBooking\Hooks;

use DateTime;
use Exception;
use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Models\Listing;
use Rtcl\Services\FormBuilder\FBHelper;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RtclBooking\Resources\ListingDetails;
use RtclBooking\Resources\Options;

class ActionHooks {

	public static function init() {
		if ( ! FBHelper::isEnabled() ) {
			add_action( 'rtcl_listing_details_meta_box', [ __CLASS__, 'booking_meta_boxes' ] );
		}
		add_action( 'rtcl_listing_form_after_save_or_update', [ __CLASS__, 'save_booking_meta' ], 10, 5 );
		if ( rtcl()->is_request( 'frontend' ) ) {
			add_action( "rtcl_listing_form", [ __CLASS__, 'booking_form' ], 18 );
		}
		add_action( 'rtcl_after_single_listing_sidebar', [ __CLASS__, 'listing_booking_submit_form' ] );
		add_action( 'init', [ __CLASS__, 'add_booking_endpoint' ] );
		add_action( 'template_redirect', [ __CLASS__, 'booking_confirmation_template' ], 99 );
		add_action( 'rtcl_account_my-bookings_endpoint', [ __CLASS__, 'account_my_bookings_endpoint' ] );
		add_action( 'rtcl_account_all-bookings_endpoint', [ __CLASS__, 'account_all_bookings_endpoint' ] );
		add_action( 'after_delete_post', [ __CLASS__, 'delete_booking_data' ], 10, 2 );
	}

	/**
	 * @return void
	 */
	public static function account_my_bookings_endpoint() {
		// Process output
		Functions::get_template( "myaccount/my-bookings", '', '', rtclBooking()->get_plugin_template_path() );
	}

	/**
	 * @return void
	 */
	public static function account_all_bookings_endpoint() {
		// Process output
		Functions::get_template( "myaccount/all-bookings", '', '', rtclBooking()->get_plugin_template_path() );
	}

	/**
	 * @return void
	 */
	public static function booking_meta_boxes() {
		add_meta_box(
			'rtcl_booking',
			__( 'Booking', 'rtcl-booking' ),
			[ ListingDetails::class, 'booking_fields' ],
			rtcl()->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * @param $listing
	 * @param $type
	 * @param $cat_id
	 * @param $new_listing_status
	 * @param $request_data
	 *
	 * @return void
	 */
	public static function save_booking_meta( $listing, $type, $cat_id, $new_listing_status, $request_data = [ 'data' => '' ] ) {
		/** @var array $data */
		$data = $request_data['data'];

		if ( is_a( $listing, Listing::class ) ) {
			if ( FBHelper::isEnabled() ) {
				$form = $listing->getForm();
				if ( empty( $form ) || empty( $fields = $form->fields ) ) {
					return;
				}
				$bookingField = null;
				foreach ( $fields as $field ) {
					if ( 'booking' === $field['element'] ) {
						$bookingField = $field;
						break;
					}
				}
				if ( empty( $bookingField ) ) {
					return;
				}

				$bookingData = [];
				if ( ! empty( $data['formData'] ) && is_string( $data['formData'] ) ) {
					parse_str( $_POST['formData'], $formData );
					$bookingData = ! empty( $formData['booking'] ) ? $formData['booking'] : [];
				}elseif ( ! empty( $data['form_data'] ) && is_string( $data['form_data'] ) ) {
                    parse_str( $_POST['form_data'], $formData );
                    $bookingData = ! empty( $formData['booking'] ) ? $formData['booking'] : [];
                }

				$isActive = ! empty( $bookingData['active'] ) && filter_var( $bookingData['active'], FILTER_VALIDATE_BOOLEAN );
				if ( $isActive ) {
					$types       = array_keys( Options::get_listing_booking_types() );
					$defaultType = ! empty( $bookingField['type'] ) ? $bookingField['type'] : ( ! empty( $types ) ? $types[0] : 'event' );
					$type        = ! empty( $bookingData['type'] ) && in_array( $bookingData['type'], $types ) ? $bookingData['type'] : $defaultType;

					$data       = [
						'_rtcl_booking_active'                     => 1,
						'_rtcl_listing_booking'                    => $type,
						'_rtcl_show_available_tickets'             => ! empty( $bookingData['display_tickets'] ) ? 1 : '',
						'_rtcl_instant_booking'                    => ! empty( $bookingData['instant'] ) ? 1 : '',
						'_rtcl_booking_fee'                        => ! empty( $bookingData['fee'] ) ? $bookingData['fee'] : '',
						'_rtcl_available_tickets'                  => ! empty( $bookingData['tickets'] ) ? $bookingData['tickets'] : '',
						'_rtcl_booking_allowed_ticket'             => ! empty( $bookingData['tickets_per_booking'] ) ? $bookingData['tickets_per_booking'] : '',
						'_rtcl_booking_max_guest'                  => ! empty( $bookingData['max_guest'] ) ? $bookingData['max_guest'] : '',
						//services
						'_rtcl_shs'                                => ! empty( $bookingData['schedule'] ) ? $bookingData['schedule'] : [],
						'schedule_time_format'                     => ! empty( $bookingField['time_format'] ) ? $bookingField['time_format'] : 'H:i',
						//pre_order
						'_rtcl_booking_pre_order_available_volumn' => ! empty( $bookingData['volume'] ) ? $bookingData['volume'] : '',
						'_rtcl_booking_pre_order_maximum'          => ! empty( $bookingData['max_order'] ) ? $bookingData['max_order'] : '',
					];
					$dateFormat = 'Y-m-d';
					if ( ! empty( $bookingData['pre_order_available_date'] ) ) {
						try {
							$date                                           = DateTime::createFromFormat( $dateFormat,
								$bookingData['pre_order_available_date'] );
							$data['_rtcl_booking_pre_order_available_date'] = $date ? $date->format( $dateFormat ) : '';
						} catch ( Exception $e ) {
							$data['_rtcl_booking_pre_order_available_date'] = '';
						}
					}
					if ( ! empty( $bookingData['pre_order_date_range'] ) && is_array( $bookingData['pre_order_date_range'] ) ) {
						$dateRange = $bookingData['pre_order_date_range'];
						try {
							$start = $end = '';
							if ( ! empty( $dateRange['start'] ) ) {
								$date  = DateTime::createFromFormat( $dateFormat, $dateRange['start'] );
								$start = $date ? $date->format( $dateFormat ) : '';
							}
							if ( ! empty( $dateRange['end'] ) ) {
								$date = DateTime::createFromFormat( $dateFormat, $dateRange['end'] );
								$end  = $date ? $date->format( $dateFormat ) : '';
							}
							if ( $start && $end ) {
								$data['_rtcl_booking_pre_order_date'] = $start . ' - ' . $end;
							} else {
								$data['_rtcl_booking_pre_order_date'] = '';
							}
						} catch ( Exception $e ) {
							$data['_rtcl_booking_pre_order_date'] = '';
						}
					}
					if ( ! empty( $bookingData['rent_unavailable_dates'] ) ) {
						$disabledDates = [];
						foreach ( $bookingData['rent_unavailable_dates'] as $date ) {
							try {
								$date = DateTime::createFromFormat( $dateFormat, $date );
								if ( $date ) {
									$disabledDates[] = $date->format( $dateFormat );
								}
							} catch ( Exception $e ) {

							}
						}
						if ( ! empty( $disabledDates ) ) {
							$data['_rtcl_booking_disable_date'] = $disabledDates;
						}
					}
				} else {
					$data = [];
				}
			}

			if ( ! empty( $data['_rtcl_booking_active'] ) ) {
				$booking_type = sanitize_text_field( $data['_rtcl_listing_booking'] );

				BookingFunctions::delete_booking_meta( $listing->get_id(), '_rtcl_instant_booking' );
				BookingFunctions::delete_booking_meta( $listing->get_id(), '_rtcl_shs' );
				BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_active', 1 );
				BookingFunctions::update_booking_meta( $listing->get_id(), 'rtcl_listing_booking_type', sanitize_text_field( $data['_rtcl_listing_booking'] ) );

				if ( ! empty( $data['_rtcl_show_available_tickets'] ) ) {
					BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_show_available_tickets', 1 );
				} else {
					BookingFunctions::delete_booking_meta( $listing->get_id(), '_rtcl_show_available_tickets' );
				}

				if ( ! empty( $data['_rtcl_instant_booking'] ) ) {
					BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_instant_booking', 1 );
				}

				if ( isset( $data['_rtcl_booking_fee'] ) ) {
					BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_fee', floatval( $data['_rtcl_booking_fee'] ) );
				}

				if ( 'event' == $booking_type ) {
					if ( isset( $data['_rtcl_available_tickets'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_available_tickets', absint( $data['_rtcl_available_tickets'] ) );
					}

					if ( isset( $data['_rtcl_booking_allowed_ticket'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_allowed_ticket',
							absint( $data['_rtcl_booking_allowed_ticket'] ) );
					}
				} else if ( 'services' == $booking_type ) {
					if ( isset( $data['_rtcl_booking_max_guest'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_max_guest', absint( $data['_rtcl_booking_max_guest'] ) );
					}

					if ( ! empty( $data['_rtcl_booking_active'] ) && ! empty( $data['_rtcl_shs'] ) && is_array( $data['_rtcl_shs'] ) ) {
						if ( Functions::isEnableFb() ) {
							$new_shs = BookingFunctions::sanitizeServicesScheduleHours( $data['_rtcl_shs'],
								! empty( $data['schedule_time_format'] ) ? $data['schedule_time_format'] : '' );
						} else {
							$new_shs = Functions::sanitize( $data['_rtcl_shs'], 'business_hours' );
						}
						if ( ! empty( $new_shs ) ) {
							BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_shs', $new_shs );
						}
					}
				} else if ( 'pre_order' == $booking_type ) {
					if ( isset( $data['_rtcl_booking_pre_order_available_volumn'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_pre_order_available_volumn',
							absint( $data['_rtcl_booking_pre_order_available_volumn'] ) );
					}
					if ( isset( $data['_rtcl_booking_pre_order_maximum'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_pre_order_maximum',
							absint( $data['_rtcl_booking_pre_order_maximum'] ) );
					}
					if ( isset( $data['_rtcl_booking_pre_order_available_date'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_pre_order_available_date',
							sanitize_text_field( $data['_rtcl_booking_pre_order_available_date'] ) );
					}
					if ( isset( $data['_rtcl_booking_pre_order_date'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_pre_order_date',
							sanitize_text_field( $data['_rtcl_booking_pre_order_date'] ) );
					}
				} else if ( 'rent' == $booking_type ) {
					if ( isset( $data['_rtcl_booking_disable_date'] ) ) {
						if ( is_array( $data['_rtcl_booking_disable_date'] ) ) {
							$unavailable_date = array_filter( array_map( 'sanitize_text_field', $data['_rtcl_booking_disable_date'] ) );
							if ( empty( $unavailable_date ) ) {
								delete_post_meta( $listing->get_id(), '_rtcl_booking_rent_unavailable_date' );
							} else {
								BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_rent_unavailable_date', $unavailable_date );
							}
						} else {
							$unavailable_date = sanitize_text_field( $data['_rtcl_booking_disable_date'] );
							$unavailable_date = empty( $unavailable_date ) ? [] : explode( ',', $unavailable_date );
							BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_rent_unavailable_date', $unavailable_date );
						}
					}
					if ( isset( $data['_rtcl_booking_rent_max_guest'] ) ) {
						BookingFunctions::update_booking_meta( $listing->get_id(), '_rtcl_booking_max_guest', absint( $data['_rtcl_booking_rent_max_guest'] ) );
					}
				}
			} else {
				BookingFunctions::delete_booking_meta( $listing->get_id(), '_rtcl_booking_active' );
			}
		}
	}

	/**
	 * @param $post_id
	 *
	 * @return void
	 */
	public static function booking_form( $post_id ) {
		if ( $post_id ) {
			$listing = rtcl()->factory->get_listing( $post_id );
			if ( is_a( $listing, Listing::class ) ) {
				$term = $listing->get_current_selected_category();
				if ( ! empty( $term ) ) {
					$term_meta = esc_attr( get_term_meta( $term->term_id, "_rtcl_booking_disable", true ) );
					if ( 'yes' === $term_meta ) {
						return;
					}
				}
			}
		} else {
			$cat_id    = isset( $_GET['category'] ) ? absint( $_GET['category'] ) : 0;
			$term_meta = esc_attr( get_term_meta( $cat_id, "_rtcl_booking_disable", true ) );
			if ( 'yes' === $term_meta ) {
				return;
			}
		}
		$data = [
			'post_id'      => $post_id,
			'booking_type' => BookingFunctions::get_booking_meta( $post_id, 'rtcl_listing_booking_type' ),
			'booking_fee'  => BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_fee' ),
			'max_guest'    => BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_max_guest' ),
		];

		Functions::get_template( 'listing-form/booking', $data, '', rtclBooking()->get_plugin_template_path() );
	}

	/**
	 * @param $listing_id
	 *
	 * @return void
	 */
	public static function listing_booking_submit_form( $listing_id ) {
		$post_status = get_post_status( $listing_id );

		if ( ( BookingFunctions::is_active_booking( $listing_id ) && BookingFunctions::is_enable_booking() ) && 'publish' === $post_status ) {
			$type = BookingFunctions::get_booking_type( $listing_id );
			if ( ! empty( $type ) ) {
				Functions::get_template( 'booking/listing-booking-form',
					[
						'type'       => $type,
						'listing_id' => $listing_id
					],
					'',
					rtclBooking()->get_plugin_template_path()
				);
			}
		}
	}

	// Confirmation form endpoint

	/**
	 * @return void
	 */
	public static function add_booking_endpoint() {
		if ( $endpoint = BookingFunctions::get_booking_confirmation_endpoint() ) {
			add_rewrite_endpoint( $endpoint, EP_PERMALINK );
		}
		flush_rewrite_rules();
	}

	/**
	 * @return void
	 */
	public static function booking_confirmation_template() {
		global $wp_query;

		if ( $endpoint = BookingFunctions::get_booking_confirmation_endpoint() ) {
			if ( ! isset( $wp_query->query_vars[ $endpoint ] ) || ! Functions::is_listing() ) {
				return;
			}

			$data = [
				'listing_id' => get_the_ID(),
				'user_id'    => get_current_user_id()
			];

			Functions::get_template( 'booking/confirmation-form', $data, '', rtclBooking()->get_plugin_template_path() );
		}

		exit;
	}

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * @return void
	 */
	public static function delete_booking_data( $post_id, $post ) {

		if ( ! $post_id ) {
			return;
		}

		if ( rtcl()->post_type !== $post->post_type ) {
			return;
		}

		global $wpdb;
		$booking_info_table = $wpdb->prefix . "rtcl_booking_info";
		$booking_meta_table = $wpdb->prefix . "rtcl_booking_meta";

		$wpdb->delete( $booking_info_table, [ 'listing_id' => $post_id ] );
		$wpdb->delete( $booking_meta_table, [ 'listing_id' => $post_id ] );

	}

}
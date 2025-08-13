<?php

namespace RtclBooking\Hooks;

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Utility;
use Rtcl\Models\Listing;
use Rtcl\Resources\Options;
use RtclBooking\Emails\BookingApprovedEmailToUser;
use RtclBooking\Emails\BookingRejectedEmailToUser;
use RtclBooking\Emails\BookingRequestEmailToOwner;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RtclBooking\Resources\Options as ResourcesOptions;

class FilterHooks {

	public static function init() {
		add_filter( 'postbox_classes_' . rtcl()->post_type . '_rtcl_booking', [
			__CLASS__,
			'add_meta_box_classes'
		] );
		add_filter( 'rtcl_account_menu_items', [ __CLASS__, 'add_bookings_menu_item_at_account_menu' ] );
		add_filter( 'rtcl_my_account_endpoint', [ __CLASS__, 'add_my_account_bookings_end_points' ] );
		if ( ! Functions::is_enable_business_hours() ) {
			add_filter( 'rtcl_sanitize', [ __CLASS__, 'sanitize_business_hours' ], 10, 3 );
		}
		add_filter( 'rtcl_email_services', [ __CLASS__, 'add_booking_email_services' ] );
		add_filter( 'body_class', [ __CLASS__, 'add_booking_class' ] );
		add_filter( 'rtcl_licenses', [ __CLASS__, 'license' ], 20 );
		add_filter( 'rtcl_listing_booking_types', [ __CLASS__, 'disable_types' ] );
		add_filter( 'rtcl_fb_fields', [ __CLASS__, 'booking_fields' ] );
		add_filter( 'rtcl_fb_editor_settings_placement', [ __CLASS__, 'booking_editor_settings_placement' ] );
		add_filter( 'rtcl_fb_editor_settings_fields', [ __CLASS__, 'booking_editor_settings_fields' ] );
		add_filter( 'rtcl_fb_localized_options', [ __CLASS__, 'booking_localize_fb_options' ] );
		add_filter( 'rtcl_rest_listing_fb_data', [ __CLASS__, 'rest_booking_localize_fb_options' ] );
		add_filter( 'rtcl_fb_field_value_booking', [ __CLASS__, 'booking_form_data' ], 10, 3 );
		add_filter( 'rtcl_fb_localized_public_strings', [ __CLASS__, 'translations' ] );
	}

	/**
	 * @param array $strings
	 *
	 * @return array
	 */
	public static function translations( $strings ) {

		// Booking
		$strings['booking'] = [
			'pre_order_date'    => __( 'Pre-Order Date', 'rtcl-booking' ),
			'active'            => __( 'Active Booking', 'rtcl-booking' ),
			'type'              => __( 'Booking Type', 'rtcl-booking' ),
			'fee'               => __( 'Booking Fee', 'rtcl-booking' ),
			'display_tickets'   => __( 'Display Available Tickets', 'rtcl-booking' ),
			'available_tickets' => __( 'Available Tickets', 'rtcl-booking' ),
			'allowed_tickets'   => __( 'Tickets allowed per booking', 'rtcl-booking' ),
			'max_guest'         => __( 'Maximum Number of Guests', 'rtcl-booking' ),
			'schedule'          => __( 'Schedule', 'rtcl-booking' ),
			'open_24'           => __( 'Open 24 hours', 'rtcl-booking' ),
			'unavailable_dates' => __( 'Unavailable Dates', 'rtcl-booking' ),
			'available_date'    => __( 'Available From', 'rtcl-booking' ),
			'volume'            => __( 'Available Volume', 'rtcl-booking' ),
			'max_order'         => __( 'Maximum Order per booking', 'rtcl-booking' ),
			'instant'           => __( 'Instant Booking', 'rtcl-booking' ),
		];

		return $strings;
	}

	/**
	 * @param array | static | null $value
	 * @param array | null          $field
	 * @param Listing               $listing
	 *
	 * @return array
	 */
	public static function booking_form_data( $value, $field, $listing ) {
		if ( ! is_a( $listing, Listing::class ) ) {
			return $value;
		}

		$metaData = BookingFunctions::get_booking_meta( $listing->get_id() );

		if ( ! empty( $metaData ) ) {
			$bookingData = [];
			foreach ( $metaData as $metaKey => $metaValue ) {
				if ( '_rtcl_booking_active' === $metaKey ) {
					$bookingData['active'] = ! empty( $metaValue ) ? 1 : 0;
				} elseif ( '_rtcl_instant_booking' === $metaKey ) {
					$bookingData['instant'] = ! empty( $metaValue ) ? 1 : 0;
				} elseif ( 'rtcl_listing_booking_type' === $metaKey ) {
					$bookingData['type'] = $metaValue;
				} elseif ( '_rtcl_show_available_tickets' === $metaKey ) {
					$bookingData['display_tickets'] = ! empty( $metaValue ) ? 1 : 0;
				} elseif ( '_rtcl_available_tickets' === $metaKey ) {
					$bookingData['tickets'] = $metaValue ? absint( $metaValue ) : '';
				} elseif ( '_rtcl_booking_allowed_ticket' === $metaKey ) {
					$bookingData['tickets_per_booking'] = $metaValue;
				} elseif ( '_rtcl_booking_max_guest' === $metaKey ) {
					$bookingData['max_guest'] = $metaValue;
				} elseif ( '_rtcl_shs' === $metaKey ) {
					$schedule = maybe_unserialize( $metaValue );
					if ( ! empty( $schedule ) ) {
						$timeFormat = ! empty( $field['time_format'] ) ? $field['time_format'] : 'H:i';
						$days       = [];
						foreach ( $schedule as $day_key => $day ) {
							if ( ! empty( $day['open'] ) ) {
								$days[ $day_key ]['open'] = true;
								if ( ! empty( $day['times'] ) && is_array( $day['times'] ) ) {
									$newTimes = [];
									foreach ( $day['times'] as $time ) {
										if ( ! empty( $time['start'] ) && ! empty( $time['end'] ) ) {
											$start = Utility::formatTime( $time['start'], $timeFormat, 'H:i' );
											$end   = Utility::formatTime( $time['end'], $timeFormat, 'H:i' );
											if ( $start && $end ) {
												$newTimes[] = [ 'start' => $start, 'end' => $end ];
											}
										}
									}
									if ( ! empty( $newTimes ) ) {
										$days[ $day_key ]['times'] = $newTimes;
									}
								}
							}
						}
						if ( ! empty( $days ) ) {
							$bookingData['schedule'] = $days;
						}
					}
				} elseif ( '_rtcl_booking_rent_unavailable_date' === $metaKey ) {
					$rawDates             = maybe_unserialize( $metaValue );
					$rentUnavailableDates = [];
					if ( ! empty( $rawDates ) && is_array( $rawDates ) ) {
						foreach ( $rawDates as $rawDate ) {
							if ( $rawDate ) {
								$rentUnavailableDates[] = gmdate( 'Y-m-d', strtotime( $rawDate ) );
							}
						}
					}
					if ( ! empty( $rentUnavailableDates ) ) {
						$bookingData['rent_unavailable_dates'] = $rentUnavailableDates;
					}
				} elseif ( '_rtcl_booking_pre_order_available_volumn' === $metaKey ) {
					$bookingData['volume'] = $metaValue;
				} elseif ( '_rtcl_booking_pre_order_maximum' === $metaKey ) {
					$bookingData['max_order'] = $metaValue;
				} elseif ( '_rtcl_booking_pre_order_date' === $metaKey ) {
					$dates = explode( ' - ', $metaValue );
					if ( ! empty( $dates ) && count( $dates ) === 2 ) {
						$bookingData['pre_order_date_range'] = [
							'start' => ! empty( $dates[0] ) ? gmdate( 'Y-m-d', strtotime( $dates[0] ) ) : null,
							'end'   => ! empty( $dates[1] ) ? gmdate( 'Y-m-d', strtotime( $dates[1] ) ) : null
						];
					}
				} elseif ( '_rtcl_booking_pre_order_available_date' === $metaKey ) {
					$bookingData['pre_order_available_date'] = gmdate( 'Y-m-d', strtotime( $metaValue ) );
				} elseif ( '_rtcl_booking_fee' === $metaKey ) {
					$bookingData['fee'] = $metaValue;
				}
			}
			if ( ! empty( $bookingData ) ) {
				return $bookingData;
			}
		}

		return $value;
	}

	public static function booking_localize_fb_options( $params ) {
		$params['booking'] = [
			'types' => ResourcesOptions::get_listing_booking_types()
		];

		return $params;
	}

	public static function rest_booking_localize_fb_options( $params ) {
		$params['options']['booking'] = [
			'types' => ResourcesOptions::get_listing_booking_types()
		];

		return $params;
	}

	public static function booking_editor_settings_fields( $fields ) {
		$rawTypes = ResourcesOptions::get_listing_booking_types();
		$types    = [
			[
				'value' => '',
				'label' => __( 'None', 'rtcl-booking' ),
			]
		];
		if ( ! empty( $rawTypes ) ) {
			foreach ( $rawTypes as $rawTypeKey => $rawType ) {
				$types[] = [
					'value' => $rawTypeKey,
					'label' => $rawType,
				];
			}
		}

		return array_merge( $fields, [
			'default_booking_type' => [
				'key'      => 'type',
				'template' => 'select',
				'label'    => __( 'Default Booking Type', 'rtcl-booking' ),
				'options'  => $types
			]
		] );
	}

	public static function booking_editor_settings_placement( $placement ) {
		$placement['booking'] = [
			'general' => [
				'label',
				'label_placement',
				'default_booking_type',
				'time_format',
				'validation'
			],
			'advance' => [
				'container_class',
				'help_message',
				'admin_use_only',
				'logics'
			]
		];

		return $placement;
	}

	public static function booking_fields( $fields ) {
		$fields['booking'] = [
			'element'         => 'booking',
			'preset'          => 1,
			'name'            => 'booking',
			'class'           => '',
			'placeholder'     => '',
			'container_class' => '',
			'pricing_type'    => 'price',
			'type'            => 'event',
			'label'           => __( 'Booking', 'rtcl-booking' ),
			'label_placement' => '',
			'time_format'     => 'H:i',
			'help_message'    => '',
			'validation'      => [
				'required' => [
					'value'   => false,
					'message' => __( 'This field is required', 'rtcl-booking' ),
				],
			],
			'logics'          => '',
			'admin_use_only'  => false,
			'editor'          => [
				'title'      => __( 'Booking', 'rtcl-booking' ),
				'icon_class' => 'rtcl-icon-calendar-check-o',
				'template'   => 'booking',
			]
		];

		return $fields;
	}

	public static function disable_types( $types ) {
		// remove event type
		if ( BookingFunctions::is_disable_booking_event_type() ) {
			unset( $types['event'] );
		}
		// remove service type
		if ( BookingFunctions::is_disable_booking_service_type() ) {
			unset( $types['services'] );
		}
		// remove pre-order type
		if ( BookingFunctions::is_disable_booking_pre_order_type() ) {
			unset( $types['pre_order'] );
		}
		// remove rent type
		if ( BookingFunctions::is_disable_booking_rent_type() ) {
			unset( $types['rent'] );
		}

		return $types;
	}

	/**
	 * @param array $classes
	 *
	 * @return array
	 */
	static function add_meta_box_classes( $classes = [] ) {
		array_push( $classes, sanitize_html_class( 'rtcl' ) );

		return $classes;
	}

	public static function add_bookings_menu_item_at_account_menu( $items ) {
		$position = array_search( 'edit-account', array_keys( $items ) );

		$booking = \RtclBooking\Helpers\Functions::get_all_bookings();

		$menu['my-bookings'] = apply_filters( 'rtcl_my_booking_title', esc_html__( 'My Bookings', 'rtcl-booking' ) );

		if ( ! empty( $booking ) ) {
			$menu['all-bookings'] = apply_filters( 'rtcl_all_booking_title', esc_html__( 'All Bookings', 'rtcl-booking' ) );
		}

		if ( $position > - 1 ) {
			Functions::array_insert( $items, $position, $menu );
		}

		return $items;
	}

	public static function add_my_account_bookings_end_points( $endpoints ) {
		$endpoints['my-bookings']  = Functions::get_option_item( 'rtcl_booking_settings', 'myaccount_booking_endpoint', 'my-bookings' );
		$endpoints['all-bookings'] = Functions::get_option_item( 'rtcl_booking_settings', 'myaccount_all_booking_endpoint', 'all-bookings' );

		return $endpoints;
	}

	public static function sanitize_business_hours( $sanitize_value, $raw_ohs, $type ) {

		if ( in_array( $type, [ 'business_hours', 'special_business_hours' ] ) ) {
			$new_bhs = [];
			if ( is_array( $raw_ohs ) && ! empty( $raw_ohs ) ) {
				if ( "business_hours" === $type ) {
					foreach ( Options::get_week_days() as $day_key => $day ) {
						if ( ! empty( $raw_ohs[ $day_key ] ) ) {
							$bh = $raw_ohs[ $day_key ];
							if ( ! empty( $bh['open'] ) ) {
								$new_bhs[ $day_key ]['open'] = true;
								if ( isset( $bh['times'] ) && is_array( $bh['times'] ) && ! empty( $bh['times'] ) ) {
									$new_times = [];
									foreach ( $bh['times'] as $time ) {
										if ( ! empty( $time['start'] ) && ! empty( $time['end'] ) ) {
											$start = Utility::formatTime( $time['start'], 'H:i' );
											$end   = Utility::formatTime( $time['end'], 'H:i' );
											if ( $start && $end ) {
												$new_times[] = [ 'start' => $start, 'end' => $end ];
											}
										}
									}
									if ( ! empty( $new_times ) ) {
										$new_bhs[ $day_key ]['times'] = $new_times;
									}
								}
							} else {
								$new_bhs[ $day_key ]['open'] = false;
							}
						}
					}
				} else if ( "special_business_hours" === $type ) {
					$temp_count = 0;
					$temp_keys  = [];
					foreach ( $raw_ohs as $sh_key => $sbh ) {
						if ( ! empty( $sbh['date'] ) && ! isset( $temp_keys[ $sbh['date'] ] ) && $date = Utility::formatDate( $sbh['date'], 'Y-m-d' ) ) {
							$temp_keys[] = $new_bhs[ $temp_count ]['date'] = $date;
							if ( ! empty( $sbh['open'] ) ) {
								$new_bhs[ $temp_count ]['open'] = true;
								if ( isset( $sbh['times'] ) && is_array( $sbh['times'] ) && ! empty( $sbh['times'] ) ) {
									$new_times = [];
									foreach ( $sbh['times'] as $time ) {
										if ( ! empty( $time['start'] ) && ! empty( $time['end'] ) ) {
											$start = Utility::formatTime( $time['start'], 'H:i' );
											$end   = Utility::formatTime( $time['end'], 'H:i' );
											if ( $start && $end ) {
												$new_times[] = [ 'start' => $start, 'end' => $end ];
											}
										}
									}
									if ( ! empty( $new_times ) ) {
										$new_bhs[ $temp_count ]['times'] = $new_times;
									}
								}
							} else {
								$new_bhs[ $temp_count ]['open'] = false;
							}
						}
						$temp_count ++;
					}
				}
			}

			$sanitize_value = $new_bhs;
		}

		return $sanitize_value;
	}

	public static function add_booking_class( $classes ) {
		global $wp;

		if ( Functions::is_listing() ) {
			$booking_type = BookingFunctions::get_booking_meta( get_the_ID(), 'rtcl_listing_booking_type' );
			if ( 'rent' === $booking_type ) {
				$classes[] = 'rent-type-booking';
			}
		}

		if ( isset( $wp->query_vars['my-bookings'] ) || isset( $wp->query_vars['rtcl-my-bookings'] ) ) {
			$classes[] = 'my-bookings';
		}

		if ( isset( $wp->query_vars['all-bookings'] ) || isset( $wp->query_vars['rtcl-all-bookings'] ) ) {
			$classes[] = 'all-bookings';
		}

		return $classes;
	}

	public static function add_booking_email_services( $services ) {
		$services['Booking_Approved_Email'] = new BookingApprovedEmailToUser();
		$services['Booking_Rejected_Email'] = new BookingRejectedEmailToUser();
		$services['Booking_Request_Email']  = new BookingRequestEmailToOwner();

		return $services;
	}

	public static function license( $licenses ) {
		$licenses[] = [
			'plugin_file' => RTCL_BOOKING_PLUGIN_FILE,
			'api_data'    => [
				'key_name'    => 'booking_license_key',
				'status_name' => 'booking_license_status',
				'action_name' => 'rtcl_manage_booking_licensing',
				'product_id'  => 195735,
				'version'     => RTCL_BOOKING_VERSION,
			],
			'settings'    => [
				'title' => esc_html__( 'Booking addon license key', 'rtcl-booking' ),
			],
		];

		return $licenses;
	}

}
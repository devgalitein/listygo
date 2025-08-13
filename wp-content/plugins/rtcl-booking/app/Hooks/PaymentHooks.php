<?php

namespace RtclBooking\Hooks;

use Rtcl\Models\Listing;
use RtclBooking\Helpers\Functions as BookingFunctions;

class PaymentHooks {

	public static function init() {
		add_filter( 'woocommerce_data_stores', [ __CLASS__, 'data_stores' ] );
		add_filter( 'woocommerce_product_get_price', [ __CLASS__, 'woocommerce_get_price' ], 20, 2 );
		add_action( 'woocommerce_checkout_order_created', [ __CLASS__, 'create_booking_order' ], 10 );
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'update_booking_status' ], 10, 3 );
	}

	/**
	 * @param $stores
	 *
	 * @return mixed
	 */
	public static function data_stores( $stores ) {
		require_once RTCL_BOOKING_PATH . 'app/RtclBookingDataStore.php';

		$stores['product'] = 'RtclBookingDataStore';

		return $stores;
	}

	/**
	 * @param $price
	 * @param $product
	 *
	 * @return mixed
	 */
	public static function woocommerce_get_price( $price, $product ) {

		if ( get_post_type( $product->get_id() ) === 'rtcl_listing' ) {
			$price = BookingFunctions::get_booking_meta( $product->get_id(), '_rtcl_booking_fee' );
		}

		return $price;
	}

	/**
	 * @param $wc_order
	 *
	 * @return false|void
	 */
	public static function create_booking_order( $wc_order ) {
		if ( ! $wc_order ) {
			return false;
		}

		$rtcl_booking_id = absint( $wc_order->get_meta( '_rtcl_booking_id' ) );

		if ( $rtcl_booking_id ) {
			return false;
		}

		$wc_items = $wc_order->get_items();

		if ( ! $wc_items ) {
			return false;
		}

		$data = [];

		foreach ( $wc_items as $item ) {

			if ( isset( $item->legacy_values ) ) {
				$values = $item->legacy_values;

				$listing_id = ! empty( $values['_rtcl_booking_listing_id'] ) ? $values['_rtcl_booking_listing_id'] : 0;

				if ( rtcl()->post_type !== get_post_type( $listing_id ) ) {
					continue;
				}

				$data['listing_id']   = $listing_id;
				$data['quantity']     = $item->get_quantity();
				$data['booking_fee']  = $item->get_total();
				$data['booking_date'] = ! empty( $values['_rtcl_booking_date'] ) ? $values['_rtcl_booking_date'] : '';
				$data['time_slot']    = ! empty( $values['_rtcl_booking_time_slot'] ) ? $values['_rtcl_booking_time_slot'] : '';

				break;
			}
		}

		if ( empty( $data ) ) {
			return false;
		}

		$listing = rtcl()->factory->get_listing( $data['listing_id'] );

		if ( is_a( $listing, Listing::class ) ) {
			$booking_id = BookingFunctions::insert_listing_booking( $wc_order, $listing, $data );

			if ( $booking_id ) {
				$wc_order->add_meta_data( '_rtcl_booking_id', $booking_id );
				$wc_order->add_meta_data( '_rtcl_booking_listing_id', $listing->get_id() );
				$wc_order->save();
			}
		}

	}

	/**
	 * @param $order_id
	 * @param $old_status
	 * @param $new_status
	 *
	 * @return void
	 */
	public static function update_booking_status( $order_id, $old_status, $new_status ) {
		$wc_order   = wc_get_order( $order_id );
		$booking_id = absint( $wc_order->get_meta( '_rtcl_booking_id' ) );
		$listing_id = absint( $wc_order->get_meta( '_rtcl_booking_listing_id' ) );

		if ( $booking_id && $listing_id && get_post_type( $listing_id ) === rtcl()->post_type ) {
			$listing_id = rtcl()->factory->get_listing( $listing_id );

			if ( $listing_id ) {
				BookingFunctions::update_booking_status( $booking_id, $new_status );
			}
		}
	}

}
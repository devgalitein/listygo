<?php

namespace RtclBooking\Resources;

use Rtcl\Helpers\Functions;
use RtclBooking\Helpers\Functions as BookingFunctions;
use WP_Post;

class ListingDetails {
	/**
	 * @param WP_Post $post
	 */
	static function booking_fields( WP_Post $post ) {
		$post_id      = $post->ID;
		$booking_type = BookingFunctions::get_booking_meta( $post_id, 'rtcl_listing_booking_type' );
		$booking_fee  = BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_fee' );
		$max_guest    = BookingFunctions::get_booking_meta( $post_id, '_rtcl_booking_max_guest' );

		Functions::get_template( "listing-form/booking", compact( 'post_id','booking_type', 'booking_fee', 'max_guest' ), '', rtclBooking()->get_plugin_template_path() );
	}

}
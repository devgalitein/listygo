<?php

namespace RtclBooking\Resources;

class Options {

	public static function get_listing_booking_types() {
		$types = [
			"event"     => esc_html__( "Event", "rtcl-booking" ),
			"services"  => esc_html__( "Services", "rtcl-booking" ),
			"rent"      => esc_html__( "Rent", "rtcl-booking" ),
			"pre_order" => esc_html__( "Pre-Order", "rtcl-booking" ),
		];

		return apply_filters( 'rtcl_listing_booking_types', $types );
	}

}
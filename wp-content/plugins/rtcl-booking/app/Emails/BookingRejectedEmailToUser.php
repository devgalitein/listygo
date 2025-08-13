<?php

namespace RtclBooking\Emails;

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;
use RtclBooking\Helpers\Functions as BookingFunctions;

class BookingRejectedEmailToUser extends RtclEmail {

	protected $data;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->db            = true;
		$this->id            = 'booking_rejected';
		$this->template_html = 'emails/booking-rejected-email-to-user';

		// Call parent constructor.
		parent::__construct();

	}

	/**
	 * Get email subject.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return esc_html__( '[{site_title}] Booking "{listing_title}" is rejected', 'rtcl-booking' );
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return esc_html__( 'Your booking is rejected', 'rtcl-booking' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param $booking_id
	 * @param $listing
	 *
	 * @throws \Exception
	 */
	public function trigger( $booking_id, $data = [] ) {
		$this->setup_locale();

		if ( isset( $data['listing_id'] ) ) {
			$listing = rtcl()->factory->get_listing( $data['listing_id'] );
		}

		$booking_info = BookingFunctions::get_booking_user_details( $booking_id );

		$booking_details = isset( $booking_info[0]->details ) ? unserialize( $booking_info[0]->details ) : [];

		$this->data = $booking_details;

		if ( is_a( $listing, Listing::class ) ) {
			$this->object       = $listing;
			$this->placeholders = wp_parse_args( array(
				'{listing_title}' => $listing->get_the_title()
			), $this->placeholders );
			$this->set_recipient( $booking_details['email'] );
		}

		if ( $this->get_recipient() ) {
			$this->send();
		}

		$this->restore_locale();
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return Functions::get_template_html(
			$this->template_html,
			array(
				'listing' => $this->object,
				'email'   => $this,
				'booking' => $this->data
			),
			'',
			rtclBooking()->get_plugin_template_path()
		);
	}
}
<?php

namespace RtclBooking\Emails;

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;

class BookingRequestEmailToOwner extends RtclEmail {

	protected $data;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->db            = true;
		$this->id            = 'booking_request';
		$this->template_html = 'emails/booking-request-email-to-owner';

		// Call parent constructor.
		parent::__construct();

	}

	/**
	 * Get email subject.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return esc_html__( '[{site_title}] Booking request for "{listing_title}"', 'rtcl-booking' );
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return esc_html__( 'A request for booking.', 'rtcl-booking' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param $listing_id
	 * @param $data
	 *
	 * @throws \Exception
	 */
	public function trigger( $listing_id, $data = [] ) {
		$this->setup_locale();

		if ( ! empty( $listing_id ) ) {
			$listing = rtcl()->factory->get_listing( $listing_id );
		}

		$this->data = $data;

		if ( is_a( $listing, Listing::class ) ) {
			$this->object       = $listing;
			$this->placeholders = wp_parse_args( array(
				'{listing_title}' => $listing->get_the_title()
			), $this->placeholders );
			$this->set_recipient( $listing->get_owner_email() );
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
				'listing'  => $this->object,
				'email'    => $this,
				'customer' => $this->data
			),
			'',
			rtclBooking()->get_plugin_template_path()
		);
	}
}
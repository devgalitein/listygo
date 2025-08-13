<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/booking-approved-email-to-user.php
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 * @var RtclEmail $email
 * @var array $booking
 * @var object $listing
 */

use Rtcl\Models\RtclEmail;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p><?php printf( esc_html__( 'Hi %s,', 'rtcl-booking' ), $booking['name'] ); ?></p>
    <p><?php printf( __( 'Your booking <strong>%1$s</strong> is approved at <a href="%2$s">%1$s</a>.', 'rtcl-booking' ), $listing->get_the_title(), $listing->get_the_permalink() ) ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
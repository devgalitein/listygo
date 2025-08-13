<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/booking-rejected-email-to-user.php
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 * @var RtclEmail $email
 * @var array $customer
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
    <p><?php printf( esc_html__( 'Hi %s,', 'rtcl-booking' ), $listing->get_owner_name() ); ?></p>
    <p><?php printf( __( '%s sent a booking request for <a href="%s">%s</a>.', 'rtcl-booking' ), ( isset( $customer['name'] ) ? $customer['name'] : 'Just' ), $listing->get_the_permalink(), $listing->get_the_title() ) ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
<?php
/**
 * User verify email
 *
 * @package ClassifiedListing/Templates/Emails
 * @version 1.2.27
 *
 * @var RtclEmail $email
 * @var WP_User   $user
 * @var string    $verify_link
 */

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclEmail;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>

	<p style="margin: 0 0 16px;"><?php printf( esc_html__( 'Hello %s,', 'classified-listing-pro' ), esc_html( Functions::get_author_name( $user ) ) ); ?></p>
	<p style="margin: 0 0 16px;"><?php printf( __( "Thanks for registering on our site, please %s.", 'classified-listing-pro' ), sprintf( '<a href="%s" target="_blank">%s</a>', $verify_link, __( 'click here to confirm your email address', 'classified-listing-pro' ) ) ); ?></p>
	<p style="margin: 0 0 16px;"><?php esc_html_e( 'If you didn\'t make this request, just ignore this email.', 'classified-listing-pro' ); ?></p>
	<p style="margin: 0 0 16px;"><?php esc_html_e( 'Thanks for reading.', 'classified-listing-pro' ); ?></p>

<?php
/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );

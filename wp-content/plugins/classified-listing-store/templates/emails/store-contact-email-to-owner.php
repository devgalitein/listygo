<?php
/**
 * Store contact mail to store owner
 *
 * @package ClassifiedListingStore/Templates/Emails
 * @version 1.2.0
 *
 * @var array     $data
 * @var Store     $store
 * @var RtclEmail $email
 */


use Rtcl\Models\RtclEmail;
use RtclStore\Models\Store;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p style="margin: 0 0 16px;"><?php printf( __( 'Hi %s', 'classified-listing-store' ), $store->owner_name() ); ?>,</p>
    <p style="margin: 0 0 16px;"><?php printf( __( 'You have received a reply from your store at %s.', 'classified-listing-store' ), sprintf( '<strong>%s</strong>', esc_html( $store->get_the_title() ) ) ); ?></p>
<?php printf( __( '<strong>Name</strong>: %s', 'classified-listing-store' ), esc_html( $data['name'] ) ) ?><br>
<?php printf( __( '<strong>Email</strong>: %s', 'classified-listing-store' ), esc_html( $data['email'] ) ) ?><br>
<?php printf( __( '<strong>Phone</strong>: %s', 'classified-listing-store' ), esc_html( $data['phone'] ) ) ?><br>
<?php printf( __( '<strong>Message</strong>:<br>%s', 'classified-listing-store' ), wp_kses_post( wp_unslash( nl2br( $data['message'] ) ) ) ) ?>
    <br>
    <br>
<?php
/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );

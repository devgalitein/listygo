<?php
/**
 * Admin Notification at Store update
 *
 * @package ClassifiedListingStore/Templates/Emails
 * @version 1.2.0
 *
 * @var WP_Post   $post
 * @var WP_User   $user
 * @var RtclEmail $email
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
    <p style="margin: 0 0 16px;"><?php esc_html_e( 'Hi Admin', 'classified-listing-store' ); ?>,</p>
    <p style="margin: 0 0 16px;"><?php printf( "%s Store is information is updated by %s ( %s ) to your site %s.",
			esc_html( $post->post_title ),
			esc_html( $user->user_login ),
			esc_html( $user->user_email ),
			esc_html( Functions::get_blogname() ) ) ?></p>
    <p style="margin: 0 0 16px;"><?php esc_html_e( "Please do not respond to this message. It is automatically generated and is for information purposes only.",
			'classified-listing-store' ) ?></p>
    <p style="margin: 0 0 16px;"><?php esc_html_e( 'Thanks for reading.', 'classified-listing-store' ); ?></p>


<?php
/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );

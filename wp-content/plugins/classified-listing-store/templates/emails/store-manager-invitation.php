<?php
/**
 * Store Manager request email
 *
 * @package ClassifiedListingStore/Templates/Emails
 * @version 1.3.34
 *
 * @var WP_User   $user
 * @var Store     $store
 * @var array     $data
 * @var RtclEmail $email
 * @var Store     $store
 */


use Rtcl\Helpers\Link;
use Rtcl\Models\RtclEmail;
use RtclStore\Models\Store;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */

do_action( 'rtcl_email_header', $email ); ?>
    <p style="margin: 0 0 16px;"><?php printf( __( 'Hi %s', 'classified-listing-store' ), $user->user_login ); ?>,</p>
    <p style="margin: 0 0 16px;"><?php printf( __( 'You have received a store manager request from the store owner of %s.', 'classified-listing-store' ), sprintf( '<strong><a href="%s">%s</a></strong>', esc_url( $store->get_the_permalink() ), esc_html( $store->get_the_title() ) ) ); ?></p>
    <p style="margin: 0 0 16px;"><?php printf( __( 'You can approve this request from here: %s', 'classified-listing-store' ), esc_url( add_query_arg( [
			'rtcl_store_manager_key' => $data['key'],
			'rtcl_store_id'          => $store->get_id(),
			'rtcl_store_manager_id'  => $user->ID
		], Link::get_account_endpoint_url() ) ) ) ?></p>
<?php
/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );

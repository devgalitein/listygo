<?php

use RtclBooking\Helpers\Functions;
use Rtcl\Helpers\Functions as RtclFunctions;

$booking_status = isset( $_GET['status'] ) ? esc_html( $_GET['status'] ) : '';
$results        = Functions::get_all_bookings( $booking_status );
$booking_count  = count( $results );
$post_per_page  = apply_filters( 'rtcl_booking_posts_per_page', 5 );
$pages          = ceil( $booking_count / $post_per_page );
?>
<div class="rtcl-my-booking-wrap">
    <form action="" method="get">
        <div class="rtcl-form-group">
            <select name="status" class="rtcl-form-control">
                <option value=""><?php esc_html_e( '---Select Type---', 'rtcl-booking' ); ?></option>
                <option value="approved" <?php selected( $booking_status, 'approved', true ); ?>><?php esc_html_e( 'Approved', 'rtcl-booking' ); ?></option>
                <option value="pending" <?php selected( $booking_status, 'pending', true ); ?>><?php esc_html_e( 'Pending', 'rtcl-booking' ); ?></option>
                <option value="cancelled" <?php selected( $booking_status, 'cancelled', true ); ?>><?php esc_html_e( 'Cancelled', 'rtcl-booking' ); ?></option>
                <option value="rejected" <?php selected( $booking_status, 'rejected', true ); ?>><?php esc_html_e( 'Rejected', 'rtcl-booking' ); ?></option>
            </select>
            <button class="rtcl-btn btn-primary" type="submit"><?php esc_html_e( 'Search', 'rtcl-booking' ); ?></button>
        </div>
    </form>
	<?php if ( ! empty( $results ) ): ?>
        <div class="rtcl-single-booking-wrap">
			<?php
			$i = 1;
			foreach ( $results as $booking ) {
				if ( $i > $post_per_page ) {
					break;
				}
				$i ++;
				$listing = rtcl()->factory->get_listing( $booking->listing_id );

				if ( is_object( $listing ) ) {
					RtclFunctions::get_template( 'myaccount/booking-info',
						[
							'booking'     => $booking,
							'listing'     => $listing,
							'booking_for' => 'all'
						], '', rtclBooking()->get_plugin_template_path() );
				}
			}
			?>
        </div>
		<?php if ( $pages > 1 ):
			RtclFunctions::get_template( 'booking/pagination',
				[
					'post_per_page' => $post_per_page,
					'pages'         => $pages,
					'booking'       => 'all'
				], '', rtclBooking()->get_plugin_template_path() );
		endif; ?>
	<?php endif; ?>
</div>
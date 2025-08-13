<?php

use RtclBooking\Helpers\Functions;
use Rtcl\Helpers\Functions as RtclFunctions;

$results       = Functions::get_my_bookings();
$booking_count = count( $results );
$post_per_page = apply_filters( 'rtcl_booking_posts_per_page', 5 );
$pages         = ceil( $booking_count / $post_per_page );
?>
<div class="rtcl-my-booking-wrap">
	<?php if ( ! empty( $results ) ) { ?>
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
							'booking_for' => 'my'
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
					'booking'       => 'my'
				], '', rtclBooking()->get_plugin_template_path() );
		endif; ?>
	<?php } else { ?>
        <p class="not-found"><?php esc_html_e( 'No results found', 'rtcl-booking' ); ?></p>
	<?php } ?>
</div>
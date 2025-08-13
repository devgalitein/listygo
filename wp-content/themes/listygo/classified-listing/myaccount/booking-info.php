<?php
/**
 * Booking Pagination
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.1
 *
 * @var object $booking
 * @var object $listing
 * @var string $booking_for
 */

use RtclBooking\Helpers\Functions as BookingFunctions;

$details      = isset( $booking->details ) ? unserialize( $booking->details ) : [];
$booking_type = BookingFunctions::get_booking_meta( $booking->listing_id, 'rtcl_listing_booking_type' );
?>
<div class="single-booking rtcl-MyAccount-content-inner" data-booking-id="<?php echo esc_attr( $booking->id ); ?>"
     data-id="<?php echo esc_attr( $booking->listing_id ); ?>">
    <div class="booking-listing-thumbnail">
        <a href="<?php $listing->the_permalink(); ?>"
           class="rtcl-media"><?php $listing->the_thumbnail(); ?></a>
    </div>
    <div class="booking-listing-content">
        <div class="booking-listing-title">
            <h3><a href="<?php $listing->the_permalink(); ?>"><?php $listing->the_title(); ?></a></h3>
            <span class="booking-status"><?php echo esc_html( $booking->status ); ?></span>
        </div>
		<?php if ( ! empty( $booking->booking_date ) ): ?>
            <div class="booking-date">
                <strong><?php esc_html_e( 'Booking Date:', 'listygo' ); ?></strong>
                <span>
                    <?php
                    echo esc_html( $booking->booking_date );
                    if ( ! empty( $booking->time_slot ) ) {
	                    echo '<span>' . esc_html( $booking->time_slot ) . '</span>';
                    }
                    if ( 'rent' === $booking_type ) {
	                    $days = BookingFunctions::get_days_from_date_range( $booking->booking_date );
	                    printf( _n( ' (%s day)', ' (%s days)', $days, 'listygo' ), number_format_i18n( $days ) );
                    }
                    ?>
                </span>
            </div>
		<?php endif; ?>
		<?php if ( $booking->quantity ): ?>
            <div class="booking-client-info">
				<?php if ( 'pre_order' == $booking_type ): ?>
                    <strong><?php esc_html_e( 'Order Volumn:', 'listygo' ); ?></strong>
				<?php else: ?>
                    <strong><?php esc_html_e( 'Guest:', 'listygo' ); ?></strong>
				<?php endif; ?>
                <span><?php esc_html_e( $booking->quantity ); ?></span>
            </div>
		<?php endif; ?>
        <div class="booking-client-info">
            <strong><?php esc_html_e( 'Customer:', 'listygo' ); ?></strong>
            <ul>
                <li><?php echo esc_html( $details['name'] ); ?></li>
                <li><?php echo esc_html( $details['email'] ); ?></li>
                <li><?php echo esc_html( $details['phone'] ); ?></li>
            </ul>
        </div>
		<?php if ( ! empty( $details['message'] ) ): ?>
            <div class="booking-client-message">
                <strong><?php esc_html_e( 'Message:', 'listygo' ); ?></strong>
                <p><?php echo wp_kses_post( $details['message'] ); ?></p>
            </div>
		<?php endif; ?>
        <div class="booking-requested">
            <strong><?php esc_html_e( 'Requested on:', 'listygo' ); ?></strong>
            <p><?php echo esc_html( $booking->requested_at ); ?></p>
        </div>
		<?php if ( 'pre_order' == $booking_type ):
			$available_date = BookingFunctions::get_booking_meta( $booking->listing_id, '_rtcl_booking_pre_order_available_date' );
			if ( ! empty( $available_date ) ):
				?>
                <div class="booking-requested">
                    <strong><?php esc_html_e( 'Available from:', 'listygo' ); ?></strong>
                    <p><?php echo esc_html( $available_date ); ?></p>
                </div>
			<?php endif; ?>
		<?php endif; ?>
        <div class="booking-btn">
			<?php if ( 'my' == $booking_for ): ?>
				<?php if ( 'pending' === $booking->status ): ?>
                    <button id="cancel-booking"
                            class="btn btn-warning"><?php esc_html_e( 'Cancel', 'listygo' ); ?></button>
				<?php elseif ( 'canceled' == $booking->status ): ?>
                    <button id="delete-booking"
                            class="btn btn-danger"><?php esc_html_e( 'Delete', 'listygo' ); ?></button>
				<?php endif; ?>
			<?php else: ?>
				<?php if ( 'pending' == $booking->status ): ?>
                    <button id="approve-booking"
                            class="btn btn-primary"><?php esc_html_e( 'Approve', 'listygo' ); ?></button>
                    <button id="reject-booking"
                            class="btn btn-warning"><?php esc_html_e( 'Reject', 'listygo' ); ?></button>
				<?php elseif ( 'approved' == $booking->status ): ?>
                    <button id="cancel-booking"
                            class="btn btn-warning"><?php esc_html_e( 'Cancel', 'listygo' ); ?></button>
				<?php else: ?>
                    <button id="delete-booking"
                            class="btn btn-danger"><?php esc_html_e( 'Delete', 'listygo' ); ?></button>
				<?php endif; ?>
			<?php endif; ?>
        </div>
    </div>
</div>
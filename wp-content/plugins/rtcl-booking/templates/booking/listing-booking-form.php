<?php
/**
 * Listing Booking Form
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.1
 *
 * @var string $type
 * @var int    $listing_id
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use Rtcl\Helpers\Utility;
use RtclBooking\Helpers\Functions as BookingFunctions;

?>
<div class="<?php echo apply_filters( 'rtcl_booking_form_wrap_class', 'rtcl-listing-booking-wrap' ); ?>">
    <div class="rtcl-listing-side-title">
        <h3><?php esc_html_e( 'Booking', 'rtcl-booking' ); ?></h3>
    </div>
    <form method="post">
		<?php
		$ticket_fee = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_fee' );

		if ( 'event' === $type ) {
			$ticketAllowed    = (int) BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_allowed_ticket' );
			$ticketAllowed    = ! empty( $ticketAllowed ) ? $ticketAllowed : apply_filters( 'rtcl_booking_allowed_ticket', 5 );
			$available_ticket = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_available_tickets' );
			$show_ticket      = (bool) BookingFunctions::get_booking_meta( $listing_id, '_rtcl_show_available_tickets' );
			$ticket_booked    = BookingFunctions::get_booked_ticket( $listing_id );
			?>
			<?php if ( $available_ticket > $ticket_booked ): ?>
                <div class="rtcl-form-group">
                    <label for="no_of_ticket"><?php esc_html_e( 'Number of Guest', 'rtcl-booking' ); ?></label>
                    <select class="rtcl-form-control" name="no_of_ticket" id="no_of_ticket">
						<?php
						for ( $i = 1; $i <= $ticketAllowed; $i ++ ) {
							?>
                            <option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
							<?php
						}
						?>
                    </select>
                </div>
                <input type="hidden" id="post_id" name="post_id"
                       value="<?php echo esc_attr( $listing_id ); ?>"/>
                <input type="hidden" id="booking_fee" name="booking_fee"
                       value="<?php echo esc_attr( $ticket_fee ); ?>"/>
				<?php if ( is_user_logged_in() ): ?>
                    <button type="submit" id="rtcl-booking-submit-btn" class="rtcl-btn btn-primary">
						<?php echo esc_html( apply_filters( 'rtcl_booking_event_buton_text', BookingFunctions::get_booking_button_text() ) ); ?>
                    </button>
				<?php else: ?>
                    <a href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>"
                       class="rtcl-btn btn-primary"><?php esc_html_e( 'Login to Book', 'rtcl-booking' ); ?></a>
				<?php endif; ?>
                <div class="rtcl-booking-info">
					<?php if ( $show_ticket && $available_ticket ):
						$remaining_ticket = $available_ticket - $ticket_booked;
						?>
                        <div class="rtcl-booking-available-ticket">
                            <strong><?php echo esc_html( apply_filters( 'rtcl_booking_get_available_ticket_label',
									BookingFunctions::get_available_ticket_label() ) ); ?></strong>
                            <span><?php echo esc_html( $remaining_ticket ); ?></span>
                        </div>
					<?php endif; ?>
					<?php if ( $ticket_fee ): ?>
                        <div class="rtcl-booking-cost">
                            <strong><?php echo esc_html( apply_filters( 'rtcl_booking_fee_label', BookingFunctions::get_booking_fee_label() ) ); ?></strong>
                            <span><?php echo esc_html( Functions::get_currency_symbol() ); ?><span><?php echo esc_html( $ticket_fee ); ?></span></span>
                        </div>
					<?php endif; ?>
                </div>
			<?php else: ?>
                <div class="alert rtcl-response alert-danger">
                    <p><?php echo esc_html( apply_filters( 'rtcl_booking_not_available_ticket_message',
							esc_html__( 'Ticket not available.', 'rtcl-booking' ) ) ); ?></p>
                </div>
			<?php endif; ?>
			<?php
		} else if ( 'services' === $type ) {
			$date_options = [
				'minDate'          => Utility::formatDate( date( 'Y-m-d' ) ),
				'singleDatePicker' => true,
				'timePicker'       => false,
				'timePicker24Hour' => false,
				'locale'           => BookingFunctions::date_range_picker_locale()
			];
			?>
            <div class="rtcl-form-group">
                <label for="booking_date"><?php esc_html_e( 'Date', 'rtcl-booking' ); ?></label>
                <input type="text" id="booking_date" class="rtcl-date rtcl-form-control"
                       data-options="<?php echo esc_attr( wp_json_encode( $date_options ) ); ?>"/>
                <input type="hidden" id="listing_id" name="listing_id"
                       value="<?php echo esc_attr( $listing_id ); ?>"/>
            </div>
            <div class="rtcl-form-group">
                <label for="no_of_ticket"><?php echo esc_html( apply_filters( 'rtcl_booking_service_guest_no_label',
						__( 'Number of Guest', 'rtcl-booking' ) ) ); ?></label>
                <input type="number" min="1" class="rtcl-form-control" name="no_of_ticket" id="no_of_ticket" required/>
            </div>
            <input type="hidden" id="post_id" name="post_id"
                   value="<?php echo esc_attr( $listing_id ); ?>"/>
            <input type="hidden" id="booking_fee" name="booking_fee"
                   value="<?php echo esc_attr( $ticket_fee ); ?>"/>
			<?php if ( is_user_logged_in() ): ?>
                <button type="submit" id="rtcl-booking-submit-btn" class="rtcl-btn btn-primary">
					<?php echo esc_html( apply_filters( 'rtcl_booking_service_buton_text', BookingFunctions::get_booking_button_text() ) ); ?>
                </button>
			<?php else: ?>
                <a href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>"
                   class="rtcl-btn btn-primary"><?php esc_html_e( 'Login to Book', 'rtcl-booking' ); ?></a>
			<?php endif; ?>
            <div class="rtcl-booking-info">
                <div class="rtcl-booking-cost">
                    <strong><?php echo esc_html( apply_filters( 'rtcl_booking_fee_label', BookingFunctions::get_booking_fee_label() ) ); ?></strong>
                    <span><?php echo esc_html( Functions::get_currency_symbol() ); ?><span><?php echo esc_html( $ticket_fee ); ?></span></span>
                </div>
            </div>
		<?php } else if ( 'pre_order' === $type ) {
			Functions::get_template( 'booking/type/pre-order',
				[
					'listing_id' => $listing_id
				],
				'',
				rtclBooking()->get_plugin_template_path()
			);
		} else if ( 'rent' === $type ) {
			Functions::get_template( 'booking/type/rent',
				[
					'listing_id' => $listing_id
				],
				'',
				rtclBooking()->get_plugin_template_path()
			);
		}
		?>
    </form>
</div>
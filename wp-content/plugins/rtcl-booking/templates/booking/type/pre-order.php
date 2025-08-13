<?php
/**
 * Listing pre order Form
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.1
 *
 * @var int $listing_id
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclBooking\Helpers\Functions as BookingFunctions;

$booking_fee      = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_fee' );
$available_volumn = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_available_volumn' );
$max_order_volumn = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_maximum' );
$available_date   = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_available_date' );
$pre_order_date   = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_pre_order_date' );
$already_ordered  = BookingFunctions::get_booked_ticket( $listing_id );

if ( ! empty( $pre_order_date ) ) {
	$pre_order_range = explode( ' - ', $pre_order_date );
	if ( time() < strtotime( $pre_order_range[0] ) ) {
		$message = sprintf( esc_html__( 'Pre-order not started yet, will be start from %s', 'rtcl-booking' ),
			date( 'd M, Y', strtotime( $pre_order_range[0] ) ) );
	} else if ( time() > strtotime( $pre_order_range[1] ) ) {
		$message = esc_html__( 'Order already closed.', 'rtcl-booking' );
	}
	if ( isset( $message ) ) {
		echo esc_html( $message );

		return;
	}
}
?>
<div class="rtcl-form-group">
    <label for="no_of_ticket"><?php esc_html_e( 'Number of Item', 'rtcl-booking' ); ?></label>
    <select class="rtcl-form-control" name="no_of_ticket" id="no_of_ticket">
		<?php
		for ( $i = 1; $i <= $max_order_volumn; $i ++ ) {
			?>
            <option value="<?php echo esc_attr( $i ); ?>"><?php echo absint( $i ); ?></option>
			<?php
		}
		?>
    </select>
</div>
<input type="hidden" id="post_id" name="post_id"
       value="<?php echo esc_attr( $listing_id ); ?>"/>
<input type="hidden" id="booking_fee" name="booking_fee"
       value="<?php echo esc_attr( $booking_fee ); ?>"/>

<?php if ( is_user_logged_in() ): ?>
    <button type="submit" id="rtcl-booking-submit-btn" class="rtcl-btn btn-primary">
		<?php echo esc_html( apply_filters( 'rtcl_booking_order_buton_text', BookingFunctions::get_booking_button_text() ) ); ?>
    </button>
<?php else: ?>
    <a href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>"
       class="rtcl-btn btn-primary"><?php esc_html_e( 'Login to Book', 'rtcl-booking' ); ?></a>
<?php endif; ?>

<div class="rtcl-booking-info">
	<?php if ( $available_volumn ):
		$remaining_order = $available_volumn - $already_ordered;
		?>
        <div class="rtcl-booking-available-ticket">
            <strong><?php echo esc_html( BookingFunctions::get_pre_order_availalbe_volumn_label() ); ?>:</strong>
            <span><?php echo esc_html( $remaining_order ); ?></span>
        </div>
	<?php endif; ?>
	<?php if ( ! empty( $available_date ) ): ?>
        <div class="rtcl-booking-order-available-time">
            <strong><?php echo esc_html__( 'Available from:', 'rtcl-booking' ); ?></strong>
            <span><?php echo date_i18n( get_option( 'date_format' ), strtotime( $available_date ) ); ?></span>
        </div>
	<?php endif; ?>
	<?php if ( $booking_fee ): ?>
        <div class="rtcl-booking-cost">
            <strong><?php echo esc_html__( BookingFunctions::get_booking_fee_label() ); ?>:</strong>
            <span><?php echo esc_html( Functions::get_currency_symbol() ); ?><span><?php echo esc_html( $booking_fee ); ?></span></span>
        </div>
	<?php endif; ?>
</div>
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
use Rtcl\Helpers\Utility;
use RtclBooking\Helpers\Functions as BookingFunctions;

$booking_fee      = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_fee' );
$max_guest        = BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_max_guest' );
$unavailable_date = maybe_unserialize( BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_rent_unavailable_date' ) );
$booked_date      = BookingFunctions::get_rent_booked_date( $listing_id );
$pending_date     = BookingFunctions::get_rent_booked_date( $listing_id, 'pending' );
// merge unavailable date with already booked date
$unavailable_date = is_array( $unavailable_date ) ? $unavailable_date : [];
$unavailable_date = array_merge( $unavailable_date, $booked_date );

$date_options = [
	'minDate'          => Utility::formatDate( date( 'Y-m-d' ) ),
	'timePicker'       => false,
	'timePicker24Hour' => false,
	'locale'           => BookingFunctions::date_range_picker_locale(),
	'invalidDateList'  => $unavailable_date,
	'bookedDateList'   => $booked_date,
	'pendingDateList'  => $pending_date
];

$date_options = apply_filters( 'rtcl_booking_rent_datepicker_options', $date_options );

?>
<div class="rtcl-form-group">
    <label for="booking_rent_date"><?php esc_html_e( 'Date', 'rtcl-booking' ); ?></label>
    <input type="text" id="booking_rent_date" class="rtcl-date rtcl-form-control"
           data-options="<?php echo esc_attr( wp_json_encode( $date_options ) ); ?>"/>
</div>
<div class="rtcl-form-group">
    <label for="guest_number"><?php esc_html_e( 'Guest', 'rtcl-booking' ); ?></label>
    <div class="rent-guest-quantity">
        <span class="minus-sign">−</span>
        <input type="text" id="guest_number" data-max="<?php echo intval( $max_guest ); ?>" value="1" readonly/>
        <span class="plus-sign">+</span>
    </div>
</div>
<input type="hidden" id="post_id" name="post_id"
       value="<?php echo esc_attr( $listing_id ); ?>"/>

<?php if ( is_user_logged_in() ): ?>
    <button type="submit" id="rtcl-booking-submit-btn" class="rtcl-btn rtcl-btn-primary">
		<?php echo esc_html( apply_filters( 'rtcl_booking_order_buton_text', BookingFunctions::get_booking_button_text() ) ); ?>
    </button>
<?php else: ?>
    <a href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>"
       class="rtcl-btn rtcl-btn-primary"><?php esc_html_e( 'Login to Book', 'rtcl-booking' ); ?></a>
<?php endif; ?>

<div class="rtcl-booking-info">
	<?php if ( $booking_fee ): ?>
        <div class="rtcl-booking-cost">
            <strong><?php echo esc_html( apply_filters( 'rtcl_booking_fee_label', BookingFunctions::get_booking_fee_label() ) ); ?></strong>
            <span><?php echo esc_html( Functions::get_currency_symbol() ); ?><span><?php echo esc_html( $booking_fee ); ?></span></span>
        </div>
	<?php endif; ?>
</div>
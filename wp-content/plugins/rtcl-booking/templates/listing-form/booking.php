<?php
/**
 * Listing Form Contact
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var int    $post_id
 * @var string $booking_type
 * @var int    $booking_fee
 * @var int    $max_guest
 */

use RtclBooking\Helpers\Functions;
use Rtcl\Helpers\Utility;
use RtclBooking\Resources\Options;
use Rtcl\Resources\Options as RtclOptons;

if ( ! Functions::is_enable_booking() ) {
	return;
}

$listingBookingTypes = Options::get_listing_booking_types();

$weekdays            = RtclOptons::get_week_days();
$activeBooking       = Functions::get_booking_meta( $post_id, '_rtcl_booking_active' );
$shs                 = Functions::get_booking_meta( $post_id, '_rtcl_shs' );
$shs                 = ! empty( $shs ) && is_serialized( $shs ) ? unserialize( $shs ) : [];
$availableTicket     = Functions::get_booking_meta( $post_id, '_rtcl_available_tickets' );
$showAvailableTicket = Functions::get_booking_meta( $post_id, '_rtcl_show_available_tickets' );
$ticketAllowed       = Functions::get_booking_meta( $post_id, '_rtcl_booking_allowed_ticket' );
$instantBooking      = Functions::get_booking_meta( $post_id, '_rtcl_instant_booking' );
// Pre-Order
$available_volumn = Functions::get_booking_meta( $post_id, '_rtcl_booking_pre_order_available_volumn' );
$max_order_volumn = Functions::get_booking_meta( $post_id, '_rtcl_booking_pre_order_maximum' );
$available_date   = Functions::get_booking_meta( $post_id, '_rtcl_booking_pre_order_available_date' );
$pre_order_date   = Functions::get_booking_meta( $post_id, '_rtcl_booking_pre_order_date' );
// Rent
$unavailable_date = maybe_unserialize( Functions::get_booking_meta( $post_id, '_rtcl_booking_rent_unavailable_date' ) );
$unavailable_date = is_array( $unavailable_date ) ? implode( ',', $unavailable_date ) : $unavailable_date;
// default booking type
if ( empty( $booking_type ) ) {
	$booking_type = 'event';
}
$labelColumn = is_admin() ? 'rtcl-col-sm-2' : 'rtcl-col-sm-3';
$inputColumn = is_admin() ? 'rtcl-col-sm-10' : 'rtcl-col-sm-9';
$inputOffset = is_admin() ? 'rtcl-offset-sm-2' : 'rtcl-offset-sm-3';
?>
<div class="rtcl-post-booking rtcl-post-section<?php echo esc_attr( is_admin() ? " rtcl-is-admin" : '' ); ?>">
    <div class="rtcl-post-section-title">
        <h3>
            <i class="rtcl-icon rtcl-icon-users"></i><?php esc_html_e( "Booking", "rtcl-booking" ); ?>
        </h3>
    </div>
    <div id="rtcl-booking-holder" class="rtcl-booking-holder">
        <div class="rtcl-form-checkbox-group">
            <input type="checkbox" name="_rtcl_booking_active" value="1"
                   id="_rtcl_booking_active" <?php checked( $activeBooking, '1', true ); ?>/>
            <label class="rtcl-field-label"
                   for="_rtcl_booking_active"><?php esc_html_e( 'Active Booking', 'rtcl-booking' ); ?></label>
        </div>
        <div class="rtcl-booking-wrap">
            <div class="rtcl-form-group rtcl-row rtcl-booking-type">
                <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                    <label class="rtcl-field-label">
						<?php esc_html_e( 'Booking Type', 'rtcl-booking' ); ?>
                    </label>
                </div>
                <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
					<?php
					$i = 0;
					foreach ( $listingBookingTypes as $type_id => $type ) {
						$i ++;
						?>
                        <div class="rtcl-radio-field">
                            <input type="radio" name="_rtcl_listing_booking"
                                   id="_rtcl_listing_booking<?php echo esc_attr( $type_id ) ?>"
								<?php echo $booking_type === $type_id ? 'checked' : ( $i == 1 ? 'checked' : '' ); ?>
                                   value="<?php echo esc_attr( $type_id ) ?>">
                            <label for="_rtcl_listing_booking<?php echo esc_attr( $type_id ) ?>">
								<?php echo esc_html( $type ); ?>
                            </label>
                        </div>
						<?php
					}
					?>
                </div>
            </div>
            <div class="rtcl-booking-fields">
				<?php if ( ! Functions::is_disable_booking_event_type() ) { ?>
                    <div class="rtcl-form-checkbox-group rtcl-row rtcl-booking-event-fields">
                        <div class="<?php echo esc_attr( $inputColumn ); ?> <?php echo esc_attr( $inputOffset ); ?>">
                            <input type="checkbox" id="_rtcl_show_available_tickets" value="1"
                                   name="_rtcl_show_available_tickets" <?php checked( $showAvailableTicket, '1', true ); ?>/>
                            <label for="_rtcl_show_available_tickets">
								<?php esc_html_e( 'Display Available Tickets', 'rtcl-booking' ); ?>
                            </label>
                        </div>
                    </div>
				<?php } ?>
                <div class="rtcl-form-group rtcl-row">
                    <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                        <label for="_rtcl_booking_fee"
                               class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_fee_label', Functions::get_booking_fee_label() ); ?></label>
                    </div>
                    <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                        <input type="number" class="rtcl-form-control" id="_rtcl_booking_fee" name="_rtcl_booking_fee"
                               value="<?php echo esc_attr( $booking_fee ); ?>"/>
                    </div>
                </div>
				<?php if ( ! Functions::is_disable_booking_event_type() ) { ?>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-event-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_available_tickets"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_get_available_ticket_label',
									Functions::get_available_ticket_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="number" class="rtcl-form-control" id="_rtcl_available_tickets"
                                   name="_rtcl_available_tickets" value="<?php echo esc_attr( $availableTicket ); ?>"/>
                        </div>
                    </div>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-event-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_allowed_ticket"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_ticket_allowed_per_booking_label',
									Functions::get_ticket_allowed_per_booking_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="number" class="rtcl-form-control" id="_rtcl_booking_allowed_ticket"
                                   name="_rtcl_booking_allowed_ticket"
                                   value="<?php echo esc_attr( $ticketAllowed ); ?>"/>
                        </div>
                    </div>
				<?php } ?>
				<?php if ( ! Functions::is_disable_booking_service_type() ) { ?>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-service-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_max_guest"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_max_no_of_guest_label',
									Functions::get_maximum_number_of_guest_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="number" class="rtcl-form-control" id="_rtcl_booking_max_guest"
                                   name="_rtcl_booking_max_guest" value="<?php echo esc_attr( $max_guest ); ?>"/>
                        </div>
                    </div>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-service-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label class="rtcl-field-label">
								<?php echo apply_filters( 'rtcl_booking_schedule_label', Functions::get_service_schedule_label() ); ?>
                            </label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <div class="rtcl-booking-service-slots">
								<?php foreach ( $weekdays as $day_key => $day ) { ?>
                                    <div class="rtcl-booking-service-slot">
                                        <div class="rtcl-day-label"><?php echo esc_html( $day ); ?></div>
                                        <div class="rtcl-day-actions">
                                            <div class="action-item form-check open">
                                                <label class="form-check-label"
                                                       for="service-hours-open-<?php echo esc_html( $day_key ); ?>">
													<?php esc_html_e( "Open", "rtcl-booking" ); ?>
                                                </label>
                                            </div>
                                            <input type="checkbox" name="_rtcl_shs[<?php echo $day_key ?>][open]"
                                                   class="form-check-input check-open"
                                                   id="service-hours-open-<?php echo esc_html( $day_key ); ?>"<?php echo ! empty( $shs[ $day_key ]['open'] )
												? ' checked' : '' ?>>
                                            <div class="action-item form-check day-time-slot">
                                                <label class="form-check-label"
                                                       for="service-time-slot-open-<?php echo esc_html( $day_key ); ?>">
													<?php esc_html_e( "Want to set a time slot? (Default All day long)", "rtcl-booking" ); ?>
                                                </label>
                                            </div>
                                            <input type="checkbox" name="_rtcl_shs[<?php echo $day_key ?>][open]"
                                                   value="1"
                                                   class="form-check-input check-time-slot"
                                                   id="service-time-slot-open-<?php echo esc_html( $day_key ); ?>"
												<?php echo ! empty( $shs[ $day_key ]['times'] ) ? ' checked' : '' ?>>
                                            <div class="action-item time-slots">
												<?php
												$count = 0;
												if ( ! empty( $shs[ $day_key ]['times'] ) ) {
													foreach ( $shs[ $day_key ]['times'] as $time_id => $time ) {
														?>
                                                        <div class="time-slot">
                                                            <div class="time-slot-start" data-column="<?php esc_html_e( 'Start', 'rtcl-booking' ); ?>">
                                                                <input type="text"
                                                                       name="_rtcl_shs[<?php echo $day_key ?>][times][<?php echo $count ?>][start]"
                                                                       value="<?php echo esc_attr( Utility::formatTime( $time['start'], null, 'H:i' ) ) ?>"
                                                                       class="bhs-timepicker" autocomplete="off">
                                                            </div>
                                                            <div class="time-slot-end" data-column="<?php esc_html_e( 'End', 'rtcl-booking' ); ?>">
                                                                <input type="text"
                                                                       name="_rtcl_shs[<?php echo $day_key ?>][times][<?php echo $count ?>][end]"
                                                                       value="<?php echo esc_attr( Utility::formatTime( $time['end'], null, 'H:i' ) ) ?>"
                                                                       class="bhs-timepicker" autocomplete="off">
                                                            </div>
                                                            <div class="time-slot-action">
                                                                <i class="rtcl-bhs-btn rtcl-icon rtcl-icon-minus"></i>
                                                            </div>
                                                        </div>
														<?php $count ++;
													}
												}
												?>
                                                <div class="time-slot">
                                                    <div class="time-slot-start" data-column="<?php esc_html_e( 'Start', 'rtcl-booking' ); ?>">
                                                        <input type="text"
                                                               name="_rtcl_shs[<?php echo $day_key ?>][times][<?php echo $count ?>][start]"
                                                               class="bhs-timepicker" autocomplete="off">
                                                    </div>
                                                    <div class="time-slot-end" data-column="<?php esc_html_e( 'End', 'rtcl-booking' ); ?>">
                                                        <input type="text"
                                                               name="_rtcl_shs[<?php echo $day_key ?>][times][<?php echo $count ?>][end]"
                                                               class="bhs-timepicker" autocomplete="off">
                                                    </div>
                                                    <div class="time-slot-action">
                                                        <i class="rtcl-bhs-btn rtcl-icon rtcl-icon-plus"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
                            </div>
                        </div>
                    </div>
				<?php } ?>
				<?php if ( ! Functions::is_disable_booking_pre_order_type() ) {
					$date_options           = [
						'minDate'          => Utility::formatDate( date( 'Y-m-d' ) ),
						'timePicker'       => false,
						'timePicker24Hour' => false,
						'locale'           => Functions::date_range_picker_locale()
					];
					$available_date_options = array_merge( [ 'singleDatePicker' => true ], $date_options );
					?>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-pre-order-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_pre_order_date"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_pre_order_date_label',
									Functions::get_pre_order_date_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="text" class="rtcl-date rtcl-form-control" id="_rtcl_booking_pre_order_date"
                                   name="_rtcl_booking_pre_order_date"
                                   data-options="<?php echo esc_attr( wp_json_encode( $date_options ) ); ?>"
                                   value="<?php echo esc_attr( $pre_order_date ); ?>"/>
                        </div>
                    </div>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-pre-order-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_pre_order_available_date"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_pre_order_available_date_label',
									Functions::get_pre_order_availalbe_date_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="text" class="rtcl-date rtcl-form-control"
                                   id="_rtcl_booking_pre_order_available_date"
                                   name="_rtcl_booking_pre_order_available_date"
                                   data-options="<?php echo esc_attr( wp_json_encode( $available_date_options ) ); ?>"
                                   value="<?php echo esc_attr( $available_date ); ?>"/>
                        </div>
                    </div>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-pre-order-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_pre_order_available_volumn"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_pre_order_available_volumn_label',
									Functions::get_pre_order_availalbe_volumn_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="number" class="rtcl-form-control" id="_rtcl_booking_pre_order_available_volumn"
                                   name="_rtcl_booking_pre_order_available_volumn"
                                   value="<?php echo esc_attr( $available_volumn ); ?>"/>
                        </div>
                    </div>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-pre-order-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_pre_order_maximum"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_pre_order_maximum_label',
									Functions::get_pre_order_per_order_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="number" class="rtcl-form-control" id="_rtcl_booking_pre_order_maximum"
                                   name="_rtcl_booking_pre_order_maximum"
                                   value="<?php echo esc_attr( $max_order_volumn ); ?>"/>
                        </div>
                    </div>
				<?php } ?>
				<?php if ( ! Functions::is_disable_booking_rent_type() ) { ?>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-rent-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_rent_max_guest"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_max_no_of_guest_label',
									Functions::get_maximum_number_of_guest_label() ); ?></label>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <input type="number" class="rtcl-form-control" id="_rtcl_booking_rent_max_guest"
                                   name="_rtcl_booking_rent_max_guest" value="<?php echo esc_attr( $max_guest ); ?>"/>
                        </div>
                    </div>
                    <div class="rtcl-form-group rtcl-row rtcl-booking-rent-fields">
                        <div class="rtcl-col-12 <?php echo esc_attr( $labelColumn ); ?>">
                            <label for="_rtcl_booking_rent_date"
                                   class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_rent_date_label',
									__( 'Unavailable Date', 'rtcl-booking' ) ); ?></label>
                            <input type="hidden" id="rent_unavailable_date" name="_rtcl_booking_disable_date"
                                   value="<?php echo esc_attr( $unavailable_date ); ?>"/>
                        </div>
                        <div class="rtcl-col-12 <?php echo esc_attr( $inputColumn ); ?>">
                            <div id="rtclBookingCalendar"></div>
                        </div>
                    </div>
				<?php } ?>
                <div class="rtcl-form-checkbox-group rtcl-row">
                    <div class="rtcl-col-6 <?php echo esc_attr( $labelColumn ); ?>">
                        <label class="rtcl-field-label"><?php echo apply_filters( 'rtcl_booking_instant_booking_label',
								Functions::get_instant_booking_label() ); ?></label>
                    </div>
                    <div class="rtcl-col-6 <?php echo esc_attr( $inputColumn ); ?>">
                        <input type="checkbox" name="_rtcl_instant_booking" value="1"
                               id="_rtcl_instant_booking" <?php checked( $instantBooking, '1', true ); ?>/>
                        <label for="_rtcl_instant_booking"><?php esc_html_e( 'Enable', 'rtcl-booking' ); ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php do_action( 'rtcl_listing_form_template_booking_end', $post_id ); ?>
</div>
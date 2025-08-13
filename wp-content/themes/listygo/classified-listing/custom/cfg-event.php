<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;

global $listing;

$event_group_id = isset( RDTListygo::$options['custom_events_fields_types'] ) ? RDTListygo::$options['custom_events_fields_types'] : [];

if ( $event_group_id ) {
  $field_ids   = Functions::get_cf_ids_by_cfg_id( $event_group_id );
}

$date = '';

?>

<?php
if ( ! empty( $field_ids ) ) {
	foreach ( $field_ids as $single_field ) {
		$field = new RtclCFGField( $single_field );
		$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
		if ( ! $value || empty( $value ) ) {
			continue;
		}
			$countDown = 'has-countdown';
			$countdown_time  = strtotime( $value );

			$date = date( 'Y/m/d H:i:s', $countdown_time );
		?>
 	<li class="entry-countdown <?php echo  $countDown; ?>">
	   	<div class="countdown-content">
	      	<div data-countdown="<?php echo esc_attr( $date ); ?>" class="event-countdown"></div>
	   	</div> 
  	</li>
<?php
	}
}
?>
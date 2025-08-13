<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

global $listing;

$doctor_group_id = isset( RDTListygo::$options['custom_doctor_fields_types'] ) ? RDTListygo::$options['custom_doctor_fields_types'] : [];

if ( $doctor_group_id ) {
  $field_ids   = Functions::get_cf_ids_by_cfg_id( $doctor_group_id );
}

?>

<?php
if (  Functions::isEnableFb() && Listing_Functions::form_builder_custom_group_field_check()){
	$listing_form = $listing->getForm();
	$designation = get_post_meta( $listing->get_id(), 'doctor-designation', true );
	$meetTime = get_post_meta( $listing->get_id(), 'doctor-meet-time', true );
	$designationIcon = $listing_form->getFieldByName('doctor-designation');
	$meetTimeIcon = $listing_form->getFieldByName('doctor-meet-time');
    if (!empty($designation)) {
    ?>
    <li class="doctor-meta">
        <span class="rtcl-cat-icon rtcl-icon rtcl-icon- demo-icon <?php echo esc_attr( $designationIcon['icon']['class'] ); ?>"></span>
        <?php echo esc_html( $designation ); ?>
    </li>
    <?php } if (!empty($meetTime)) { ?>
        <li class="doctor-meta">
            <span class="rtcl-cat-icon rtcl-icon rtcl-icon- demo-icon <?php echo esc_attr( $meetTimeIcon['icon']['class'] ); ?>"></span>
	        <?php echo esc_html( $meetTime ); ?>
        </li>
	<?php } ?>
<?php } else {
    if ( ! empty( $field_ids ) ) {
        foreach ( $field_ids as $single_field ) {
            $field = new RtclCFGField( $single_field );
            $value = $field->getFormattedCustomFieldValue( $listing->get_id() );
            $icon = $field->getIconClass();
            if ( ! $value || empty( $value ) ) {
                continue;
            }
            if ( $icon ){
                $icon = sprintf( '<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $icon );
            }
            ?>
        <li class="doctor-meta <?php echo $value; ?>">
            <?php printf("%s %s", $icon, $value); ?>
        </li>
    <?php
        }
    }
}
?>
<?php 
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;
global $listing;

$event_group_id = isset( RDTListygo::$options['custom_events_fields_types'] ) ? RDTListygo::$options['custom_events_fields_types'] : [];

$date = '';
if ( $event_group_id ) {
  $field_ids   = Functions::get_cf_ids_by_cfg_id( $event_group_id );
}

if ( ! empty( $field_ids ) ) {
    foreach ( $field_ids as $single_field ) {
        $field = new RtclCFGField( $single_field );
        $value = $field->getFormattedCustomFieldValue( $listing->get_id() );
        if ( ! $value ) {
            continue;
        }
        $date = $value ? $value : '';
    }
}

$countdown = $date ? 'db-countdown dn-metalist' : 'dn-countdown';

?>

<div class="product-box <?php echo esc_attr( $countdown ); ?>">
    <?php
    /**
     * Hook: rtcl_before_listing_loop_item.
     *
     * @hooked rtcl_template_loop_product_link_open - 10
     */
    do_action( 'rtcl_before_listing_loop_item' );

    /**
     * Hook: rtcl_listing_loop_item.
     *
     * @hooked listing_thumbnail - 10
     */
    do_action( 'rtcl_listing_loop_item_start' );

    /**
     * Hook: rtcl_listing_loop_item.
     *
     * @hooked loop_item_wrap_start - 10
     * @hooked loop_item_listing_title - 20
     * @hooked loop_item_labels - 30
     * @hooked loop_item_listable_fields - 40 
     * @hooked loop_item_meta - 50
     * @hooked loop_item_excerpt - 60
     * @hooked loop_item_wrap_end - 100
     */
    do_action('rtcl_listing_loop_item');

    /**
     * Hook: rtcl_after_listing_loop_item.
     *
     * @hooked listing_loop_map_data - 50
     */
    do_action( 'rtcl_after_listing_loop_item' );
    ?>
</div>
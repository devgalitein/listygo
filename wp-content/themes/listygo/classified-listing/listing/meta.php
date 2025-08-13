<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;
use RtclMarketplace\Hooks\ActionHooks;
use radiustheme\listygo\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;

global $listing;

if ( ! class_exists( 'RtclPro' ) ) {
	return;
}

$show_listing_custom_field = RDTListygo::$options['show_listing_custom_fields'];

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );

$show_address = ! empty( $archive_settings['display_options'] ) && in_array( 'address', $archive_settings['display_options'] );
$show_phone   = ! empty( $archive_settings['display_options'] ) && in_array( 'phone', $archive_settings['display_options'] );
$show_website = ! empty( $archive_settings['display_options'] ) && in_array( 'website', $archive_settings['display_options'] );

$category_id = $icon = '';
$images      = $listing->get_images();

$location_type = Functions::location_type();
$address       = get_post_meta( $listing->get_id(), 'address', true );
$geo_address   = get_post_meta( $listing->get_id(), '_rtcl_geo_address', true );
$website       = str_replace( [ 'https://', 'http://' ], '', get_post_meta( $listing->get_id(), 'website', true ) );
$phone         = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url     = str_replace( ' ', '', $phone );
$listing_id    = $listing->get_id();

$generalSettings   = Functions::get_option( 'rtcl_general_settings' );
$appointment_label = ! empty( $generalSettings['listygo_doctor_appointment_label'] ) ? $generalSettings['listygo_doctor_appointment_label'] : '';

?>
    <ul class="contact-info">
		<?php
		$cats_ids = $listing->get_category_ids();
		foreach ( $cats_ids as $key => $value ) {
			$category_id = $value;
		}

		$doctor_cat_id = Listing_Functions::get_listing_doctor_category_id();
		$has_doctor    = Listing_Functions::listygo_selected_category_fields( $category_id, $doctor_cat_id );

		if ( $has_doctor && Listing_Functions::is_enable_doctor_listing() ) {
			Helper::get_custom_listing_template( 'cfg-doctor' );
		} else {

			if ( $listing->has_location() && $listing->can_show_location() ) { ?>
                <li class="meta-address">
					<?php
					echo Helper::map_icon();
					$listing->the_locations( true, true );
					?>
                </li>
			<?php }
			if ( ! empty( $address || $geo_address && $show_address ) ) { ?>
                <li class="meta-address">
					<?php
					echo Helper::map_icon();
					if ( $location_type == 'geo' && ! empty( $geo_address ) ) {
						echo esc_html( $geo_address );
					} else {
						echo esc_html( $address );
					}
					?>
                </li>
			<?php }
			if ( ! empty( $phone && $show_phone ) ) { ?>
                <li class="meta-phone"><a
                            href="tel:<?php echo esc_attr( $phone_url ); ?>"><?php echo Helper::phone_icon(); ?><?php echo esc_html( $phone ); ?></a>
                </li>
			<?php }
			if ( ! empty( $website && $show_website ) ) { ?>
                <li class="meta-website"><a
                            href="<?php echo esc_url( $website ); ?>"><?php echo Helper::globe_icon(); ?><?php echo esc_html( $website ); ?></a>
                </li>
			<?php }
			if ( $listing->can_show_date() ) { ?>
                <li class="meta-time"><i class="fa-solid fa-calendar-days"></i> <?php $listing->the_time(); ?></li>
			<?php }
			if ( $listing->can_show_views() ) { ?>
                <li class="meta-view">
					<?php echo Helper::view_icon(); ?>
					<?php echo sprintf( _n( "View: %s", "Views: %s", $listing->get_view_counts(), 'listygo' ), number_format_i18n( $listing->get_view_counts() ) ); ?>
                </li>
			<?php }
		} ?>
    </ul>

<?php
if ( $show_listing_custom_field ) {
	NewTemplateHooks::loop_item_listable_fields();
}
?>

    <ul class="meta-item">
		<?php if ( $listing->can_show_price() ) { ?>
            <li class="meta-price">
				<?php printf( "%s", $listing->get_price_html() ); ?>
            </li>
		<?php } ?>

        <li class="entry-meta">
            <ul>
				<?php
				if ( $has_doctor && Listing_Functions::is_enable_doctor_listing() ) {
					if ( ! empty( $phone && $show_phone ) ) { ?>
                        <li class="meta-phone doctor-list"><a href="tel:<?php echo esc_attr( $phone_url ); ?>"
                                                              data-bs-toggle="tooltip" data-bs-placement="top"
                                                              data-bs-trigger="hover"
                                                              title="<?php esc_attr_e( "Call for appointment", "listygo" ) ?>"><?php echo Helper::phone_icon(); ?><?php echo esc_html( $appointment_label ); ?></a>
                        </li>
					<?php }
				} else {
					if ( Fns::is_enable_quick_view() ) { ?>
                        <li class="meta-quick-view">
                            <div class="rtcl-quick-view rtcl-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                                 data-bs-trigger="hover" title="<?php esc_attr_e( "Quick view", "listygo" ) ?>"
                                 data-listing_id="<?php echo absint( $listing->get_id() ) ?>"><i
                                        class="rtcl-icon rtcl-icon-zoom-in"></i></div>
                        </li>
					<?php }
					if ( Fns::is_enable_compare() ) { ?>
                        <li class="meta-compare">
							<?php
							$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
							$selected_class = '';
							if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
								$selected_class = ' selected';
							}
							?>
                            <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                               data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                               title="<?php esc_attr_e( "Compare", "listygo" ) ?>"
                               data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
								<?php echo Helper::compare_icon(); ?>
                            </a>
                        </li>
					<?php }
					if ( Functions::is_enable_favourite() ) { ?>
                        <li class="tooltip-item meta-favourite"
                            data-bs-toggle="<?php esc_html_e( "Favourite", "listygo" ) ?>" data-bs-placement="top"
                            data-bs-trigger="hover" title="<?php esc_attr_e( "Favourite", "listygo" ) ?>">
							<?php echo Listing_Functions::get_favourites_link( $listing_id ); ?>
                        </li>
					<?php }
					$images              = $listing->get_images();
					$total_gallery_image = count( $images );
					$total_gallery_item  = $total_gallery_image;
					if ( $total_gallery_image ) {
						?>
                        <li class="archive-gallery-pswp meta-gallery">
                            <a href="#" class="rtcl-archive-gallery-trigger" data-bs-toggle="tooltip"
                               data-bs-placement="top" data-bs-trigger="hover"
                               title="<?php esc_attr_e( "Gallery", "listygo" ) ?>"><?php echo Helper::thumb_icon(); ?></a>
							<?php
							foreach ( $images as $index => $image ) :
								$img_url = wp_get_attachment_image_url( $image->ID, $size = 'full' );
								?>
                                <div class="rtcl-pswp-item">
                                    <a href="<?php echo esc_url( $img_url ); ?>">
										<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery' ); ?>
                                    </a>
                                </div>
							<?php endforeach;
							?>
                        </li>
					<?php }
				} ?>
            </ul>
        </li>

		<?php
		if ( Listing_Functions::is_enable_event_listing() ) {
			Helper::get_custom_listing_template( 'cfg-event' );
		}
		?>
    </ul>

<?php
//Css Loading
wp_enqueue_style( 'photoswipe-default-skin' );
// Js Loading
wp_enqueue_script( 'photoswipe' );
wp_enqueue_script( 'photoswipe-ui-default' );
wp_enqueue_script( 'rtcl-single-listing' );
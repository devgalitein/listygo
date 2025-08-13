<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */
use radiustheme\listygo\Listing_Functions;
use radiustheme\listygo\RDTListygo;
use Rtcl\Helpers\Functions;
global $listing;

$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
$sidebar_class = [
	'col-lg-4',
	'order-2',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
} elseif ( $sidebar_position == "bottom" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-4' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}
?>

<!-- Seller / User Information -->
<div id="sticky_sidebar" class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
    <div class="listing-sidebar">
		<?php 
			do_action( 'listygo_before_sidebar_ad' );
			if (RDTListygo::$options['listing_list_information']) {
				$listing->the_user_info();
			} 
			if (RDTListygo::$options['listing_list_timing']) {
				do_action('rtcl_single_listing_business_hours');
			} 
		?>
		<?php if ( is_active_sidebar( 'listing-single-sidebar' ) ): ?>
            <aside class="sidebar-widget">
				<?php dynamic_sidebar( 'listing-single-sidebar' ); ?>
            </aside>
		<?php 
			endif; 
			do_action( 'listygo_after_sidebar_ad' );
		?>
    </div>
</div>

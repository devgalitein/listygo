<?php
/**
 * The template for displaying product content in the single-rtcl_listing.php template
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/content-single-rtcl_listing.php.
 *
 * @package ClassifiedListing/Templates
 * @version 1.5.56
 */
use radiustheme\listygo\Listing_Functions;
use radiustheme\listygo\Helper;
use Rtcl\Helpers\Functions;

defined('ABSPATH') || exit;

global $listing;

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
$sidebar_position = Functions::get_option_item('rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right');
$sidebar_class = array(
    'col-lg-4',
    'order-2'
);
$content_class = array(
    'col-lg-8',
    'order-1',
    'listing-content'
);
if ($sidebar_position == "left") {
    $sidebar_class = array_diff($sidebar_class, array('order-2'));
    $sidebar_class[] = 'order-1';
    $content_class = array_diff($content_class, array('order-1'));
    $content_class[] = 'order-2';
} else if ($sidebar_position == "bottom") {
    $content_class = array_diff($content_class, array('col-lg-8'));
    $sidebar_class = array_diff($sidebar_class, array('col-lg-4'));
    $content_class[] = 'col-sm-12';
    $sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

$style = Listing_Functions::listing_single_style();

/**
 * Hook: rtcl_before_single_product.
 *
 * @hooked rtcl_print_notices - 10
 */
do_action( 'rtcl_before_single_listing' );

?>

<div id="rtcl-listing-<?php the_ID(); ?>" <?php Functions::listing_class( '', $listing ); ?>>
    <?php Helper::get_custom_listing_template( 'content-single-' . $style, true, compact( 'sidebar_position', 'content_class' ) ); ?>   
</div>

<?php do_action('rtcl_after_single_listing'); ?>

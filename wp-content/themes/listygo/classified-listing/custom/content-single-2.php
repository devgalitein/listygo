<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;

defined('ABSPATH') || exit;
global $listing;
?>

<?php Helper::get_custom_listing_template( 'listing-single-header-2' ); ?>

<!-- listing Details -->
<section class="listing-details listing-details--layout1 version2">
   <div class="listingDetails-main">
      <div class="container">
         <div class="row g-30">
            <div class="<?php echo esc_attr(implode(' ', $content_class)); ?>">
               <?php Helper::get_custom_listing_template( 'listing-single-main' ); ?>
            </div>
            <?php if ( in_array( $sidebar_position, array( 'left', 'right' ) ) ) : ?>
               <?php do_action('rtcl_single_listing_sidebar'); ?>
            <?php endif; ?>
         </div>
      </div>
      <?php if ( RDTListygo::$options['show_related_listing'] ) : ?>
      <div class="container">
         <div class="row">
             <div class="col-12 listing-title-wrap-enable">
				<?php $listing->the_related_listings(); ?>
             </div>
         </div>
      </div>
      <?php endif; ?>
   </div>
</section>
<!-- listing Details End -->
<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

if (!class_exists( 'RtclPro' )) return;

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

defined('ABSPATH') || exit;


$bodyLayout = RDTListygo::$options['listing_archive_box_layout'] ? RDTListygo::$options['listing_archive_box_layout'] : 'container';

//Map enabled
$has_map   = false;
if ( Helper::is_map_enabled() ) {
	global $rtcl_has_map_data;
	$rtcl_has_map_data = 1;
	$bodyLayout         = 'container-fluid has-map';
	$has_map           = true;
}

?>
<?php get_header(); ?>

<?php
if ( $has_map ) {
	get_template_part( 'templates/listing-map-archive' );
} else {
?>
<section class="listing-archvie-page bg--accent">
    <div class="<?php echo esc_attr( $bodyLayout ); ?>">
	    <?php if ( $has_map ) : ?>
        <div class="row">
            <div class="col-7 map-left-side-content">
			    <?php endif; ?>
                  <?php
                    /**
                      * Hook: rtcl_before_main_content.
                      *
                      * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
                      */
                    do_action( 'rtcl_before_main_content' );
                  ?>
                  <div class="row">
                    <div class="<?php Helper::listing_layout_class(); ?>">

                      <?php
                        Listing_Functions::listygo_taxonomy_archive_description();
                        do_action( 'listygo_listing_archive_before_content_ad' );
                      ?>

                      <?php if ( rtcl()->wp_query()->have_posts() ) { ?>
                        <div class="listing-box-grid">
                          <?php
                            /**
                             * Hook: rtcl_before_listing_loop.
                             *
                             * @hooked TemplateHooks::output_all_notices() - 10
                             * @hooked TemplateHooks::listings_actions - 20
                             *
                             */
                            do_action( 'rtcl_before_listing_loop' );

                            Functions::listing_loop_start();

                            /**
                            * Top listings
                            */
                            if ( Fns::is_enable_top_listings() ) {
                              do_action( 'rtcl_listing_loop_prepend_data' );
                            }

                            while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

                            /**
                             * Hook: rtcl_listing_loop.
                             */
                            do_action( 'rtcl_listing_loop' );

                            Functions::get_template_part( 'content', 'listing' );

                            endwhile;

                            Functions::listing_loop_end();
                          ?>
                        </div>
                            <?php
                              /**
                                * Hook: rtcl_after_listing_loop.
                                *
                                * @hooked TemplateHook::pagination() - 10
                                */
                                do_action( 'rtcl_after_listing_loop' );

                                } else {

                              /**
                               * Prepend listings
                               */
                                ob_start();
                                do_action('rtcl_listing_loop_prepend_data');
                                $listing_loop_prepend_data = ob_get_clean();
                                if ($listing_loop_prepend_data) {
                                  Functions::listing_loop_start();
                                  echo wp_kses_post($listing_loop_prepend_data);
                                  Functions::listing_loop_end();
                                }
                                /**
                                  * Hook: rtl_no_listings_found.
                                  *
                                  * @hooked no_listings_found - 10
                                  */
                                do_action('rtcl_no_listings_found');
                              }
                            ?>
                        <?php do_action( 'listygo_listing_archive_after_content_ad' ); ?>
                    </div>
                    <?php Helper::listing_sidebar(); ?>
                  </div>
                  <?php
                    /**
                      * Hook: rtcl_after_main_content.
                      *
                      * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
                      */
                    do_action( 'rtcl_after_main_content' );
                  ?>
	        <?php if ( $has_map ) : ?>
            </div>
            <div class="col-5 map-right-side-content">
                <div class="rtcl-search-map rtcl-archive-map-embed">
                    <div class="rtcl-map-view" data-map-type="search"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php } get_footer(); ?>
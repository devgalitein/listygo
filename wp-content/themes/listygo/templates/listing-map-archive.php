<?php
/**
 * Template Name: Listing Map
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.2
 */

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;

get_header();

?>
<div id="primary" class="product-grid listing-map-wrapper listing-map-archive-wrapper bg--accent">
  	<div class="container-fluid has-map full-width p0">
		<div class="custom-row">
			<div class="custom-column custom-column--one mb-30">
				<?php do_action( 'listygo_before_sidebar_ad' ); ?>
				<div class="filter-form">
					<?php 
						if (is_active_sidebar('listing-archive-sidebar')) {
							dynamic_sidebar( 'listing-archive-sidebar' );
						} else {
							echo do_action( 'listygo_listing_grid_search_filter' ); 
						}
					?>
				</div>
				<?php do_action( 'listygo_after_sidebar_ad' ); ?>
			</div>
			<div class="custom-column custom-column--two mb-30">
				<div class="listygo-listing-map-wrapper">
					<?php do_action( 'listygo_listing_archive_before_content_ad' ); ?>
                    <div class="row">
                        <div class="col-lg-7">
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
                        </div>
                        <div class="col-5 map-right-side-content">
                            <div class="rtcl-search-map rtcl-archive-map-embed">
                                <div class="rtcl-map-view" data-map-type="search"></div>
                            </div>
                        </div>
                    </div>

					<?php do_action( 'listygo_listing_archive_after_content_ad' ); ?>
				</div>
			</div>
		</div>
  	</div>
</div>

<?php get_footer(); ?>
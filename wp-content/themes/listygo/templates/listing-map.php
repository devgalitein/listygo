<?php
/**
 * Template Name: Listing Map
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.2
 */

get_header();

?>
<div id="primary" class="product-grid listing-map-wrapper bg--accent">
  	<div class="container-fluid full-width p0">
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
					<?php
						if ( get_the_content() ) {
							the_content();
						} else {
							echo do_shortcode( '[rtcl_listings map="1" columns="2" paginate="false" limit="6"]' );
						}
					?>
					<?php do_action( 'listygo_listing_archive_after_content_ad' ); ?>
				</div>
			</div>
		</div>
  	</div>
</div>

<?php get_footer(); ?>
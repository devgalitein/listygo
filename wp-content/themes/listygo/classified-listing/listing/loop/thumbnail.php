<?php
use Rtrs\Models\Review;
use RtclPro\Helpers\Fns;
use radiustheme\listygo\Helper;
use radiustheme\listygo\Listing_Functions;
use Rtcl\Controllers\Hooks\TemplateHooks;

global $listing;

if (!class_exists('RtclPro')) return;

// Rating
if( class_exists( Review::class ) ){
    $average_rating = Review::getAvgRatings( get_the_ID() );
    $rating_count   = Review::getTotalRatings(  get_the_ID() );
} else {
    $average_rating = $listing->get_average_rating();
    $rating_count   = $listing->get_rating_count();
}

?>
<div class="item-img">
    <div class="rt-categories">
	    <?php
            if ( $listing->has_category() && $listing->can_show_category() ) {
                $categories = $listing->get_categories();
	            foreach ( $categories as $category ) {
		    ?>
            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="category-list">
                <?php echo wp_kses_post( Listing_Functions::listygo_cat_icon( $category->term_id, 'icon' ) ); ?>
                &nbsp;<?php echo esc_html( $category->name ); ?>
            </a>
        <?php }
            }
        ?>
        <?php TemplateHooks::loop_item_badges(); ?>
    </div>

    <div class="open-close-location-status">
	    <?php Listing_Functions::business_open_close_status( $listing, false ); ?>
    </div>

    <div class="listing-thumb">
        <a href="<?php the_permalink(); ?>" class="rtcl-media grid-view-img bg--gradient-50"><?php echo wp_kses_post( $listing->get_the_thumbnail( 'rtcl-thumbnail' ) ); ?></a>
        <a href="<?php the_permalink(); ?>" class="rtcl-media list-view-img bg--gradient-50"><?php echo wp_kses_post( $listing->get_the_thumbnail( 'listygo-size-1' ) ); ?></a>
	</div>
    <?php
        Helper::get_listing_author_info( $listing );
    ?>
    <?php
    if ( $listing
    && Fns::is_enable_mark_as_sold()
    && Fns::is_mark_as_sold( $listing->get_id() )
    ) {
    echo '<span class="rtcl-sold-out">'
        . apply_filters( 'rtcl_sold_out_header_text',
        esc_html__( "Sold Out",
        'listygo' ) ) . '</span>';
    }
    ?>
</div>
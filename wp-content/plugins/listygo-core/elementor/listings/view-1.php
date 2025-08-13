<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/listing/view-1.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
global $listing;
$prefix = LISTYGO_CORE_THEME_PREFIX;
extract( $data );

$cat = $display_cat ? '' : 'dn-cat';
$status = $display_status ? '' : 'dn-status';
$location = $display_location ? '' : 'dn-loc';
$poster = $display_poster ? '' : 'dn-poster';
$rating = $display_rating ? '' : 'dn-rating';
$excerpt = $display_excerpt ? '' : 'dn-excerpt';
$badge = $display_badge ? '' : 'dn-badge';
$address = $display_address ? '' : 'dn-address';
$phone = $display_phone ? '' : 'dn-phone';
$website = $display_website ? '' : 'dn-website';
$date = $display_date ? '' : 'dn-date';
$view = $display_view ? '' : 'dn-view';
$price = $display_price ? '' : 'dn-price';
$metalist = $display_metalist ? 'db-metalist' : 'dn-metalist';
$countdown = $display_countdown ? 'db-countdown' : 'dn-countdown';
$qv = $display_qv ? '' : 'dn-qv';
$compare = $display_compare ? '' : 'dn-compare';
$fav = $display_fav ? '' : 'dn-fav';
$gallery = $display_gallery ? '' : 'dn-gallery';
$dclass = $cat.' '.$status.' '.$location.' '.$poster.' '.$rating.' '.$excerpt.' '.$badge.' '.$address.' '.$phone.' '.$website.' '.$date.' '.$view.' '.$price.' '.$metalist.' '.$countdown.' '.$qv.' '.$compare.' '.$fav.' '.$gallery;

$post_type = 'rtcl_listing';
$grid_query = null;
$args = array(
  'post_type'      => $post_type,
  'post_status'    => 'publish',
  'posts_per_page' => $number,
  'orderby'        => $orderby
);

if ( $query_type == 'loccat' ) {

  $tax_query = [];

  if(!empty($locations && $locations[0] != 0 )){
    $tax_query[] = array(
      'taxonomy' => 'rtcl_location',
      'field'    => 'id',
      'terms' => $locations
    );
  }
  if(!empty($terms && $terms[0] != 0 )){
    $tax_query[] = array(
      'taxonomy' => 'rtcl_category',
      'field'    => 'id',
      'terms' => $terms
    );
  }

  if(!empty($tax_query)){
    if(count($tax_query) > 1){
      $tax_query['relation'] = 'AND';
    }
    $args['tax_query'] = $tax_query;
  }
} elseif ( $query_type == 'meta_key' && !empty( $badges ) ) {
  $popular_threshold = (int) Functions::get_option_item( 'rtcl_moderation_settings', 'popular_listing_threshold', 0, 'number' );
  if ($badges == '_views') {
    $args['meta_query'][] = [
      'key'     => '_views',
      'compare' => '>=',
      'value' => $popular_threshold,
      'type' => 'NUMERIC',
    ];
  } else {
    $args['meta_query'][] = [
      'key'     => $badges,
      'value'   => '1',
      'compare' => 'in',
    ];
  }
} elseif ( $query_type == 'titles' && !empty( $postbytitle ) ) {
  $args['post__in'] = $postbytitle;
}

if ( $gutters == false ) {
  $gutters = 'g-0';
} else {
  $gutters = 'gutter-enable';
}

$grid_query = new \WP_Query( $args );

if ( $grid_query->have_posts() ) :

?>
<div class="row row-cols-lg-<?php echo esc_attr( $cols ); ?> row-cols-sm-2 row-cols-1 <?php echo esc_attr( $gutters ); ?> listing-shortcode listing-grid-shortcode rtcl-grid-view justify-content-center">
  <?php
    while ( $grid_query->have_posts() ) : $grid_query->the_post();

      if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
        $action_class = 'is-sold';
      } else {
        $action_class = '';
      }
  ?>
  <div class="col <?php echo esc_attr( $action_class ); ?>">
    <div class="product-box listygo-rating <?php echo esc_attr( $dclass ); ?>">
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

        // do_action( 'rtcl_listing_loop_item_end' );

        /**
         * Hook: rtcl_after_listing_loop_item.
         *
         * @hooked listing_loop_map_data - 50
         */
        do_action( 'rtcl_after_listing_loop_item' );
      ?>
    </div>
  </div>
  <?php endwhile; wp_reset_postdata(); ?>
</div>
<?php endif; ?>
<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/listing-single-box/class.php
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use Rtcl\Helpers\Link;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;
global $listing;
extract( $data );

$event_group_id = isset( RDTListygo::$options['custom_events_fields_types'] ) ? RDTListygo::$options['custom_events_fields_types'] : [];

if ( $event_group_id ) {
  $field_ids   = Functions::get_cf_ids_by_cfg_id( $event_group_id );
}

$date = '';

$detailOption = Functions::get_option_item( 'rtcl_moderation_settings', 'display_options_detail', [] );
$post_type = 'rtcl_listing';
$grid_query = null;
$args     = null;

if ( !empty( $postbytitle ) ) {
  $args = array(
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'taxonomy'       => 'rtcl_category',
    'p'              => $postbytitle
  );
} else {
  $args = array(
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => 1
  );
}

$grid_query = new \WP_Query( $args );

if ( $grid_query->have_posts() ) : 
    
  while ( $grid_query->have_posts() ) : $grid_query->the_post();

  global $listing;

  $address = get_post_meta( $listing->get_id(), 'address', true );
  $phone = get_post_meta( $listing->get_id(), 'phone', true );
  $eventdatetime = get_post_meta( $listing->get_id(), 'eventdatetime', true );
  if(!empty($eventdatetime)){
    $countdown_time  = strtotime( $eventdatetime );
    $date  = date('Y/m/d H:i:s', $countdown_time);
  }

  $video_urls       = [];
  if ( ! Functions::is_video_urls_disabled() ) {
    $video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
    $video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
  }

?>
<div class="event-block">
    <?php 
    if ( $enable_cat ){
      if ( $listing->has_category() ){
        $category = $listing->get_categories();
        $category = end( $category ); 
        $term_id = $category->term_id;
    ?> 
    <div class="event-block__tag">
      <a href="<?php echo esc_url( Link::get_category_page_link( $category ) ); ?>" class="tag__link">
        <?php echo wp_kses_post( Listing_Functions::listygo_cat_icon( $term_id, 'icon' ) ); ?>
        <?php echo esc_html( $category->name ); ?>
      </a>
    </div>
    <?php } }  ?> 
    <?php if ( $enable_video_btn && in_array('video_url', $detailOption) && ! empty( $video_urls ) ){ ?>
    <div class="video-btn-wrap">
      <a href="<?php echo esc_url($video_urls[0]); ?>" class="play-btn video-btn">
        <i class="fas fa-play"></i>
      </a>
    </div>
    <?php } ?>
    <h2 class="event-block__heading mb-15">
      <a class="event-block__heading__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <div class="event-block__meta mb-15">
      <ul>
        <?php if ( $enable_date ){ ?>
        <li>
          <i aria-hidden="true" class=" demo-icon rt-icon-03-calendar-check"></i>
          <span><?php $listing->the_time(); ?></span>
        </li>
        <?php } if ( $enable_address ){ ?>
        <li>
          <i aria-hidden="true" class="flaticon-placeholder"></i>
          <span><?php echo esc_html( $address ); ?></span>
        </li>
        <?php } ?>
      </ul>
    </div>
    
    <?php if ( $enable_author ){ ?>
    <div class="rtcl">
      <?php Helper::get_listing_author_info( $listing ); ?>
    </div>
    <?php } if (!empty($excerpt)) { ?>
    <p class="event-block__text mb-15">
      <?php echo Helper::listygo_excerpt( $excerpt ); ?>
    </p>
    <?php } if ( $enable_link ){ ?>
      <div class="btn-wrap">
          <a href="<?php the_permalink(); ?>" class="item-btn">
              <span class="btn__icon">
                  <?php echo Helper::btn_right_icon(); ?>
              </span>
              <?php echo esc_html( $data['btntext'] ); ?> 
              <span class="btn__icon">
                  <?php echo Helper::btn_right_icon(); ?>
              </span>
          </a>
      </div>
    <?php } ?>
    <?php
      if ( $enable_countdown && ! empty( $field_ids ) ) {
        foreach ( $field_ids as $single_field ) {
          $field = new RtclCFGField( $single_field );
          $value = $field->getFormattedCustomFieldValue( $listing->get_id() );
          if ( ! $value || empty( $value ) ) {
            continue;
          }
            $countdown_time  = strtotime( $value );
            $date = date( 'Y/m/d H:i:s', $countdown_time );
          ?>
          <div class="countdown-content">
            <div data-countdown="<?php echo esc_attr( $date ); ?>" class="event-countdown"></div>
          </div>  
      <?php
        }
      }
    ?>

</div>
<?php endwhile; wp_reset_postdata(); ?>
<?php endif; ?>
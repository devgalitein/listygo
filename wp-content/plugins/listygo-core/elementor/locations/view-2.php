<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/locations/view-2.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Rtcl\Helpers\Link;
$prefix = LISTYGO_CORE_THEME_PREFIX;
extract( $data );

if ( $gutters == false ) {
  $gutters = 'no-gutters';
} else {
  $gutters = 'gutter-enable';
}
$class = $data['display_count'] ? 'rtin-has-count' : '';
?>

<div class="listing-box-wrap listing-shortcode location-shortcode listing-slider-shortcode">
  <div class="swiper-container1 swiper">
    <?php if ($slider_arrow) { ?>
      <div class="sliderNav sliderNav--style1">
        <div class="sliderNav__btn swiper-button-prev"></div>
        <div class="sliderNav__btn swiper-button-next"></div>
      </div>
    <?php } ?>
    <div class="swiper-wrapper" data-carousel-options="<?php echo esc_attr( $data['swiper_data'] );?>">
      <?php
      foreach ( $data['locations'] as $item ) {
        $term = get_term( $item['location_name'], 'rtcl_location' );
        if ( $term && !is_wp_error( $term ) ) {
          $item['title']     = $term->name;
          $item['count']     = $this->rt_term_post_count( $term->term_id );
          $item['permalink'] = Link::get_location_page_link( $term );
        } else {
          $item['permalink'] = '';
          $item['title'] = esc_html__( 'Please Select a Location and Background', 'listygo-core' );
          $item['count'] = 0;
          $item['display_count'] = $data['display_count'] = false;
        }
        $count_html = sprintf( _nx( '%s Listing', '%s Listings', $item['count'], 'Number of Listings', 'listygo-core' ), number_format_i18n( $item['count'] ) );
      ?>
      <div class="swiper-slide">
        <?php if ( $data['style'] == 1 ) { ?>
        <div class="category-box-layout2 common-style">
          <figure class="item-thumb bg--gradient-60" data-bg-image="<?php echo esc_url($item['bgimg']['url']); ?>">
          </figure>
          <div class="item-content">
            <h3 class="item-title"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
            <?php if ( $data['display_count'] ): ?>
            <div class="listing-number"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $count_html ); ?></a></div>
            <?php endif; ?>
          </div>
        </div>
        <?php } elseif ( $data['style'] == 2 ) {
          $link_start = $data['enable_link'] ? '<a href="'.$item['permalink'].'">' : '';
          $link_end   = $data['enable_link'] ? '</a>' : '';
        ?>
        <div class="feature-box-layout1 common-style">
          <?php echo wp_kses_post( $link_start ); ?>
            <div class="item-img inline-element show-on-scroll" data-bg-image="<?php echo esc_url($item['bgimg']['url']); ?>">
            </div>
            <div class="item-content">
              <?php if ( $data['display_count'] ): ?>
              <p><?php echo esc_html( $count_html ); ?></p>
              <?php endif; ?>
              <h3 class="item-title"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
            </div>
            <?php echo wp_kses_post( $link_end ); ?>
        </div>
        <?php } elseif ( $data['style'] == 3 ) { ?>
        <div class="location-box-layout3 <?php echo esc_attr( $data['view'] ); ?> common-style">
        <?php if ( $data['view'] == 'grid' ) { ?>
          <div class="item-img">
            <?php echo wp_get_attachment_image( $item['bgimg']['id'], 'full' ); ?>
          </div>
          <div class="img-location">
            <div class="location-count">
              <h3 class="item-title"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
              <?php if ( $data['display_count'] ): ?>
                <div class="listing-number"><?php echo esc_html( $count_html ); ?></div>
              <?php endif; ?>
            </div>
             <?php if ( $data['enable_link'] ): ?>
                <div class="btn-box btn-box2"><a href="<?php echo esc_url( $item['permalink']); ?>"><i class="fa-solid fa-arrow-right"></i></a></div>
              <?php endif; ?>
          </div>
        <?php } else { ?>
        <div class="img-location">
          <div class="item-img" data-bg-image="<?php echo esc_url($item['bgimg']['url']); ?>"></div>
          <div class="location-count">
            <h3 class="item-title"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
            <?php if ( $data['display_count'] ): ?>
              <div class="listing-number"><?php echo esc_html( $count_html ); ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php if ( $data['enable_link'] ): ?>
          <div class="btn-box btn-box2"><a href="<?php echo esc_url( $item['permalink']); ?>"><i class="fa-solid fa-arrow-right"></i></a></div>
        <?php endif; ?>
        <?php } ?>
      </div>
        <?php } else { ?>
        <div class="category-box-layout2 common-style">
          <figure class="item-thumb bg--gradient-60" data-bg-image="<?php echo esc_url($item['bgimg']['url']); ?>"></figure>
          <div class="item-content">
            <h3 class="item-title"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
            <?php if ( $data['display_count'] ): ?>
            <div class="listing-number"><a href="<?php echo esc_url( $item['permalink']); ?>"><?php echo esc_html( $count_html ); ?></a></div>
            <?php endif; ?>
          </div>
        </div>
      <?php } ?>
      </div>
      <?php } ?>
    </div>
    <?php if ($slider_dots) { ?>
      <div class="swiper-pagination"></div>
    <?php } ?>
  </div>
</div>
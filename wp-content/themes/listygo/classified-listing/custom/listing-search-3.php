<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5.3
 */

namespace radiustheme\listygo;

if (!class_exists('RtclPro')) return;

use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options as RtclOptions;
use RtclPro\Helpers\Fns;
use RtclPro\Helpers\Options;
use radiustheme\listygo\RDTListygo;

$classes = 'form-shortcode';

$loc_text = esc_attr__('Select Location', 'listygo');
$brand_text = esc_attr__('Select Brand', 'listygo');
$model_text = esc_attr__('Select Model', 'listygo');


$selected_location = $selected_category = false;

if (get_query_var('rtcl_location') && $location = get_term_by('slug', get_query_var('rtcl_location'), rtcl()->location)) {
  $selected_location = $location;
}

if (get_query_var('listygo_car_category') && $category = get_term_by('slug', get_query_var('listygo_car_category'), rtcl()->category)) {
  $selected_category = $category;
}

$orderby = strtolower(Functions::get_option_item('rtcl_archive_listing_settings', 'taxonomy_orderby', 'name'));
$order = strtoupper(Functions::get_option_item('rtcl_archive_listing_settings', 'taxonomy_order', 'ASC'));
$style = '';
if (!empty($search_style)) {
  $style = $search_style;
} else {
  $style = RDTListygo::$options['listing_banner_search_style'];
}

$arr = [];
$banner_search_args = RDTListygo::$options['listing_banner_search_items'];
foreach ($banner_search_args as $key => $value) {
  $arr[$value] = $value;
}
$arr2 = [];
foreach ($listingTypes as $key => $value) {
  $arr2[$value] = $value;
}

$search_items = array_filter($arr);
if (!empty($search_items)) { ?>

  <form action="<?php echo esc_url(Functions::get_filter_form_url()); ?>" class="form-vertical rtcl-widget-search-form rtcl-search-inline-form listygo-listing-search-form rtin-style-<?php echo esc_attr($style); ?>">
    <div class="inner-form-wrap">
      <div class="search-form-top">
        <div class="form-title">
          <h3>Search Your Listing</h3>
        </div>
        <div class="form-type">
          <div class="ad-type-wrapper search-radio-check">
            <ul class="list-inline">
              <?php
              $listing_types = Functions::get_listing_types();
              $listing_types = empty($listing_types) ? [] : $listing_types;
              if (!empty($arr2)) {
                $listing_types = array_intersect_key($listing_types, $arr2);
              }
              foreach ($listing_types as $key => $listing_type) :
              ?>
                <li>
                  <label for="<?php echo esc_attr($key); ?>">
                    <input class="sr-only-m" type="radio" name="<?php echo esc_attr('filters[ad_type]') ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($key); ?>" id="outline">
                    <span><?php echo esc_html($listing_type); ?></span>
                  </label>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <?php if (isset($search_items['location']) &&  $search_items['location'] == 'location' || isset($search_items['category']) &&  $search_items['category'] == 'category' || isset($search_items['keyword']) &&  $search_items['keyword'] == 'keyword') { ?>
        <div class="location-category-item">

          <!-- Select Brand -->
          <?php if (isset($search_items['category']) &&  $search_items['category'] == 'category') { ?>
            <div class="search-cats">
              <div class="form-group">
                <?php if ($style == 'suggestion') : ?>
                  <div class="rtcl-search-input-button rtin-category">
                    <input type="text" data-type="category" class="rtcl-autocomplete rtin-category" placeholder="<?php echo esc_attr('Write Category Name', 'listygo'); ?>" value="<?php echo esc_attr($selected_category ? $selected_category->slug : ''); ?>">
                    <input type="hidden" name="rtcl_category" value="<?php echo esc_attr($selected_category ? $selected_category->slug : ''); ?>">
                  </div>
                <?php elseif ($style == 'standard') : ?>
                  <div class="rtcl-search-input-button rtin-category">
                    <?php
                    $cat_args = array(
                      'show_option_none'  => $brand_text,
                      'option_none_value' => '',
                      'taxonomy'          => 'listygo_car_category',
                      'name'              => 'listygo_car_category',
                      'id'                => 'rtcl-category-search-' . wp_rand(),
                      'class'             => 'select2 form-control rtcl-category-search',
                      'selected'          => get_query_var('listygo_car_category'),
                      'hierarchical'      => true,
                      'value_field'       => 'slug',
                      'depth'             => Functions::get_category_depth_limit(),
                      'orderby'           => $orderby,
                      'order'             => ('DESC' === $order) ? 'DESC' : 'ASC',
                      'show_count'        => false,
                      'hide_empty'        => false,
                    );
                    if ('_rtcl_order' === $orderby) {
                      $args['orderby'] = 'meta_value_num';
                      $args['meta_key'] = '_rtcl_order';
                    }
                    wp_dropdown_categories($cat_args);
                    ?>
                  </div>
                <?php elseif ($style == 'dependency') : ?>
                  <div class="rtcl-search-input-button rtin-category">
                    <?php
                    Functions::dropdown_terms(array(
                      'show_option_none'  => $brand_text,
                      'option_none_value' => -1,
                      'taxonomy'          => 'listygo_car_category',
                      'name'              => 'c',
                      'class'             => 'select2 form-control rtcl-category-search',
                      'selected'          => $selected_category ? $selected_category->term_id : 0
                    ));
                    ?>
                  </div>
                <?php else : ?>
                  <div class="rtcl-search-input-button rtcl-search-input-category rtin-category">
                    <span class="search-input-label category-name">
                      <?php echo esc_html($selected_category ? $selected_category->name : $brand_text); ?>
                    </span>
                    <input type="hidden" name="rtcl_category" class="rtcl-term-field" value="<?php echo esc_attr($selected_category ? $selected_category->slug : ''); ?>">
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php } ?>

          <!-- Select Model -->
          <?php if (isset($search_items['category']) &&  $search_items['category'] == 'category') { ?>
            <div class="search-cats">
              <div class="form-group">
                <?php if ($style == 'suggestion') : ?>
                  <div class="rtcl-search-input-button rtin-category">
                    <input type="text" data-type="category" class="rtcl-autocomplete rtin-category" placeholder="<?php echo esc_attr('Write Category Name', 'listygo'); ?>" value="<?php echo esc_attr($selected_category ? $selected_category->slug : ''); ?>">
                    <input type="hidden" name="rtcl_category" value="<?php echo esc_attr($selected_category ? $selected_category->slug : ''); ?>">
                  </div>
                <?php elseif ($style == 'standard') : ?>
                  <div class="rtcl-search-input-button rtin-category">
                    <?php
                    $cat_args = array(
                      'show_option_none'  => $model_text,
                      'option_none_value' => '',
                      'taxonomy'          => 'listygo_car_category',
                      'name'              => 'listygo_car_category',
                      'id'                => 'rtcl-category-search-' . wp_rand(),
                      'class'             => 'select2 form-control rtcl-category-search',
                      'selected'          => get_query_var('listygo_car_category'),
                      'hierarchical'      => false,
                      'value_field'       => 'slug',
                      'depth'             => 1,
                      'orderby'           => $orderby,
                      'order'             => ('DESC' === $order) ? 'DESC' : 'ASC',
                      'show_count'        => false,
                      'hide_empty'        => false,
                    );
                    if ('_rtcl_order' === $orderby) {
                      $args['orderby'] = 'meta_value_num';
                      $args['meta_key'] = '_rtcl_order';
                    }
                    wp_dropdown_categories($cat_args);
                    ?>
                  </div>
                <?php elseif ($style == 'dependency') : ?>
                  <div class="rtcl-search-input-button rtin-category">
                    <?php
                    Functions::dropdown_terms(array(
                      'show_option_none'  => $model_text,
                      'option_none_value' => -1,
                      'taxonomy'          => 'listygo_car_category',
                      'name'              => 'c',
                      'class'             => 'select2 form-control rtcl-category-search',
                      'selected'          => $selected_category ? $selected_category->term_id : 0
                    ));
                    ?>
                  </div>
                <?php else : ?>
                  <div class="rtcl-search-input-button rtcl-search-input-category rtin-category">
                    <span class="search-input-label category-name">
                      <?php echo esc_html($selected_category ? $selected_category->name : $model_text); ?>
                    </span>
                    <input type="hidden" name="rtcl_category" class="rtcl-term-field" value="<?php echo esc_attr($selected_category ? $selected_category->slug : ''); ?>">
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php } ?>

          <?php if (isset($search_items['location']) &&  $search_items['location'] == 'location') { ?>
            <?php if ('local' === Functions::location_type()) { ?>
              <div class="search-location">
                <div class="form-group">
                  <?php if ($style == 'suggestion') : ?>
                    <div class="rtcl-search-input-button rtin-location">
                      <input type="text" data-type="location" class="rtcl-autocomplete rtcl-location" placeholder="<?php echo esc_attr('Write Location Name', 'listygo'); ?>" value="<?php echo esc_attr($selected_location ? $selected_location->name : ''); ?>">
                      <input type="hidden" name="rtcl_location" value="<?php echo esc_attr($selected_location ? $selected_location->slug : ''); ?>">
                    </div>
                  <?php elseif ($style == 'standard') : ?>
                    <div class="rtcl-search-input-button rtin-location">
                      <?php
                      $loc_args = array(
                        'show_option_none'  => $loc_text,
                        'option_none_value' => '',
                        'taxonomy'          => rtcl()->location,
                        'name'              => 'rtcl_location',
                        'id'                => 'rtcl-location-search-' . wp_rand(),
                        'class'             => 'select2 form-control rtcl-location-search',
                        'selected'          => get_query_var('rtcl_location'),
                        'hierarchical'      => true,
                        'value_field'       => 'slug',
                        'depth'             => Functions::get_location_depth_limit(),
                        'orderby'           => $orderby,
                        'order'             => ('DESC' === $order) ? 'DESC' : 'ASC',
                        'show_count'        => false,
                        'hide_empty'        => false,
                      );
                      if ('_rtcl_order' === $orderby) {
                        $args['orderby'] = 'meta_value_num';
                        $args['meta_key'] = '_rtcl_order';
                      }
                      wp_dropdown_categories($loc_args);
                      ?>
                    </div>
                  <?php elseif ($style == 'dependency') : ?>
                    <div class="rtcl-search-input-button rtin-location">
                      <?php
                      Functions::dropdown_terms(array(
                        'show_option_none' => $loc_text,
                        'taxonomy'         => rtcl()->location,
                        'name'             => 'l',
                        'class'            => 'select2 form-control',
                        'selected'         => $selected_location ? $selected_location->term_id : 0
                      ));
                      ?>
                    </div>
                  <?php else : ?>
                    <div class="rtcl-search-input-button rtcl-search-input-location rtin-location">
                      <span class="search-input-label location-name">
                        <?php echo esc_html($selected_location ? $selected_location->name : $loc_text); ?>
                      </span>
                      <input type="hidden" class="rtcl-term-field" name="rtcl_location" value="<?php echo esc_attr($selected_location ? $selected_location->slug : ''); ?>">
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php } else { ?>
              <?php
              //$rs_data = Options::radius_search_options(); 
              $rs_data = RtclOptions::radius_search_options();
              ?>
              <div class="search-location">
                <div class="form-group">
                  <div class="rtcl-search-input-button rtin-location">
                    <input type="text" name="geo_address" autocomplete="off" value="<?php echo !empty($_GET['geo_address']) ? esc_attr($_GET['geo_address']) : '' ?>" placeholder="<?php esc_attr_e("Select a location", "listygo"); ?>" class="form-control rtcl-geo-address-input" />
                    <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                    <input type="hidden" class="latitude" name="center_lat" value="<?php echo !empty($_GET['center_lat']) ? esc_attr($_GET['center_lat']) : '' ?>">
                    <input type="hidden" class="longitude" name="center_lng" value="<?php echo !empty($_GET['center_lng']) ? esc_attr($_GET['center_lng']) : '' ?>">
                  </div>
                </div>
              </div>
              <?php if (!empty(RDTListygo::$options['listing_search_items']['radius'])) { ?>
                <div class="rt-mmm">
                  <div class="form-group">
                    <div class="rtcl-search-input-button rtin-radius">
                      <input type="number" class="form-control" name="distance" value="<?php echo !empty($_GET['distance']) ? absint($_GET['distance']) : 30 ?>" placeholder="<?php esc_attr_e("Radius", "listygo"); ?>">
                    </div>
                  </div>
                </div>
              <?php } else { ?>
                <input type="hidden" class="distance" name="distance" value="30">
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
      <div class="d-flex-items price-btn-items">
        <!-- Price fields -->
        <div class="d-flex-item price-item">
          <div class="form-group">
            <input type="text" name="filters[price][min]" class="form-control" placeholder="<?php esc_attr_e('Min Price', 'listygo'); ?>" value="<?php if (isset($_GET['filters']['price'])) { echo esc_attr($_GET['filters']['price']['min']); } ?>">
          </div>
        </div>
        <div class="d-flex-item price-item">
          <!-- Price fields -->
          <div class="form-group">
            <input type="text" name="filters[price][max]" class="form-control" placeholder="<?php esc_attr_e('Max price', 'listygo'); ?>" value="<?php if (isset($_GET['filters']['price'])) { echo esc_attr($_GET['filters']['price']['max']); } ?>">
          </div>
        </div>
        <div class="rtin-btn-holder d-flex-item">
          <div class="form-group">
            <button type="submit" class="rtin-search-btn rdtheme-button-1 btn">
              <?php esc_html_e('Search', 'listygo'); ?>
              <img src="<?php echo get_template_directory_uri() . '/assets/img/theme/search.svg'; ?>" alt="<?php esc_attr_e('Search', 'listygo'); ?>">
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php } ?>
<?php
/**
 * @var array $data
 * @var bool  $can_search_by_keyword
 * @since   1.0
 * @version 1.2.4
 *
 * @author  RadiusTheme
 * @package
 */

 /**

 */

namespace radiustheme\Listygo_Core;

use Rtcl\Helpers\Text;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;
use Rtcl\Resources\Options as RtclOptions;
use radiustheme\listygo\Listing_Functions;

$currency = Functions::get_currency_symbol();
extract( $data );
$cat_text = esc_attr__( 'All Categories', 'listygo' );
$loc_text = esc_attr__( 'All Cities', 'listygo' );

$selected_location = $selected_category = false;

if (get_query_var('rtcl_location') && $location = get_term_by('slug', get_query_var('rtcl_location'), rtcl()->location)) {
  $selected_location = $location;
}

if (get_query_var('rtcl_category') && $category = get_term_by('slug', get_query_var('rtcl_category'), rtcl()->category)) {
  $selected_category = $category;
}

$orderby = strtolower(Functions::get_option_item('rtcl_archive_listing_settings', 'taxonomy_orderby', 'name'));
$order = strtoupper(Functions::get_option_item('rtcl_archive_listing_settings', 'taxonomy_order', 'ASC'));

$should_ajax_cf = 'rtcl-category-ajax';
$cfID           = '0';
if ( ! empty( $rtcl_cf_form ) && Functions::isEnableFb() ) {
	$should_ajax_cf .= ' rtcl-fb-enable';
	$cfID           = $rtcl_cf_form;
}


?>

<div class="rtcl rtcl-widget-search listing-inner">
    <form action="" class="advance-search-form rtcl-widget-search-form is-preloader">
		<?php $permalink_structure = get_option( 'permalink_structure' ); ?>
		<?php if ( ! $permalink_structure ) : ?>
            <input type="hidden" name="post_type" value="rtcl_listing">
		<?php endif; ?>
        <div class="search-box">
			<?php if ( $can_search_by_keyword ): ?>
                <div class="search-item search-keyword">
                    <div class="input-group">
                        <input type="text" data-type="listing" name="s" class="rtcl-autocomplete form-control"
                               placeholder="<?php esc_attr_e( 'What are you looking for?', 'listygo' ); ?>"
                               value="<?php if ( isset( $_GET['s'] ) ) {
							       echo esc_attr( $_GET['s'] );
						       } ?>"/>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_category ): ?>
                <div class="search-item search-select rtin-category <?php echo esc_attr( $should_ajax_cf ) ?>" data-fb-id="<?php echo esc_attr( $cfID ); ?>">
					<?php if ($style === 'suggestion') { ?>
                        <input type="text" data-type="category"
                               class="rtcl-autocomplete rtin-category form-control"
                               placeholder="<?php echo esc_html(Text::get_select_category_text()) ?>"
                               value="<?php echo $selected_category ? $selected_category->name : '' ?>">
                        <input type="hidden" name="rtcl_location"
                               value="<?php echo $selected_category ? $selected_category->slug : '' ?>">
                        <?php
                    } elseif ($style === 'standard') {
                        $cat_args = [
                            'show_option_none'  => Text::get_select_category_text(),
                            'option_none_value' => '',
                            'taxonomy'          => rtcl()->category,
                            'name'              => 'rtcl_category',
                            'id'                => 'rtcl-category-search-' . wp_rand(),
                            'class'             => 'form-control rtcl-category-search',
                            'selected'          => get_query_var('rtcl_category'),
                            'hierarchical'      => true,
                            'value_field'       => 'slug',
                            'depth'             => Functions::get_category_depth_limit(),
                            'orderby'           => $orderby,
                            'order'             => ('DESC' === $order) ? 'DESC' : 'ASC',
                            'show_count'        => false,
                            'hide_empty'        => false,
                        ];
                        if ('_rtcl_order' === $orderby) {
                            $args['orderby'] = 'meta_value_num';
                            $args['meta_key'] = '_rtcl_order';
                        }
                        wp_dropdown_categories($cat_args);
                    } elseif ($style === 'dependency') {
                        Functions::dropdown_terms([
                            'show_option_none'  => Text::get_select_category_text(),
                            'option_none_value' => -1,
                            'taxonomy'          => rtcl()->category,
                            'name'              => 'c',
                            'class'             => 'form-control rtcl-category-search',
                            'selected'          => $selected_category ? $selected_category->term_id : 0,
                        ]);
                    } elseif ($style == 'popup') { ?>
                        <div class="rtcl-search-input-button rtcl-search-input-category btn btn-primary">
                            <span class="search-input-label category-name">
                                <?php echo $selected_category ? esc_html($selected_category->name) : esc_html(Text::get_select_category_text()); ?>
                            </span>
                            <input type="hidden" name="rtcl_category" class="rtcl-term-field"
                                   value="<?php echo $selected_category ? esc_attr($selected_category->slug) : '' ?>">
                        </div>
                    <?php } ?>
				</div>
			<?php endif; ?>

			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && $can_search_by_location && 'local' === Functions::location_type() ): ?>
               <div class="search-item search-select rtin-location">
					<?php if ($style === 'suggestion') { ?>
                        <input type="text" data-type="location"
                               class="rtcl-autocomplete rtcl-location form-control"
                               placeholder="<?php echo esc_html(Text::get_select_location_text()) ?>"
                               value="<?php echo $selected_location ? $selected_location->name : '' ?>">
                        <input type="hidden" name="rtcl_location"
                               value="<?php echo $selected_location ? $selected_location->slug : '' ?>">
                        <?php
                    } elseif ($style === 'standard') {
                        $args = [
                            'show_option_none'  => Text::get_select_location_text(),
                            'option_none_value' => '',
                            'taxonomy'          => rtcl()->location,
                            'name'              => 'rtcl_location',
                            'id'                => 'rtcl-location-search-' . wp_rand(),
                            'class'             => 'form-control rtcl-location-search',
                            'selected'          => get_query_var('rtcl_location'),
                            'hierarchical'      => true,
                            'value_field'       => 'slug',
                            'depth'             => Functions::get_location_depth_limit(),
                            'orderby'           => $orderby,
                            'order'             => ('DESC' === $order) ? 'DESC' : 'ASC',
                            'show_count'        => false,
                            'hide_empty'        => false,
                        ];
                        if ('_rtcl_order' === $orderby) {
                            $args['orderby'] = 'meta_value_num';
                            $args['meta_key'] = '_rtcl_order';
                        }
                        wp_dropdown_categories($args);
                    } elseif ($style === 'dependency') {
                        Functions::dropdown_terms([
                            'show_option_none' => Text::get_select_location_text(),
                            'taxonomy'         => rtcl()->location,
                            'name'             => 'l',
                            'class'            => 'form-control',
                            'selected'         => $selected_location ? $selected_location->term_id : 0,
                        ]);
                    } elseif ($style == 'popup') {
                        ?>
                        <div class="rtcl-search-input-button rtcl-search-input-location btn btn-primary">
                            <span class="search-input-label location-name">
                                <?php echo $selected_location ? esc_html($selected_location->name) : esc_html(Text::get_select_location_text()) ?>
                            </span>
                            <input type="hidden" class="rtcl-term-field" name="rtcl_location"
                                   value="<?php echo $selected_location ? esc_attr($selected_location->slug) : '' ?>">
                        </div>
                        <?php
                    } ?>
                </div>
			<?php endif; ?>

			<?php if ( $can_search_by_radius_search ): ?>
               <div class="distance-search">
					<?php $rs_data = RtclOptions::radius_search_options(); ?>
					<div class="form-group ws-item ws-location">
						<h4 class="radius-serarch-title"><?php echo esc_html(Text::get_select_location_text()); ?></h4>
						<div class="rtcl-geo-address-field">
							<input type="text" name="geo_address" autocomplete="off"
								value="<?php echo !empty($_GET['geo_address']) ? esc_attr($_GET['geo_address']) : '' ?>"
								placeholder="<?php esc_attr_e("Select a location", "listygo") ?>"
								class="form-control rtcl-geo-address-input"/>
							<i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
							<input type="hidden" class="latitude" name="center_lat"
								value="<?php echo !empty($_GET['center_lat']) ? esc_attr($_GET['center_lat']) : '' ?>">
							<input type="hidden" class="longitude" name="center_lng"
								value="<?php echo !empty($_GET['center_lng']) ? esc_attr($_GET['center_lng']) : '' ?>">
						</div>
						<div class="rtcl-range-slider-field">
							<div class="rtcl-range-label">
								<h4 class="advanced-serarch-title"><?php esc_html_e( 'Radius', 'listygo' ); ?></h4> 
								<span class="rtcl-range-value">
									<?php echo !empty($_GET['distance']) ? absint($_GET['distance']) : 30 ?> 
								</span> 
								<span class="rtcl-range-units">
									<?php in_array($rs_data['units'], ['km', 'kilometers']) ? esc_html_e("km", "listygo") : esc_html_e( "Miles", "listygo" ); ?>
								</span>
							</div>
							<input type="range" class="form-control-range rtcl-range-slider-input" name="distance" min="0" max="<?php echo absint($rs_data['max_distance']) ?>" value="<?php echo isset($_GET['distance']) ? $_GET['distance'] : $rs_data['default_distance']; ?>">
						</div>
					</div>
				</div>
			<?php endif ?>

			<?php if ( $can_search_by_price ): ?>

            <div class="search-item price-item-box">
                <?php if (RDTListygo::$options['listing_price_search_type'] == 'range') { ?>
                    <div class="price-range">
                        <h4 class="price-filter-title"><?php esc_html_e('Price Range', 'listygo'); ?></h4>
                        <?php
                        $currency  = Functions::get_currency_symbol();
                        $data_form = '';
                        $data_to   = '';
                        if ( isset( $_GET['filters']['price']['min'] ) ) {
                            $data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
                        }
                        if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
                            $data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
                        }
                        $min_price = RDTListygo::$options['listing_widget_min_price'];
                        $max_price = RDTListygo::$options['listing_widget_max_price'];
                        ?>
                        <input type="number"
                            class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
                            data-min="<?php echo isset( $min_price ) && ! empty( $min_price ) ? $min_price : 0; ?>"
                            data-max="<?php echo isset( $max_price ) && ! empty( $max_price ) ? $max_price : 80000; ?>"
                            data-prefix="<?php echo esc_html( $currency ) ?>"/>
                        <input type="hidden" class="min-volumn" name="filters[price][min]"
                            value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
                                echo absint( $_GET['filters']['price']['min'] );
                            } ?>">
                        <input type="hidden" class="max-volumn" name="filters[price][max]"
                            value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
                                echo absint( $_GET['filters']['price']['max'] );
                            } ?>">
                    </div>
                <?php } else { ?>
                    <!-- Price fields -->
                    <div class="form-group">
                        <h4 class="price-filter-title"><?php esc_html_e('Price Range', 'listygo'); ?></h4>
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <input type="text" name="filters[price][min]" class="form-control"
                                    placeholder="<?php esc_attr_e('min', 'listygo'); ?>"
                                    value="<?php if (isset($_GET['filters']['price'])) {
                                        echo esc_attr($_GET['filters']['price']['min']);
                                    } ?>">
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <input type="text" name="filters[price][max]" class="form-control"
                                    placeholder="<?php esc_attr_e('max', 'listygo'); ?>"
                                    value="<?php if (isset($_GET['filters']['price'])) {
                                        echo esc_attr($_GET['filters']['price']['max']);
                                    } ?>">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

			<?php endif; ?>

			<?php

			if ( $can_search_by_custom_field ): ?>
               <div class="search-box rtcl_cf_by_category_html">
	               <?php
						$group_ids = '';
						$group_ids = RDTListygo::$options['custom_fields_search_items'] ?? [];

                        if ( ! is_array( $group_ids ) ) {
							$group_ids = explode(',', $group_ids);
						}

						$group_ids = array_filter( $group_ids );

						if ( count( $group_ids ) ) {
							foreach ( $group_ids as $group_id ) {
								$field_ids  = Functions::get_cf_ids_by_cfg_id( $group_id );

							if ( ! empty( $field_ids ) ) {
						?>
							<div class="form-cf-items expanded-wrap">
								<div class="cf-inner">
									<?php
										$args      = [
											'is_searchable'     => true,
											'exclude_group_ids' => $group_id,
										];
                                        $args['meta_query'][] = [
                                            'key'   => '_listable',
                                            'value' => 1,
                                        ];
										$fields_id = Functions::get_cf_ids( $args );
										$html = '';
										foreach ( $fields_id as $field ) {
											$field_label = new RtclCFGField( $field );
											if ( $field_label->getLabel() ){
												$html .= '<h4 class="advanced-serarch-check-title">'.$field_label->getLabel().'</h4>';
											}
											$html .= Listing_Functions::get_advanced_search_field_html( $field );
										}
										Functions::print_html( $html, true );
									?>
								</div>
							</div>
						<?php }
						}
					}
					?>
				</div>
			<?php endif; ?>

            <div class="search-item search-btn mb-0">
               <button type="submit" class="submit-btn"><?php echo Helper::search_icon(); ?><?php esc_html_e( 'Search', 'listygo' ); ?></button>
            </div>
        </div>
    </form>
</div>
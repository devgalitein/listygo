<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTheme;
use radiustheme\listygo\Helper;
use WP_Query;

trait CustomQueryTrait {

  /**
   * Portfolio Elements
   * ==========================================================================
   */
  public static function portfolio_query() {
    $cpt = LISTYGO_THEME_CPT_PREFIX;
    $filter_enable = RDTheme::$options['portfolio_filter'];
    $cats_slug = RDTheme::$options['portfolio_cats_slug'];
    $terms = explode(',', $cats_slug);


    if ( $filter_enable == 1 && !empty( $terms ) ) {
      $args = array(
        'post_type'      => "{$cpt}_portfolio",
        'posts_per_page' => RDTheme::$options['portfolio_archive_number'],
        'orderby'        => RDTheme::$options['portfolio_orderby'],
        'tax_query' => array(
          array(
            'taxonomy' => $cpt.'_portfolio_category',
            'field' => 'id',
            'terms' => $terms
          )
        ),
      );
    } else {
      $args = array(
        'post_type'      => "{$cpt}_portfolio",
        'posts_per_page' => RDTheme::$options['portfolio_archive_number'],
        'orderby'        => RDTheme::$options['portfolio_orderby'],
      );
    }

    if ( get_query_var('paged') ) {
      $args['paged'] = get_query_var('paged');
    }
    elseif ( get_query_var('page') ) {
      $args['paged'] = get_query_var('page');
    }
    else {
      $args['paged'] = 1;
    }

    $query = new WP_Query( $args );

    return $query;
  }

  public static function get_portfolio_cat_slug(){
    $cpt = LISTYGO_THEME_CPT_PREFIX;
    $cat_slug = '';
    $terms = get_the_terms( get_the_ID(), "{$cpt}_portfolio_category" );          
    if ( $terms && ! is_wp_error( $terms ) ) {
      $slug_list = array();
      foreach ( $terms as $term ) {
        $slug_list[] = $term->slug;
      }        
      $cat_slug = join( " ", $slug_list );
    }
    return $cat_slug;
  }

  /**
   * Getting Custome taxanomy for portfolio - category- single service
   */
  public static function listing_categories_slug() {
    $cpt = LISTYGO_THEME_CPT_PREFIX;
    if (class_exists('RtclPro')) {
      $terms = get_terms( "rtcl_category" );
      if(!empty($terms)){
        $category_links = array();
        foreach ($terms as $key => $value) {
          $category_links[$value->term_id] = $value->name;
        }
        return $category_links;
      }
    }
  }

  /**
   * Getting Custome taxanomy for Car - categories
   */
  public static function car_categories_slug() {
    if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) && Listing_Functions::is_enable_car_listing()) {
      $terms = get_terms( "listygo_car_category" );
      if(!empty($terms)){
        $car_category = array();
        foreach ($terms as $key => $value) {
          $car_category[$value->term_id] = $value->name;
        }
        return $car_category;
      }
    }
  }

  /**
   * Global Code
   * ==========================================================================
   */
  static function generate_array_iterator_postfix( $array, $index, $postfix = ', ' ) {
    $length = count($array);
    if ($length) {
      $last_index = $length - 1;
      return $index < $last_index ? $postfix : '';
    }
  }

  public static function wp_set_temp_query( $query ){
    global $wp_query;
    $temp = $wp_query;
    $wp_query = $query;
    return $temp;
  }

  public static function wp_reset_temp_query( $temp ){
    global $wp_query;
    $wp_query = $temp;
    wp_reset_postdata();
  }

  public static function set_order_orderby($rd_field){
    $orderby = '';
    $order   = 'DESC';
    switch ( RDTheme::$options[ $rd_field ] ) {
      case 'title':
      case 'menu_order':
      $orderby = RDTheme::$options[ $rd_field ];
      $order = 'ASC';
      break;
    }
    if ( $orderby ) {
      $args['orderby'] = $orderby;
      $args['order'] = $order;
    }
    return $args;
  } 

  public static function set_args_orderby( $args, $rd_field ){
    $orderby = '';
    $order   = 'DESC';
    switch ( RDTheme::$options[ $rd_field ] ) {
      case 'title':
      case 'menu_order':
      $orderby = RDTheme::$options[ $rd_field ];
      $order = 'ASC';
      break;
    }
    if ( $orderby ) {
      $args['orderby'] = $orderby;
      $args['order'] = $order;
    }
    return $args;
  }

  /**
   * for setting up pagination for custom post type
   * we have to pass paged key
   */
  public static function set_args_paged ($args) {
    if ( get_query_var('paged') ) {
      $args['paged'] = get_query_var('paged');
    }
    elseif ( get_query_var('page') ) {
      $args['paged'] = get_query_var('page');
    }
    else {
      $args['paged'] = 1;
    }
    return $args;
  }

}

<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use \RT_Posts;
use radiustheme\listygo\Listing_Functions;

if ( !class_exists( 'RT_Posts' ) ) {
	return;
}

$prefix = LISTYGO_CORE_THEME_PREFIX;
$post_types = [];
$taxonomies = [];

// if ( Listing_Functions::is_enable_car_listing() ){
// 	$taxonomies = array(
// 		"{$prefix}_car_category" => array(
// 			'title'        => __( 'Car Brand', 'listygo-core' ),
// 			'plural_title' => __( 'Car Brands', 'listygo-core' ),
// 			'post_types'   => "rtcl_listing",
// 			'show_in_rest'   => true,
// 		),
// 	);
// }

$Posts = RT_Posts::getInstance();
$Posts->add_post_types( $post_types );
$Posts->add_taxonomies( $taxonomies );
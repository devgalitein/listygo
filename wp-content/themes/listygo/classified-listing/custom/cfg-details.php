<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

global $listing;

$group_ids = isset( RDTListygo::$options['custom_fields_list_types'] ) ? RDTListygo::$options['custom_fields_list_types'] : [];

if ( ! is_array( $group_ids ) ) {
	$group_ids = explode(',', $group_ids);
}

$group_ids = array_filter( $group_ids );

if( empty( $group_ids ) ){
	return;
}

Listing_Functions::listygo_listing_details_cfg( $group_ids );
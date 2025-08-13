<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

global $listing;

$group_ids = isset( RDTListygo::$options['custom_fields_group_types'] ) ? RDTListygo::$options['custom_fields_group_types'] : [];

if( empty( $group_ids ) ){
	return;
}

$group_ids = array_filter( $group_ids );

Listing_Functions::listygo_listing_details_cfg(  $group_ids );
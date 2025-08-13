<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Models\Listing;
use WP_REST_Request;

class Api_Setup {

	private $namespace = 'rtcl/v1';
	private $route = '/(?P<id>\d+)';
	private $restaurant_field_value = 'listygo_food_list';
	private $doctor_field_value = 'listygo_doctor_chamber';
	private $logo_field_value = 'listing_logo_img';

	public function __construct() {
		add_filter( 'rtcl_rest_api_listing_data',[ $this, 'listygo_single_data' ] );
		add_action( 'rtcl_rest_listing_form_after_save_or_update', [ $this, 'listygo_form_data_save' ], 10, 2 );
	}

	public function listygo_single_data( $data ) {
		$post_id = $data['listing_id'];

		$data['listygo'] = [];

		$main_category = Helper::get_parent_category( $post_id, 'rtcl_category' );
		$data['listygo']['listing_category'] = $main_category;

		// Restaurant
		$restaurnat = Helper::push_food_image_api( $post_id, $this->restaurant_field_value );
		if ( ! empty( $restaurnat ) ) {
			$data['listygo']['is_restaurant'] = true;
			$data['listygo']['listygo_food_list'] = $restaurnat;
		} else {
			$data['listygo']['is_restaurant'] = false;
		}

		// Doctor
		$doctor = Helper::push_chamber_image_api( $post_id, $this->doctor_field_value );
		if ( ! empty( $doctor ) ) {
			$data['listygo']['listygo_doctor_chamber_list'] = $doctor;
		}

		// logo data
		$logo_id = get_post_meta( $post_id, $this->logo_field_value, true );
		if ( $logo_id ) {
			$data['listygo']['listygo_listing_logo'] = wp_get_attachment_image_url( $logo_id, 'full' );
		}
		return $data;
	}

	/**
	 * @param Listing         $listing
	 * @param WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function listygo_form_data_save( $listing, $request ) {

        error_log(print_r([
            $request->get_file_params(),
            $request->get_params()
            ], true). "\n", 3, ABSPATH ."wp-content/logs.log");


		/* = Restaurant Food List = */
		Helper::process_restaurant_data( $request, $listing );

		/* = Doctor Chamber List = */
		Helper::process_chambers_data( $request, $listing );

		/* = Listing Logo = */
		Helper::process_listing_logo( $request, $listing );

	}

}

new Api_Setup;
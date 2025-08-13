<?php
/**
 * Main MetaOptions  Class.
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  review-schema-pro
 *
 * @since    1.0.0
 */

namespace Rtrsp\Controllers\Admin\Meta;

use Rtrsp\Traits\SingletonTrait;

/**
 * MetaOptions class
 */
class MetaOptions {
	private $prefix = 'rtrs_';
	/**
	 *
	 */
	use SingletonTrait;

	/**
	 * Init function.
	 *
	 * @return void
	 */
	public function init() {
		// Tab option filter .
		add_filter( 'rtrs_section_schema_fields', [ $this, 'section_schema_fields' ] );
	}
	/**
	 * Undocumented function
	 *
	 * @param array $schema_fields schema fields.
	 * @return array
	 */
	public function section_schema_fields( $schema_fields ) {
		$schema_fields[] = $this->tv_series_schema_fields();
		$schema_fields[] = $this->CollectionPageSchemaFields();
		$schema_fields[] = $this->vacationRentalSchemaFields();
		$schema_fields[] = $this->vehicleListingSchemaFields();
		return $schema_fields;
	}
	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public function tv_series_schema_fields() {
		$prefix = 'rtrs_';
		$id     = 'tv_series';
		// Collection page.
		$settings_fields = [
			'type'        => 'group',
			'name'        => $this->prefix . $id . '_schema',
			'id'          => 'rtrs-' . $id . '_schema',
			'holderClass' => 'rtrs-hidden rtrs-schema-field',
			'label'       => esc_html__( 'TV Series schema', 'review-schema-pro' ),
			'fields'      => [
				[
					'id'     => $id,
					'type'   => 'auto-fill',
					'is_pro' => true,
					'label'  => esc_html__( 'Auto Fill', 'review-schema-pro' ),
				],
				[
					'name'    => 'status',
					'type'    => 'tab',
					'label'   => esc_html__( 'Status', 'review-schema-pro' ),
					'default' => 'show',
					'options' => [
						'show' => esc_html__( 'Show', 'review-schema-pro' ),
						'hide' => esc_html__( 'Hide', 'review-schema-pro' ),
					],
				],
				[
					'name'     => 'name',
					'type'     => 'text',
					'label'    => esc_html__( 'Name', 'review-schema-pro' ),
					'required' => true,
				],
				[
					'name'  => 'image',
					'type'  => 'image',
					'label' => esc_html__( 'Image', 'review-schema-pro' ),
				],
				[
					'name'    => 'author-type',
					'type'    => 'select',
					'label'   => esc_html__( 'Author Type', 'review-schema-pro' ),
					'empty'   => esc_html__( 'Select one', 'review-schema-pro' ),
					'options' => [
						'Person'       => esc_html__( 'Person', 'review-schema-pro' ),
						'Organization' => esc_html__( 'Organization', 'review-schema-pro' ),
					],
				],
				[
					'name'        => 'author',
					'type'        => 'text',
					'label'       => esc_html__( 'Author', 'review-schema-pro' ),
					'placeholder' => esc_html__( 'Author Name', 'review-schema-pro' ),
				],
				[
					'type'   => 'group',
					'name'   => 'actor',
					'label'  => esc_html__( 'Actor\'s', 'review-schema-pro' ),
					'fields' => [
						[
							'name'  => 'actor-name',
							'type'  => 'text',
							'label' => esc_html__( 'Actor Name', 'review-schema-pro' ),
						],
					],
				],
				[
					'name'     => 'description',
					'type'     => 'textarea',
					'label'    => esc_html__( 'Description', 'review-schema-pro' ),
					'required' => true,
				],
				[
					'type'   => 'group',
					'name'   => 'season',
					'label'  => esc_html__( 'Season', 'review-schema-pro' ),
					'fields' => [
						[
							'name'  => 'season-name',
							'type'  => 'text',
							'label' => esc_html__( 'Season Name', 'review-schema-pro' ),
						],
						[
							'name'  => 'date-published',
							'type'  => 'text',
							'label' => esc_html__( 'Published Date', 'review-schema-pro' ),
							'class' => 'rtrs-date',
							'desc'  => esc_html__( 'Like this: 2021-08-25 14:20:00', 'review-schema-pro' ),
						],
						[
							'name'  => 'number-of-episodes',
							'type'  => 'number',
							'label' => esc_html__( 'Number Of Episodes', 'review-schema-pro' ),
						],
						[
							'name'  => 'episode-name',
							'type'  => 'text',
							'label' => esc_html__( 'Episode Name', 'review-schema-pro' ),
						],
						[
							'name'  => 'episode-number',
							'type'  => 'number',
							'label' => esc_html__( 'Episode Number', 'review-schema-pro' ),
						],
					],
				],
			],
		];
		return $settings_fields;
	}
	/**
	 * Collection Page Schema Fields
	 */
	public function CollectionPageSchemaFields() {
		$author_url = '';
		$author     = get_userdata( get_current_user_id() );
		if ( $author && is_object( $author ) ) {
			$author_url = $author->user_url;
		}
		$id = 'collection_page';
		// Collection page.
		$settings_fields = [
			'type'        => 'group',
			'name'        => $this->prefix . $id . '_schema',
			'id'          => 'rtrs-' . $id . '_schema',
			'holderClass' => 'rtrs-hidden rtrs-schema-field',
			'label'       => esc_html__( 'Collection Page', 'review-schema-pro' ),
			'fields'      => [
				[
					'id'     => $id,
					'type'   => 'auto-fill',
					'is_pro' => true,
					'label'  => esc_html__( 'Auto Fill', 'review-schema-pro' ),
				],
				[
					'name'    => 'status',
					'type'    => 'tab',
					'label'   => esc_html__( 'Status', 'review-schema-pro' ),
					'default' => 'show',
					'options' => [
						'show' => esc_html__( 'Show', 'review-schema-pro' ),
						'hide' => esc_html__( 'Hide', 'review-schema-pro' ),
					],
				],
				[
					'name'     => 'name',
					'type'     => 'text',
					'label'    => esc_html__( 'Headline', 'review-schema-pro' ),
					'desc'     => esc_html__( 'Title', 'review-schema-pro' ),
					'required' => true,
				],
				[
					'name'  => 'webpage_url',
					'label' => __( 'Webpage url', 'review-schema-pro' ),
					'type'  => 'url',
					'desc'  => __( 'Web Page Url', 'review-schema-pro' ),
				],
				[
					'name'  => 'description',
					'label' => __( 'Description', 'review-schema-pro' ),
					'type'  => 'textarea',
					'desc'  => __( 'Short description. New line is not supported.', 'review-schema-pro' ),
				],
				[
					'name'     => 'image',
					'label'    => __( 'Image', 'review-schema-pro' ),
					'type'     => 'image',
					'required' => true,
				],
				[
					'type'   => 'group',
					'name'   => 'itempage',
					'label'  => esc_html__( 'Item page', 'review-schema-pro' ),
					'fields' => [
						[
							'name'     => 'itempage-name',
							'type'     => 'text',
							'label'    => esc_html__( 'Name', 'review-schema-pro' ),
							'desc'     => esc_html__( 'Title', 'review-schema-pro' ),
							'required' => true,
						],
						[
							'name'  => 'itempage-description',
							'type'  => 'textarea',
							'label' => esc_html__( 'Description', 'review-schema-pro' ),
						],
						[
							'name'  => 'mainEntityOfPage',
							'label' => __( 'Main Entity Page url', 'review-schema-pro' ),
							'type'  => 'url',
						],
					],
				],
			],
		];
		return $settings_fields;
	}

	/**
	 * Vacation Rental Schema Fields
	 */
	public function vacationRentalSchemaFields() {
		// Vacation Rental.
		$id              = 'vacation_rental';
		$settings_fields = [
			'type'        => 'group',
			'name'        => $this->prefix . $id . '_schema',
			'id'          => 'rtrs-' . $id . '_schema',
			'holderClass' => 'rtrs-hidden rtrs-schema-field',
			'label'       => esc_html__( 'Vacation Rental', 'review-schema-pro' ),
			'fields'      => [
				[
					'id'     => $id,
					'type'   => 'auto-fill',
					'is_pro' => true,
					'label'  => esc_html__( 'Auto Fill', 'review-schema-pro' ),
				],
				[
					'name'    => 'status',
					'type'    => 'tab',
					'label'   => esc_html__( 'Status', 'review-schema-pro' ),
					'default' => 'show',
					'options' => [
						'show' => esc_html__( 'Show', 'review-schema-pro' ),
						'hide' => esc_html__( 'Hide', 'review-schema-pro' ),
					],
				],
				[
					'name'     => 'name',
					'type'     => 'text',
					'label'    => esc_html__( 'Headline', 'review-schema-pro' ),
					'desc'     => esc_html__( 'Title', 'review-schema-pro' ),
					'required' => true,
				],
				[
					'name'        => 'additionalType',
					'label'       => __( 'Additional Type', 'review-schema-pro' ),
					'type'        => 'text',
					'recommended' => true,
					'desc'        => __( 'Default: HolidayVillageRental. Check Docs: ', 'review-schema-pro' ) . '<a target="_blank" href="https://developers.google.com/search/docs/appearance/structured-data/vacation-rental">Vacation Rental</a>',
				],
				[
					'name'        => 'description',
					'label'       => __( 'Description', 'review-schema-pro' ),
					'type'        => 'text',
					'recommended' => true,
				],
				[
					'name'  => 'priceRange',
					'label' => __( 'Price Range', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'Ex: $200 - $500 per night', 'review-schema-pro' ),
				],
				[
					'name'  => 'telephone',
					'label' => __( 'Telephone', 'review-schema-pro' ),
					'type'  => 'text',
				],
				[
					'name'     => 'identifier',
					'label'    => __( 'Identifier', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'name'     => 'latitude',
					'label'    => __( 'Latitude', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'name'     => 'longitude',
					'label'    => __( 'Longitude', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
				],
				// 'servesCuisine'   => 'American',
				[
					'name'  => 'containsPlace',
					'label' => __( 'Contains Place', 'review-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'    => 'containsPlaceType',
					'label'   => __( 'Contains Place Additional Type', 'review-schema-pro' ),
					'type'    => 'select',
					'empty'   => 'Select one',
					'options' => [
						'EntirePlace' => 'EntirePlace',
						'PrivateRoom' => 'PrivateRoom',
						'SharedRoom'  => 'SharedRoom',
					],
				],
				[
					'name'  => 'occupancy',
					'label' => __( 'Occupancy Value', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
					'desc'  => __( 'The numerical value of guests allowed to stay at the vacation rental listing.', 'review-schema-pro' ),
				],
				[
					'name'  => 'numberOfBathroomsTotal',
					'label' => __( 'The Total Number Of Bathrooms', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
				],
				[
					'name'  => 'numberOfBedrooms',
					'label' => __( 'The Total number Number Of Bedrooms', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
				],
				[
					'name'  => 'numberOfRooms',
					'label' => __( 'The Total number Number Of Rooms', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
				],
				[
					'name'  => 'floorSize',
					'label' => __( 'Floor Size', 'review-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'  => 'floorSizeValue',
					'label' => __( 'Value', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
				],
				[
					'name'  => 'unitCode',
					'label' => __( 'UnitCode', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'EX: MTK', 'review-schema-pro' ),
				],
				// beds.
				[
					'name'      => 'beds',
					'required'  => true,
					'type'      => 'group',
					'label'     => esc_html__( 'Bed', 'review-schema-pro' ),
					'duplicate' => true,
					'fields'    => [
						[
							'name'  => 'review_heading',
							'label' => __( 'Bed', 'review-schema-pro' ),
							'type'  => 'heading',
						],
						[
							'name'  => 'numberOfBeds',
							'label' => __( 'Number Of Beds', 'review-schema-pro' ),
							'type'  => 'number',
							'attr'  => 'step="any" min="1"',
						],
						[
							'name'  => 'typeOfBed',
							'label' => __( 'Bed Type', 'review-schema-pro' ),
							'type'  => 'text',
							'desc'  => __( 'Check Details For typeOfBed: ', 'review-schema-pro' ) . '<a target="_blank" href="https://developers.google.com/search/docs/appearance/structured-data/vacation-rental">Vacation Rental </a>',
						],
					],
				],
				// beds.
				 [
					 'name'      => 'amenityFeature',
					 'required'  => true,
					 'type'      => 'group',
					 'label'     => esc_html__( 'Amenity Feature', 'review-schema-pro' ),
					 'duplicate' => true,
					 'fields'    => [
						 [
							 'name'  => 'review_heading',
							 'label' => __( 'Amenity Feature', 'review-schema-pro' ),
							 'type'  => 'heading',
						 ],
						 [
							 'name'  => 'feature',
							 'label' => __( 'Feature', 'review-schema-pro' ),
							 'type'  => 'text',
							 'desc'  => __( 'Check Details For amenityFeature: ', 'review-schema-pro' ) . '<a target="_blank" href="https://developers.google.com/search/docs/appearance/structured-data/vacation-rental">Vacation Rental </a>',
						 ],
						 [
							 'name'  => 'value',
							 'label' => __( 'Available?', 'review-schema-pro' ),
							 'type'  => 'checkbox',
							 'desc'  => __( 'checkmark (✓) if the feature is available', 'review-schema-pro' ),
						 ],
					 ],
				 ],

				// Aggregate Rating.
				[
					'name'  => 'aggregate_rating_section',
					'label' => __( 'Aggregate Rating', 'review-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'  => 'aggregate_ratingValue',
					'label' => __( 'Rating value', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
					'desc'  => __( 'A numerical quality rating for the item.', 'review-schema-pro' ),
				],
				[
					'name'  => 'aggregate_bestRating',
					'label' => __( 'Best rating', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
					'desc'  => __( 'A numerical quality rating for the item.', 'review-schema-pro' ),
				],
				[
					'name'  => 'aggregate_worstRating',
					'label' => __( 'Worst rating', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
					'desc'  => __( 'A numerical quality rating for the item.', 'review-schema-pro' ),
				],
				[
					'name'  => 'aggregate_ratingCount',
					'label' => __( 'Rating Count', 'review-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
					'desc'  => __( 'A numerical quality rating for the item.', 'review-schema-pro' ),
				],

				// review.
				[
					'name'      => 'reviews',
					'required'  => true,
					'type'      => 'group',
					'label'     => esc_html__( 'Reviews', 'review-schema-pro' ),
					'duplicate' => true,
					'fields'    => [
						[
							'name'  => 'review_heading',
							'label' => __( 'Review', 'review-schema-pro' ),
							'type'  => 'heading',
						],
						[
							'name'     => 'author',
							'label'    => __( 'Author', 'review-schema-pro' ),
							'type'     => 'text',
							'required' => true,
						],
						[
							'name'  => 'ratingValue',
							'label' => __( 'Rating value', 'review-schema-pro' ),
							'type'  => 'number',
							'attr'  => 'step="any"',
							'desc'  => __( 'A numerical quality rating for the item.', 'review-schema-pro' ),
						],
						[
							'name'  => 'bestRating',
							'label' => __( 'Best rating', 'review-schema-pro' ),
							'type'  => 'number',
							'attr'  => 'step="any"',
							'desc'  => __( 'The highest value allowed in this rating system.', 'review-schema-pro' ),
						],
						[
							'name'     => 'datePublished',
							'label'    => __( 'Published date', 'review-schema-pro' ),
							'type'     => 'text',
							'class'    => 'kcseo-date',
							'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'review-schema-pro' ),
							'required' => true,
						],
					],
				],
				// Address.
				[
					'name'  => 'PostalAddress',
					'label' => __( 'Address', 'review-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'  => 'streetAddress',
					'label' => __( 'Street Address', 'review-schema-pro' ),
					'type'  => 'text',
				],
				[
					'name'  => 'addressLocality',
					'label' => __( 'Address Locality', 'review-schema-pro' ),
					'type'  => 'text',
				],
				[
					'name'  => 'region',
					'label' => __( 'Region', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'Ex: CA ', 'review-schema-pro' ),
				],
				[
					'name'  => 'postalCode',
					'label' => __( 'Postal Code', 'review-schema-pro' ),
					'type'  => 'text',
				],
				[
					'name'  => 'addressCountry',
					'label' => __( 'Country', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'Ex: US ', 'review-schema-pro' ),
				],
				[
					'name'  => 'images_heading',
					'label' => __( 'Images', 'review-schema-pro' ),
					'type'  => 'heading',
					'desc'  => __( 'One or more images of the listing. The listing must have a minimum of 8 photos (at least 1 image of each of the following: bedroom, bathroom, and common area)', 'review-schema-pro' ),
				],
				[
					'name'      => 'images',
					'required'  => true,
					'type'      => 'group',
					'label'     => esc_html__( 'Images', 'review-schema-pro' ),
					'duplicate' => true,
					'fields'    => [
						[
							'name'  => 'image',
							'label' => __( 'Upload Images', 'review-schema-pro' ),
							'type'  => 'image',
						],
					],
				],

			],
		];
		return $settings_fields;
	}

	/**
	 * Vehicle Listing Schema Fields
	 *
	 * @return array
	 */
	public function vehicleListingSchemaFields() {
		// Vacation Rental.
		$id              = 'vehicle_listing';
		$settings_fields = [
			'type'        => 'group',
			'name'        => $this->prefix . $id . '_schema',
			'id'          => 'rtrs-' . $id . '_schema',
			'holderClass' => 'rtrs-hidden rtrs-schema-field',
			'label'       => esc_html__( 'Vehicle Listing', 'review-schema-pro' ),
			'fields'      => [
				[
					'id'     => $id,
					'type'   => 'auto-fill',
					'is_pro' => true,
					'label'  => esc_html__( 'Auto Fill', 'review-schema-pro' ),
				],
				[
					'name'    => 'status',
					'type'    => 'tab',
					'label'   => esc_html__( 'Status', 'review-schema-pro' ),
					'default' => 'show',
					'options' => [
						'show' => esc_html__( 'Show', 'review-schema-pro' ),
						'hide' => esc_html__( 'Hide', 'review-schema-pro' ),
					],
				],
				[
					'name'     => 'name',
					'label'    => __( 'Name', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'name'     => 'type',
					'label'    => __( 'Vehicle Type', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
					'desc'     => esc_html__( 'Type Look like: BusOrCoach, Car, Motorcycle, MotorizedBicycle. Ex: Car.  More Details:', 'review-schema-pro' ) . "<a href='https://schema.org/Vehicle' target='_blank'>" . esc_html__( 'Vehicle', 'review-schema-pro' ) . '</a>',
				],
				[
					'name'     => 'IdentificationNumber',
					'label'    => __( 'Identification Number', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
					'desc'     => esc_html__( 'The Vehicle Identification Number (VIN), which is a unique alphanumeric identifier for each vehicle.', 'review-schema-pro' ) . "<a href='https://schema.org/Vehicle' target='_blank'>" . esc_html__( 'Vehicle', 'review-schema-pro' ) . '</a>',
				],
				[
					'name'  => 'url',
					'label' => esc_html__( 'URL', 'review-schema-pro' ),
					'type'  => 'url',
				],
				[
					'name'  => 'description',
					'type'  => 'textarea',
					'label' => esc_html__( 'Description', 'review-schema-pro' ),
				],
				[
					'name'  => 'pricing_section_heading',
					'label' => __( 'Offer', 'review-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'  => 'offers_price',
					'label' => __( 'Price', 'review-schema-pro' ),
					'type'  => 'number',
				],
				[
					'name'  => 'priceCurrency',
					'label' => __( 'Price Currency', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'The 3-letter currency code.', 'review-schema-pro' ),
				],
				[
					'name'        => 'priceValidUntil',
					'type'        => 'text',
					'label'       => esc_html__( 'Price Valid Until', 'review-schema' ),
					'recommended' => true,
					'class'       => 'rtrs-date',
					'desc'        => esc_html__( 'The date (in ISO 8601 date format) after which the price will no longer be available. Like this: 2020-12-25 14:20:00', 'review-schema' ),
				],
				[
					'name'    => 'availability',
					'label'   => 'Availability',
					'type'    => 'select',
					'empty'   => 'Select one',
					'options' => [
						'http://schema.org/InStock'      => 'InStock',
						'http://schema.org/InStoreOnly'  => 'InStoreOnly',
						'http://schema.org/OutOfStock'   => 'OutOfStock',
						'http://schema.org/SoldOut'      => 'SoldOut',
						'http://schema.org/OnlineOnly'   => 'OnlineOnly',
						'http://schema.org/LimitedAvailability' => 'LimitedAvailability',
						'http://schema.org/Discontinued' => 'Discontinued',
						'http://schema.org/PreOrder'     => 'PreOrder',
					],
					'desc'    => __( 'Select a availability type', 'review-schema-pro' ),
				],
				[
					'name'     => 'itemCondition',
					'label'    => __( 'Item Condition', 'review-schema-pro' ),
					'type'     => 'select',
					'required' => true,
					'options'  => [
						'https://schema.org/NewCondition'  => 'New',
						'https://schema.org/UsedCondition' => 'Used',
					],
				],
				[
					'name'     => 'brand',
					'label'    => __( 'Brand', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
					'desc'     => __( 'The brand of the product (Used globally).', 'review-schema-pro' ),
				],
				[
					'name'     => 'model',
					'label'    => __( 'Model', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'name'     => 'vehicleConfiguration',
					'label'    => __( 'Configuration', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
					'desc'     => __( 'The trim of the model, such as S, SV, or SL..', 'review-schema-pro' ),
				],
				[
					'name'     => 'vehicleModelDate',
					'label'    => __( 'Model Date', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
					'desc'     => __( 'EX: 1999', 'review-schema-pro' ),
				],
				[
					'name'  => 'Mileage',
					'label' => __( 'Mileage', 'review-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'     => 'mileageFromOdometer',
					'label'    => __( 'Mileage From Odometer', 'review-schema-pro' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'name'  => 'unitCode',
					'label' => __( 'Unit Code', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'Use one of the following values: ( For miles: SMI For kilometers: KMT ).', 'review-schema-pro' ),
				],

				[
					'name'  => 'color',
					'label' => __( 'Color', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'The OEM-specified exterior color, such as White, Platinum, or Metallic Tri-Coat.', 'review-schema-pro' ),
				],
				[
					'name'  => 'vehicleInteriorColor',
					'label' => __( 'Interior Color', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'The OEM-specified interior color, such as Brown or Ivory.', 'review-schema-pro' ),
				],
				[
					'name'  => 'vehicleInteriorType',
					'label' => __( 'Interior Type', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'The type or material of the interior of the vehicle (for example, synthetic fabric, leather, wood).', 'review-schema-pro' ),
				],
				[
					'name'  => 'bodyType',
					'label' => __( 'Body Type', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'Check Bodytype: ', 'review-schema-pro' ) . "<a href='https://developers.google.com/search/docs/appearance/structured-data/vehicle-listing' target='_blank'>" . esc_html__( 'Body Type', 'review-schema-pro' ) . '</a>',
				],
				[
					'name'     => 'driveWheelConfiguration',
					'label'    => __( 'Drive Wheel Configuration', 'review-schema-pro' ),
					'type'     => 'select',
					'required' => true,
					'options'  => [
						'https://schema.org/AllWheelDriveConfiguration'  => 'AllWheel',
						'https://schema.org/FourWheelDriveConfiguration'  => 'FourWheel',
						'https://schema.org/FrontWheelDriveConfiguration'  => 'FrontWheel',
						'https://schema.org/RearWheelDriveConfiguration'  => 'RearWheel',
					],
				],
				[
					'name'  => 'fuelType',
					'label' => __( 'Engine Fuel Type', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'The type of fuel that\'s suitable for the engine of the vehicle.', 'review-schema-pro' ),
				],
				[
					'name'  => 'vehicleTransmission',
					'label' => __( 'Transmission', 'review-schema-pro' ),
					'type'  => 'text',
					'desc'  => __( 'The transmission specification. For example, 9-speed automatic or manual.', 'review-schema-pro' ),
				],
				[
					'name'  => 'numberOfDoors',
					'label' => __( 'Number Of Doors', 'review-schema-pro' ),
					'type'  => 'number',
				],
				[
					'name'  => 'vehicleSeatingCapacity',
					'label' => __( 'Seating Capacity', 'review-schema-pro' ),
					'type'  => 'number',
				],
				[
					'name'  => 'images_heading',
					'label' => __( 'Images', 'review-schema-pro' ),
					'type'  => 'heading',
					'desc'  => __( 'One or more images of the listing. The listing must have a minimum of 8 photos (at least 1 image of each of the following: bedroom, bathroom, and common area)', 'review-schema-pro' ),
				],
				[
					'required'  => true,
					'type'      => 'group',
					'label'     => esc_html__( 'Images', 'review-schema-pro' ),
					'name'      => 'images',
					'duplicate' => true,
					'fields'    => [
						[
							'name'  => 'image',
							'label' => __( 'Upload Images', 'review-schema-pro' ),
							'type'  => 'image',
						],
					],
				],

				[
					'name'  => 'MerchantReturnPolicy',
					'type'  => 'heading',
					'label' => esc_html__( 'Merchant Return Policy', 'review-schema' ),
				],
				[
					'name'        => 'applicableCountry',
					'type'        => 'text',
					'placeholder' => 'US',
					'label'       => esc_html__( 'Applicable Country', 'review-schema' ),
					'desc'        => esc_html__( 'The two-letter country code, in ISO 3166-1 alpha-2 format.', 'review-schema' ),
				],
				[
					'name'        => 'merchantReturnDays',
					'type'        => 'text',
					'placeholder' => '10',
					'label'       => esc_html__( 'Merchant Return Days', 'review-schema' ),
				],
				[
					'name'  => 'shipping_details',
					'type'  => 'heading',
					'label' => esc_html__( 'Shipping Details', 'review-schema' ),
				],
				[
					'name'  => 'shippingRate',
					'type'  => 'float',
					'label' => esc_html__( 'Shipping Rate ( Price )', 'review-schema' ),
					'desc'  => esc_html__( 'Shipping Cost.', 'review-schema' ),
				],
				[
					'name'        => 'shippingDestination',
					'type'        => 'text',
					'placeholder' => 'US',
					'label'       => esc_html__( 'Shipping Destination', 'review-schema' ),
					'desc'        => esc_html__( 'The two-letter country code, in ISO 3166-1 alpha-2 format.', 'review-schema' ),
				],
				[
					'name'        => 'addressRegion',
					'type'        => 'text',
					'placeholder' => ' "NY", "AL", "AK"',
					'label'       => esc_html__( 'Address Region', 'review-schema' ),
					'desc'        => esc_html__( 'If you include this property, the region must be a 2- or 3-digit ISO 3166-2 subdivision code, without country prefix. Currently, Google Search only supports the US, Australia, and Japan. Examples: "NY" (for US, state of New York), "NSW" (for Australia, state of New South Wales), or "03" (for Japan, Iwate prefecture).Example: "NY", "AL", "AK".', 'review-schema' ),
				],
				[
					'name'  => 'handlingTime',
					'type'  => 'heading',
					'label' => esc_html__( 'Handling Time', 'review-schema' ),
				],

				[
					'name'        => 'handlingTimeMinimum',
					'type'        => 'text',
					'placeholder' => '5',
					'label'       => esc_html__( 'Handling Time Minimum ( Days )', 'review-schema' ),
					'desc'        => esc_html__( 'Minimum days for handling time.', 'review-schema' ),
				],
				[
					'name'        => 'handlingTimeMaximum',
					'type'        => 'text',
					'placeholder' => '5',
					'label'       => esc_html__( 'Handling Time Maximum (Days)', 'review-schema' ),
					'desc'        => esc_html__( 'Maximum days for handling time.', 'review-schema' ),
				],

				[
					'name'        => 'transitTimeMinimum',
					'type'        => 'text',
					'placeholder' => '5',
					'label'       => esc_html__( 'Transit Time Minimum ( Days )', 'review-schema' ),
					'desc'        => esc_html__( 'Minimum days for Transit Time.', 'review-schema' ),
				],
				[
					'name'        => 'transitTimeMaximum',
					'type'        => 'text',
					'placeholder' => '10',
					'label'       => esc_html__( 'Transit Time Maximum ( Days )', 'review-schema' ),
					'desc'        => esc_html__( 'Maximum days for Transit Time.', 'review-schema' ),
				],

				[
					'name'  => 'rating_section',
					'label' => __( 'Review & Rating', 'wp-seo-structured-data-schema-pro' ),
					'type'  => 'heading',
				],
				[
					'name'        => 'reviewRatingValue',
					'label'       => __( 'Review rating value', 'wp-seo-structured-data-schema-pro' ),
					'type'        => 'number',
					'recommended' => true,
					'attr'        => 'step="any"',
					'desc'        => __( 'Rating value. (1 , 2.5, 3, 5 etc)', 'wp-seo-structured-data-schema-pro' ),
				],
				[
					'name'        => 'reviewBestRating',
					'label'       => __( 'Review Best rating', 'wp-seo-structured-data-schema-pro' ),
					'type'        => 'number',
					'recommended' => true,
					'attr'        => 'step="any"',
				],
				[
					'name'        => 'reviewWorstRating',
					'label'       => __( 'Review Worst rating', 'wp-seo-structured-data-schema-pro' ),
					'type'        => 'number',
					'recommended' => true,
					'attr'        => 'step="any"',
				],
				[
					'name'  => 'reviewAuthor',
					'label' => __( 'Review author', 'wp-seo-structured-data-schema-pro' ),
					'type'  => 'text',
				],
				[
					'name'        => 'ratingValue',
					'label'       => __( 'Aggregate Rating value', 'wp-seo-structured-data-schema-pro' ),
					'type'        => 'number',
					'recommended' => true,
					'attr'        => 'step="any"',
					'desc'        => __( 'Rating value. (1 , 2.5, 3, 5 etc)', 'wp-seo-structured-data-schema-pro' ),
				],
				[
					'name'  => 'reviewCount',
					'label' => __( 'Aggregate Total review count', 'wp-seo-structured-data-schema-pro' ),
					'type'  => 'number',
					'attr'  => 'step="any"',
					'desc'  => __( "Review Count. <span class='required'>This is required if (Rating value) is given</span>", 'wp-seo-structured-data-schema-pro' ),
				],

			],
		];
		return $settings_fields;
	}
}

<?php

namespace Rtcl\Resources;

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Text;
use Rtcl\Traits\SingletonTrait;

class Options {

	use SingletonTrait;


	/**
	 * Checkout fields are stored here.
	 *
	 * @var array|null
	 */
	protected $checkout_fields = null;

	/**
	 * Filter Form fields
	 *
	 * @return array
	 */
	static function filterFormItems() {
		$fields = [
			'search'        => [
				'label'  => esc_html__( 'Keyword', 'classified-listing' ),
				'icon'   => 'rtcl-icon-search',
				'fields' => [
					[
						'label'       => esc_html__( 'Item title', 'classified-listing' ),
						'id'          => 'title',
						'default'     => __( 'Keyword', 'classified-listing' ),
						'placeholder' => __( 'Search ...', 'classified-listing' ),
						'type'        => 'text',
						'required'    => 1,
					],
					[
						'label'   => esc_html__( 'Placeholder', 'classified-listing' ),
						'id'      => 'placeholder',
						'default' => __( 'Search ...', 'classified-listing' ),
						'type'    => 'text',
					]
				]
			],
			'category'      => [
				'label'  => esc_html__( 'Categories', 'classified-listing' ),
				'icon'   => 'rtcl-icon-folder',
				'fields' => [
					[
						'label'    => esc_html__( 'Item title', 'classified-listing' ),
						'id'       => 'title',
						'default'  => __( 'Category', 'classified-listing' ),
						'type'     => 'text',
						'required' => 1,
					],
					[
						'label'    => esc_html__( 'Form field type', 'classified-listing' ),
						'id'       => 'type',
						'default'  => 'checkbox',
						'options'  => [
							'checkbox' => esc_html__( 'Checkbox', 'classified-listing' ),
							'radio'    => esc_html__( 'Radio', 'classified-listing' )
						],
						'type'     => 'select',
						'required' => 1,
					],
					[
						'label' => esc_html__( 'Hide empty', 'classified-listing' ),
						'id'    => 'hide_empty',
						'type'  => 'switch'
					],
					[
						'label' => esc_html__( 'Show count', 'classified-listing' ),
						'id'    => 'show_count',
						'type'  => 'switch'
					],
					[
						'label'   => esc_html__( 'Show icon or image', 'classified-listing' ),
						'id'      => 'show_icon_image',
						'default' => 1,
						'type'    => 'switch'
					],
					[
						'label' => esc_html__( 'More Less', 'classified-listing' ),
						'id'    => 'more_less',
						'type'  => 'switch'
					]
				]
			],
			'location'      => [
				'label'  => esc_html__( 'Locations', 'classified-listing' ),
				'icon'   => 'rtcl-icon-location',
				'fields' => [
					[
						'label'    => esc_html__( 'Item title', 'classified-listing' ),
						'id'       => 'title',
						'default'  => __( 'Location', 'classified-listing' ),
						'type'     => 'text',
						'required' => 1,
					],
					[
						'label'   => esc_html__( 'Form field type', 'classified-listing' ),
						'id'      => 'type',
						'default' => 'checkbox',
						'options' => [
							'checkbox' => esc_html__( 'Checkbox', 'classified-listing' ),
							'radio'    => esc_html__( 'Radio', 'classified-listing' )
						],
						'type'    => 'select',
					],
					[
						'label' => esc_html__( 'Hide empty', 'classified-listing' ),
						'id'    => 'hide_empty',
						'type'  => 'switch'
					],
					[
						'label' => esc_html__( 'Show count', 'classified-listing' ),
						'id'    => 'show_count',
						'type'  => 'switch'
					],
					[
						'label' => esc_html__( 'More Less', 'classified-listing' ),
						'id'    => 'more_less',
						'type'  => 'switch'
					]
				]
			],
			'tag'           => [
				'label'    => esc_html__( 'Tags', 'classified-listing' ),
				'icon'     => 'rtcl-icon-tags',
				'settings' => [
					'hide_empty' => 1,
					'all_link'   => 1,
					'type'       => 'checkboxes',
					'hide_count' => 1
				],
				'fields'   => [
					[
						'label'    => esc_html__( 'Item title', 'classified-listing' ),
						'id'       => 'title',
						'default'  => __( 'Tag', 'classified-listing' ),
						'type'     => 'text',
						'required' => 1,
					],
					[
						'label'   => esc_html__( 'Form field type', 'classified-listing' ),
						'id'      => 'type',
						'default' => 'checkbox',
						'options' => [
							'checkbox' => esc_html__( 'Checkbox', 'classified-listing' ),
							'radio'    => esc_html__( 'Radio', 'classified-listing' )
						],
						'type'    => 'select'
					],
					[
						'label'   => esc_html__( 'Hide empty', 'classified-listing' ),
						'id'      => 'hide_empty',
						'default' => 1,
						'type'    => 'switch'
					],
					[
						'label' => esc_html__( 'Show count', 'classified-listing' ),
						'id'    => 'show_count',
						'type'  => 'switch'
					],
					[
						'label' => esc_html__( 'More Less', 'classified-listing' ),
						'id'    => 'more_less',
						'type'  => 'switch'
					]
				]
			],
			'ad_type'       => [
				'label'  => esc_html__( 'Listing Ad Types', 'classified-listing' ),
				'icon'   => 'rtcl-icon-th-list',
				'fields' => [
					[
						'label'    => esc_html__( 'Item title', 'classified-listing' ),
						'id'       => 'title',
						'default'  => __( 'Ad Type', 'classified-listing' ),
						'type'     => 'text',
						'required' => 1,
					],
					[
						'label'   => esc_html__( 'Form field type', 'classified-listing' ),
						'id'      => 'type',
						'default' => 'checkbox',
						'options' => [
							'checkbox' => esc_html__( 'Checkbox', 'classified-listing' ),
							'radio'    => esc_html__( 'Radio', 'classified-listing' ),
							'select'   => esc_html__( 'Dropdown', 'classified-listing' ),
						],
						'type'    => 'select'
					]
				]
			],
			'price_range'   => [
				'label'  => esc_html__( 'Price Range', 'classified-listing' ),
				'icon'   => 'rtcl-icon-dollar',
				'fields' => [
					[
						'label'    => esc_html__( 'Item title', 'classified-listing' ),
						'id'       => 'title',
						'default'  => __( 'Price Range', 'classified-listing' ),
						'type'     => 'text',
						'required' => 1,
					],
					[
						'label'   => esc_html__( 'Default Min Price', 'classified-listing' ),
						'id'      => 'min_price',
						'default' => 0,
						'type'    => 'number'
					],
					[
						'label'   => esc_html__( 'Default Max Price', 'classified-listing' ),
						'id'      => 'max_price',
						'default' => 50000,
						'type'    => 'number'
					],
					[
						'label'   => esc_html__( 'Range step', 'classified-listing' ),
						'id'      => 'step',
						'default' => 1000,
						'type'    => 'number'
					],
				]
			],
			'radius_filter' => [
				'icon'   => 'rtcl-icon-location',
				'label'  => esc_html__( 'Radius Filter', 'classified-listing' ),
				'fields' => [
					[
						'label'    => esc_html__( 'Item title', 'classified-listing' ),
						'id'       => 'title',
						'default'  => __( 'Radius Filter', 'classified-listing' ),
						'type'     => 'text',
						'required' => 1,
					],
				]
			]
		];

		return apply_filters( 'rtcl_filter_form_items', $fields );
	}

	public static function google_map_script_options() {
		$options = [
			'v'         => '3.exp',
			'libraries' => 'geometry,places'
		];

		return wp_parse_args( apply_filters( 'rtcl_google_map_script_options', $options ), $options );
	}

	public static function radius_search_options() {
		$options = [
			'units'            => 'miles',
			'max_distance'     => 300,
			'default_distance' => 30
		];

		return wp_parse_args( apply_filters( 'rtcl_radius_search_options', $options ), $options );
	}

	public static function widget_listings_fields() {
		$fields = [
			'title'            => [
				'label' => esc_html__( 'Title', 'classified-listing' ),
				'type'  => 'text'
			],
			'location'         => [
				'label' => esc_html__( 'Filter by Location', 'classified-listing' ),
				'type'  => 'location'
			],
			'category'         => [
				'label' => esc_html__( 'Filter by Category', 'classified-listing' ),
				'type'  => 'category'
			],
			'type'             => [
				'label'   => esc_html__( 'Filter by ad Type', 'classified-listing' ),
				'type'    => 'select',
				'options' => [
					'featured_only' => esc_html__( 'Featured only', 'classified-listing' ),
					'all'           => esc_html__( 'All Type', 'classified-listing' )
				]
			],
			'related_listings' => [
				'label' => esc_html__( 'Related Listings', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'limit'            => [
				'label' => esc_html__( 'Limit / Listing per page(pagination)', 'classified-listing' ),
				'type'  => 'text'
			],
			'orderby'          => [
				'label'   => esc_html__( 'Order By', 'classified-listing' ),
				'type'    => 'select',
				'options' => [
					'title' => esc_html__( 'Title', 'classified-listing' ),
					'date'  => esc_html__( 'Date posted', 'classified-listing' ),
					'price' => esc_html__( 'Price', 'classified-listing' ),
					'views' => esc_html__( 'Views count', 'classified-listing' ),
					'rand'  => esc_html__( 'Random', 'classified-listing' )
				]
			],
			'order'            => [
				'label'   => esc_html__( 'Order', 'classified-listing' ),
				'type'    => 'select',
				'options' => [
					'asc'  => esc_html__( 'ASC', 'classified-listing' ),
					'desc' => esc_html__( 'DESC', 'classified-listing' )
				]
			],
			'display_options'  => [
				'label' => esc_html__( 'Display Options', 'classified-listing' ),
				'type'  => 'section_title'
			],
			'view'             => [
				'label'      => esc_html__( 'View', 'classified-listing' ),
				'type'       => 'select',
				'wrap_class' => 'rtcl-widget-listings-view',
				'options'    => [
					'grid'   => esc_html__( 'Grid', 'classified-listing' ),
					'slider' => esc_html__( 'Slider', 'classified-listing' )
				]
			],
			'columns'          => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Number of columns / Items to display at slider', 'classified-listing' ),
				'type'       => 'select',
				'options'    => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8
				]
			],
			'tab_items'        => [
				'wrap_class' => 'rtcl-slider-item rtcl-general-item',
				'label'      => esc_html__( 'Number of items at Tab (Slider)', 'classified-listing' ),
				'type'       => 'select',
				'options'    => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8
				]
			],
			'mobile_items'     => [
				'wrap_class' => 'rtcl-slider-item rtcl-general-item',
				'label'      => esc_html__( 'Number of items at Mobile (Slider)', 'classified-listing' ),
				'type'       => 'select',
				'options'    => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8
				]
			],
			'show_image'       => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Image', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'image_position'   => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Image Position', 'classified-listing' ),
				'type'       => 'select',
				'options'    => [
					'top'  => esc_html__( 'Top', 'classified-listing' ),
					'left' => esc_html__( 'Left', 'classified-listing' )
				]
			],
			'show_category'    => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Category', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'show_location'    => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Location', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'show_labels'      => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Labels', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'show_price'       => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Price', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'show_date'        => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Date', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'show_user'        => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show User', 'classified-listing' ),
				'type'       => 'checkbox',
			],
			'show_views'       => [
				'wrap_class' => 'rtcl-general-item',
				'label'      => esc_html__( 'Show Views', 'classified-listing' ),
				'type'       => 'checkbox',
			],
		];

		return apply_filters( 'rtcl_widget_listings_fields', $fields );
	}

	static function widget_filter_fields() {
		$fields = [
			'title'               => [
				'label' => esc_html__( 'Title', 'classified-listing' ),
				'type'  => 'text'
			],
			'search_by_category'  => [
				'label' => esc_html__( 'Search by Category', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'search_by_tag'       => [
				'label' => esc_html__( 'Search by Tag', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'search_by_location'  => [
				'label' => esc_html__( 'Search by Location', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'radius_search'       => [
				'label' => esc_html__( 'Radius Search (Location search will turn off)', 'classified-listing' ),
				'type'  => 'checkbox',
			],
			'search_by_ad_type'   => [
				'label' => esc_html__( 'Search by ad Types', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'search_by_price'     => [
				'label' => esc_html__( 'Search by Price', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'hide_empty'          => [
				'label' => esc_html__( 'Hide empty Category / Location', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'show_count'          => [
				'label' => esc_html__( 'Show count for Category / Location', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'ajax_load'           => [
				'label' => esc_html__( 'Ajax load for Category / Location to increase PageSpeed.', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'taxonomy_reset_link' => [
				'label' => esc_html__( 'All Categories / All Locations link', 'classified-listing' ),
				'type'  => 'checkbox'
			]
		];
		if ( 'local' !== Functions::location_type() ) {
			unset( $fields['search_by_location'] );
		}

		return apply_filters( 'rtcl_widget_filter_fields', $fields );
	}

	static function widget_search_fields() {
		$fields = [
			'title'                   => [
				'label' => esc_html__( 'Title', 'classified-listing' ),
				'type'  => 'text'
			],
			'style'                   => [
				'label'   => esc_html__( 'Style', 'classified-listing' ),
				'type'    => 'radio',
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'classified-listing' ),
					'inline'   => esc_html__( 'inline', 'classified-listing' )
				]
			],
			'search_by_category'      => [
				'label' => esc_html__( 'Search by Category', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'search_by_location'      => [
				'label' => esc_html__( 'Search by Location', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'radius_search'           => [
				'label' => esc_html__( 'Radius Search (Location search will turn off)', 'classified-listing' ),
				'type'  => 'checkbox',
			],
			'search_by_listing_types' => [
				'label' => esc_html__( 'Search by Types', 'classified-listing' ),
				'type'  => 'checkbox'
			],
			'search_by_price'         => [
				'label' => esc_html__( 'Search by Price', 'classified-listing' ),
				'type'  => 'checkbox'
			]
		];
		if ( 'local' !== Functions::location_type() ) {
			unset( $fields['search_by_location'] );
		}

		return apply_filters( 'rtcl_widget_search_fields', $fields );
	}

	static function get_social_profiles_list() {
		$options = [
			'facebook'  => esc_html__( 'Facebook', 'classified-listing' ),
			'twitter'   => esc_html__( 'Twitter', 'classified-listing' ),
			'youtube'   => esc_html__( 'Youtube', 'classified-listing' ),
			'instagram' => esc_html__( 'Instagram', 'classified-listing' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'classified-listing' ),
			'pinterest' => esc_html__( 'Pinterest', 'classified-listing' ),
			'reddit'    => esc_html__( 'Reddit', 'classified-listing' ),
			'tiktok'    => esc_html__( 'Tiktok', 'classified-listing' )
		];

		return apply_filters( 'rtcl_social_profiles_list', $options );
	}

	public static function get_listing_orderby_options() {
		$options = [
			'title-asc'  => esc_html__( 'A to Z ( title )', 'classified-listing' ),
			'title-desc' => esc_html__( 'Z to A ( title )', 'classified-listing' ),
			'date-desc'  => esc_html__( 'Recently added ( latest )', 'classified-listing' ),
			'date-asc'   => esc_html__( 'Date added ( oldest )', 'classified-listing' ),
			'views-desc' => esc_html__( 'Most viewed', 'classified-listing' ),
			'views-asc'  => esc_html__( 'Less viewed', 'classified-listing' )
		];

		if ( ! Functions::is_price_disabled() ) {
			$options['price-asc']  = esc_html__( 'Price ( low to high )', 'classified-listing' );
			$options['price-desc'] = esc_html__( 'Price ( high to low )', 'classified-listing' );
		}

		return apply_filters( 'rtcl_listing_orderby_options', $options );
	}

	/**
	 * @return mixed|void
	 */
	public static function get_redirect_page_list() {

		$list = [
			'account'    => esc_html__( 'Account', 'classified-listing' ),
			'submission' => esc_html__( 'Regular submission', 'classified-listing' ),
			'custom'     => esc_html__( 'Custom', 'classified-listing' )
		];

		return apply_filters( 'rtcl_get_redirect_page_list', $list );
	}

	public static function get_listing_promotions() {
		$featured_label = Functions::get_option_item( 'rtcl_general_listing_label_settings', 'listing_featured_label' );
		$featured_label = $featured_label ?: esc_html__( 'Featured', 'classified-listing' );

		$promotions = [ 'featured' => $featured_label ];

		return apply_filters( 'rtcl_listing_promotions', $promotions );
	}

	public static function get_status_list( $all = null ) {
		$status = [
			'publish'       => esc_html__( 'Published', 'classified-listing' ),
			'pending'       => esc_html__( 'Pending', 'classified-listing' ),
			'draft'         => esc_html__( 'Draft', 'classified-listing' ),
			'rtcl-reviewed' => esc_html__( 'Reviewed', 'classified-listing' ),
			'rtcl-expired'  => esc_html__( 'Expired', 'classified-listing' ),
		];
		if ( $all ) {
			$status['rtcl-temp'] = esc_html__( 'Temporary', 'classified-listing' );
		}

		return apply_filters( 'rtcl_listing_get_status_list', $status );
	}

	/**
	 * @return array
	 */
	public static function detail_page_sidebar_position() {
		$status = [
			'right'  => esc_html__( 'Right', 'classified-listing' ),
			'left'   => esc_html__( 'Left', 'classified-listing' ),
			'bottom' => esc_html__( 'Bottom', 'classified-listing' ),
		];

		return apply_filters( 'rtcl_detail_page_sidebar_position', $status );
	}

	public static function get_payment_status_list( $short = false ) {
		$statuses = [
			'rtcl-pending'    => _x( 'Pending', 'Payment status', 'classified-listing' ),
			'rtcl-processing' => _x( 'Processing', 'Payment status', 'classified-listing' ),
			'rtcl-on-hold'    => _x( 'On hold', 'Payment status', 'classified-listing' ),
			'rtcl-completed'  => _x( 'Completed', 'Payment status', 'classified-listing' ),
			'rtcl-cancelled'  => _x( 'Cancelled', 'Payment status', 'classified-listing' ),
			'rtcl-refunded'   => _x( 'Refunded', 'Payment status', 'classified-listing' ),
			'rtcl-failed'     => _x( 'Failed', 'Payment status', 'classified-listing' ),
			'rtcl-created'    => _x( 'Created', 'Payment status', 'classified-listing' )
		];
		if ( $short ) {
			unset( $statuses['rtcl-created'] );
		}

		return apply_filters( 'rtcl_get_payment_status_list', $statuses );
	}

	public static function get_price_types() {
		$price_types = [
			'fixed'      => Text::price_type_fixed(),
			'negotiable' => Text::price_type_negotiable(),
			'on_call'    => Text::price_type_on_call()
		];

		return apply_filters( 'rtcl_price_types', $price_types );
	}

	public static function get_default_listing_types() {
		$default_types = [
			'sell'     => esc_html__( 'Sell', 'classified-listing' ),
			'buy'      => esc_html__( 'Buy', 'classified-listing' ),
			'exchange' => esc_html__( 'Exchange', 'classified-listing' ),
			'job'      => esc_html__( 'Job', 'classified-listing' ),
			'to_let'   => esc_html__( 'To-Let', 'classified-listing' ),
		];

		return apply_filters( 'rtcl_get_default_listing_types', $default_types );
	}

	/**
	 * @return mixed|void
	 * @deprecated  1.2.17
	 */
	public static function get_ad_types() {
		_deprecated_function( __FUNCTION__, '1.2.17', 'Functions::get_listing_types()' );

		return Functions::get_listing_types();
	}

	public static function get_date_js_format_placeholder() {
		return apply_filters(
			'rtcl_custom_field_date_js_format_placeholder',
			[
				'Y-m-d'  => 'YYYY-MM-DD',
				'm/d/Y'  => 'MM/DD/YYYY',
				'd/m/Y'  => 'DD/MM/YYYY',
				'F j, Y' => 'MMMM D, YYYY',
				'j F, Y' => 'D MMMM, YYYY',
				'j F Y'  => 'D MMMM YYYY',
				'h:i:s'  => 'hh:mm:ss',
				'g:i a'  => 'h:mm a',
				'g:i A'  => 'h:mm A',
				'H:i'    => 'HH:mm'
			]
		);
	}

	public static function get_custom_field_list() {
		return apply_filters(
			'rtcl_custom_field_list',
			[
				'text'     => [
					'name'    => esc_html__( 'Text Box', 'classified-listing' ),
					'symbol'  => 'pencil',
					'options' => self::common_options() +
					             [
						             '_default_value' => [
							             'label' => esc_html__( 'Default value', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_placeholder'   => [
							             'label' => esc_html__( 'Placeholder text', 'classified-listing' ),
							             'type'  => 'text',
						             ]
					             ]
				],
				'textarea' => [
					'name'    => esc_html__( 'Textarea', 'classified-listing' ),
					'symbol'  => 'list-alt',
					'options' => self::common_options() +
					             [
						             '_default_value' => [
							             'label' => esc_html__( 'Default value', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_placeholder'   => [
							             'label' => esc_html__( 'Placeholder text', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_rows'          => [
							             'label' => esc_html__( 'Rows', 'classified-listing' ),
							             'type'  => 'number'
						             ]
					             ]
				],
				'url'      => [
					'name'    => esc_html__( 'URL', 'classified-listing' ),
					'symbol'  => 'link',
					'options' => self::common_options() +
					             [
						             '_default_value' => [
							             'label' => esc_html__( 'Default value', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_placeholder'   => [
							             'label' => esc_html__( 'Placeholder text', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_target'        => [
							             'label' => esc_html__( 'Open link in a new window?', 'classified-listing' ),
							             'type'  => 'switch',
						             ],
						             '_nofollow'      => [
							             'label' => esc_html__(
								             'Use rel="nofollow" when displaying the link?',
								             'classified-listing'
							             ),
							             'type'  => 'switch',
						             ],
					             ]
				],
				'number'   => [
					'name'    => esc_html__( 'Number', 'classified-listing' ),
					'symbol'  => 'calc',
					'options' => self::common_options() +
					             [
						             '_default_value' => [
							             'label' => esc_html__( 'Default value', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_placeholder'   => [
							             'label' => esc_html__( 'Placeholder text', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_min'           => [
							             'label' => esc_html__( 'Minimum value', 'classified-listing' ),
							             'type'  => 'number'
						             ],
						             '_max'           => [
							             'label' => esc_html__( 'Maximum value', 'classified-listing' ),
							             'type'  => 'number'
						             ],
						             '_step_size'     => [
							             'label' => esc_html__( 'Step Size', 'classified-listing' ),
							             'type'  => 'number'
						             ]
					             ]
				],
				'date'     => [
					'name'    => esc_html__( 'Date', 'classified-listing' ),
					'symbol'  => 'calendar',
					'options' => self::common_options() +
					             [
						             '_placeholder'          => [
							             'label' => esc_html__( 'Placeholder text', 'classified-listing' ),
							             'type'  => 'text',
						             ],
						             '_date_type'            => [
							             'label'   => esc_html__( 'Type', 'classified-listing' ),
							             'type'    => 'radio',
							             'class'   => 'horizontal',
							             'default' => 'date',
							             'options' => [
								             'date'            => esc_html__( 'Date', 'classified-listing' ),
								             'date_time'       => esc_html__( 'Date & Time', 'classified-listing' ),
								             'date_range'      => esc_html__( 'Date Range', 'classified-listing' ),
								             'date_time_range' => esc_html__( 'Date & Time Range', 'classified-listing' ),
							             ]
						             ],
						             '_date_format'          => [
							             'label'   => esc_html__( 'Date Format', 'classified-listing' ),
							             'type'    => 'radio',
							             'default' => 'Y-m-d',
							             'options' => [
								             'Y-m-d'  => 'Y-m-d (2025-11-12)',
								             'm/d/Y'  => 'm/d/Y (11/12/2025)',
								             'd/m/Y'  => 'd/m/Y (12/11/2025)',
								             'F j, Y' => 'F j, Y (November 12, 2025)',
								             'j F, Y' => 'j F, Y (12 November, 2025)',
								             'j F Y'  => 'j F Y (12 November 2025)',
							             ]
						             ],
						             '_date_time_format'     => [
							             'label'   => esc_html__( 'Time Format', 'classified-listing' ),
							             'type'    => 'radio',
							             'default' => 'h:i:s',
							             'options' => [
								             'h:i:s' => 'h:i:s (00:00:00)',
								             'g:i a' => 'g:i a (3:10 pm)',
								             'g:i A' => 'g:i A (3:10 PM)',
								             'H:i'   => 'H:i (15:10)'
							             ]
						             ],
						             '_date_searchable_type' => [
							             'label'   => esc_html__( 'Search able date type', 'classified-listing' ),
							             'type'    => 'radio',
							             'class'   => 'horizontal',
							             'default' => 'single',
							             'options' => [
								             'single' => esc_html__( 'Single', 'classified-listing' ),
								             'range'  => esc_html__( 'Range', 'classified-listing' ),
							             ]
						             ]
					             ]
				],
				'select'   => [
					'name'    => esc_html__( 'Select', 'classified-listing' ),
					'symbol'  => 'tablet rtcl-rotate-180',
					'options' => self::common_options() +
					             [
						             '_options' => [
							             'label' => esc_html__( 'Options', 'classified-listing' ),
							             'type'  => 'select'
						             ]
					             ]
				],
				'radio'    => [
					'name'    => esc_html__( 'Radio', 'classified-listing' ),
					'symbol'  => 'dot-circled',
					'options' => self::common_options() +
					             [
						             '_options' => [
							             'label' => esc_html__( 'Options', 'classified-listing' ),
							             'type'  => 'select'
						             ]
					             ]
				],
				'checkbox' => [
					'name'    => esc_html__( 'Checkbox', 'classified-listing' ),
					'symbol'  => 'check rtcl-checkboxes',
					'options' => self::common_options() +
					             [
						             '_options' => [
							             'label' => esc_html__( 'Options', 'classified-listing' ),
							             'type'  => 'checkbox'
						             ]
					             ]
				]
			]
		);
	}

	static function common_options() {
		return apply_filters(
			'rtcl_custom_field_list_common_options',
			[
				'_label'             => [
					'label'       => esc_html__( 'Field label', 'classified-listing' ),
					'type'        => 'text',
					'placeholder' => esc_html__( 'Enter field label', 'classified-listing' ),
					'class'       => 'rtcl-forms-set-legend js-rtcl-slugize-source'
				],
				'_slug'              => [
					'label'       => esc_html__( 'Field slug/name', 'classified-listing' ),
					'type'        => 'text',
					'placeholder' => esc_html__( 'Enter field slug/name', 'classified-listing' ),
					'class'       => 'rtcl-forms-field-slug js-rtcl-slugize'
				],
				'_description'       => [
					'label'       => esc_html__( 'Field description', 'classified-listing' ),
					'type'        => 'textarea',
					'placeholder' => esc_html__( 'Enter field description', 'classified-listing' )
				],
				'_icon'              => [
					'label'   => esc_html__( 'Icon', 'classified-listing' ),
					'type'    => 'dropdown',
					'class'   => 'rtcl-select2-icon',
					'empty'   => esc_html__( 'Select one', 'classified-listing' ),
					'options' => self::get_icon_list()
				],
				'_required'          => [
					'label' => esc_html__( 'Required?', 'classified-listing' ),
					'type'  => 'switch'
				],
				'_searchable'        => [
					'label'      => esc_html__( 'Include this field in the filter (Widget)?', 'classified-listing' ) . rtcl()->pro_tag(),
					'type'       => 'switch',
					'wrap_class' => ! rtcl()->has_pro() ? [ 'is_pro' ] : ''
				],
				'_listable'          => [
					'label'      => esc_html__( 'Include this field in the listing?', 'classified-listing' ) . rtcl()->pro_tag(),
					'type'       => 'switch',
					'wrap_class' => ! rtcl()->has_pro() ? [ 'is_pro' ] : ''
				],
				'_conditional_logic' => [
					'label'      => esc_html__( 'Conditional Logic', 'classified-listing' ) . rtcl()->pro_tag(),
					'type'       => 'switch',
					'wrap_class' => ! rtcl()->has_pro() ? [ 'is_pro' ] : '',
					'class'      => 'conditions-toggle'
				]
			]
		);
	}

	static function getContactDetailsFields() {
		return apply_filters(
			'rtcl_listing_contact_details_fields',
			[
				'zipcode'               => [
					'type'  => 'text',
					'label' => esc_html__( 'Zip Code', 'classified-listing' ),
					'id'    => 'rtcl-zipcode',
					'class' => 'rtcl-map-field'
				],
				'address'               => [
					'type'  => 'textarea',
					'label' => esc_html__( 'Address', 'classified-listing' ),
					'id'    => 'rtcl-address',
					'class' => 'rtcl-map-field'
				],
				'phone'                 => [
					'type'  => 'text',
					'label' => esc_html__( 'Phone', 'classified-listing' ),
					'id'    => 'rtcl-phone',
					'class' => ''
				],
				'_rtcl_whatsapp_number' => [
					'type'  => 'text',
					'label' => esc_html__( 'Whatsapp number', 'classified-listing' ),
					'id'    => 'rtcl-whatsapp-phone',
					'class' => ''
				],
				'email'                 => [
					'type'  => 'email',
					'label' => esc_html__( 'Email', 'classified-listing' ),
					'id'    => 'rtcl-email',
					'class' => ''
				],
				'website'               => [
					'type'  => 'url',
					'label' => esc_html__( 'Website', 'classified-listing' ),
					'id'    => 'rtcl-website',
					'class' => ''
				]
			]
		);
	}

	static function get_month_list() {
		return [
			esc_html__( 'Jan', 'classified-listing' ),
			esc_html__( 'Feb', 'classified-listing' ),
			esc_html__( 'Mar', 'classified-listing' ),
			esc_html__( 'Apr', 'classified-listing' ),
			esc_html__( 'May', 'classified-listing' ),
			esc_html__( 'Jun', 'classified-listing' ),
			esc_html__( 'Jul', 'classified-listing' ),
			esc_html__( 'Aug', 'classified-listing' ),
			esc_html__( 'Sep', 'classified-listing' ),
			esc_html__( 'Oct', 'classified-listing' ),
			esc_html__( 'Nov', 'classified-listing' ),
			esc_html__( 'Dec', 'classified-listing' )
		];
	}

	static function allowed_tags() {

		$allowed_atts = [
			'align'      => [],
			'class'      => [],
			'type'       => [],
			'id'         => [],
			'dir'        => [],
			'lang'       => [],
			'style'      => [],
			'xml:lang'   => [],
			'src'        => [],
			'alt'        => [],
			'href'       => [],
			'rel'        => [],
			'rev'        => [],
			'target'     => [],
			'novalidate' => [],
			'value'      => [],
			'name'       => [],
			'tabindex'   => [],
			'action'     => [],
			'method'     => [],
			'for'        => [],
			'width'      => [],
			'height'     => [],
			'data'       => [],
			'title'      => [],
		];
		$allowedTags  = [
			'form'     => $allowed_atts,
			'label'    => $allowed_atts,
			'input'    => $allowed_atts,
			'textarea' => $allowed_atts,
			'iframe'   => $allowed_atts,
			'script'   => $allowed_atts,
			'style'    => $allowed_atts,
			'strong'   => $allowed_atts,
			'small'    => $allowed_atts,
			'table'    => $allowed_atts,
			'span'     => $allowed_atts,
			'abbr'     => $allowed_atts,
			'code'     => $allowed_atts,
			'pre'      => $allowed_atts,
			'div'      => $allowed_atts,
			'img'      => $allowed_atts,
			'h1'       => $allowed_atts,
			'h2'       => $allowed_atts,
			'h3'       => $allowed_atts,
			'h4'       => $allowed_atts,
			'h5'       => $allowed_atts,
			'h6'       => $allowed_atts,
			'ol'       => $allowed_atts,
			'ul'       => $allowed_atts,
			'li'       => $allowed_atts,
			'em'       => $allowed_atts,
			'hr'       => $allowed_atts,
			'br'       => $allowed_atts,
			'tr'       => $allowed_atts,
			'td'       => $allowed_atts,
			'p'        => $allowed_atts,
			'a'        => $allowed_atts,
			'b'        => $allowed_atts,
			'i'        => $allowed_atts
		];

		return $allowedTags;
	}

	static function get_currency_symbols() {
		$symbols = [
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x10da;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => 'HK&#36;',
			'HNL' => 'L',
			'HRK' => 'Kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'MOP&#36;',
			'MRO' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#x434;&#x438;&#x43d;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STD' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		];

		return apply_filters( 'rtcl_get_currency_symbols', $symbols );
	}

	static function get_currencies() {
		$currency_list = self::get_currency_list();
		$currencies    = [];
		foreach ( $currency_list as $code => $name ) {
			$currencies[ $code ] = sprintf( '%1$s (%2$s)', $name, Functions::get_currency_symbol( $code ) );
		}

		return apply_filters( 'rtcl_currencies', $currencies );
	}

	static function get_currency_list() {
		$currency_list = [
			'AED' => esc_html__( 'United Arab Emirates dirham', 'classified-listing' ),
			'AFN' => esc_html__( 'Afghan afghani', 'classified-listing' ),
			'ALL' => esc_html__( 'Albanian lek', 'classified-listing' ),
			'AMD' => esc_html__( 'Armenian dram', 'classified-listing' ),
			'ANG' => esc_html__( 'Netherlands Antillean guilder', 'classified-listing' ),
			'AOA' => esc_html__( 'Angolan kwanza', 'classified-listing' ),
			'ARS' => esc_html__( 'Argentine peso', 'classified-listing' ),
			'AUD' => esc_html__( 'Australian dollar', 'classified-listing' ),
			'AWG' => esc_html__( 'Aruban florin', 'classified-listing' ),
			'AZN' => esc_html__( 'Azerbaijani manat', 'classified-listing' ),
			'BAM' => esc_html__( 'Bosnia and Herzegovina convertible mark', 'classified-listing' ),
			'BBD' => esc_html__( 'Barbadian dollar', 'classified-listing' ),
			'BDT' => esc_html__( 'Bangladeshi taka', 'classified-listing' ),
			'BGN' => esc_html__( 'Bulgarian lev', 'classified-listing' ),
			'BHD' => esc_html__( 'Bahraini dinar', 'classified-listing' ),
			'BIF' => esc_html__( 'Burundian franc', 'classified-listing' ),
			'BMD' => esc_html__( 'Bermudian dollar', 'classified-listing' ),
			'BND' => esc_html__( 'Brunei dollar', 'classified-listing' ),
			'BOB' => esc_html__( 'Bolivian boliviano', 'classified-listing' ),
			'BRL' => esc_html__( 'Brazilian real', 'classified-listing' ),
			'BSD' => esc_html__( 'Bahamian dollar', 'classified-listing' ),
			'BTC' => esc_html__( 'Bitcoin', 'classified-listing' ),
			'BTN' => esc_html__( 'Bhutanese ngultrum', 'classified-listing' ),
			'BWP' => esc_html__( 'Botswana pula', 'classified-listing' ),
			'BYR' => esc_html__( 'Belarusian ruble (old)', 'classified-listing' ),
			'BYN' => esc_html__( 'Belarusian ruble', 'classified-listing' ),
			'BZD' => esc_html__( 'Belize dollar', 'classified-listing' ),
			'CAD' => esc_html__( 'Canadian dollar', 'classified-listing' ),
			'CDF' => esc_html__( 'Congolese franc', 'classified-listing' ),
			'CHF' => esc_html__( 'Swiss franc', 'classified-listing' ),
			'CLP' => esc_html__( 'Chilean peso', 'classified-listing' ),
			'CNY' => esc_html__( 'Chinese yuan', 'classified-listing' ),
			'COP' => esc_html__( 'Colombian peso', 'classified-listing' ),
			'CRC' => esc_html__( 'Costa Rican col&oacute;n', 'classified-listing' ),
			'CUC' => esc_html__( 'Cuban convertible peso', 'classified-listing' ),
			'CUP' => esc_html__( 'Cuban peso', 'classified-listing' ),
			'CVE' => esc_html__( 'Cape Verdean escudo', 'classified-listing' ),
			'CZK' => esc_html__( 'Czech koruna', 'classified-listing' ),
			'DJF' => esc_html__( 'Djiboutian franc', 'classified-listing' ),
			'DKK' => esc_html__( 'Danish krone', 'classified-listing' ),
			'DOP' => esc_html__( 'Dominican peso', 'classified-listing' ),
			'DZD' => esc_html__( 'Algerian dinar', 'classified-listing' ),
			'EGP' => esc_html__( 'Egyptian pound', 'classified-listing' ),
			'ERN' => esc_html__( 'Eritrean nakfa', 'classified-listing' ),
			'ETB' => esc_html__( 'Ethiopian birr', 'classified-listing' ),
			'EUR' => esc_html__( 'Euro', 'classified-listing' ),
			'FJD' => esc_html__( 'Fijian dollar', 'classified-listing' ),
			'FKP' => esc_html__( 'Falkland Islands pound', 'classified-listing' ),
			'GBP' => esc_html__( 'Pound sterling', 'classified-listing' ),
			'GEL' => esc_html__( 'Georgian lari', 'classified-listing' ),
			'GGP' => esc_html__( 'Guernsey pound', 'classified-listing' ),
			'GHS' => esc_html__( 'Ghana cedi', 'classified-listing' ),
			'GIP' => esc_html__( 'Gibraltar pound', 'classified-listing' ),
			'GMD' => esc_html__( 'Gambian dalasi', 'classified-listing' ),
			'GNF' => esc_html__( 'Guinean franc', 'classified-listing' ),
			'GTQ' => esc_html__( 'Guatemalan quetzal', 'classified-listing' ),
			'GYD' => esc_html__( 'Guyanese dollar', 'classified-listing' ),
			'HKD' => esc_html__( 'Hong Kong dollar', 'classified-listing' ),
			'HNL' => esc_html__( 'Honduran lempira', 'classified-listing' ),
			'HRK' => esc_html__( 'Croatian kuna', 'classified-listing' ),
			'HTG' => esc_html__( 'Haitian gourde', 'classified-listing' ),
			'HUF' => esc_html__( 'Hungarian forint', 'classified-listing' ),
			'IDR' => esc_html__( 'Indonesian rupiah', 'classified-listing' ),
			'ILS' => esc_html__( 'Israeli new shekel', 'classified-listing' ),
			'IMP' => esc_html__( 'Manx pound', 'classified-listing' ),
			'INR' => esc_html__( 'Indian rupee', 'classified-listing' ),
			'IQD' => esc_html__( 'Iraqi dinar', 'classified-listing' ),
			'IRR' => esc_html__( 'Iranian rial', 'classified-listing' ),
			'IRT' => esc_html__( 'Iranian toman', 'classified-listing' ),
			'ISK' => esc_html__( 'Icelandic kr&oacute;na', 'classified-listing' ),
			'JEP' => esc_html__( 'Jersey pound', 'classified-listing' ),
			'JMD' => esc_html__( 'Jamaican dollar', 'classified-listing' ),
			'JOD' => esc_html__( 'Jordanian dinar', 'classified-listing' ),
			'JPY' => esc_html__( 'Japanese yen', 'classified-listing' ),
			'KES' => esc_html__( 'Kenyan shilling', 'classified-listing' ),
			'KGS' => esc_html__( 'Kyrgyzstani som', 'classified-listing' ),
			'KHR' => esc_html__( 'Cambodian riel', 'classified-listing' ),
			'KMF' => esc_html__( 'Comorian franc', 'classified-listing' ),
			'KPW' => esc_html__( 'North Korean won', 'classified-listing' ),
			'KRW' => esc_html__( 'South Korean won', 'classified-listing' ),
			'KWD' => esc_html__( 'Kuwaiti dinar', 'classified-listing' ),
			'KYD' => esc_html__( 'Cayman Islands dollar', 'classified-listing' ),
			'KZT' => esc_html__( 'Kazakhstani tenge', 'classified-listing' ),
			'LAK' => esc_html__( 'Lao kip', 'classified-listing' ),
			'LBP' => esc_html__( 'Lebanese pound', 'classified-listing' ),
			'LKR' => esc_html__( 'Sri Lankan rupee', 'classified-listing' ),
			'LRD' => esc_html__( 'Liberian dollar', 'classified-listing' ),
			'LSL' => esc_html__( 'Lesotho loti', 'classified-listing' ),
			'LYD' => esc_html__( 'Libyan dinar', 'classified-listing' ),
			'MAD' => esc_html__( 'Moroccan dirham', 'classified-listing' ),
			'MDL' => esc_html__( 'Moldovan leu', 'classified-listing' ),
			'MGA' => esc_html__( 'Malagasy ariary', 'classified-listing' ),
			'MKD' => esc_html__( 'Macedonian denar', 'classified-listing' ),
			'MMK' => esc_html__( 'Burmese kyat', 'classified-listing' ),
			'MNT' => esc_html__( 'Mongolian t&ouml;gr&ouml;g', 'classified-listing' ),
			'MOP' => esc_html__( 'Macanese pataca', 'classified-listing' ),
			'MRO' => esc_html__( 'Mauritanian ouguiya', 'classified-listing' ),
			'MUR' => esc_html__( 'Mauritian rupee', 'classified-listing' ),
			'MVR' => esc_html__( 'Maldivian rufiyaa', 'classified-listing' ),
			'MWK' => esc_html__( 'Malawian kwacha', 'classified-listing' ),
			'MXN' => esc_html__( 'Mexican peso', 'classified-listing' ),
			'MYR' => esc_html__( 'Malaysian ringgit', 'classified-listing' ),
			'MZN' => esc_html__( 'Mozambican metical', 'classified-listing' ),
			'NAD' => esc_html__( 'Namibian dollar', 'classified-listing' ),
			'NGN' => esc_html__( 'Nigerian naira', 'classified-listing' ),
			'NIO' => esc_html__( 'Nicaraguan c&oacute;rdoba', 'classified-listing' ),
			'NOK' => esc_html__( 'Norwegian krone', 'classified-listing' ),
			'NPR' => esc_html__( 'Nepalese rupee', 'classified-listing' ),
			'NZD' => esc_html__( 'New Zealand dollar', 'classified-listing' ),
			'OMR' => esc_html__( 'Omani rial', 'classified-listing' ),
			'PAB' => esc_html__( 'Panamanian balboa', 'classified-listing' ),
			'PEN' => esc_html__( 'Peruvian nuevo sol', 'classified-listing' ),
			'PGK' => esc_html__( 'Papua New Guinean kina', 'classified-listing' ),
			'PHP' => esc_html__( 'Philippine peso', 'classified-listing' ),
			'PKR' => esc_html__( 'Pakistani rupee', 'classified-listing' ),
			'PLN' => esc_html__( 'Polish z&#x142;oty', 'classified-listing' ),
			'PRB' => esc_html__( 'Transnistrian ruble', 'classified-listing' ),
			'PYG' => esc_html__( 'Paraguayan guaran&iacute;', 'classified-listing' ),
			'QAR' => esc_html__( 'Qatari riyal', 'classified-listing' ),
			'RON' => esc_html__( 'Romanian leu', 'classified-listing' ),
			'RSD' => esc_html__( 'Serbian dinar', 'classified-listing' ),
			'RUB' => esc_html__( 'Russian ruble', 'classified-listing' ),
			'RWF' => esc_html__( 'Rwandan franc', 'classified-listing' ),
			'SAR' => esc_html__( 'Saudi riyal', 'classified-listing' ),
			'SBD' => esc_html__( 'Solomon Islands dollar', 'classified-listing' ),
			'SCR' => esc_html__( 'Seychellois rupee', 'classified-listing' ),
			'SDG' => esc_html__( 'Sudanese pound', 'classified-listing' ),
			'SEK' => esc_html__( 'Swedish krona', 'classified-listing' ),
			'SGD' => esc_html__( 'Singapore dollar', 'classified-listing' ),
			'SHP' => esc_html__( 'Saint Helena pound', 'classified-listing' ),
			'SLL' => esc_html__( 'Sierra Leonean leone', 'classified-listing' ),
			'SOS' => esc_html__( 'Somali shilling', 'classified-listing' ),
			'SRD' => esc_html__( 'Surinamese dollar', 'classified-listing' ),
			'SSP' => esc_html__( 'South Sudanese pound', 'classified-listing' ),
			'STD' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'classified-listing' ),
			'SYP' => esc_html__( 'Syrian pound', 'classified-listing' ),
			'SZL' => esc_html__( 'Swazi lilangeni', 'classified-listing' ),
			'THB' => esc_html__( 'Thai baht', 'classified-listing' ),
			'TJS' => esc_html__( 'Tajikistani somoni', 'classified-listing' ),
			'TMT' => esc_html__( 'Turkmenistan manat', 'classified-listing' ),
			'TND' => esc_html__( 'Tunisian dinar', 'classified-listing' ),
			'TOP' => esc_html__( 'Tongan pa&#x2bb;anga', 'classified-listing' ),
			'TRY' => esc_html__( 'Turkish lira', 'classified-listing' ),
			'TTD' => esc_html__( 'Trinidad and Tobago dollar', 'classified-listing' ),
			'TWD' => esc_html__( 'New Taiwan dollar', 'classified-listing' ),
			'TZS' => esc_html__( 'Tanzanian shilling', 'classified-listing' ),
			'UAH' => esc_html__( 'Ukrainian hryvnia', 'classified-listing' ),
			'UGX' => esc_html__( 'Ugandan shilling', 'classified-listing' ),
			'USD' => esc_html__( 'United States dollar', 'classified-listing' ),
			'UYU' => esc_html__( 'Uruguayan peso', 'classified-listing' ),
			'UZS' => esc_html__( 'Uzbekistani som', 'classified-listing' ),
			'VEF' => esc_html__( 'Venezuelan bol&iacute;var', 'classified-listing' ),
			'VND' => esc_html__( 'Vietnamese &#x111;&#x1ed3;ng', 'classified-listing' ),
			'VUV' => esc_html__( 'Vanuatu vatu', 'classified-listing' ),
			'WST' => esc_html__( 'Samoan t&#x101;l&#x101;', 'classified-listing' ),
			'XAF' => esc_html__( 'Central African CFA franc', 'classified-listing' ),
			'XCD' => esc_html__( 'East Caribbean dollar', 'classified-listing' ),
			'XOF' => esc_html__( 'West African CFA franc', 'classified-listing' ),
			'XPF' => esc_html__( 'CFP franc', 'classified-listing' ),
			'YER' => esc_html__( 'Yemeni rial', 'classified-listing' ),
			'ZAR' => esc_html__( 'South African rand', 'classified-listing' ),
			'ZMW' => esc_html__( 'Zambian kwacha', 'classified-listing' ),
		];

		return apply_filters( 'rtcl_currency_list', $currency_list );
	}


	public static function get_listing_pricing_types() {
		$types = [
			'price'    => esc_html__( 'Price', 'classified-listing' ),
			'range'    => esc_html__( 'Price Range', 'classified-listing' ),
			'disabled' => esc_html__( 'Disabled', 'classified-listing' ),
		];

		return apply_filters( 'rtcl_listing_pricing_types', $types );
	}


	public static function get_pricing_types() {
		$types = [
			'regular' => esc_html__( 'Regular', 'classified-listing' ),
		];

		return apply_filters( 'rtcl_payment_pricing_types', $types );
	}

	public static function get_currency_positions() {
		return [
			'left'        => esc_html__( 'Left ($99)', 'classified-listing' ),
			'right'       => esc_html__( 'Right (99$)', 'classified-listing' ),
			'left_space'  => esc_html__( 'Left with space ($ 99)', 'classified-listing' ),
			'right_space' => esc_html__( 'Right with space (99 $)', 'classified-listing' )
		];
	}

	public static function get_icon_list() {
		$icons = [
			'500px',
			'address-book',
			'address-book-o',
			'address-card',
			'address-card-o',
			'adjust',
			'align-left',
			'amazon',
			'ambulance',
			'american-sign-language-interpreting',
			'anchor',
			'android',
			'angellist',
			'angle-circled-down',
			'angle-circled-left',
			'angle-circled-right',
			'angle-circled-up',
			'angle-double-down',
			'angle-double-left',
			'angle-double-right',
			'angle-double-up',
			'angle-down',
			'angle-left',
			'angle-right',
			'angle-up',
			'apple',
			'arrows-cw',
			'asl-interpreting',
			'assistive-listening-systems',
			'asterisk',
			'at',
			'attach',
			'attach-2',
			'attention',
			'attention-alt',
			'attention-circled',
			'audio-description',
			'award',
			'balance-scale',
			'bandcamp',
			'bank',
			'barcode',
			'basket',
			'bath',
			'battery-0',
			'battery-1',
			'battery-2',
			'battery-3',
			'battery-4',
			'beaker',
			'beaker-1',
			'bed',
			'behance',
			'behance-squared',
			'bell',
			'bell-alt',
			'bell-off',
			'bell-off-empty',
			'bicycle',
			'binoculars',
			'birthday',
			'bitbucket',
			'bitbucket-squared',
			'bitcoin',
			'black-tie',
			'blank',
			'blind',
			'block',
			'bluetooth',
			'bluetooth-b',
			'bold',
			'bomb',
			'book',
			'book-open',
			'bookmark',
			'bookmark-empty',
			'box',
			'braille',
			'briefcase',
			'brush',
			'bug',
			'building',
			'building-filled',
			'bullseye',
			'bus',
			'buysellads',
			'cab',
			'calc',
			'calendar',
			'calendar-1',
			'calendar-check-o',
			'calendar-empty',
			'calendar-minus-o',
			'calendar-plus-o',
			'calendar-times-o',
			'camera',
			'camera-1',
			'camera-alt',
			'cancel',
			'cancel-2',
			'cancel-circled',
			'cancel-circled2',
			'cc',
			'cc-amex',
			'cc-diners-club',
			'cc-discover',
			'cc-jcb',
			'cc-mastercard',
			'cc-paypal',
			'cc-stripe',
			'cc-visa',
			'ccw',
			'cd-1',
			'certificate',
			'chart-area',
			'chart-bar',
			'chart-line',
			'chart-pie',
			'chat',
			'chat-empty',
			'check',
			'check-1',
			'check-empty',
			'child',
			'chrome',
			'circle',
			'circle-empty',
			'circle-notch',
			'circle-thin',
			'clock',
			'clock-2',
			'clone',
			'cloud',
			'cloud-2',
			'code',
			'codeopen',
			'codiepie',
			'coffee',
			'cog',
			'cog-2',
			'cog-alt',
			'collapse',
			'collapse-left',
			'columns',
			'comment',
			'comment-3',
			'comment-empty',
			'commenting',
			'commenting-o',
			'compass',
			'connectdevelop',
			'contao',
			'copyright',
			'creative-commons',
			'credit-card',
			'credit-card-alt',
			'crop',
			'css3',
			'cube',
			'cubes',
			'cup',
			'cw',
			'dashcube',
			'database',
			'database-1',
			'delicious',
			'desktop',
			'desktop-1',
			'deviantart',
			'diamond',
			'diamond-1',
			'digg',
			'direction',
			'doc',
			'doc-2',
			'doc-inv',
			'doc-text',
			'doc-text-inv',
			'docs',
			'dollar',
			'dot-circled',
			'down',
			'down-big',
			'down-circled',
			'down-circled2',
			'down-dir',
			'down-hand',
			'down-open',
			'download',
			'download-cloud',
			'dribbble',
			'dropbox',
			'drupal',
			'edge',
			'edit',
			'eercast',
			'eject',
			'ellipsis',
			'ellipsis-vert',
			'empire',
			'envelope-open',
			'envelope-open-o',
			'envira',
			'eraser',
			'etsy',
			'euro',
			'exchange',
			'expand',
			'expand-right',
			'expeditedssl',
			'export',
			'export-alt',
			'extinguisher',
			'eye',
			'eye-1',
			'eye-2',
			'eye-off',
			'eyedropper',
			'facebook',
			'facebook-official',
			'facebook-squared',
			'fast-bw',
			'fast-fw',
			'female',
			'fighter-jet',
			'file-archive',
			'file-image',
			'file-pdf',
			'filter',
			'fire',
			'fire-1',
			'firefox',
			'first-order',
			'flag',
			'flag-checkered',
			'flag-empty',
			'flash',
			'flash-1',
			'flickr',
			'flight',
			'flight-1',
			'floppy',
			'folder',
			'folder-empty',
			'folder-open',
			'folder-open-empty',
			'font',
			'font-awesome',
			'fonticons',
			'food',
			'food-1',
			'fork',
			'fort-awesome',
			'forumbee',
			'forward',
			'foursquare',
			'free-code-camp',
			'frown',
			'gamepad',
			'gauge',
			'genderless',
			'get-pocket',
			'gg',
			'gg-circle',
			'gift',
			'git',
			'git-squared',
			'github',
			'github-circled',
			'github-squared',
			'gitlab',
			'gittip',
			'glass',
			'glide-g',
			'globe',
			'globe-1',
			'google',
			'google-plus-circle',
			'gplus',
			'gplus-squared',
			'graduation-cap',
			'graduation-cap-1',
			'grav',
			'gwallet',
			'h-sigh',
			'hacker-news',
			'hammer',
			'hand-grab-o',
			'hand-lizard-o',
			'hand-paper-o',
			'hand-peace-o',
			'hand-pointer-o',
			'hand-scissors-o',
			'hand-spock-o',
			'handshake-o',
			'hashtag',
			'hdd',
			'header',
			'headphones',
			'heart',
			'heart-2',
			'heart-empty',
			'heartbeat',
			'help',
			'help-circled',
			'home',
			'hospital',
			'hourglass',
			'hourglass-1',
			'hourglass-2',
			'hourglass-3',
			'hourglass-o',
			'houzz',
			'i-cursor',
			'id-badge',
			'id-card',
			'id-card-o',
			'imdb',
			'inbox',
			'inbox-2',
			'industry',
			'info',
			'info-circled',
			'instagram',
			'internet-explorer',
			'ioxhost',
			'italic',
			'joomla',
			'jsfiddle',
			'key',
			'key-1',
			'keyboard',
			'language',
			'laptop',
			'lastfm',
			'lastfm-squared',
			'layout',
			'leaf',
			'leanpub',
			'left',
			'left-big',
			'left-circled',
			'left-circled2',
			'left-dir',
			'left-hand',
			'left-open',
			'lemon',
			'level-down',
			'level-up',
			'lifebuoy',
			'lightbulb',
			'lightbulb-1',
			'link',
			'link-ext',
			'link-ext-alt',
			'linkedin',
			'linkedin-squared',
			'linode',
			'linux',
			'list-alt',
			'location',
			'location-2',
			'lock',
			'lock-2',
			'lock-open',
			'lock-open-alt',
			'login',
			'logout',
			'low-vision',
			'magic',
			'magnet',
			'mail',
			'mail-1',
			'mail-alt',
			'male',
			'map',
			'map-o',
			'map-pin',
			'map-signs',
			'mars',
			'mars-double',
			'mars-stroke',
			'mars-stroke-h',
			'mars-stroke-v',
			'maxcdn',
			'meanpath',
			'medium',
			'medkit',
			'meetup',
			'megaphone',
			'megaphone-1',
			'meh',
			'menu',
			'mercury',
			'mic',
			'microchip',
			'minus',
			'minus-1',
			'minus-circled',
			'minus-circled-1',
			'minus-squared',
			'minus-squared-1',
			'minus-squared-alt',
			'mixcloud',
			'mobile',
			'mobile-1',
			'modx',
			'money',
			'money-1',
			'moon',
			'moon-1',
			'motorcycle',
			'mouse-pointer',
			'move',
			'music',
			'music-2',
			'mute',
			'neuter',
			'newspaper',
			'note-1',
			'object-group',
			'object-ungroup',
			'odnoklassniki',
			'odnoklassniki-square',
			'off',
			'ok',
			'ok-circled',
			'ok-circled2',
			'ok-squared',
			'opencart',
			'openid',
			'opera',
			'optin-monster',
			'pagelines',
			'paper-plane',
			'paper-plane-1',
			'paper-plane-2',
			'paper-plane-empty',
			'params',
			'paste',
			'pause',
			'pause-circle',
			'pause-circle-o',
			'paw',
			'paypal',
			'pencil',
			'pencil-1',
			'pencil-squared',
			'percent',
			'phone',
			'phone-squared',
			'photo',
			'picture',
			'pied-piper',
			'pied-piper-alt',
			'pied-piper-squared',
			'pin',
			'pinterest',
			'pinterest-circled',
			'pinterest-squared',
			'play',
			'play-circled',
			'play-circled2',
			'plug',
			'plus',
			'plus-1',
			'plus-circled',
			'plus-circled-1',
			'plus-squared',
			'plus-squared-1',
			'plus-squared-alt',
			'podcast',
			'pound',
			'print',
			'product-hunt',
			'puzzle',
			'qq',
			'qrcode',
			'question-circle-o',
			'quora',
			'quote-left',
			'quote-right',
			'ravelry',
			'rebel',
			'record',
			'recycle',
			'reddit',
			'reddit-alien',
			'reddit-squared',
			'registered',
			'renren',
			'reply',
			'reply-all',
			'resize-full',
			'resize-full-alt',
			'resize-horizontal',
			'resize-small',
			'resize-vertical',
			'retweet',
			'right',
			'right-big',
			'right-circled',
			'right-circled2',
			'right-dir',
			'right-hand',
			'road',
			'rocket',
			'rouble',
			'rss',
			'rss-squared',
			'rupee',
			'safari',
			'scissors',
			'scribd',
			'search',
			'search-2',
			'sellsy',
			'server',
			'share',
			'shekel',
			'shield',
			'ship',
			'shirtsinbulk',
			'shop',
			'shopping-bag',
			'shopping-basket',
			'shower',
			'shuffle',
			'sign-language',
			'signal',
			'signal-1',
			'simplybuilt',
			'sitemap',
			'skyatlas',
			'skype',
			'slack',
			'sliders',
			'slideshare',
			'smile',
			'snapchat',
			'snapchat-ghost',
			'snapchat-square',
			'snowflake-o',
			'soccer-ball',
			'sort',
			'sort-alt-down',
			'sort-alt-up',
			'sort-down',
			'sort-name-down',
			'sort-name-up',
			'sort-number-down',
			'sort-number-up',
			'sort-up',
			'sound',
			'soundcloud',
			'space-shuttle',
			'spinner',
			'spoon',
			'spotify',
			'stackexchange',
			'stackoverflow',
			'star',
			'star-2',
			'star-3',
			'star-empty',
			'star-empty-1',
			'star-half',
			'star-half-alt',
			'steam',
			'steam-squared',
			'stethoscope',
			'sticky-note',
			'sticky-note-o',
			'stop',
			'stop-circle',
			'stop-circle-o',
			'street-view',
			'strike',
			'stumbleupon',
			'stumbleupon-circled',
			'subscript',
			'subway',
			'suitcase',
			'sun',
			'superpowers',
			'superscript',
			't-shirt',
			'table',
			'tablet',
			'tag',
			'tag-2',
			'tags',
			'target',
			'tasks',
			'taxi',
			'telegram',
			'television',
			'tencent-weibo',
			'terminal',
			'text-width',
			'th',
			'th-large',
			'th-list',
			'themeisle',
			'thermometer',
			'thermometer-0',
			'thermometer-2',
			'thermometer-3',
			'thermometer-quarter',
			'thumbs-down',
			'thumbs-down-1',
			'thumbs-down-alt',
			'thumbs-up',
			'thumbs-up-1',
			'thumbs-up-2',
			'thumbs-up-alt',
			'ticket',
			'tint',
			'to-end',
			'to-end-alt',
			'to-start',
			'to-start-alt',
			'toggle-off',
			'toggle-on',
			'trademark',
			'train',
			'transgender',
			'transgender-alt',
			'trash',
			'trash-1',
			'trash-empty',
			'tree',
			'trello',
			'tripadvisor',
			'trophy',
			'truck',
			'truck-1',
			'try',
			'tty',
			'tumblr',
			'tumblr-squared',
			'tv',
			'twitch',
			'twitter',
			'twitter-squared',
			'umbrella',
			'underline',
			'universal-access',
			'unlink',
			'up',
			'up-big',
			'up-circled',
			'up-circled2',
			'up-dir',
			'up-hand',
			'up-open',
			'upload',
			'upload-cloud',
			'usb',
			'user',
			'user-2',
			'user-3',
			'user-circle',
			'user-circle-o',
			'user-md',
			'user-o',
			'user-plus',
			'user-secret',
			'user-times',
			'users',
			'venus',
			'venus-double',
			'venus-mars',
			'viacoin',
			'viadeo',
			'viadeo-square',
			'video',
			'videocam',
			'videocam-1',
			'vimeo',
			'vimeo-squared',
			'vine',
			'vkontakte',
			'volume-control-phone',
			'volume-down',
			'volume-off',
			'volume-up',
			'wallet',
			'wechat',
			'weibo',
			'whatsapp',
			'wheelchair',
			'wheelchair-alt',
			'wifi',
			'wikipedia-w',
			'window-close',
			'window-close-o',
			'window-maximize',
			'window-minimize',
			'window-restore',
			'windows',
			'won',
			'wordpress',
			'wpbeginner',
			'wpexplorer',
			'wpforms',
			'wrench',
			'xing',
			'xing-squared',
			'y-combinator',
			'yahoo',
			'yelp',
			'yen',
			'yoast',
			'youtube',
			'youtube-play',
			'youtube-squared',
			'zoom-in',
			'zoom-out',
			'fas fa-walking',
			'fa-solid fa-truck-fast',
			'fa-solid fa-pen-nib',
			'fa-solid fa-bolt',
			'fa-solid fa-bottle-water',
			'fa-solid fa-ferry',
			'fa-solid fa-ship',
			'fa-solid fa-snowflake',
			'fa-solid fa-fish',
			'fa-solid fa-cow',
			'fa-solid fa-dove',
			'fa-solid fa-crow',
			'fa-brands fa-the-red-yeti',
			'fa-solid fa-feather-pointed',
			'fa-solid fa-dog',
			'fa-solid fa-cat',
			'fa-solid fa-bone',
			'fa-solid fa-frog',
			'fa-solid fa-car',
			'fa-solid fa-caravan',
			'fa-solid fa-water',
			'fa-solid fa-crown',
			'fa-solid fa-tractor',
			'fa-solid fa-syringe',
		];

		return apply_filters( 'rtcl_get_icon_list', $icons );
	}

	public static function get_icon_class_list() {
		$icons = [

			'rtcl-icon-500px',
			'rtcl-icon-address-book',
			'rtcl-icon-address-book-o',
			'rtcl-icon-address-card',
			'rtcl-icon-address-card-o',
			'rtcl-icon-adjust',
			'rtcl-icon-align-left',
			'rtcl-icon-amazon',
			'rtcl-icon-ambulance',
			'rtcl-icon-american-sign-language-interpreting',
			'rtcl-icon-anchor',
			'rtcl-icon-android',
			'rtcl-icon-angellist',
			'rtcl-icon-angle-circled-down',
			'rtcl-icon-angle-circled-left',
			'rtcl-icon-angle-circled-right',
			'rtcl-icon-angle-circled-up',
			'rtcl-icon-angle-double-down',
			'rtcl-icon-angle-double-left',
			'rtcl-icon-angle-double-right',
			'rtcl-icon-angle-double-up',
			'rtcl-icon-angle-down',
			'rtcl-icon-angle-left',
			'rtcl-icon-angle-right',
			'rtcl-icon-angle-up',
			'rtcl-icon-apple',
			'rtcl-icon-arrows-cw',
			'rtcl-icon-asl-interpreting',
			'rtcl-icon-assistive-listening-systems',
			'rtcl-icon-asterisk',
			'rtcl-icon-at',
			'rtcl-icon-attach',
			'rtcl-icon-attach-2',
			'rtcl-icon-attention',
			'rtcl-icon-attention-alt',
			'rtcl-icon-attention-circled',
			'rtcl-icon-audio-description',
			'rtcl-icon-award',
			'rtcl-icon-balance-scale',
			'rtcl-icon-bandcamp',
			'rtcl-icon-bank',
			'rtcl-icon-barcode',
			'rtcl-icon-basket',
			'rtcl-icon-bath',
			'rtcl-icon-battery-0',
			'rtcl-icon-battery-1',
			'rtcl-icon-battery-2',
			'rtcl-icon-battery-3',
			'rtcl-icon-battery-4',
			'rtcl-icon-beaker',
			'rtcl-icon-beaker-1',
			'rtcl-icon-bed',
			'rtcl-icon-behance',
			'rtcl-icon-behance-squared',
			'rtcl-icon-bell',
			'rtcl-icon-bell-alt',
			'rtcl-icon-bell-off',
			'rtcl-icon-bell-off-empty',
			'rtcl-icon-bicycle',
			'rtcl-icon-binoculars',
			'rtcl-icon-birthday',
			'rtcl-icon-bitbucket',
			'rtcl-icon-bitbucket-squared',
			'rtcl-icon-bitcoin',
			'rtcl-icon-black-tie',
			'rtcl-icon-blank',
			'rtcl-icon-blind',
			'rtcl-icon-block',
			'rtcl-icon-bluetooth',
			'rtcl-icon-bluetooth-b',
			'rtcl-icon-bold',
			'rtcl-icon-bomb',
			'rtcl-icon-book',
			'rtcl-icon-book-open',
			'rtcl-icon-bookmark',
			'rtcl-icon-bookmark-empty',
			'rtcl-icon-box',
			'rtcl-icon-braille',
			'rtcl-icon-briefcase',
			'rtcl-icon-brush',
			'rtcl-icon-bug',
			'rtcl-icon-building',
			'rtcl-icon-building-filled',
			'rtcl-icon-bullseye',
			'rtcl-icon-bus',
			'rtcl-icon-buysellads',
			'rtcl-icon-cab',
			'rtcl-icon-calc',
			'rtcl-icon-calendar',
			'rtcl-icon-calendar-1',
			'rtcl-icon-calendar-check-o',
			'rtcl-icon-calendar-empty',
			'rtcl-icon-calendar-minus-o',
			'rtcl-icon-calendar-plus-o',
			'rtcl-icon-calendar-times-o',
			'rtcl-icon-camera',
			'rtcl-icon-camera-1',
			'rtcl-icon-camera-alt',
			'rtcl-icon-cancel',
			'rtcl-icon-cancel-2',
			'rtcl-icon-cancel-circled',
			'rtcl-icon-cancel-circled2',
			'rtcl-icon-cc',
			'rtcl-icon-cc-amex',
			'rtcl-icon-cc-diners-club',
			'rtcl-icon-cc-discover',
			'rtcl-icon-cc-jcb',
			'rtcl-icon-cc-mastercard',
			'rtcl-icon-cc-paypal',
			'rtcl-icon-cc-stripe',
			'rtcl-icon-cc-visa',
			'rtcl-icon-ccw',
			'rtcl-icon-cd-1',
			'rtcl-icon-certificate',
			'rtcl-icon-chart-area',
			'rtcl-icon-chart-bar',
			'rtcl-icon-chart-line',
			'rtcl-icon-chart-pie',
			'rtcl-icon-chat',
			'rtcl-icon-chat-empty',
			'rtcl-icon-check',
			'rtcl-icon-check-1',
			'rtcl-icon-check-empty',
			'rtcl-icon-child',
			'rtcl-icon-chrome',
			'rtcl-icon-circle',
			'rtcl-icon-circle-empty',
			'rtcl-icon-circle-notch',
			'rtcl-icon-circle-thin',
			'rtcl-icon-clock',
			'rtcl-icon-clock-2',
			'rtcl-icon-clone',
			'rtcl-icon-cloud',
			'rtcl-icon-cloud-2',
			'rtcl-icon-code',
			'rtcl-icon-codeopen',
			'rtcl-icon-codiepie',
			'rtcl-icon-coffee',
			'rtcl-icon-cog',
			'rtcl-icon-cog-2',
			'rtcl-icon-cog-alt',
			'rtcl-icon-collapse',
			'rtcl-icon-collapse-left',
			'rtcl-icon-columns',
			'rtcl-icon-comment',
			'rtcl-icon-comment-3',
			'rtcl-icon-comment-empty',
			'rtcl-icon-commenting',
			'rtcl-icon-commenting-o',
			'rtcl-icon-compass',
			'rtcl-icon-connectdevelop',
			'rtcl-icon-contao',
			'rtcl-icon-copyright',
			'rtcl-icon-creative-commons',
			'rtcl-icon-credit-card',
			'rtcl-icon-credit-card-alt',
			'rtcl-icon-crop',
			'rtcl-icon-css3',
			'rtcl-icon-cube',
			'rtcl-icon-cubes',
			'rtcl-icon-cup',
			'rtcl-icon-cw',
			'rtcl-icon-dashcube',
			'rtcl-icon-database',
			'rtcl-icon-database-1',
			'rtcl-icon-delicious',
			'rtcl-icon-desktop',
			'rtcl-icon-desktop-1',
			'rtcl-icon-deviantart',
			'rtcl-icon-diamond',
			'rtcl-icon-diamond-1',
			'rtcl-icon-digg',
			'rtcl-icon-direction',
			'rtcl-icon-doc',
			'rtcl-icon-doc-2',
			'rtcl-icon-doc-inv',
			'rtcl-icon-doc-text',
			'rtcl-icon-doc-text-inv',
			'rtcl-icon-docs',
			'rtcl-icon-dollar',
			'rtcl-icon-dot-circled',
			'rtcl-icon-down',
			'rtcl-icon-down-big',
			'rtcl-icon-down-circled',
			'rtcl-icon-down-circled2',
			'rtcl-icon-down-dir',
			'rtcl-icon-down-hand',
			'rtcl-icon-down-open',
			'rtcl-icon-download',
			'rtcl-icon-download-cloud',
			'rtcl-icon-dribbble',
			'rtcl-icon-dropbox',
			'rtcl-icon-drupal',
			'rtcl-icon-edge',
			'rtcl-icon-edit',
			'rtcl-icon-eercast',
			'rtcl-icon-eject',
			'rtcl-icon-ellipsis',
			'rtcl-icon-ellipsis-vert',
			'rtcl-icon-empire',
			'rtcl-icon-envelope-open',
			'rtcl-icon-envelope-open-o',
			'rtcl-icon-envira',
			'rtcl-icon-eraser',
			'rtcl-icon-etsy',
			'rtcl-icon-euro',
			'rtcl-icon-exchange',
			'rtcl-icon-expand',
			'rtcl-icon-expand-right',
			'rtcl-icon-expeditedssl',
			'rtcl-icon-export',
			'rtcl-icon-export-alt',
			'rtcl-icon-extinguisher',
			'rtcl-icon-eye',
			'rtcl-icon-eye-1',
			'rtcl-icon-eye-2',
			'rtcl-icon-eye-off',
			'rtcl-icon-eyedropper',
			'rtcl-icon-facebook',
			'rtcl-icon-facebook-official',
			'rtcl-icon-facebook-squared',
			'rtcl-icon-fast-bw',
			'rtcl-icon-fast-fw',
			'rtcl-icon-female',
			'rtcl-icon-fighter-jet',
			'rtcl-icon-file-archive',
			'rtcl-icon-file-image',
			'rtcl-icon-file-pdf',
			'rtcl-icon-filter',
			'rtcl-icon-fire',
			'rtcl-icon-fire-1',
			'rtcl-icon-firefox',
			'rtcl-icon-first-order',
			'rtcl-icon-flag',
			'rtcl-icon-flag-checkered',
			'rtcl-icon-flag-empty',
			'rtcl-icon-flash',
			'rtcl-icon-flash-1',
			'rtcl-icon-flickr',
			'rtcl-icon-flight',
			'rtcl-icon-flight-1',
			'rtcl-icon-floppy',
			'rtcl-icon-folder',
			'rtcl-icon-folder-empty',
			'rtcl-icon-folder-open',
			'rtcl-icon-folder-open-empty',
			'rtcl-icon-font',
			'rtcl-icon-font-awesome',
			'rtcl-icon-fonticons',
			'rtcl-icon-food',
			'rtcl-icon-food-1',
			'rtcl-icon-fork',
			'rtcl-icon-fort-awesome',
			'rtcl-icon-forumbee',
			'rtcl-icon-forward',
			'rtcl-icon-foursquare',
			'rtcl-icon-free-code-camp',
			'rtcl-icon-frown',
			'rtcl-icon-gamepad',
			'rtcl-icon-gauge',
			'rtcl-icon-genderless',
			'rtcl-icon-get-pocket',
			'rtcl-icon-gg',
			'rtcl-icon-gg-circle',
			'rtcl-icon-gift',
			'rtcl-icon-git',
			'rtcl-icon-git-squared',
			'rtcl-icon-github',
			'rtcl-icon-github-circled',
			'rtcl-icon-github-squared',
			'rtcl-icon-gitlab',
			'rtcl-icon-gittip',
			'rtcl-icon-glass',
			'rtcl-icon-glide-g',
			'rtcl-icon-globe',
			'rtcl-icon-globe-1',
			'rtcl-icon-google',
			'rtcl-icon-google-plus-circle',
			'rtcl-icon-gplus',
			'rtcl-icon-gplus-squared',
			'rtcl-icon-graduation-cap',
			'rtcl-icon-graduation-cap-1',
			'rtcl-icon-grav',
			'rtcl-icon-gwallet',
			'rtcl-icon-h-sigh',
			'rtcl-icon-hacker-news',
			'rtcl-icon-hammer',
			'rtcl-icon-hand-grab-o',
			'rtcl-icon-hand-lizard-o',
			'rtcl-icon-hand-paper-o',
			'rtcl-icon-hand-peace-o',
			'rtcl-icon-hand-pointer-o',
			'rtcl-icon-hand-scissors-o',
			'rtcl-icon-hand-spock-o',
			'rtcl-icon-handshake-o',
			'rtcl-icon-hashtag',
			'rtcl-icon-hdd',
			'rtcl-icon-header',
			'rtcl-icon-headphones',
			'rtcl-icon-heart',
			'rtcl-icon-heart-2',
			'rtcl-icon-heart-empty',
			'rtcl-icon-heartbeat',
			'rtcl-icon-help',
			'rtcl-icon-help-circled',
			'rtcl-icon-home',
			'rtcl-icon-hospital',
			'rtcl-icon-hourglass',
			'rtcl-icon-hourglass-1',
			'rtcl-icon-hourglass-2',
			'rtcl-icon-hourglass-3',
			'rtcl-icon-hourglass-o',
			'rtcl-icon-houzz',
			'rtcl-icon-i-cursor',
			'rtcl-icon-id-badge',
			'rtcl-icon-id-card',
			'rtcl-icon-id-card-o',
			'rtcl-icon-imdb',
			'rtcl-icon-inbox',
			'rtcl-icon-inbox-2',
			'rtcl-icon-industry',
			'rtcl-icon-info',
			'rtcl-icon-info-circled',
			'rtcl-icon-instagram',
			'rtcl-icon-internet-explorer',
			'rtcl-icon-ioxhost',
			'rtcl-icon-italic',
			'rtcl-icon-joomla',
			'rtcl-icon-jsfiddle',
			'rtcl-icon-key',
			'rtcl-icon-key-1',
			'rtcl-icon-keyboard',
			'rtcl-icon-language',
			'rtcl-icon-laptop',
			'rtcl-icon-lastfm',
			'rtcl-icon-lastfm-squared',
			'rtcl-icon-layout',
			'rtcl-icon-leaf',
			'rtcl-icon-leanpub',
			'rtcl-icon-left',
			'rtcl-icon-left-big',
			'rtcl-icon-left-circled',
			'rtcl-icon-left-circled2',
			'rtcl-icon-left-dir',
			'rtcl-icon-left-hand',
			'rtcl-icon-left-open',
			'rtcl-icon-lemon',
			'rtcl-icon-level-down',
			'rtcl-icon-level-up',
			'rtcl-icon-lifebuoy',
			'rtcl-icon-lightbulb',
			'rtcl-icon-lightbulb-1',
			'rtcl-icon-link',
			'rtcl-icon-link-ext',
			'rtcl-icon-link-ext-alt',
			'rtcl-icon-linkedin',
			'rtcl-icon-linkedin-squared',
			'rtcl-icon-linode',
			'rtcl-icon-linux',
			'rtcl-icon-list-alt',
			'rtcl-icon-location',
			'rtcl-icon-location-2',
			'rtcl-icon-lock',
			'rtcl-icon-lock-2',
			'rtcl-icon-lock-open',
			'rtcl-icon-lock-open-alt',
			'rtcl-icon-login',
			'rtcl-icon-logout',
			'rtcl-icon-low-vision',
			'rtcl-icon-magic',
			'rtcl-icon-magnet',
			'rtcl-icon-mail',
			'rtcl-icon-mail-1',
			'rtcl-icon-mail-alt',
			'rtcl-icon-male',
			'rtcl-icon-map',
			'rtcl-icon-map-o',
			'rtcl-icon-map-pin',
			'rtcl-icon-map-signs',
			'rtcl-icon-mars',
			'rtcl-icon-mars-double',
			'rtcl-icon-mars-stroke',
			'rtcl-icon-mars-stroke-h',
			'rtcl-icon-mars-stroke-v',
			'rtcl-icon-maxcdn',
			'rtcl-icon-meanpath',
			'rtcl-icon-medium',
			'rtcl-icon-medkit',
			'rtcl-icon-meetup',
			'rtcl-icon-megaphone',
			'rtcl-icon-megaphone-1',
			'rtcl-icon-meh',
			'rtcl-icon-menu',
			'rtcl-icon-mercury',
			'rtcl-icon-mic',
			'rtcl-icon-microchip',
			'rtcl-icon-minus',
			'rtcl-icon-minus-1',
			'rtcl-icon-minus-circled',
			'rtcl-icon-minus-circled-1',
			'rtcl-icon-minus-squared',
			'rtcl-icon-minus-squared-1',
			'rtcl-icon-minus-squared-alt',
			'rtcl-icon-mixcloud',
			'rtcl-icon-mobile',
			'rtcl-icon-mobile-1',
			'rtcl-icon-modx',
			'rtcl-icon-money',
			'rtcl-icon-money-1',
			'rtcl-icon-moon',
			'rtcl-icon-moon-1',
			'rtcl-icon-motorcycle',
			'rtcl-icon-mouse-pointer',
			'rtcl-icon-move',
			'rtcl-icon-music',
			'rtcl-icon-music-2',
			'rtcl-icon-mute',
			'rtcl-icon-neuter',
			'rtcl-icon-newspaper',
			'rtcl-icon-note-1',
			'rtcl-icon-object-group',
			'rtcl-icon-object-ungroup',
			'rtcl-icon-odnoklassniki',
			'rtcl-icon-odnoklassniki-square',
			'rtcl-icon-off',
			'rtcl-icon-ok',
			'rtcl-icon-ok-circled',
			'rtcl-icon-ok-circled2',
			'rtcl-icon-ok-squared',
			'rtcl-icon-opencart',
			'rtcl-icon-openid',
			'rtcl-icon-opera',
			'rtcl-icon-optin-monster',
			'rtcl-icon-pagelines',
			'rtcl-icon-paper-plane',
			'rtcl-icon-paper-plane-1',
			'rtcl-icon-paper-plane-2',
			'rtcl-icon-paper-plane-empty',
			'rtcl-icon-params',
			'rtcl-icon-paste',
			'rtcl-icon-pause',
			'rtcl-icon-pause-circle',
			'rtcl-icon-pause-circle-o',
			'rtcl-icon-paw',
			'rtcl-icon-paypal',
			'rtcl-icon-pencil',
			'rtcl-icon-pencil-1',
			'rtcl-icon-pencil-squared',
			'rtcl-icon-percent',
			'rtcl-icon-phone',
			'rtcl-icon-phone-squared',
			'rtcl-icon-photo',
			'rtcl-icon-picture',
			'rtcl-icon-pied-piper',
			'rtcl-icon-pied-piper-alt',
			'rtcl-icon-pied-piper-squared',
			'rtcl-icon-pin',
			'rtcl-icon-pinterest',
			'rtcl-icon-pinterest-circled',
			'rtcl-icon-pinterest-squared',
			'rtcl-icon-play',
			'rtcl-icon-play-circled',
			'rtcl-icon-play-circled2',
			'rtcl-icon-plug',
			'rtcl-icon-plus',
			'rtcl-icon-plus-1',
			'rtcl-icon-plus-circled',
			'rtcl-icon-plus-circled-1',
			'rtcl-icon-plus-squared',
			'rtcl-icon-plus-squared-1',
			'rtcl-icon-plus-squared-alt',
			'rtcl-icon-podcast',
			'rtcl-icon-pound',
			'rtcl-icon-print',
			'rtcl-icon-product-hunt',
			'rtcl-icon-puzzle',
			'rtcl-icon-qq',
			'rtcl-icon-qrcode',
			'rtcl-icon-question-circle-o',
			'rtcl-icon-quora',
			'rtcl-icon-quote-left',
			'rtcl-icon-quote-right',
			'rtcl-icon-ravelry',
			'rtcl-icon-rebel',
			'rtcl-icon-record',
			'rtcl-icon-recycle',
			'rtcl-icon-reddit',
			'rtcl-icon-reddit-alien',
			'rtcl-icon-reddit-squared',
			'rtcl-icon-registered',
			'rtcl-icon-renren',
			'rtcl-icon-reply',
			'rtcl-icon-reply-all',
			'rtcl-icon-resize-full',
			'rtcl-icon-resize-full-alt',
			'rtcl-icon-resize-horizontal',
			'rtcl-icon-resize-small',
			'rtcl-icon-resize-vertical',
			'rtcl-icon-retweet',
			'rtcl-icon-right',
			'rtcl-icon-right-big',
			'rtcl-icon-right-circled',
			'rtcl-icon-right-circled2',
			'rtcl-icon-right-dir',
			'rtcl-icon-right-hand',
			'rtcl-icon-road',
			'rtcl-icon-rocket',
			'rtcl-icon-rouble',
			'rtcl-icon-rss',
			'rtcl-icon-rss-squared',
			'rtcl-icon-rupee',
			'rtcl-icon-safari',
			'rtcl-icon-scissors',
			'rtcl-icon-scribd',
			'rtcl-icon-search',
			'rtcl-icon-search-2',
			'rtcl-icon-sellsy',
			'rtcl-icon-server',
			'rtcl-icon-share',
			'rtcl-icon-shekel',
			'rtcl-icon-shield',
			'rtcl-icon-ship',
			'rtcl-icon-shirtsinbulk',
			'rtcl-icon-shop',
			'rtcl-icon-shopping-bag',
			'rtcl-icon-shopping-basket',
			'rtcl-icon-shower',
			'rtcl-icon-shuffle',
			'rtcl-icon-sign-language',
			'rtcl-icon-signal',
			'rtcl-icon-signal-1',
			'rtcl-icon-simplybuilt',
			'rtcl-icon-sitemap',
			'rtcl-icon-skyatlas',
			'rtcl-icon-skype',
			'rtcl-icon-slack',
			'rtcl-icon-sliders',
			'rtcl-icon-slideshare',
			'rtcl-icon-smile',
			'rtcl-icon-snapchat',
			'rtcl-icon-snapchat-ghost',
			'rtcl-icon-snapchat-square',
			'rtcl-icon-snowflake-o',
			'rtcl-icon-soccer-ball',
			'rtcl-icon-sort',
			'rtcl-icon-sort-alt-down',
			'rtcl-icon-sort-alt-up',
			'rtcl-icon-sort-down',
			'rtcl-icon-sort-name-down',
			'rtcl-icon-sort-name-up',
			'rtcl-icon-sort-number-down',
			'rtcl-icon-sort-number-up',
			'rtcl-icon-sort-up',
			'rtcl-icon-sound',
			'rtcl-icon-soundcloud',
			'rtcl-icon-space-shuttle',
			'rtcl-icon-spinner',
			'rtcl-icon-spoon',
			'rtcl-icon-spotify',
			'rtcl-icon-stackexchange',
			'rtcl-icon-stackoverflow',
			'rtcl-icon-star',
			'rtcl-icon-star-2',
			'rtcl-icon-star-3',
			'rtcl-icon-star-empty',
			'rtcl-icon-star-empty-1',
			'rtcl-icon-star-half',
			'rtcl-icon-star-half-alt',
			'rtcl-icon-steam',
			'rtcl-icon-steam-squared',
			'rtcl-icon-stethoscope',
			'rtcl-icon-sticky-note',
			'rtcl-icon-sticky-note-o',
			'rtcl-icon-stop',
			'rtcl-icon-stop-circle',
			'rtcl-icon-stop-circle-o',
			'rtcl-icon-street-view',
			'rtcl-icon-strike',
			'rtcl-icon-stumbleupon',
			'rtcl-icon-stumbleupon-circled',
			'rtcl-icon-subscript',
			'rtcl-icon-subway',
			'rtcl-icon-suitcase',
			'rtcl-icon-sun',
			'rtcl-icon-superpowers',
			'rtcl-icon-superscript',
			'rtcl-icon-t-shirt',
			'rtcl-icon-table',
			'rtcl-icon-tablet',
			'rtcl-icon-tag',
			'rtcl-icon-tag-2',
			'rtcl-icon-tags',
			'rtcl-icon-target',
			'rtcl-icon-tasks',
			'rtcl-icon-taxi',
			'rtcl-icon-telegram',
			'rtcl-icon-television',
			'rtcl-icon-tencent-weibo',
			'rtcl-icon-terminal',
			'rtcl-icon-text-width',
			'rtcl-icon-th',
			'rtcl-icon-th-large',
			'rtcl-icon-th-list',
			'rtcl-icon-themeisle',
			'rtcl-icon-thermometer',
			'rtcl-icon-thermometer-0',
			'rtcl-icon-thermometer-2',
			'rtcl-icon-thermometer-3',
			'rtcl-icon-thermometer-quarter',
			'rtcl-icon-thumbs-down',
			'rtcl-icon-thumbs-down-1',
			'rtcl-icon-thumbs-down-alt',
			'rtcl-icon-thumbs-up',
			'rtcl-icon-thumbs-up-1',
			'rtcl-icon-thumbs-up-2',
			'rtcl-icon-thumbs-up-alt',
			'rtcl-icon-ticket',
			'rtcl-icon-tint',
			'rtcl-icon-to-end',
			'rtcl-icon-to-end-alt',
			'rtcl-icon-to-start',
			'rtcl-icon-to-start-alt',
			'rtcl-icon-toggle-off',
			'rtcl-icon-toggle-on',
			'rtcl-icon-trademark',
			'rtcl-icon-train',
			'rtcl-icon-transgender',
			'rtcl-icon-transgender-alt',
			'rtcl-icon-trash',
			'rtcl-icon-trash-1',
			'rtcl-icon-trash-empty',
			'rtcl-icon-tree',
			'rtcl-icon-trello',
			'rtcl-icon-tripadvisor',
			'rtcl-icon-trophy',
			'rtcl-icon-truck',
			'rtcl-icon-truck-1',
			'rtcl-icon-try',
			'rtcl-icon-tty',
			'rtcl-icon-tumblr',
			'rtcl-icon-tumblr-squared',
			'rtcl-icon-tv',
			'rtcl-icon-twitch',
			'rtcl-icon-twitter',
			'rtcl-icon-twitter-squared',
			'rtcl-icon-umbrella',
			'rtcl-icon-underline',
			'rtcl-icon-universal-access',
			'rtcl-icon-unlink',
			'rtcl-icon-up',
			'rtcl-icon-up-big',
			'rtcl-icon-up-circled',
			'rtcl-icon-up-circled2',
			'rtcl-icon-up-dir',
			'rtcl-icon-up-hand',
			'rtcl-icon-up-open',
			'rtcl-icon-upload',
			'rtcl-icon-upload-cloud',
			'rtcl-icon-usb',
			'rtcl-icon-user',
			'rtcl-icon-user-2',
			'rtcl-icon-user-3',
			'rtcl-icon-user-circle',
			'rtcl-icon-user-circle-o',
			'rtcl-icon-user-md',
			'rtcl-icon-user-o',
			'rtcl-icon-user-plus',
			'rtcl-icon-user-secret',
			'rtcl-icon-user-times',
			'rtcl-icon-users',
			'rtcl-icon-venus',
			'rtcl-icon-venus-double',
			'rtcl-icon-venus-mars',
			'rtcl-icon-viacoin',
			'rtcl-icon-viadeo',
			'rtcl-icon-viadeo-square',
			'rtcl-icon-video',
			'rtcl-icon-videocam',
			'rtcl-icon-videocam-1',
			'rtcl-icon-vimeo',
			'rtcl-icon-vimeo-squared',
			'rtcl-icon-vine',
			'rtcl-icon-vkontakte',
			'rtcl-icon-volume-control-phone',
			'rtcl-icon-volume-down',
			'rtcl-icon-volume-off',
			'rtcl-icon-volume-up',
			'rtcl-icon-wallet',
			'rtcl-icon-wechat',
			'rtcl-icon-weibo',
			'rtcl-icon-whatsapp',
			'rtcl-icon-wheelchair',
			'rtcl-icon-wheelchair-alt',
			'rtcl-icon-wifi',
			'rtcl-icon-wikipedia-w',
			'rtcl-icon-window-close',
			'rtcl-icon-window-close-o',
			'rtcl-icon-window-maximize',
			'rtcl-icon-window-minimize',
			'rtcl-icon-window-restore',
			'rtcl-icon-windows',
			'rtcl-icon-won',
			'rtcl-icon-wordpress',
			'rtcl-icon-wpbeginner',
			'rtcl-icon-wpexplorer',
			'rtcl-icon-wpforms',
			'rtcl-icon-wrench',
			'rtcl-icon-xing',
			'rtcl-icon-xing-squared',
			'rtcl-icon-y-combinator',
			'rtcl-icon-yahoo',
			'rtcl-icon-yelp',
			'rtcl-icon-yen',
			'rtcl-icon-yoast',
			'rtcl-icon-youtube',
			'rtcl-icon-youtube-play',
			'rtcl-icon-youtube-squared',
			'rtcl-icon-zoom-in',
			'rtcl-icon-zoom-out'
		];

		return apply_filters( 'rtcl_get_icon_class_list', $icons );
	}


	public static function get_price_unit_list() {

		$unit_list = [
			'year'  => [
				'title' => esc_html__( 'Year', 'classified-listing' ),
				'short' => esc_html__( 'per year', 'classified-listing' )
			],
			'month' => [
				'title' => esc_html__( 'Month', 'classified-listing' ),
				'short' => esc_html__( 'per month', 'classified-listing' )
			],
			'week'  => [
				'title' => esc_html__( 'Week', 'classified-listing' ),
				'short' => esc_html__( 'per week', 'classified-listing' )
			],
			'day'   => [
				'title' => esc_html__( 'Day', 'classified-listing' ),
				'short' => esc_html__( 'per day', 'classified-listing' )
			],
			'hour'  => [
				'title' => esc_html__( 'Hour', 'classified-listing' ),
				'short' => esc_html__( 'per hour', 'classified-listing' )
			],
			'sqft'  => [
				'title' => esc_html__( 'Square Feet', 'classified-listing' ),
				'short' => esc_html__( 'per sqft', 'classified-listing' )
			],
			'total' => [
				'title' => esc_html__( 'Total Price', 'classified-listing' ),
				'short' => esc_html__( 'total price', 'classified-listing' )
			]
		];

		return apply_filters( 'rtcl_get_price_unit_list', $unit_list );
	}

	public static function get_admin_email_notification_options() {
		$options = [
			'register_new_user' => esc_html__( 'A new user is registered (Only work when user registered using Classified listing plugin registration form)',
				'classified-listing' ),
			'listing_submitted' => esc_html__( 'A new listing is submitted', 'classified-listing' ),
			'listing_edited'    => esc_html__( 'A listing is edited', 'classified-listing' ),
			'listing_expired'   => esc_html__( 'A listing expired', 'classified-listing' ),
			'order_created'     => esc_html__( 'Order created', 'classified-listing' ),
			'order_completed'   => esc_html__( 'Payment received / Order Completed', 'classified-listing' ),
			'listing_contact'   => esc_html__( 'Contact message (Email to listing owner)', 'classified-listing' )
		];

		return apply_filters( 'rtcl_get_admin_email_notification_options', $options );
	}

	public static function get_user_email_notification_options() {
		$options = [
			'register_new_user'     => esc_html__( 'A new user is registered (Only work when user registered using Classified listing plugin registration form)',
				'classified-listing' ),
			'listing_submitted'     => esc_html__( 'Listing is submitted', 'classified-listing' ),
			'listing_published'     => esc_html__( 'Listing is approved/published', 'classified-listing' ),
			'listing_renewal'       => esc_html__( 'Listing is about to expire (reached renewal email threshold).', 'classified-listing' ),
			'listing_expired'       => esc_html__( 'Listing expired', 'classified-listing' ),
			'remind_renewal'        => esc_html__( 'Listing expired and reached renewal reminder email threshold', 'classified-listing' ),
			'order_created'         => esc_html__( 'Order created', 'classified-listing' ),
			'order_completed'       => esc_html__( 'Order completed', 'classified-listing' ),
			'user_import'           => esc_html__( 'User imported', 'classified-listing' ),
			'disable_contact_email' => esc_html__( 'Disable contact email to listing owner', 'classified-listing' )
		];

		return apply_filters( 'rtcl_get_user_email_notification_options', $options );
	}

	public static function get_exclude_slugs() {
		$excludeSlugs = null;
		$exclude      = [];
		$potTypes     = get_post_types(
			[
				'public'   => true,
				'_builtin' => false
			]
		);
		foreach ( $potTypes as $pot_type ) {
			$obj = get_post_type_object( $pot_type );
			if ( $obj->rewrite['slug'] ) {
				$exclude[] = $obj->rewrite['slug'];
			} else {
				$exclude[] = $pot_type;
			}
		}
		$exclude = apply_filters( 'rtcl_get_exclude_slugs', $exclude );
		if ( ! empty( $exclude ) ) {
			$excludeSlugs = implode( '|', $exclude );
		}

		return apply_filters( 'rtcl_get_exclude_slugs_string', $excludeSlugs );
	}

	public static function get_email_type_options() {
		$types = [ 'plain' => esc_html__( 'Plain text', 'classified-listing' ) ];

		if ( class_exists( 'DOMDocument' ) ) {
			$types['html']      = esc_html__( 'HTML', 'classified-listing' );
			$types['multipart'] = esc_html__( 'Multipart', 'classified-listing' );
		}

		return $types;
	}

	public static function get_recaptcha_form_list() {
		return apply_filters(
			'rtcl_recaptcha_form_list',
			[
				'login'        => esc_html__( 'User Login form', 'classified-listing' ),
				'registration' => esc_html__( 'User Registration form', 'classified-listing' ),
				'listing'      => esc_html__( 'New Listing form', 'classified-listing' ),
				'contact'      => esc_html__( 'Contact form', 'classified-listing' ),
				'report_abuse' => esc_html__( 'Report abuse form', 'classified-listing' )
			]
		);
	}


	public static function get_listing_detail_page_display_options() {
		$options = [
			'date'       => esc_html__( 'Date added', 'classified-listing' ),
			'user'       => esc_html__( 'Listing owner name', 'classified-listing' ),
			'user_link'  => esc_html__( 'Listing owner link', 'classified-listing' ),
			'views'      => esc_html__( 'Views count', 'classified-listing' ),
			'featured'   => esc_html__( 'Feature Label', 'classified-listing' ),
			'new'        => esc_html__( 'New Label', 'classified-listing' ),
			'category'   => esc_html__( 'Category name', 'classified-listing' ),
			'location'   => esc_html__( 'Location name', 'classified-listing' ),
			'ad_type'    => esc_html__( 'Ad Type', 'classified-listing' ),
			'price'      => esc_html__( 'Price', 'classified-listing' ),
			'price_type' => esc_html__( 'Price type', 'classified-listing' ),
			'address'    => esc_html__( 'Address', 'classified-listing' ),
			'zipcode'    => esc_html__( 'Zip Code', 'classified-listing' )
		];

		return apply_filters( 'rtcl_get_listing_detail_page_display_options', $options );
	}

	public static function get_listing_common_display_options() {
		$options = [
			'category'   => esc_html__( 'Category name', 'classified-listing' ),
			'location'   => esc_html__( 'Location name', 'classified-listing' ),
			'ad_type'    => esc_html__( 'Ad Type', 'classified-listing' ),
			'price'      => esc_html__( 'Price', 'classified-listing' ),
			'price_type' => esc_html__( 'Price type', 'classified-listing' )
		];

		return apply_filters( 'rtcl_get_listing_common_display_options', $options );
	}

	public static function get_listing_display_options() {
		$options = [
			'date'       => esc_html__( 'Date added', 'classified-listing' ),
			'user'       => esc_html__( 'Listing owner name', 'classified-listing' ),
			'user_link'  => esc_html__( 'Listing owner link', 'classified-listing' ),
			'views'      => esc_html__( 'Views count', 'classified-listing' ),
			'featured'   => esc_html__( 'Feature Label', 'classified-listing' ),
			'new'        => esc_html__( 'New Label', 'classified-listing' ),
			'category'   => esc_html__( 'Category name', 'classified-listing' ),
			'location'   => esc_html__( 'Location name', 'classified-listing' ),
			'ad_type'    => esc_html__( 'Ad Type', 'classified-listing' ),
			'price'      => esc_html__( 'Price', 'classified-listing' ),
			'price_type' => esc_html__( 'Price type', 'classified-listing' ),
			'excerpt'    => esc_html__( 'Short description', 'classified-listing' )
		];

		return apply_filters( 'rtcl_get_listing_display_options', $options );
	}

	public static function get_listing_form_hide_fields() {
		$options = [
			'ad_type'         => esc_html__( 'Ad Type', 'classified-listing' ),
			'pricing_type'    => esc_html__( 'Pricing Type', 'classified-listing' ),
			'price_type'      => esc_html__( 'Price Type', 'classified-listing' ),
			'price'           => esc_html__( 'Price', 'classified-listing' ),
			'description'     => esc_html__( 'Description', 'classified-listing' ),
			'tags'            => esc_html__( 'Tags', 'classified-listing' ),
			'gallery'         => esc_html__( 'Gallery', 'classified-listing' ),
			'video_urls'      => esc_html__( 'Video URL', 'classified-listing' ),
			'location'        => esc_html__( 'Location', 'classified-listing' ),
			'zipcode'         => esc_html__( 'Zip Code', 'classified-listing' ),
			'address'         => esc_html__( 'Address', 'classified-listing' ),
			'phone'           => esc_html__( 'Phone', 'classified-listing' ),
			'whatsapp_number' => esc_html__( 'Whatsapp Number', 'classified-listing' ),
			'telegram'        => esc_html__( 'Telegram', 'classified-listing' ),
			'email'           => esc_html__( 'Email', 'classified-listing' ),
			'website'         => esc_html__( 'Website URL', 'classified-listing' ),
		];

		return apply_filters( 'rtcl_get_listing_form_hide_fields', $options );
	}

	public static function get_week_days() {
		global $wp_locale;
		$weekStart = apply_filters( 'rtcl_start_of_week', get_option( 'start_of_week' ) );
		$weekday   = $wp_locale->weekday;
		for ( $i = 0; $i < $weekStart; $i ++ ) {

			$day = array_slice( $weekday, 0, 1, true );
			unset( $weekday[ $i ] );

			$weekday = $weekday + $day;
		}

		return $weekday;
	}

	public static function get_timezone_list() {
		static $mo_loaded = false, $locale_loaded = null;

		$continents = [
			'Africa',
			'America',
			'Antarctica',
			'Arctic',
			'Asia',
			'Atlantic',
			'Australia',
			'Europe',
			'Indian',
			'Pacific'
		];

		$zonen = [];
		foreach ( timezone_identifiers_list() as $zone ) {
			$zone = explode( '/', $zone );
			if ( ! in_array( $zone[0], $continents, true ) ) {
				continue;
			}

			// This determines what gets set and translated - we don't translate Etc/* strings here, they are done later.
			$exists    = [
				0 => ( isset( $zone[0] ) && $zone[0] ),
				1 => ( isset( $zone[1] ) && $zone[1] ),
				2 => ( isset( $zone[2] ) && $zone[2] ),
			];
			$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
			$exists[4] = ( $exists[1] && $exists[3] );
			$exists[5] = ( $exists[2] && $exists[3] );

			// phpcs:disable WordPress.WP.I18n.LowLevelTranslationFunction,WordPress.WP.I18n.NonSingularStringLiteralText
			$zonen[] = [
				'continent'   => ( $exists[0] ? $zone[0] : '' ),
				'city'        => ( $exists[1] ? $zone[1] : '' ),
				'subcity'     => ( $exists[2] ? $zone[2] : '' ),
				't_continent' => ( $exists[3] ? translate( str_replace( '_', ' ', $zone[0] ), 'continents-cities' ) : '' ),
				't_city'      => ( $exists[4] ? translate( str_replace( '_', ' ', $zone[1] ), 'continents-cities' ) : '' ),
				't_subcity'   => ( $exists[5] ? translate( str_replace( '_', ' ', $zone[2] ), 'continents-cities' ) : '' ),
			];
			// phpcs:enable
		}
		usort( $zonen, '_wp_timezone_choice_usort_callback' );

		$zones  = [];
		$_zones = [];
		foreach ( $zonen as $key => $zone ) {
			// Build value in an array to join later.
			$value = [ $zone['continent'] ];

			if ( empty( $zone['city'] ) ) {
				// It's at the continent level (generally won't happen).
				$display = $zone['t_continent'];
			} else {

				// Add the city to the value.
				$value[] = $zone['city'];

				$display = $zone['t_city'];
				if ( ! empty( $zone['subcity'] ) ) {
					// Add the subcity to the value.
					$value[] = $zone['subcity'];
					$display .= ' - ' . $zone['t_subcity'];
				}
			}

			// Build the value.
			$value    = implode( '/', $value );
			$_zones[] = [
				'label' => esc_html( $display ),
				'value' => $value
			];
			// Close continent optgroup.
			if ( ! empty( $zone['city'] )
			     && ( ! isset( $zonen[ $key + 1 ] )
			          || ( isset( $zonen[ $key + 1 ] )
			               && $zonen[ $key + 1 ]['continent'] !== $zone['continent'] ) )
			) {
				$zones[] = [
					'label'   => $zone['t_continent'],
					'options' => $_zones
				];
			}
		}

		$zones[] = [
			'label' => __( 'UTC' ),
			'value' => 'UTC'
		];

		$manuals = [];
		// Do manual UTC offsets.
		$offset_range = [
			- 12,
			- 11.5,
			- 11,
			- 10.5,
			- 10,
			- 9.5,
			- 9,
			- 8.5,
			- 8,
			- 7.5,
			- 7,
			- 6.5,
			- 6,
			- 5.5,
			- 5,
			- 4.5,
			- 4,
			- 3.5,
			- 3,
			- 2.5,
			- 2,
			- 1.5,
			- 1,
			- 0.5,
			0,
			0.5,
			1,
			1.5,
			2,
			2.5,
			3,
			3.5,
			4,
			4.5,
			5,
			5.5,
			5.75,
			6,
			6.5,
			7,
			7.5,
			8,
			8.5,
			8.75,
			9,
			9.5,
			10,
			10.5,
			11,
			11.5,
			12,
			12.75,
			13,
			13.75,
			14,
		];
		foreach ( $offset_range as $offset ) {
			if ( 0 <= $offset ) {
				$offset_name = '+' . $offset;
			} else {
				$offset_name = (string) $offset;
			}

			$offset_value = $offset_name;
			$offset_name  = str_replace( [ '.25', '.5', '.75' ], [ ':15', ':30', ':45' ], $offset_name );
			$offset_name  = 'UTC' . $offset_name;
			$offset_value = 'UTC' . $offset_value;
			$manuals[]    = [
				'label' => esc_html( $offset_name ),
				'value' => $offset_value
			];
		}
		$zones[] = [
			'label'   => __( 'Manual Offsets' ),
			'options' => $manuals
		];

		return $zones;
	}

	public static function social_services_options() {
		$options = [
			'facebook'  => esc_html__( 'Facebook', 'classified-listing' ),
			'twitter'   => esc_html__( 'Twitter', 'classified-listing' ),
			'linkedin'  => esc_html__( 'Linkedin', 'classified-listing' ),
			'pinterest' => esc_html__( 'Pinterest', 'classified-listing' ),
			'whatsapp'  => esc_html__( 'WhatsApp (Only at mobile)', 'classified-listing' ),
			'telegram'  => esc_html__( 'Telegram (Only at mobile)', 'classified-listing' )
		];

		return apply_filters( 'rtcl_social_services_options', $options );
	}

	public static function addons() {
		$addons = [
			'ext_rtcl_bundle'         => [
				'type'     => 'bundle',
				'title'    => 'Classified Listing Plugins and Themes Bundled',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/bundle-extension.png',
				'demo_url' => 'https://radiustheme.com/demo/wordpress/classifiedpro/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-pro-plugins-bundle/',
			],
			'ext_rtcl_app'            => [
				'type'     => 'app',
				'title'    => 'Classified Listing - Android and iOS Mobile App',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/classified-listing-mobile-app-android-ios.jpg',
				'demo_url' => 'https://www.radiustheme.com/downloads/classified-listing-android-and-ios-mobile-app/',
			],
			'ext_rtcl_pro'            => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing Pro for WordPress',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/Classified-Listing-classified-ads-business-directory-plugin.png',
				'demo_url' => 'https://radiustheme.com/demo/wordpress/classifiedpro/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-pro-wordpress/',
			],
			'ext_rtcl_store'          => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing Store & Membership addon for WordPress',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/Classified-Listing-store-membership-addon.png',
				'demo_url' => 'https://www.radiustheme.com/demo/wordpress/themes/classima/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-store-membership-addon-for-wordpress/',
			],
			'ext_rtcl_wpml'           => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing MultiLingual Addon',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/Classified-Listing-multilingual-addon.png',
				'demo_url' => 'https://www.radiustheme.com/wordpress-plugins/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-multilingual-addon/',
			],
			'ext_el_builder'          => [
				'type'     => 'Extension',
				'title'    => 'Elementor Builder – Archive & Single Page Builder',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/elementor-builder-addon-for-classified-listing.png',
				'demo_url' => 'https://radiustheme.com/demo/wordpress/classifiedpro/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-elementor-builder/',
			],
			'ext_otp_verification'    => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing – Mobile Number Verification',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/mobile-no-verification.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/classima',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-mobile-no-verification/',
			],
			'ext_seller_verification' => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing – Seller Verification',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/seller-verification.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/classima',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-seller-verification/',
			],
			'ext_booking'             => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing – Booking (Reservation & Appointment)',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/booking.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/classima',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-booking/',
			],
			'ext_rtcl_buddypress'     => [
				'type'     => 'Extension',
				'title'    => 'BuddyPress Integration',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/buddypress-integration.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/classima',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-buddypress-integration/',
			],
			'ext_rtcl_buddyboss'      => [
				'type'     => 'Extension',
				'title'    => 'BuddyBoss Integration',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/buddyboss-integration.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/classima',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-buddyboss-integration/',
			],
			'ext_multi_currency'      => [
				'type'     => 'Extension',
				'title'    => 'Classified Listing – Multi-Currency',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/multi-currency.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/classima',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-multi-currency-addon/',
			],
		];

		return apply_filters( 'rtcl_addons', $addons );
	}

	public static function themes() {
		$themes = [
			'theme_cl_classified'    => [
				'type'     => 'free',
				'title'    => 'CL Classified – Classified Listing WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/cl-classified-wordpress-theme.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/cl-classified/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-pro-plugins-bundle/',
			],
			'theme_radius_directory' => [
				'type'     => 'free',
				'title'    => 'Radius Directory – Directory WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/radius-directory.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/radius-directory/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/radius-directory-directory-wordpress-theme/',
			],
			'theme_classima'         => [
				'type'     => 'Theme',
				'title'    => 'Classima – Classified Ads WordPress Theme',
				'img_url'  => 'https://radiustheme.com/our-plugins/Classima-classified-wordpress-theme.png',
				'demo_url' => 'https://www.radiustheme.com/demo/wordpress/themes/classima/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classima-classified-ads-wordpress-theme/',
			],
			'app_classima'           => [
				'type'     => 'Mobile App',
				'title'    => 'Classima - Classified Ads Android & iOS App',
				'img_url'  => 'https://radiustheme.com/our-plugins/Classified-ads-android-ios-app.png',
				'demo_url' => 'https://play.google.com/store/apps/details?id=com.classima.radiustheme',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classified-listing-android-app/',
			],
			'theme_cl_property'      => [
				'type'     => 'Theme',
				'title'    => 'CL Property – Real Estate WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/clproperty-wordpress-theme.png',
				'demo_url' => 'https://radiustheme.com/demo/wordpress/themes/clproperty/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/clproperty-real-estate-wordpress-theme/',
			],
			'theme_cl_directory'     => [
				'type'     => 'Theme',
				'title'    => 'CL Directory – Directory WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/cldirectory-wordpress-theme.png',
				'demo_url' => 'https://radiustheme.com/demo/wordpress/themes/cldirectory/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/cldirectory-directory-wordpress-theme/',
			],
			'theme_cl_car'           => [
				'type'     => 'Theme',
				'title'    => 'CL Car – Classified Listing WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/car-listing-wordpress-theme.png',
				'demo_url' => 'https://www.radiustheme.com/demo/wordpress/themes/clcar/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/clcar-car-listing-wordpress-theme/',
			],
			'theme_cl_restaurant'    => [
				'type'     => 'Theme',
				'title'    => 'CL Restaurant – Restaurant Listing WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/restaurant-listing-wordpress-theme.png',
				'demo_url' => 'https://radiustheme.com/demo/wordpress/themes/clrestaurant/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/clrestaurant-restaurant-directory-wordpress-theme/',
			],
			'theme_cl_doctor'        => [
				'type'     => 'Theme',
				'title'    => 'CL Doctor – Doctor Directory WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/doctor-listing-wordpress-theme.png',
				'demo_url' => 'https://www.radiustheme.com/demo/wordpress/themes/cldoctor/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/cldoctor-doctor-directory-wordpress-theme/',
			],
			'theme_obitore'          => [
				'type'     => 'Theme',
				'title'    => 'Obitore– Funeral Home WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/obitore-funeral-wordpress-theme.png',
				'demo_url' => 'https://radiustheme.net/publicdemo/obitore/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/obitore-funeral-home-wordpress-theme/',
			],
			'theme_service_listing'  => [
				'type'     => 'Theme',
				'title'    => 'Servlisting – Service Finder WordPress Theme',
				'img_url'  => 'https://radiustheme.com/demo/cl-extensions/service-listing-wordpress-theme.png',
				'demo_url' => 'https://www.radiustheme.com/demo/wordpress/themes/servlisting/',
				'buy_url'  => 'https://www.radiustheme.com/downloads/servlisting-service-listing-wordpress-theme/',
			],
			'theme_classiList'       => [
				'type'     => 'Theme',
				'title'    => 'ClassiList – Classified Ads WordPress Theme',
				'img_url'  => 'https://radiustheme.com/our-plugins/ClassiList-classified-ads-wordpress-theme.png',
				'demo_url' => 'https://www.radiustheme.com/demo/wordpress/themes/classilist',
				'buy_url'  => 'https://www.radiustheme.com/downloads/classilist-classified-ads-wordpress-theme/',
			]
		];

		return apply_filters( 'rtcl_themes', $themes );
	}
}
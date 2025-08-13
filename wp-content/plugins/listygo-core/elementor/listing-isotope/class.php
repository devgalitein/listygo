<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

 namespace radiustheme\Listygo_Core;

use Elementor\Controls_Manager;
use \WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Listing_Isotope extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Listing Isotope', 'listygo-core' );
		$this->rt_base = 'rt-listing-isotope';
		$this->rt_translate = array(
			'cols'  => array(
				'1' => __( '1 Col', 'listygo-core' ),
				'2' => __( '2 Col', 'listygo-core' ),
				'3' => __( '3 Col', 'listygo-core' ),
				'4' => __( '4 Col', 'listygo-core' ),
				'5' => __( '5 Col', 'listygo-core' ),
			),
		);
		parent::__construct( $data, $args );
	}

	private function rt_load_scripts(){
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'isotope' );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => __( 'General', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'type',
				'label'   => __( 'Items to Show', 'listygo-core' ),
				'options' => array(
					'popular'  => __( 'Popular', 'listygo-core' ),
					'featured' => __( 'Featured', 'listygo-core' ),
					'new'      => __( 'New', 'listygo-core' ),
					'top'      => __( 'Top', 'listygo-core' ),
				),
				'multiple' => true,
				'default' => array( 'popular', 'featured')
			),
			array(
				'type'       => Controls_Manager::NUMBER,
				'id'         => 'number',
				'label'      => __( 'Number of Items per ', 'listygo-core' ),
				'default'    => '8',
				'description' => __( 'Write -1 to show all', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'random',
				'label'       => __( 'Change items on every page load', 'listygo-core' ),
				'label_on'    => __( 'On', 'listygo-core' ),
				'label_off'   => __( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'      => Controls_Manager::SELECT2,
				'id'        => 'orderby',
				'label'     => __( 'Order By', 'listygo-core' ),
				'options'   => array(
					'date'   => __( 'Date (Recents comes first', 'listygo-core' ),
					'title'  => __( 'Title', 'listygo-core' ),
				),
				'default'   => 'date',
				'condition' => array( 'random' => array( '' ) ),
			),
			
			array(
				'type'        => Controls_Manager::HEADING,
				'id'          => 'thumb_area',
				'label'       => esc_html__( 'Thumbnail Area Control', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_cat',
				'label'       => esc_html__( 'Category', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable Category. Default: On', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_status',
				'label'       => esc_html__( 'On/Off Status', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Open or Close status tag on/off', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_location',
				'label'       => esc_html__( 'Location', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Location tag on/off', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_poster',
				'label'       => esc_html__( 'Listing Author', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Listing Author Information', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_rating',
				'label'       => esc_html__( 'Listing Rating', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Listing Rating', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::HEADING,
				'id'          => 'content_area',
				'label'       => esc_html__( 'Content Area Control', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_badge',
				'label'       => esc_html__( 'Badge', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Listing Badge', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_address',
				'label'       => esc_html__( 'Address', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Listing Badge', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_phone',
				'label'       => esc_html__( 'Phone', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Phone', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_website',
				'label'       => esc_html__( 'Website', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Website', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_date',
				'label'       => esc_html__( 'Date', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Date', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_view',
				'label'       => esc_html__( 'Views', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Listing Views', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::HEADING,
				'id'          => 'footer_area',
				'label'       => esc_html__( 'Footer Area Control', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_price',
				'label'       => esc_html__( 'Price', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_countdown',
				'label'       => esc_html__( 'Countdown', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => '',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_metalist',
				'label'       => esc_html__( 'Meta List', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),

			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_qv',
				'label'       => esc_html__( 'Quick View', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_compare',
				'label'       => esc_html__( 'Compare', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_fav',
				'label'       => esc_html__( 'Favorite', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_gallery',
				'label'       => esc_html__( 'Gallery', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'mode' => 'section_end',
			),

			// Responsive Columns
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_responsive',
				'label'   => __( 'Number of Responsive Columns', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_xl',
				'label'   => __( 'Desktops: >1199px', 'listygo-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '3',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_lg',
				'label'   => __( 'Desktops: >991px', 'listygo-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '3',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_md',
				'label'   => __( 'Tablets: >767px', 'listygo-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '4',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_sm',
				'label'   => __( 'Phones: >575px', 'listygo-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '6',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_mobile',
				'label'   => __( 'Small Phones: <576px', 'listygo-core' ),
				'options' => $this->rt_translate['cols'],
				'default' => '12',
			),
			array(
				'mode' => 'section_end',
			),

			// Style Tab
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_style_color',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Color', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'bgcolor',
				'label'   => __( 'Background', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-grid-each .rtin-item' => 'background-color: {{VALUE}}' ),
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'cat_color',
				'label'   => __( 'Category', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-grid-each .rtin-item .rtin-content .rtin-cat' => 'color: {{VALUE}}' ),
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'title_color',
				'label'   => __( 'Title', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-grid-each .rtin-item .rtin-content .rtin-title a, {{WRAPPER}} .listing-grid-each-5 .rtin-item .rtin-content .rtin-title' => 'color: {{VALUE}}' ),
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'meta_color',
				'label'   => __( 'Meta', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-grid-each .rtin-item .rtin-content .rtin-meta li, {{WRAPPER}} .listing-grid-each-5 .rtin-item .rtin-content .rtin-meta-area .rtin-meta' => 'color: {{VALUE}}' ),
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'price_color',
				'label'   => __( 'Price', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-grid-each-1 .rtin-item .rtin-content .rtin-price .rtcl-price-amount, {{WRAPPER}} .listing-grid-each.listing-grid-each-2 .rtin-item .rtin-content .rtin-price .rtcl-price-amount, {{WRAPPER}} .listing-grid-each-3 .rtin-item .rtin-thumb .rtcl-price-amount, {{WRAPPER}} .listing-grid-each-4 .rtin-item .rtin-content .rtin-price .rtcl-price-amount, {{WRAPPER}} .listing-grid-each-5 .rtin-item .rtin-content .rtin-meta-area span.rtcl-price-amount' => 'color: {{VALUE}}' ),
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_style_type',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Typography', 'listygo-core' ),
			),
			array(
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'name'       => 'cat_typo',
				'label'    => __( 'Category', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-grid-each .rtin-item .rtin-content .rtin-cat',
			),
			array(
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'name'       => 'title_typo',
				'label'    => __( 'Title', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-grid-each .rtin-item .rtin-content .rtin-title, {{WRAPPER}} .listing-grid-each-5 .rtin-item .rtin-content .rtin-title',
			),
			array(
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'name'       => 'meta_typo',
				'label'    => __( 'Meta', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-grid-each .rtin-item .rtin-content .rtin-meta li, {{WRAPPER}} .listing-grid-each-5 .rtin-item .rtin-content .rtin-meta-area .rtin-meta',
			),
			array(
				'mode'     => 'group',
				'type'     => \Elementor\Group_Control_Typography::get_type(),
				'name'       => 'price_typo',
				'label'    => __( 'Price', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-grid-each span.rtcl-price-amount, {{WRAPPER}} .listing-grid-each-3 .rtin-item .rtin-thumb .rtcl-price-amount',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	private function rt_isotope_query( $data ) {

		$result = array();

		// Get plugin settings
		$settings = get_option( 'rtcl_moderation_settings' );
		$min_view = !empty( $settings['popular_listing_threshold'] ) ? (int) $settings['popular_listing_threshold'] : 500;
		$new_threshold = !empty( $settings['new_listing_threshold'] ) ? (int) $settings['new_listing_threshold'] : 3;

		// Post type
		$args = array(
			'post_type'      => 'rtcl_listing',
			'post_status'    => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page' => $data['number'],
		);

		// Ordering
		if ( $data['random'] ) {
			$args['orderby'] = 'rand';
		}
		else {
			$args['orderby'] = $data['orderby'];
			if ( $data['orderby'] == 'title' ) {
				$args['order'] = 'ASC';
			}
		}

		// Date and Meta Query results
		$args2 = array();
		
		if ( in_array( 'new' , $data['type'] ) ) {
			$args2['date_query'] = array(
				array(
					'after' => $new_threshold . ' day ago',
				),
			);
			$result['new'] = new WP_Query( $args + $args2 );
			$args2 = array();
		}

		if ( in_array( 'featured' , $data['type'] ) ) {
			$args2['meta_key'] = 'featured';
			$args2['meta_value'] = '1';
			$result['featured'] = new WP_Query( $args + $args2 );
			$args2 = array();
		}

		if ( in_array( 'top' , $data['type'] ) ) {
			$args2['meta_key'] = '_top';
			$args2['meta_value'] = '1';
			$result['top'] = new WP_Query( $args + $args2 );
			$args2 = array();
		}

		if ( in_array( 'popular' , $data['type'] ) ) {
			$args2['meta_key'] = '_views';
			$args2['meta_value'] = $min_view;
			$args2['meta_compare'] = '>=';
			$result['popular'] = new WP_Query( $args + $args2 );
			$args2 = array();
		}

		return $result;
	}

	private function rt_isotope_navigation( $data ) {
		$navs = array(
			'featured' => __( 'Featured', 'listygo-core' ),
			'new'      => __( 'New', 'listygo-core' ),
			'popular'  => __( 'Popular', 'listygo-core' ),
			'top'      => __( 'Top', 'listygo-core' ),
		);

		$navs = apply_filters( 'classipost_isotope_navigations', $navs );

		foreach ( $navs as $key => $value ) {
			if ( !in_array( $key , $data['type'] ) ) {
				unset($navs[$key]);
			}
		}

		return $navs;
	}

	protected function render() {
		$data = $this->get_settings();
		// $this->rt_load_scripts();

		$data['queries']  = $this->rt_isotope_query( $data );
		$data['navs']     = $this->rt_isotope_navigation( $data );

		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}
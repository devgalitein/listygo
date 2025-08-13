<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/listing/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Rtcl\Controllers\Hooks\TemplateHooks;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Listings extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Listings', 'listygo-core' );
		$this->rt_base = 'rt-listings';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_rt_listings',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Listings List', 'listygo-core' ),
			),
			// Layout
			array(
				'id'      => 'style',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'layout 1 (Grid)', 'listygo-core' ),
					'style2' => esc_html__( 'layout 2 (List)', 'listygo-core' ),
					'style3' => esc_html__( 'layout 3 (Slider)', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			// Post per page
			array(
				'id'      => 'number',
				'label'   =>esc_html__( 'Total number of post', 'listygo-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'description' =>esc_html__( 'Write -1 to show all', 'listygo-core' ),
				'label_block' => true,
			),
			array(
				'id'      => 'cols',
				'label'   => esc_html__( 'Grid Columns', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_grid_options(),
				'default' => '4',
				'label_block' => true,
				'condition'   => array( 'style' => array( 'style1', 'style2' ) ),
			),
			array(
				'id'      => 'orderby',
				'label'   => esc_html__( 'Order By', 'traveldo' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_post_orderby(),
				'default' => 'date',
				'label_block' => true,
			),
			//Query Type
			array(
				'id'      => 'query_type',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Get Listings', 'listygo-core' ),
				'options' => array(
					'select_type' => esc_html__( 'Select Query Type', 'listygo-core' ),
					'loccat' => esc_html__( 'By Locations and Categories', 'listygo-core' ),
					'meta_key' => esc_html__( 'By Badge', 'listygo-core' ),
					'titles' => esc_html__( 'By Titles', 'listygo-core' ),
				),
				'default' => 'select_type',
				'label_block' => true,
			),

			array(
				'id'      => 'locations',
				'label'   => esc_html__( 'List By Location', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_get_terms_by_id('rtcl_location', 'All Locations'),
				'multiple' => true,
				'label_block' => true,
				'conditions' => [
					'terms' => [
						['name' => 'query_type', 'operator' => '==', 'value' => 'loccat'],
					],
				],
			),

			// Terms
			array(
				'id'      => 'terms',
				'label' => __( 'List By Category', 'listygo-core' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $this->rt_get_terms_by_id('rtcl_category', 'All Categories'),
                'multiple' => true,
                'label_block' => true,
    			'conditions' => [
					'terms' => [
						['name' => 'query_type', 'operator' => '==', 'value' => 'loccat'],
					],
				],
			),

			array(
				'id'      => 'badges',
				'label'   => esc_html__( 'List By Badge', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''         => __( '--Select--', 'listygo-core' ),
					'_top'     => __( 'Top Product', 'listygo-core' ),
					'featured' => __( 'Featured Product', 'listygo-core' ),
					'_bump_up' => __( 'Bump Up Product', 'listygo-core' ),
					'_views'   => __( 'Popular', 'listygo-core' ),
				),
				'multiple' => true,
				'label_block' => true,
				'conditions' => [
					'terms' => [
						['name' => 'query_type', 'operator' => '==', 'value' => 'meta_key'],
					],
				],
			),

			array(
				'id'      => 'postbytitle',
				'label'   => esc_html__( 'List By Title', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_posts_title('rtcl_listing'),
				'multiple' => true,
				'label_block' => true,
				'conditions' => [
					'terms' => [
						['name' => 'query_type', 'operator' => '==', 'value' => 'titles'],
					],
				],
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'gutters',
				'label'       => esc_html__( 'Gutters', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable grid gutter. Default: On', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style1' ) ),
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
				'id'          => 'display_excerpt',
				'label'       => esc_html__( 'Excerpt', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Listing Excerpt', 'listygo-core' ),
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
			
			array(
				'mode'        => 'section_start',
				'id'          => 'sec_slider',
				'label'       => esc_html__( 'Slider Options', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_autoplay',
				'label'       => esc_html__( 'Autoplay', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable autoplay. Default: On', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'slider_autoplay_speed',
				'label'   => esc_html__( 'Autoplay Speed', 'listygo-core' ),
				'options' => $this->rt_autoplay_speed(),
				'default' => '2000',
				'description' => esc_html__( 'Select any value for autopaly speed. Default: 2000', 'listygo-core' ),
				'condition'   => array( 
					'style' => array( 'style3', ), 
					'slider_autoplay' => 'yes', 
				),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_dots',
				'label'       => esc_html__( 'Dots', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_arrow',
				'label'       => esc_html__( 'Arrow', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'slider_arrow_style',
				'label'   => esc_html__( 'Arrow position', 'listygo-core' ),
				'options' => array(
					'1' => esc_html__( 'Middle', 'listygo-core' ),
					'2' => esc_html__( 'Top right', 'listygo-core' ),
					'3' => esc_html__( 'Bottom center', 'listygo-core' ),
				),
				'default' => '1',
				'condition' => ['slider_arrow' => 'yes'],
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'slide_to_show',
				'label'   => esc_html__( 'Slide To Show', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '3',
				'description' => esc_html__( 'Select any value for desktop device show. Default: 6', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_lg',
				'label'   => esc_html__( 'Desktops: > 1199px', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'Select any value for desktop device show. Default: 4', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_md',
				'label'   => esc_html__( 'Desktops: > 991px', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'This is mostly tab view.  Default: 3', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_sm',
				'label'   => esc_html__( 'Mobile View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '1',
				'description' => esc_html__( 'Select any value for mobile device show. Default: 2', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_xs',
				'label'   => esc_html__( 'Mobile Small View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '1',
				'description' => esc_html__( 'Select any value for small mobile device show. Default: 1', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style3' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style3' ) ),
			),

			// Category
			array(
				'id'      => 'cat_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Category', 'listygo-core' ),
			),
			array(
				'id'      => 'cat_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .product-box .item-img .rt-categories a' => 'color: {{VALUE}}' ),
			),
			array(
				'name'      => 'cat_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .product-box .item-img .rt-categories',
			),
			array(
				'id'      => 'cat_h_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'cath_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .product-box .item-img .rt-categories a:hover' => 'color: {{VALUE}}' ),
			),
			array(
				'name'      => 'bgh_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .product-box .item-img .rt-categories:hover',
			),
			array(
				'mode' => 'section_end',
			),

			// Open/Close Status
			array(
				'id'      => 'open_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Open/Close', 'listygo-core' ),
			),
			array(
				'id'      => 'open_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .product-box .item-img .item-status' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'open_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .product-box .item-img .item-status',
			),
			array(
				'id'      => 'close_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Close', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'close_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .product-box .item-img .status-close' => 'color: {{VALUE}}' ),
			),
			array(
				'name'      => 'clise_gh_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .product-box .item-img .status-close',
			),
			array(
				'mode' => 'section_end',
			),

			// Title
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Title', 'listygo-core' ),
			),
			array(
				'id'      => 'title_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Title', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-title a' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'name_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-title a',
			),
			array(
				'id'      => 'title_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Title Hover Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .listing-title a:hover' => 'color: {{VALUE}}' ),
			),
			array(
				'mode' => 'section_end',
			),

			// Description
			array(
				'id'      => 'desc_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Description', 'listygo-core' ),
			),
			array(
				'id'      => 'desc_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .item-content p' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'desc_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-content p',
			),
			array(
				'mode' => 'section_end',
			),
			// Information
			array(
				'id'      => 'info_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Information', 'listygo-core' ),
			),
			array(
				'id'      => 'icon_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Icon', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} ul.contact-info li path' => 'fill: {{VALUE}}' ),
			),
			array(
				'id'      => 'text_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Text', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'text_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} li.meta-price' => 'color: {{VALUE}}',
					'{{WRAPPER}} ul.contact-info li' => 'color: {{VALUE}}',
					'{{WRAPPER}} ul.contact-info li a' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'     => 'text_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} ul.contact-info li',
			),
			array(
				'mode' => 'section_end',
			),

			array(
				'id'      => 'meta_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Meta', 'listygo-core' ),
			),
			array(
				'id'      => 'meta_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} li.entry-meta ul li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} li.entry-meta ul li a path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} li.entry-meta ul li .rtcl-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} li.entry-meta ul li a .rtcl-icon' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'      => 'meta_bgc',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '{{WRAPPER}} li.entry-meta ul li a, {{WRAPPER}} li.entry-meta ul li .rtcl-btn',
			),
			array(
				'id'        => 'meta_hover_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'meta_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} li.entry-meta ul li a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} li.entry-meta ul li a:hover path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} li.entry-meta ul li .rtcl-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} li.entry-meta ul li a:hover .rtcl-icon' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'      => 'meta_hover_bgc',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '{{WRAPPER}} li.entry-meta ul li a:hover, {{WRAPPER}} li.entry-meta ul li .rtcl-btn:hover',
			),
			array(
				'mode' => 'section_end',
			),

			// Slider Dot
			array(
				'id'      => 's_dot_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Dots', 'listygo-core' ),
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'name'     => 'sdot_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .slick-dots li button
				',
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'id'      => 'sdot_bgc',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Background color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .slick-dots li button:before' => 'background-color: {{VALUE}}' ),
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),
			// Active
			array(
				'id'        => 'dot_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Active Color', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'id'      => 'sdot_hbgc',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Background color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .slick-dots li.slick-active button:before' => 'background-color: {{VALUE}}' ),
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'id'      => 'sdot_hbbgc',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Border color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .slick-dots li.slick-active button' => 'border-color: {{VALUE}}' ),
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 
					'style' => array( 'style2' ), 
					'slider_dots' => 'yes', 
				),
			),

			// Slider Arrow
			array(
				'id'      => 's_arrow_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Arrow', 'listygo-core' ),
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'      => 'sarrow_ic',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Icon color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .slick-arrow:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-next:after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-prev:after' => 'color: {{VALUE}}' 
				),
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'name'     => 'sa_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .swiper-button-next,
					{{WRAPPER}} .swiper-button-prev
				',
				'condition'   => array( 
					'style' => array( 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'name'      => 'sarrow_bgc',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '
					{{WRAPPER}} .slick-arrow,
					{{WRAPPER}} .swiper-button-next,
					{{WRAPPER}} .swiper-button-prev
				',
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'        => 'arrow_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Active Color', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'      => 'sarrow_hic',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Icon color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .slick-arrow:hover:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-next:hover:after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-prev:hover:after' => 'color: {{VALUE}}'
				),
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'      => 'sarrow_bhic',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Border color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .swiper-button-next:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-prev:hover' => 'border-color: {{VALUE}}' 
				),
				'condition'   => array( 
					'style' => array( 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'name'      => 'sarrow_hbgc',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '
				{{WRAPPER}} .slick-arrow:hover,
				{{WRAPPER}} .swiper-button-next:hover,
				{{WRAPPER}} .swiper-button-prev:hover
				',
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 
					'style' => array( 'style2', 'style3' ), 
					'slider_arrow' => 'yes', 
				),
			),

			// Slider Arrow
			array(
				'id'      => 's_arrow_style_6',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Arrow', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'id'      => 'sarrow_ic_6',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Icon color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .slick-arrow:before' => 'color: {{VALUE}}'
				),
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'name'     => 'sa_border_6',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .slick-arrow
				',
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'name'      => 'sarrow_bgc_6',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '
					{{WRAPPER}} .slick-arrow,
				',
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'id'        => 'arrow_heading_6',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Active Color', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'id'      => 'sarrow_hic_6',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Icon color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .slick-arrow:hover:before' => 'color: {{VALUE}}'
				),
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'id'      => 'sarrow_bhc_6',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Border color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .slick-arrow:hover' => 'border-color: {{VALUE}}' 
				),
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'name'      => 'sarrow_hbgc_6',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => ' {{WRAPPER}} .slick-arrow:hover',
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style6' ) ),
			),
		);
		return $fields;
	}
 
	public function swiper(){
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );
		wp_enqueue_script( 'slider-func' );
	}
	
	public function hooks(){
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );
	}

	protected function render() {
		$data = $this->get_settings();
		$this->hooks();

		if ( $data['style'] == 'style3' ) {
			$this->swiper();
		}
		switch ($data['style']) {
			case 'style3':
				$template = 'view-3';
				break;
			case 'style2':
				$template = 'view-2';
				break;
			default:
				$template = 'view-1';
				break;
		}

		if ( $data['style'] == 'style3' ) {
			$swiper_data = array(
				'col_xl'   => $data['slide_to_show'],
				'autoplay' => $data['slider_autoplay'] == 'yes' ? true : false,
				'speed'    => $data['slider_autoplay_speed'],
				'col_lg'  => $data['col_lg'],
				'col_md'  => $data['col_md'],
				'col_sm'  => $data['col_sm'],
				'col_xs'  => $data['col_xs'],
			);
			$data['swiper_data'] = json_encode( $swiper_data );
		}

		return $this->rt_template( $template, $data );
	}
}
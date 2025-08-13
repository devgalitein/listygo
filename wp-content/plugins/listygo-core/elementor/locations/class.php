<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/locations/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use Elementor\Plugin;
use Rtcl\Helpers\Functions;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use radiustheme\listygo\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Locations extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Locations', 'listygo-core' );
		$this->rt_base = 'rt-locations';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'location_name', [
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Select Location', 'listygo-core' ),
				'description' => esc_html__( 'Location background image', 'listygo-core' ),
				'options' => $this->rt_get_terms_by_id('rtcl_location', 'All Locations'),
				'multiple' => false,
				'label_block' => true,
				'show_label' => false,
			]
		);
		$repeater->add_control(
			'bgimg', [
				'type'    => Controls_Manager::MEDIA,
				'label'   => esc_html__( 'Background Image', 'listygo-core' ),
				'default' => [
					'url' => $this->rt_placeholder_image(),
				],
				'description' => esc_html__( 'Select location background image', 'listygo-core' ),
				'show_label' => false,
			]
		);
		$fields = array(
			array(
				'id'      => 'sec_rt_locations',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Locations List', 'listygo-core' ),
			),
			// Layout
			array(
				'id'      => 'layout',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'layout 1 (Grid)', 'listygo-core' ),
					'style2' => esc_html__( 'layout 2 (Slider)', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			// Style
			array(
				'id'      => 'style',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'1' => esc_html__( 'Style 1', 'listygo-core' ),
					'2' => esc_html__( 'Style 2', 'listygo-core' ),
					'3' => esc_html__( 'Style 3', 'listygo-core' ),
				),
				'default' => '1',
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'locations',
				'label'   => esc_html__( 'Add as many locations as you want', 'listygo-core' ),
				'fields' => $repeater->get_controls(),
			),
			array(
				'type'        => Controls_Manager::SLIDER,
				'id'          => 'height',
				'label' => esc_html__( 'Box Height', 'listygo-core' ),
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors' => [
					'{{WRAPPER}} .feature-box-layout1 .item-img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .category-box-layout2 .item-thumb' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .location-box-layout3 .item-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'display_count',
				'label'       => esc_html__( 'Show Listing Counts', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_link',
				'label'       => esc_html__( 'Enable Link', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'id'      => 'cols',
				'label'   => esc_html__( 'Grid Columns', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_grid_options(),
				'default' => 'style1',
				'label_block' => true,
				'condition'   => array( 'layout' => array( 'style1' ) ),
			),
			// Style
			array(
				'id'      => 'view',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'View', 'listygo-core' ),
				'options' => array(
					'list' => esc_html__( 'List View', 'listygo-core' ),
					'grid' => esc_html__( 'Grid View', 'listygo-core' ),
				),
				'default' => 'list',
				'condition'   => array( 'style' => array( '3' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'gutters',
				'label'       => esc_html__( 'Gutters', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable grid gutter. Default: On', 'listygo-core' ),
				'condition'   => array( 'style' => array( '1' ) ),
			),
			array(
				'id'      => 'border_radius',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Image Border Radius', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .img-location .item-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .location-box-layout3.grid .item-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'condition'   => array( 'style' => array( '3' ) ),
			),
			array(
				'mode' => 'section_end',
			),

			/* = Slider Options
			=============================================*/
			array(
				'mode'        => 'section_start',
				'id'          => 'sec_slider',
				'label'       => esc_html__( 'Slider Options', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_autoplay',
				'label'       => esc_html__( 'Autoplay', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable autoplay. Default: On', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'slider_autoplay_speed',
				'label'   => esc_html__( 'Autoplay Speed', 'listygo-core' ),
				'options' => $this->rt_autoplay_speed(),
				'default' => '2000',
				'description' => esc_html__( 'Select any value for autopaly speed. Default: 2000', 'listygo-core' ),
				'condition'   => array( 
					'layout' => array( 'style2', ), 
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
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_arrow',
				'label'       => esc_html__( 'Arrow', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'slide_to_show',
				'label'   => esc_html__( 'Slide To Show', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '3',
				'description' => esc_html__( 'Select any value for desktop device show. Default: 6', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_lg',
				'label'   => esc_html__( 'Desktops: > 1199px', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'Select any value for desktop device show. Default: 4', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_md',
				'label'   => esc_html__( 'Desktops: > 991px', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'This is mostly tab view.  Default: 3', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_sm',
				'label'   => esc_html__( 'Mobile View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '1',
				'description' => esc_html__( 'Select any value for mobile device show. Default: 2', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_xs',
				'label'   => esc_html__( 'Mobile Small View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '1',
				'description' => esc_html__( 'Select any value for small mobile device show. Default: 1', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'layout' => array( 'style2' ) ),
			),

			/* = Item Styles
			==========================================*/
			// Title Styles
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
			),
			array(
				'id'      => 'title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .common-style .item-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .common-style .item-title a' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-style .item-title, {{WRAPPER}} .common-style .item-title a',
			),
			array(
				'mode' => 'section_end',
			),

			// Listing Count Style
			array(
				'id'      => 'listing_count_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Count', 'listygo-core' ),
			),
			array(
				'id'        => 'count_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .listing-number' => 'color: {{VALUE}}',
					'{{WRAPPER}} .listing-number a' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'count_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-number, {{WRAPPER}} .listing-number a',
			),
			array(
				'name'     => 'btn_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => esc_html__( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-number',
			),
			array(
				'id'      => 'hover_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'        => 'border_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .listing-number:hover' => 'border-color: {{VALUE}}'
				),
			),
			array(
				'name'      => 'btn_hbg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-number:hover',
			),
			array(
				'mode' => 'section_end',
			),

			// Link Button
			array(
				'id'      => 'link_btn_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Arrow Button', 'listygo-core' ),
				'condition' => ['style' => '3'],
			),
			
			array(
				'id'        => 'link_btn_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .common-style .btn-box a' => 'color: {{VALUE}}'
				),
				'condition' => ['style' => '3'],
			),
			array(
				'name'      => 'link_btn_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Link', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-style .btn-box a',
				'condition' => ['style' => '3'],
			),
			array(
				'id'        => 'link_hbtn_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .common-style:hover .btn-box a' => 'color: {{VALUE}}'
				),
				'condition' => ['style' => '3'],
			),
			array(
				'name'      => 'link_btn_hbg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Hover Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-style:hover .btn-box a',
				'condition' => ['style' => '3'],
			),
			array(
				'mode' => 'section_end',
				'condition' => ['style' => '3'],
			),
			// Background Overlay
			array(
				'id'      => 'overlay_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Background', 'listygo-core' ),
			),
			array(
				'id'        => 'overlay_bg',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dark Overlay', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .bg--gradient-60:after' => 'background-image:linear-gradient(transparent,{{VALUE}}),linear-gradient(transparent,{{VALUE}})'),
				'condition' => ['style' => '1'],
			),
			array(
				'name'      => 'item_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .location-box-layout3',
			),
			array(
				'id'   => 'overlay_position',
				'type' => Controls_Manager::SLIDER,
				'mode' => 'responsive',
				'size_units' => array( '%' ),
				'range' => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 60,
				),
				'label'   => esc_html__( 'Ovarlay Height', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .bg--gradient-60:after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => ['style' => '1'],
			),
			array(
				'mode' => 'section_end',
			),

			/* = Slider Options Styles
			=============================================*/
			array(
				'id'      => 's_dot_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Dots', 'listygo-core' ),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'name'     => 'sdot_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => esc_html__( 'Border', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .slick-dots li button
				',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'id'      => 'sdot_bgc',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Background color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .slick-dots li button:before' => 'background-color: {{VALUE}}' ),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),
			// Active
			array(
				'id'        => 'dot_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Active Color', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'id'      => 'sdot_hbgc',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Background color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .slick-dots li.slick-active button:before' => 'background-color: {{VALUE}}' ),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'id'      => 'sdot_hbbgc',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Border color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .slick-dots li.slick-active button' => 'border-color: {{VALUE}}' ),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_dots' => 'yes', 
				),
			),

			// Slider Arrow
			array(
				'id'      => 's_arrow_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Arrow', 'listygo-core' ),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'      => 'sarrow_ic',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Icon color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-next:after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-prev:after' => 'color: {{VALUE}}' 
				),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'name'      => 'sarrow_bgc',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => esc_html__( 'Background', 'listygo-core' ),
				'selector'  => '
					{{WRAPPER}} .swiper-button-next,
					{{WRAPPER}} .swiper-button-prev
				',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'        => 'arrow_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Hover/Active Color', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'id'      => 'sarrow_hic',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Icon color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .swiper-button-next:hover:after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-prev:hover:after' => 'color: {{VALUE}}'
				),
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'name'      => 'sarrow_hbgc',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => esc_html__( 'Background', 'listygo-core' ),
				'selector'  => '{{WRAPPER}} .sliderNav--style1 .sliderNav__btn::before',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 
					'style' => array( '2' ), 
					'slider_arrow' => 'yes', 
				),
			),
		);
		return $fields;
	}

	public function swiper(){
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );
		wp_enqueue_script( 'slider-func' );
	}

	protected function rt_term_post_count( $term_id ){
		$args = array(
			'nopaging'            => true,
			'fields'              => 'ids',
			'post_type'           => 'rtcl_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'suppress_filters'    => false,
			'tax_query' => array(
				array(
					'taxonomy' => 'rtcl_location',
					'field'    => 'term_id',
					'terms'    => $term_id,
				)
			)
		);
		$posts = get_posts( $args );
		return count( $posts );
	}
	
	protected function render() {
		$data = $this->get_settings();

		if ( $data['layout'] == 'style2' ) {
			$this->swiper();
		}

		switch ($data['layout']) {
			case 'style2':
			$template = 'view-2';
			break;
			default:
			$template = 'view-1';
			break;
		}

		if ( $data['layout'] == 'style2' ) {
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
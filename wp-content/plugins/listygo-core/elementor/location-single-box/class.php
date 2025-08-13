<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/contact-box/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

if (!class_exists( 'RtclPro' )) return;

use Rtcl\Helpers\Link;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use radiustheme\listygo\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Location_Single_Box extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Location Single Box', 'listygo-core' );
		$this->rt_base = 'rt-listing-location-box';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_listing_location_box',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Location Single Box', 'listygo-core' ),
			),
			array(
				'id'      => 'listing_location',
				'label'   => esc_html__( 'Select Location', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_get_rtcl_location_by_id('rtcl_location' ),
				'multiple' => false,
				'label_block' => true,
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
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Layout', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Layout 1', 'listygo-core' ),
					'style2' => esc_html__( 'Layout 2', 'listygo-core' ),
					'style3' => esc_html__( 'Layout 3', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_background',
				'label'   => esc_html__( 'Image Control', 'listygo-core' ),
			),
			array(
				'name'      => 'bgimg',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .feature-box-layout1 .item-img',
			),
			array(
				'type' => Controls_Manager::SLIDER,
				'mode' => 'responsive',
				'id'   => 'width',
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'label'   => esc_html__( 'Max Width', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .feature-box-layout1 .item-img' => 'max-width: {{SIZE}}{{UNIT}};',
				)
			),
			array(
				'type' => Controls_Manager::SLIDER,
				'mode' => 'responsive',
				'id'   => 'height',
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 290,
				),
				'label'   => esc_html__( 'Box Height', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .feature-box-layout1 .item-img' => 'height: {{SIZE}}{{UNIT}};',
				)
			),
			array(
				'name'      => 'overlay',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .feature-box-layout1.version2:after',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Overlay', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
				'condition' => ['style' => ['style2']],
			),
			array(
				'id'      => 'item_border_radius',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Border Radius', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .feature-box-layout1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
			),
			array(
				'mode' => 'section_end',
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
					'{{WRAPPER}} .item-content .item-title' => 'color: {{VALUE}}', 
				),
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-content .item-title',
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
				'selectors' => array( '{{WRAPPER}} .listing-number' => 'color: {{VALUE}}'),
			),
			array(
				'name'     => 'count_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-number',
			),
			array(
				'name'      => 'count_bg',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .feature-box-layout1 .listing-number',
			),
			array(
				'mode' => 'section_end',
			),

			// Background
			array(
				'id'      => 'overlay_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Full Box Background', 'listygo-core' ),
			),
			array(
				'name'      => 'fullbox_bg',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .feature-box-layout1',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	private function rt_term_post_count( $term_id ){
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

		$term = get_term( $data['listing_location'], 'rtcl_location' );

		if ( $term && !is_wp_error( $term ) ) {
			$data['title']     = $term->name;
			$data['count']     = $this->rt_term_post_count( $term->term_id );
			$data['permalink'] = Link::get_location_page_link( $term );
		}
		else {
			$data['title'] = esc_html__( 'Please Select a Location and Background', 'listygo-core' );
			$data['count'] = 0;
			$data['display_count'] = $data['enable_link'] = false;
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
		return $this->rt_template( $template, $data );
	}
}
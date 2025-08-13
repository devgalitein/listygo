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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use radiustheme\listygo\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Listing_Category_Box extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Listing Category Box', 'listygo-core' );
		$this->rt_base = 'rt-listing-category-box';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_listing_category_box',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Listing Category Box', 'listygo-core' ),
			),
			array(
				'id'      => 'style',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Style 1', 'listygo-core' ),
					'style2' => esc_html__( 'Style 2', 'listygo-core' ),
					'style3' => esc_html__( 'Style 3', 'listygo-core' ),
					'style4' => esc_html__( 'Style 4', 'listygo-core' ),
					'style5' => esc_html__( 'Style 5', 'listygo-core' ),
					'style6' => esc_html__( 'Style 6', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			array(
				'id'      => 'listing_category',
				'label'   => esc_html__( 'Highlight Categories', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_get_terms_by_id('rtcl_category', 'All Categories'),
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
				'id'      => 'cat_icon',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Icon Type', 'listygo-core' ),
				'options' => array(
					'icon' => esc_html__( 'Icon', 'listygo-core' ),
					'image' => esc_html__( 'Image', 'listygo-core' ),
					'none' => esc_html__( 'None', 'listygo-core' ),
				),
				'default' => 'icon',
			),
			array(
				'id'      => 'heading_tag',
				'mode'    => 'responsive',
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'HTML Heading Tag', 'listygo-core' ),
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h2',
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_background',
				'label'   => __( 'Background', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'name'      => 'bgimg',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .category-box-layout2 .item-thumb',
				'condition'   => array( 'style' => array( 'style2' ) ),
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
				'label'   => __( 'Max Width', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .category-box-layout2 .item-thumb' => 'max-width: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
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
					'size' => 240,
				),
				'label'   => __( 'Box Height', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .category-box-layout2 .item-thumb' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style2' ) ),
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
				'selector' => '{{WRAPPER}} .common-style .item-title',
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
				'name'     => 'btn_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => esc_html__( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-number',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'id'      => 'hover_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'id'        => 'border_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .listing-number:hover' => 'border-color: {{VALUE}}'
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'name'      => 'btn_hbg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .listing-number:hover',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'mode' => 'section_end',
			),


			// Listing Icon Style
			array(
				'id'      => 'listing_icon_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Icon', 'listygo-core' ),
			),
			array(
				'id'        => 'icon_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-icon .rtcl-icon' => 'color: {{VALUE}}'
				),
				'condition'   => array( 'cat_icon' => array( 'icon' ) ),
			),
			array(
				'id'        => 'icon_hover_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .common-style:hover .item-icon .rtcl-icon' => 'color: {{VALUE}}'
				),
				'condition'   => array( 'cat_icon' => array( 'icon' ) ),
			),
			array(
				'name'      => 'icon_bg',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-icon',
			),
			array(
				'name'      => 'icon_bg_hover',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient' ],
				'label'   => esc_html__( 'Hover Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-style:hover .item-icon',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Hover Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
			),
			array(
				'id'      => 'icon_border_radius',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Background Overlay
			array(
				'id'      => 'overlay_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style1', 'style2' ) ),
			),
			array(
				'id'        => 'overlay_bg',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dark Overlay', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .bg--gradient-60:after' => 'background-image:linear-gradient(transparent,{{VALUE}}),linear-gradient(transparent,{{VALUE}})'),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'name'      => 'item_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .category-box-layout1',
				'condition'   => array( 'style' => array( 'style1' ) ),
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
				'label'   => __( 'Ovarlay Height', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .bg--gradient-60:after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style1', 'style2' ) )
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
					'taxonomy' => 'rtcl_category',
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

		$term = get_term( $data['listing_category'], 'rtcl_category' );

		if ( $term && !is_wp_error( $term ) ) {
			$data['id']        = $term->term_id;
			$data['title']     = $term->name;
			$data['count']     = $this->rt_term_post_count( $term->term_id );
			$data['permalink'] = Link::get_category_page_link( $term );
		}
		else {
			$data['title'] = esc_html__( 'Please Select a Category', 'listygo-core' );
			$data['count'] = 0;
			$data['display_count'] = $data['enable_link'] = false;
		}

		switch ($data['style']){
			case 'style6':
				$template = 'view-6';
				break;
			case 'style5':
				$template = 'view-5';
				break;
			case 'style4':
				$template = 'view-4';
				break;
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
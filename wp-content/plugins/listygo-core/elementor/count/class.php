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

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Count extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Count', 'listygo-core' );
		$this->rt_base = 'rt-count';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_rt_count',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Count', 'listygo-core' ),
			),
			//Layout 
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Style 1', 'listygo-core' ),
					'style2' => esc_html__( 'Style 2', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			// Count Type
			array(
				'id'      => 'count_type',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Count Type', 'listygo-core' ),
				'options' => array(
					'rtcl_listing' 	=> esc_html__( 'Listing', 'listygo-core' ),
					'rtcl_location' => esc_html__( 'Listing Location', 'listygo-core' ),
					'rtcl_category' => esc_html__( 'Listing Category', 'listygo-core' ),
					'post' 			=> esc_html__( 'Blog Posts', 'listygo-core' ),
					'category' 		=> esc_html__( 'Blog Posts Category', 'listygo-core' ),
					'post_tag' 		=> esc_html__( 'Blog Posts Tags', 'listygo-core' ),
				),
				'default' => 'rtcl_listing',
			),
			array(
				'type'    => Controls_Manager::ICONS,
				'id'      => 'icon_class',
				'label'   => esc_html__( 'Icon', 'listygo-core' ),
				'default' => [
					'value' => 'flaticon-happy',
					'library' => 'fa-solid',
				],
			),
			// Number
			array(
				'id'      => 'number',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Number', 'listygo-core' ),
				'label_block' => true,
			),
			// Title
			array(
				'id'      => 'title',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'label_block' => true,
			),
			/* = Text bg shape
			============================================*/
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image',
				'label'   => esc_html__( 'Text Background Image', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
				'condition' => ['style' => ['style2']]
			),
			array(
				'mode' => 'section_end',
			),

			// Box
			array(
				'id'      => 'box_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Full Box', 'listygo-core' ),
			),
			array(
				'name'      => 'box_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .full_box',
			),
			array(
				'name'     => 'box_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .full_box',
			),
			array(
				'id'      => 'icon_border_radius',
				'label'   => esc_html__( 'Border Radius', 'listygo-core' ),
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .full_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'label_block' => true,
			),
			array(
				'id'   => 'width',
				'type' => Controls_Manager::SLIDER,
				'mode' => 'responsive',
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'label'   => __( 'Box Width', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .full_box' => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			array(
				'id'   => 'height',
				'type' => Controls_Manager::SLIDER,
				'mode' => 'responsive',
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'label'   => __( 'Box Height', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .full_box' => 'height: {{SIZE}}{{UNIT}};',
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Icon
			array(
				'id'      => 'icon_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Icon', 'listygo-core' ),
			),
			array(
				'id'      => 'icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .icon i' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'icon_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .icon i',
			),
			array(
				'name'      => 'icon_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .about-block__listing__button',
				'condition'   => array(
					'style' => array( 'style1' )
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Number
			array(
				'id'      => 'number_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Number', 'listygo-core' ),
			),
			array(
				'id'      => 'number_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .number' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'number_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .number',
			),
			array(
				'id'      => 'number_padding',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
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
				'selectors' => array( '{{WRAPPER}} .title' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'name_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .title',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}
	
	private function rt_counts( $count_type ){
		if (in_array($count_type, ['rtcl_listing', 'post'])) {
			$posts = wp_count_posts($count_type);
			$data_count = $posts->publish;			
		} elseif (in_array($count_type, ['rtcl_location', 'rtcl_category', 'category', 'post_tag'])) {
			$data_count = count( get_terms($count_type) );
		} else {
			$data_count = 0;
		}
		return $data_count;
	}

	protected function render() {
		$data = $this->get_settings();
		$count_type = $data['count_type'];

		if ($data['number']) {
			$data['counts'] = $data['number'];
		} else {
			$data['counts'] = $this->rt_counts($count_type);
		}

		if ($data['title']) {
			$count_title = $data['title'];
		} elseif ($count_type == 'rtcl_listing' ) {
			$count_title = esc_html__( 'Total Listings', 'listygo-core' );
		} elseif ($count_type == 'post' ) {
			$count_title = esc_html__( 'Blog Posts', 'listygo-core' );
		} elseif ($count_type == 'rtcl_location' ) {
			$count_title = esc_html__( 'Listing Locations', 'listygo-core' );
		} elseif ($count_type == 'rtcl_category' ) {
			$count_title = esc_html__( 'Listing Categories', 'listygo-core' );
		} elseif ($count_type == 'category' ) {
			$count_title = esc_html__( 'Posts Categories', 'listygo-core' );
		} elseif ($count_type == 'post_tag' ) {
			$count_title = esc_html__( 'Posts Tags', 'listygo-core' );
		} else {
			$count_title = esc_html__( 'Please add title', 'listygo-core' );
		}
		$data['title'] = $count_title;

		switch ($data['style']) {
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
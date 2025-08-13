<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/title/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Title extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Section Title', 'listygo-core' );
		$this->rt_base = 'rt-title';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_general',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'General', 'listygo-core' ),
			),
			// Title
			array(
				'id'      => 'title',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'default' => 'Section Title',
				'label_block' => true,
			),
			// Sub Title
			array(
				'id'      => 'subtitle',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Sub Title', 'listygo-core' ),
				'default' => 'Section Sub Title',
				'label_block' => true,
			),
			array(
				'id'      => 'heading_tag',
				'mode'    => 'responsive',
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'HTML Tag', 'listygo-core' ),
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
			// Section Description
			array(
				'id'      => 'desc',
				'type'    => Controls_Manager::WYSIWYG,
				'label'   => esc_html__( 'Description', 'listygo-core' ),
				'label_block' => true,
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'btntext',
				'label'   => esc_html__( 'Text', 'listygo-core' ),
			),
			array(
				'id'    => 'url',
				'type'  => Controls_Manager::URL,
				'label' => esc_html__( 'Link', 'listygo-core' ),
				'placeholder' => 'https://your-link.com',
			),
			array(
				'id'          => 'line_enable',
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Line', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable line. Default: off', 'listygo-core' ),
			),
			array(
				'id'      => 'align',
				'mode'    => 'responsive',
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Title Alignment', 'listygo-core' ),
				'options' => $this->rt_alignment_options(),
				'prefix_class' => 'elementor-align-',
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .section-heading' => 'text-align: {{VALUE}};',
				],
			),
			array(
				'mode' => 'section_end',
			),

			array(
				'id'      => 'sec_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
			),
			// Title Style
			array(
				'id'      => 'title_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Title Style', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .section-heading .heading-title' => 'color: {{VALUE}}'
				),
				'mode'    => 'responsive',
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .section-heading .heading-title',
			),
			array(
				'id'      => 'title_margin',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Margin', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .section-heading .heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
			),
			// Sub Title
			array(
				'id'      => 'subtitle_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Sub Title Style', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'subtitle_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .section-heading .heading-subtitle' => 'color: {{VALUE}}'
				),
				'mode'    => 'responsive',
			),
			array(
				'id'      => 'color_part_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color Part Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .section-heading .heading-subtitle' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'     => 'subtitle_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .section-heading .heading-subtitle',
			),

			// Description
			array(
				'id'      => 'desc_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Description Style', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'desc_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .section-heading p' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'     => 'desc_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .section-heading p',
			),
			// Line
			array(
				'id'      => 'line_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Line Style', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'line_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .section-heading .sce-head-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .section-heading .sce-head-icon svg path' => 'fill: {{VALUE}}; stroke: {{VALUE}}',
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Button Style
			array(
				'id'      => 'btn_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Button', 'listygo-core' ),
			),
			array(
				'id'      => 'btn_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .item-btn path' => 'fill: {{VALUE}}'
				),
			),
			array(
				'name'      => 'btn_bg_color',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '{{WRAPPER}} .item-btn',
			),
			array(
				'id'      => 'btn_hover_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'btn_hover_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .item-btn:hover path' => 'fill: {{VALUE}}'
				),
			),
			array(
				'name'      => 'btn_hbg_color',
				'mode'      => 'group',
				'type'      => Group_Control_Background::get_type(),
				'label'     => __( 'Background', 'listygo-core' ),
				'selector'  => '{{WRAPPER}} .item-btn:hover',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}
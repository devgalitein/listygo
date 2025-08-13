<?php
/**
 *
 * This file can be overridden by copying it to yourtheme/elementor-custom/button/class.php
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
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Button extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = esc_html__( 'Button', 'listygo-core' );
		$this->rt_base = 'rt-button';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => __( 'General', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'btntext',
				'label'   => __( 'Text', 'listygo-core' ),
				'default' => 'Lorem Ipsum',
			),
			array(
				'type'  => Controls_Manager::URL,
				'id'    => 'url',
				'label' => __( 'Link', 'listygo-core' ),
				'placeholder' => 'https://your-link.com',
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Text Button 1', 'listygo-core' ),
					'style2' => esc_html__( 'Text Button 2', 'listygo-core' ),
					'style3' => esc_html__( 'Video Button', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			array(
				'id'      => 'btn_padding',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .btn-wrap .item-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
			),
			array(
				'type'    => Controls_Manager::CHOOSE,
				'mode'    => 'responsive',
				'id'      => 'align',
				'label'   => __( 'Alignment', 'listygo-core' ),
				'options' => $this->rt_alignment_options(),
				'prefix_class' => 'elementor-align-',
				'selectors' => [
					'{{WRAPPER}} .btn-wrap' => 'text-align: {{VALUE}};',
				],
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'id'      => 'button_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Style', 'listygo-core' ),
			),
			array(
				'id'      => 'normal_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Normal', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'text_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .item-btn path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .event-figure__play path' => 'fill: {{VALUE}}',
				),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'button_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-btn',
				'condition'   => array(
					'style' => array( 'style1', 'style2' )
				),
			),
			array(
				'name'     => 'btn_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-btn',
			),
			array(
				'name'      => 'bbtn_bg_color',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .btn-wrap.btn-v2 .item-btn, {{WRAPPER}} .btn-wrap.btn-v3 .event-figure__play',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Button Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
		        'condition'   => array(
					'style' => array( 'style2', 'style3' )
				),
			),
			array(
				'name'      => 'bbtn_box_shadow',
				'mode'     => 'group',
				'type'     => Group_Control_Box_Shadow::get_type(),
				'label'    => esc_html__( 'Box Shadow', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .btn-wrap.btn-v2 .item-btn, {{WRAPPER}} .btn-wrap.btn-v3 .event-figure__play',
			),

			array(
				'id'      => 'bbtn_animbg_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Ripple Shape Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .ripple-shape circle' => 'fill: {{VALUE}}',
				),
				'condition'   => array(
					'style' => array( 'style3' )
				),
			),
			// Hover
			array(
				'id'      => 'hover_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'text_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-btn:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .event-figure__play:hover path' => 'fill: {{VALUE}}',
				),
			),
			array(
				'name'      => 'bbtn_hbg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-btn:hover, {{WRAPPER}} .event-figure__play:hover',
				'condition'   => array(
					'style' => array( 'style1', 'style3' )
				),
			),
			array(
				'id'      => 'bbtn_hb_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Border Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .item-btn:hover' => 'border-color: {{VALUE}} !important' ),
				'condition'   => array(
					'style' => array( 'style1', 'style2' )
				),
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	public function magnific(){
		wp_enqueue_style( 'magnific-popup' );
		wp_enqueue_script( 'jquery-magnific-popup' );
		wp_enqueue_script( 'popup-func' );
	}
	
	protected function render() {
		$data = $this->get_settings();
		if ($data['style'] == 'style3' ) {
			$this->magnific();
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
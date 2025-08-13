<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/title/class.php
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

class Rt_Price extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Pricing Table', 'fototag-core' );
		$this->rt_base = 'rt-price';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon', [
				'label' => __( 'Icon', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'flaticon-research',
					'library' => 'fa-solid',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'list', [
				'label' => __( 'List', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'feature', [
				'label' => __( 'Feature condition', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => array(
					'active' => esc_html__( 'Yes', 'listygo-core' ),
					'inactive' => esc_html__( 'No', 'listygo-core' ),
				),
				'label_block' => true,
			]
		);

		$fields = array(
			array(
				'id'      => 'sec_rt_price',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Pricing Table', 'listygo-core' ),
			),
			/* = Title = */
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'title',
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'default' => 'Basic Plan',
				'label_block' => true,
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'price',
				'label'   => esc_html__( 'Price', 'listygo-core' ),
				'default' => '$149',
				'description' => esc_html__( 'Put your price with currency', 'listygo-core' ),			
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'period',
				'label'   => esc_html__( 'Period', 'listygo-core' ),
				'default' => 'Monthly',
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'price_list',
				'label'   => esc_html__( 'Address', 'listygo-core' ),
				'prevent_empty' => false,
				'title_field' => '{{{ list }}}',
				'fields' => $repeater->get_controls(),
				'default' => array(
					array( 
						'icon' => 'flaticon-research',
						'list' => '24/7 system monitoring',
					),
					array( 
						'icon' => 'flaticon-research',
						'list' => 'Security management'
					),
					array( 
						'icon' => 'flaticon-research',
						'list' => 'Secure finance backup'
					),
					array( 
						'icon' => 'flaticon-research',
						'list' => 'Remote support'
					),
				),
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'    => 'buttontext',
				'label'   => esc_html__( 'Button Text', 'listygo-core' ),
				'default' => 'Order Plan',
				'label_block' => true,
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'    => 'buttonurl',
				'label'   => esc_html__( 'Button URL', 'listygo-core' ),
			),
			array(
				'mode' => 'section_end',
			),
		);

		$styles = array(			
			// Style
			array(
				'id'      => 'full_table_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Full Item', 'listygo-core' ),
			),
			array(
				'id'      => 'full_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Full Table', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'name'     => 'pricing_table_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-pricing',
			),
			array(
				'id'      => 'full_h_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'name'     => 'pricing_table_h_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-pricing:hover',
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'id'      => 'table_price_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Title/Pricing', 'listygo-core' ),
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
				'selectors' => array( '{{WRAPPER}} .item-title' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'title_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-title',
			),
			array(
				'id'      => 'title_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Hover Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .common-pricing:hover .item-title' => 'color: {{VALUE}}' ),
			),
			array(
				'id'      => 'price_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Price', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'price_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .price-amount' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'price_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .price-amount',
			),
			array(
				'id'      => 'price_period_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Price Period', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'price_period_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .price-duration' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'price_period_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .price-duration',
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Content', 'listygo-core' ),
			),
			array(
				'id'      => 'list_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Offer List', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'list_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .pricing-features li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pricing-features li i' => 'color: {{VALUE}}'
				),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'list_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .pricing-features li',
			),
			array(
				'mode' => 'section_end',
			),
			// Button
			array(
				'id'      => 'table_btn_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Button', 'listygo-core' ),
			),
			array(
				'id'      => 'price_btn_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .common-pricing .item-btn' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'pricing_btn_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-btn',
			),
			array(
			    'id'      => 'pricing_btn_border_radius',
			    'type'    => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%', 'em' ],
			    'label'   => __( 'Border Radius', 'listygo-core' ),                 
			    'selectors' => array(
			        '{{WRAPPER}} .item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
			    ),
			),
			array(
				'id'      => 'pricing_btn_h_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'pricing_btn_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .common-pricing:hover .item-btn' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'pricing_btn_h_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .common-pricing:hover .item-btn',
			),
			array(
				'id'      => 'pricing_btn_hb_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .common-pricing:hover .item-btn' => 'border-color: {{VALUE}}' ),
			),
			array(
				'mode' => 'section_end',
			),
		);

		$fields = array_merge( $fields, $styles );
		return $fields;
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}
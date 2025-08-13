<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/working-steps/class.php
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

class Rt_Working_Step extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Working Step', 'listygo-core' );
		$this->rt_base = 'rt-working-step';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_class', [
				'label' => __( 'Icon', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-smile',
					'library' => 'fa-solid',
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'title', [
				'label' => __( 'Title', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Business growth (2022)' , 'listygo-core' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'description', [
				'label' => __( 'Description', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => 'There are many variations of passages',
				'label_block' => true,
			]
		);

		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => esc_html__( 'General', 'listygo-core' ),
			),
			array(
				'id'      	  => 'style',
				'type'    	  => Controls_Manager::SELECT2,
				'label'   	  => esc_html__( 'Style', 'listygo-core' ),
				'options' 	  => array(
					'style1' 	  => esc_html__( 'Style 1', 'listygo-core' ),
					'style2' 	  => esc_html__( 'Style 2', 'listygo-core' ),
				),
				'default' 	  => 'style1',
				'description' => esc_html__( 'Layout. Default: 1', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'wstep_repeat',
				'label'   => esc_html__( 'Working Steps', 'listygo-core' ),
				'prevent_empty' => false,
				'title_field' => '{{{ title }}}',
				'fields' => $repeater->get_controls(),
			),
			array(
				'mode' => 'section_end',
			),

			// Full box 
			array(
				'id'      => 'full_box_style',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Full Box', 'listygo-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
				'condition' => ['style' => 'style2'],
			),
			array(
				'name'      => 'fullbox_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .working-steps.v2 .working-step-item',
				'condition' => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
				'condition' => ['style' => 'style2'],
			),

			// Icon
			array(
				'id'      => 'icon_style',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Icon', 'listygo-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			),
			array(
				'id'      => 'icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .working-step-item.visible .icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .working-steps.v2 .working-step-item .icon' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'      => 'icon_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .working-step-item .icon:after, {{WRAPPER}} .working-steps.v2 .working-step-item .icon:after',
			),
			array(
				'id'        => 'icon_hover_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Hover', 'listygo-core' ),
				'separator' => 'before',
				'condition' => ['style' => 'style2'],
			),
			array(
				'id'      => 'icon_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .working-steps.v2:hover .working-step-item .icon' => 'color: {{VALUE}}',
				),
				'condition' => ['style' => 'style2'],
			),
			array(
				'name'      => 'icon_hbg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .working-steps.v2:hover .working-step-item .icon:after',
				'condition' => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
			),

			// Number 
			array(
				'id'      => 'number_style',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Number', 'listygo-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			),
			array(
				'id'      => 'number_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .working-step-item .sl-number span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .working-step-item .sl-number span' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'      => 'number_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .working-step-item .sl-number span',
				'condition' => ['style' => 'style1'],
			),
			array(
				'id'      => 'number_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .working-steps.v2:hover .working-step-item .sl-number span' => 'color: {{VALUE}}',
				),
				'condition' => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
			),

			// Title
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Content', 'listygo-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			),
			array(
				'id'        => 'title_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Title', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'title_color',
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .item-title' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-title',
			),
			array(
				'id'        => 'text_heading',
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Text', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'text_color',
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .text' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'text_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .text',
			),
			array(
				'mode' => 'section_end',
			),

		);
		return $fields;
	}

	protected function render() {
		$data = $this->get_settings();

		switch ( $data['style'] ) {
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
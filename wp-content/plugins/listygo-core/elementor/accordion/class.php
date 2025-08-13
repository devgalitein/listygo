<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/accordion/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Accordion extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Accordion', 'evacon-core' );
		$this->rt_base = 'rt-accordion';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title', [
				'label' => __( 'Title', 'evacon-core' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'List Title' , 'evacon-core' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'accodion_text', [
				'label' => __( 'Content', 'evacon-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'List Content' , 'evacon-core' ),
				'show_label' => false,
			]
		);


		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => esc_html__( 'General', 'evacon-core' ),
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'accordion_repeat',
				'label'   => esc_html__( 'Faq', 'evacon-core' ),
				'prevent_empty' => false,
				'title_field' => '{{{ title }}}',
				'fields' => $repeater->get_controls(),
			),
			array(
				'id'          => 'icon_display',
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Icon', 'evacon-core' ),
				'label_on'    => esc_html__( 'On', 'evacon-core' ),
				'label_off'   => esc_html__( 'Off', 'evacon-core' ),
				'description' => esc_html__( 'Enable or disable icon. Default: Off', 'evacon-core' ),
				'default' => 'on',
			),
			array(
				'type'    => Controls_Manager::ICONS,
				'id'      => 'icon',
				'label'   => esc_html__( 'Icon', 'evacon-core' ),
				'default' => array(
					'value' => 'fas fa-smile-wink',
					'library' => 'fa-solid',
				),
				'condition'   => [ 'icon_display!' => ''],
			),
			array(
				'type'    => Controls_Manager::ICONS,
				'id'      => 'active_icon',
				'label'   => esc_html__( 'Active Icon', 'evacon-core' ),
				'default' => array(
					'value' => 'far fa-smile',
					'library' => 'fa-solid',
				),
				'condition'   => [ 'icon_display!' => ''],
			),
			array(	
				'id'      => 'icon_position',				 
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Icon Position', 'evacon-core' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'evacon-core' ),
						'icon' => 'far fa-hand-point-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'evacon-core' ),
						'icon' => 'far fa-hand-point-right',
					],		     
				],
				'default' => 'right',
				'label_block' => false,
				'toggle' => false,
				'condition'   => [ 'icon_display!' => ''],
			),
			array(
				'id'      => 'space_bottom',
				'mode'    => 'responsive',
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Space Bottom', 'evacon-core' ),
				'range' => [
					'px' => [
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .faq-box .panel' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			),
			array(
				'mode' => 'section_end',
			),

			// Title
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Title', 'evacon-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'title_color',
				'label'   => esc_html__( 'Color', 'evacon-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .accordion-button' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'evacon-core' ),
				'selector' => '{{WRAPPER}} .accordion-button',
			),
			array(
				'mode' => 'section_end',
			),
			// Description
			array(
				'id'      => 'desc_style',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Description', 'evacon-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'desc_color',
				'label'   => esc_html__( 'Color', 'evacon-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .faq-box .panel-body p' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'desc_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'evacon-core' ),
				'selector' => '{{WRAPPER}} .faq-box .panel-body p',
			),
			array(
				'mode' => 'section_end',
			),
			// Icon
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_style',
				'label'   => esc_html__( 'Icon', 'evacon-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
				'condition'   => [ 'icon_display!' => ''],
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'icon_color',
				'label'   => esc_html__( 'Icon Color', 'evacon-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rtin-icon i' => 'color: {{VALUE}}',
				),
				'condition'   => [ 'icon_display!' => ''],
			),
			array(
				'type'        => Controls_Manager::NUMBER,
				'id'          => 'icon_size',
				'label'       => esc_html__( 'Icon Size', 'evacon-core' ),
				'default'     => '',
				'selectors' => array(
					'{{WRAPPER}} .rtin-icon i' => 'font-size: {{VALUE}}px',
					'{{WRAPPER}} .rtin-icon svg' => 'width: {{VALUE}}px',
				),
				'description' => esc_html__( 'icon size default: 16px', 'evacon-core' ),
				'condition'   => [ 'icon_display!' => ''],
			),
			array(
				'mode' => 'section_end',
				'condition'   => [ 'icon_display!' => ''],
			),

			// Active Button
			array(
				'mode'    => 'section_start',
				'id'      => 'active_btn_style',
				'label'   => esc_html__( 'Active Button', 'evacon-core' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'title_active_color',
				'label'   => esc_html__( 'Title', 'evacon-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .faq-box .panel-heading .accordion-button:not(.collapsed)' => 'color: {{VALUE}}',
				),
			),
			array(
				'type'    => Controls_Manager::COLOR,
				'id'      => 'icon_active_color',
				'label'   => esc_html__( 'Icon', 'evacon-core' ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .faq-box .panel-heading .accordion-button:not(.collapsed) .rtin-icon i' => 'color: {{VALUE}}',
				),
				'condition'   => [ 'icon_display!' => ''],
			),
			array(
				'name'      => 'active_btn_bdcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'evacon-core' ),
				'selector' => '{{WRAPPER}} .faq-box .panel-heading .accordion-button:not(.collapsed)',
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
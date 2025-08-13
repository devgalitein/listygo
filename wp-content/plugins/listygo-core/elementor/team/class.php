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

class Rt_Team extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Team', 'listygo-core' );
		$this->rt_base = 'rt-team';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon', [
				'label' => esc_html__( 'Icon', 'listygo-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-facebook-f',
					'library' => 'fa-solid',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'link', [
				'label' => esc_html__( 'Link', 'listygo-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$fields = array(
			array(
				'id'      => 'sec_rt_team',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Team Member', 'listygo-core' ),
			),
			/* = Picture = */
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'picture',
				'label'   => esc_html__( 'Team Member', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Team member image set here recomemded size is 550×500 px', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'name',
				'label'   => esc_html__( 'Name', 'listygo-core' ),
				'default' => 'Member name goes here',
				'description' => esc_html__( 'Memner name here', 'listygo-core' ),			
			),
			array(
				'id'      => 'name_link',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Name Link', 'listygo-core' ),
				'description' => esc_html__( 'Memner name link here if need', 'listygo-core' ),			
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'designation',
				'label'   => esc_html__( 'Designation', 'listygo-core' ),
				'default' => 'Desination goes here',
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'social_list',
				'label'   => esc_html__( 'Address', 'listygo-core' ),
				'prevent_empty' => false,
				'fields' => $repeater->get_controls(),
				'default' => array(
					array( 
						'icon' => 'fab fa-facebook-f',
						'link' => '#',
					),
					array( 
						'icon' => 'fab fa-twitter',
						'link' => '#'
					),
					array( 
						'icon' => 'fab fa-instagram',
						'link' => '#'
					),
					array( 
						'icon' => 'fab fa-linkedin-in',
						'link' => '#'
					),
				),
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
				'label'   => esc_html__( 'Full Item', 'listygo-core' ),
			),
			array(
				'name'     => 'full_box_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .team-block',
			),
			array(
				'id'      => 'full_box_border_radius',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Border Radius', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .team-block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
					'{{WRAPPER}} .team-block__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0 !important;',                  
				),
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'id'      => 'name_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Name', 'listygo-core' ),
			),
			array(
				'id'      => 'name_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Name', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'name_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .team-block__heading a' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'name_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .team-block__heading a',
			),
			array(
				'id'      => 'name_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .team-block__heading a:hover' => 'color: {{VALUE}}' ),
			),
			array(
				'id'      => 'designation_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Designation', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'designation_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .team-block__title' => 'color: {{VALUE}}' ),
			),
			array(
				'name'     => 'designation_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .team-block__title',
			),
			array(
				'mode' => 'section_end',
			),
			array(
				'id'      => 'social_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Socials', 'listygo-core' ),
			),
			array(
				'id'      => 'icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .social ul li a' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'     => 'icon_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .social ul li a',
			),
			array(
			    'id'      => 'icon_border_radius',
			    'type'    => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%', 'em' ],
			    'label'   => esc_html__( 'Border Radius', 'listygo-core' ),                 
			    'selectors' => array(
			        '{{WRAPPER}} .social ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
			    ),
			),
			array(
				'id'      => 'social_hover_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'icon_hover_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .social ul li a:hover' => 'color: {{VALUE}}'
				),
			),
			array(
				'name'     => 'icon_hover_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .social ul li a:hover',
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
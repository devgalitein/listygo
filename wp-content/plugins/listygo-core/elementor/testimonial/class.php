<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/testimonial/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0.19
 */

namespace radiustheme\Listygo_Core;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Testimonial extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Testimonial', 'listygo-core' );
		$this->rt_base = 'rt-testimonial';
		parent::__construct( $data, $args );
	}

	public function get_script_depends() {
		return array( 'swiper' );
	}

	public function rt_fields(){

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'picture', [
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'label'   => esc_html__( 'Picture', 'listygo-core' ),
				'default' => [
	                'url' => $this->rt_placeholder_image(),
	            ],
				'description' => esc_html__( 'Image size should be 105px', 'listygo-core' ),
				'show_label' => false,
			]
		);
		$repeater->add_control(
			'testi_name', [
				'label' => __( 'Name', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Testimonial Name' , 'listygo-core' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'testi_desig', [
				'label' => __( 'Designation', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Testimonial Designation',
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'content', [
				'label' => __( 'Testimonial Text', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '“ Tesorem ipsum dolor sit amet consectetur adipiscing elit consectetur adipiscing elit. ”',
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'rating',
			[
				'label' => __( 'Rating', 'listygo-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '5',
				'options' => [
					'5' => __( '5', 'listygo-core' ),
					'4' => __( '4', 'listygo-core' ),
					'3' => __( '3', 'listygo-core' ),
					'2' => __( '2', 'listygo-core' ),
					'1' => __( '1', 'listygo-core' ),
				],
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
					'style3' 	  => esc_html__( 'Style 3', 'listygo-core' ),
				),
				'default' 	  => 'style1',
				'description' => esc_html__( 'Testimonial Layout. Default: 1', 'listygo-core' ),
			),
			array(
				'id'      	  => 'layout',
				'type'    	  => Controls_Manager::SELECT2,
				'label'   	  => esc_html__( 'Layout', 'listygo-core' ),
				'options' 	  => array(
					'grid' 	  => esc_html__( 'Grid layout', 'listygo-core' ),
					'slider'  => esc_html__( 'Slider Layout', 'listygo-core' ),
				),
				'default' 	  => 'slider',
				'description' => esc_html__( 'Testimonial Layout. Default: 1', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'testimonials',
				'label'   => esc_html__( 'Add as many slides as you want', 'listygo-core' ),
				'fields' => $repeater->get_controls(),
				'default' => array(
					array( 
						'testi_name' => 'Donald Simpsom', 
						'testi_desig' => 'Corporate Director', 
						'content' => '“ when an unknown printer took a galley of type aawer awtnd scrambled it to make a type specimen bookR h survived not only five centuries, but also the leap into “' 
					),
					array( 
						'testi_name' => 'Victoria Vargas', 
						'testi_desig' => 'Architect', 
						'content' => '“ when an unknown printer took a galley of type aawer awtnd scrambled it to make a type specimen bookR h survived not only five centuries, but also the leap into “' 
					),
				),
				'title_field' => '{{{ testi_name }}}',
			),
			array(
				'id'      => 'cols',
				'label'   => esc_html__( 'Grid Columns', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_grid_options(),
				'default' => '6',
				'label_block' => true,
				'condition'   => array( 'layout' => array( 'grid' ) ),
			),
			array(
				'type'    => Controls_Manager::CHOOSE,
				'mode'    => 'responsive',
				'id'      => 'align',
				'label'   => __( 'Alignment', 'listygo-core' ),
				'options' => $this->rt_alignment_options(),
				'prefix_class' => 'elementor-align-',
				'selectors' => [
					'{{WRAPPER}} .testimonial-layout' => 'text-align: {{VALUE}};',
				],
			),
			array(
				'mode' => 'section_end',
			),


			
			array(
				'mode'        => 'section_start',
				'id'          => 'sec_slider',
				'label'       => esc_html__( 'Slider Options', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_fade',
				'label'       => esc_html__( 'Fade', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable fade. Default: Off', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_autoplay',
				'label'       => esc_html__( 'Autoplay', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable autoplay. Default: On', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'slider_autoplay_speed',
				'label'   => esc_html__( 'Autoplay Speed', 'listygo-core' ),
				'options' => $this->rt_autoplay_speed(),
				'default' => '2000',
				'description' => esc_html__( 'Select any value for autopaly speed. Default: 2000', 'listygo-core' ),
				'condition'   => array( 'slider_autoplay' => 'yes' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_dots',
				'label'       => esc_html__( 'Dots', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_arrow',
				'label'       => esc_html__( 'Arrow', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'on',
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
			),
			array(
				'id'      => 'slide_to_show',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Slide To Show', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'Select any value for desktop device show. Default: 6', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1', 'style2', 'style3', 'style4' ) ),
			),
			array(
				'id'      => 'col_lg',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktop View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '4',
				'description' => esc_html__( 'Select any value for tab device show. Default: 4', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style2', 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_md',
				'label'   => esc_html__( 'Tab View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '3',
				'description' => esc_html__( 'Select any value for tab device show. Default: 3', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style2', 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_sm',
				'label'   => esc_html__( 'Mobile View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'Select any value for mobile device show. Default: 2', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style2', 'style3' ) ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_xs',
				'label'   => esc_html__( 'Mobile Small View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '1',
				'description' => esc_html__( 'Select any value for small mobile device show. Default: 1', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style2', 'style3' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'layout' => array( 'slider' ) ),
			),
		);

		$repeater2 = new \Elementor\Repeater();
		$repeater2->add_control(
			'picture', [
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'label'   => esc_html__( 'Picture', 'listygo-core' ),
				'default' => [
	                'url' => $this->rt_placeholder_image(),
	            ],
				'description' => esc_html__( 'Image size should be 85px', 'listygo-core' ),
				'show_label' => false,
			]
		);
		$fields2 = array(
			array(
				'id'      => 'left_shape_panel',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Left Shape', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style4' ) ),
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'left-shapes',
				'label'   => esc_html__( 'Left Shape Image', 'listygo-core' ),
				'fields' => $repeater2->get_controls(),
				'condition'   => array( 'style' => array( 'style4' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style4' ) ),
			),
		);

		$repeater3 = new \Elementor\Repeater();
		$repeater3->add_control(
			'picture', [
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'label'   => esc_html__( 'Picture', 'listygo-core' ),
				'default' => [
	                'url' => $this->rt_placeholder_image(),
	            ],
				'description' => esc_html__( 'Image size should be 85px', 'listygo-core' ),
				'show_label' => false,
			]
		);
		$fields3 = array(
			array(
				'id'      => 'right_shape_panel',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Right Shape', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style4' ) ),
			),
			array(
				'type'    => Controls_Manager::REPEATER,
				'id'      => 'right-shapes',
				'label'   => esc_html__( 'Right Shape Image', 'listygo-core' ),
				'fields' => $repeater3->get_controls(),
				'condition'   => array( 'style' => array( 'style4' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style4' ) ),
			),
		);

		$styles = array(
			// Style
			array(
				'id'      => 'testimonial_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Style', 'listygo-core' ),
			),
			// Title Style
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
				'selectors' => array( 
					'{{WRAPPER}} h2.heading-title' => 'color: {{VALUE}}'
				),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'title_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} h2.heading-title',
			),
			// Star
			array(
				'id'      => 'star_icon_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Star', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'star_icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Star Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-rating i' => 'color: {{VALUE}}',
				),
			),
			array(
				'id'      => 'star_icon_active_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Star Active Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .item-rating i.active' => 'color: {{VALUE}}',
				),
			),
			// Description
			array(
				'id'      => 'description_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Description', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'description_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .tes-desc p' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'description_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .tes-desc p',
			),
			// Name
			array(
				'id'      => 'name_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Name', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'name_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .item-title' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'name_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-title',
			),
			// Designation
			array(
				'id'      => 'designation_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Designation', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'designation_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .item-designation' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'designation_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-designation',
			),
		
			// Quote Icon 
			array(
				'id'      => 'quote_icon_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Quote Icon', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'quote_icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .quote-icon path' => 'fill: {{VALUE}}',
				),
			),
			// Picture
			array(
				'id'      => 'picture_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Picture', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
			    'id'      => 'picture_border_radius',
			    'type'    => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%', 'em' ],
			    'label'   => __( 'Border Radius', 'listygo-core' ),                 
			    'selectors' => array(
			    	'{{WRAPPER}} .item-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
			    ),
			    'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'mode' => 'section_end',
			),

			// Arrows/Dots Style
			array(
				'id'      => 'arrows_dots_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Arrows/Dots Style', 'listygo-core' ),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			// Arrow
			array(
				'id'      => 'arrow_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Arrow Style', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'id'      => 'arrow_icon',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Icon Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .swiper-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .swiper-button-next i' => 'color: {{VALUE}}'
				),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'name'     => 'arrow_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'name'     => 'arrow_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => esc_html__( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next',
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
			    'id'      => 'arrow_border_radius',
			    'type'    => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%', 'em' ],
			    'label'   => __( 'Border Radius', 'listygo-core' ),               
			    'selectors' => array(
			        '{{WRAPPER}} .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
			        '{{WRAPPER}} .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'                  
			    ),
			    'condition'   => array( 'layout' => array( 'slider' ) ),
			    'condition'   => array( 'style' => array( 'style1' ) ),
			),

			// Hover Arrow
			array(
				'id'      => 'hover_arrow_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Hover Arrow Style', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'id'      => 'arrow_h_icon',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Hover Icon Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .swiper-button-prev:hover i' => 'color: {{VALUE}}', 
					'{{WRAPPER}} .swiper-button-next:hover i' => 'color: {{VALUE}}' 
				),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'name'     => 'hover_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'id'      => 'arrow_h_border',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Hover Border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .swiper-button-prev:hover' => 'border-color: {{VALUE}}', 
					'{{WRAPPER}} .swiper-button-next:hover' => 'border-color: {{VALUE}}' 
				),
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),

			// Dots
			array(
				'id'      => 'dots_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Dots Style', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'name'     => 'dots_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .slick-dots li button',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
			    'id'      => 'dots_border_radius',
			    'type'    => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%', 'em' ],
			    'label'   => __( 'Border Radius', 'listygo-core' ),                 
			    'selectors' => array(
			        '{{WRAPPER}} .slick-dots li button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
			    ),
			    'condition'   => array( 'layout' => array( 'slider' ) ),
			    'condition'   => array( 'style' => array( 'style1' ) ),
			),
			// Active Dots
			array(
				'id'      => 'active_dots_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Active Dots Style', 'listygo-core' ),
				'separator' => 'before',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'name'     => 'active_dots_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .slick-dots li.slick-active button',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'layout' => array( 'slider' ) ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
		);

		$fields = array_merge( $fields, $fields2, $fields3, $styles );
		return $fields;
	}

	public function swiper(){
		wp_dequeue_script( 'swiper' );
		wp_enqueue_script( 'slider-func' );
	}

	protected function render() {
		$data = $this->get_settings();
		
		if ( $data['layout'] == 'slider' ) {
			$this->swiper();
			$swiper_data = array(
				'col_xl'   => absint($data['slide_to_show']),
				'speed'    => $data['slider_autoplay_speed'],
				'autoplay' => $data['slider_autoplay'] == 'yes' ? true : false,
				'col_lg'  => absint($data['col_lg']),
				'col_md'  => absint($data['col_md']),
				'col_sm'  => absint($data['col_sm']),
				'col_xs'  => absint($data['col_xs']),
			);
			$data['swiper_data'] = json_encode( $swiper_data );
		}
		
		switch ( $data['style'] ) {
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
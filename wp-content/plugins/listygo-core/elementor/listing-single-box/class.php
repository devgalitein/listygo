<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/listing-single-box/class.php
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

class Rt_Listing_Single_Box extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Listing Single Box', 'listygo-core' );
		$this->rt_base = 'rt-listing-single-box';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_listing_single_box',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Listing Single Box', 'listygo-core' ),
			),
			array(
				'id'      => 'postbytitle',
				'label'   => esc_html__( 'Select Title', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_posts_title('rtcl_listing'),
				'multiple' => false,
				'label_block' => true,
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_cat',
				'label'       => esc_html__( 'Enable Category', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_video_btn',
				'label'       => esc_html__( 'Enable Video Button', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_date',
				'label'       => esc_html__( 'Enable Date', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_address',
				'label'       => esc_html__( 'Enable Address', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_author',
				'label'       => esc_html__( 'Enable Author', 'listygo-core' ),
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
				'type'    => Controls_Manager::TEXT,
				'id'      => 'btntext',
				'label'   => esc_html__( 'Text', 'listygo-core' ),
				'default' => esc_html__( 'More Information', 'listygo-core' ),
				'condition' => ['enable_link' => 'yes'],
			),
			// Excerpt
			array(
				'id'      => 'excerpt',
				'type'    => Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Excerpt', 'listygo-core' ),
				'default' => 21,
				'label_block' => true,
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'enable_countdown',
				'label'       => esc_html__( 'Enable Countdown', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'yes',
			),
			array(
				'mode' => 'section_end',
			),

			/* = Item Styles
			==========================================*/

			// Category Styles
			array(
				'id'      => 'cat_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Category', 'listygo-core' ),
			),
			array(
				'id'      => 'cat_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .event-block__tag' => 'color: {{VALUE}}', 
				),
			),
			array(
				'name'      => 'cat_bg_color',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .event-block__tag',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Button Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
			),
			array(
				'name'     => 'cat_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .event-block__tag',
			),
			array(
				'name'     => 'cat_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .event-block__tag',
			),
			array(
				'id'      => 'cat_border_radius',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Border Radius', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .event-block__tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
			),
			array(
				'id'      => 'cat_padding',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .event-block__tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
				),
			),
			array(
				'mode' => 'section_end',
			),

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
					'{{WRAPPER}} .event-block__heading__link' => 'color: {{VALUE}}', 
				),
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .event-block__heading__link',
			),
			array(
				'id'      => 'title_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .event-block__heading__link:hover' => 'color: {{VALUE}}', 
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Listing meta style
			array(
				'id'      => 'meta_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Meta', 'listygo-core' ),
			),
			array(
				'id'      => 'meta_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .event-block__meta ul li' => 'color: {{VALUE}}', 
				),
			),
			array(
				'name'     => 'meta_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .event-block__meta ul li',
			),
			array(
				'mode' => 'section_end',
			),

			// Listing Count Style
			array(
				'id'      => 'listing_count_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Countdown', 'listygo-core' ),
			),
			array(
				'id'        => 'count_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .item-content p' => 'color: {{VALUE}}'),
			),
			array(
				'name'     => 'count_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .item-content p',
			),
			array(
				'name'      => 'count_bg',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .feature-box-layout1 .item-content p',
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
				'selector' => '{{WRAPPER}} .event-block',
			),
			array(
				'type'        => Controls_Manager::SLIDER,
				'id'          => 'height',
				'label' => esc_html__( 'Box Height', 'listygo-core' ),
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors' => [
					'{{WRAPPER}} .event-block' => 'height: {{SIZE}}{{UNIT}};',
				],
			),
			array(
				'id'      => 'box_padding',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .event-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                    
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
		if ($data['enable_video_btn']) {
			$this->magnific();
		}

		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}
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

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use radiustheme\listygo\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Listing_Categories extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Listing Categories', 'listygo-core' );
		$this->rt_base = 'rt-listing-categories';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_general',
				'mode'    => 'section_start',
				'label'   => __( 'Listing Categories', 'listygo-core' ),
			),
			
			// Layout
			array(
				'id'      => 'style',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'layout 1', 'listygo-core' ),
					'style2' => esc_html__( 'layout 2', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			array(
				'id'      => 'title',
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Title', 'listygo-core' ),
				'default' => 'Let’s Discover This City',
				'label_block' => true,
				'condition'   => ['style' => 'style1'],
			),
			array(
				'id'      => 'highlight_categories',
				'label'   => esc_html__( 'Highlight Categories', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => Helper::listing_categories_slug(),
				'multiple' => true,
				'label_block' => true,
			),
			// Layout
			array(
				'id'      => 'icon_type',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Icon Type', 'listygo-core' ),
				'options' => array(
					'icon' => esc_html__( 'Icon', 'listygo-core' ),
					'image' => esc_html__( 'Image', 'listygo-core' ),
				),
				'default' => 'icon',
			),
			array(
				'mode' => 'section_end',
			),

			// Slider Options
			array(
				'mode'        => 'section_start',
				'id'          => 'sec_slider',
				'label'       => esc_html__( 'Slider Options', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_fade',
				'label'       => esc_html__( 'Fade', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable fade. Default: Off', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_autoplay',
				'label'       => esc_html__( 'Autoplay', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable autoplay. Default: On', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'slider_autoplay_speed',
				'label'   => esc_html__( 'Autoplay Speed', 'listygo-core' ),
				'options' => $this->rt_autoplay_speed(),
				'default' => '2000',
				'description' => esc_html__( 'Select any value for autopaly speed. Default: 2000', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_dots',
				'label'       => esc_html__( 'Dots', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'slider_arrow',
				'label'       => esc_html__( 'Arrow', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'default'     => 'on',
				'description' => esc_html__( 'Enable or disable nav dots. Default: off', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'id'      => 'slide_to_show',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Slide To Show', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'Select any value for desktop device show. Default: 6', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'id'      => 'col_lg',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Desktop View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '4',
				'description' => esc_html__( 'Select any value for tab device show. Default: 4', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_md',
				'label'   => esc_html__( 'Tab View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '3',
				'description' => esc_html__( 'Select any value for tab device show. Default: 3', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_sm',
				'label'   => esc_html__( 'Mobile View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '2',
				'description' => esc_html__( 'Select any value for mobile device show. Default: 2', 'listygo-core' ),
				'condition'   => array( 'style' => 'style2' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'col_xs',
				'label'   => esc_html__( 'Mobile Small View', 'listygo-core' ),
				'options' => $this->rt_number_options(),
				'default' => '1',
				'description' => esc_html__( 'Select any value for small mobile device show. Default: 1', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
				'condition'   => ['style' => 'style2'],
			),

			/* = Item Styles
			==========================================*/

			// Title Styles
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Title', 'listygo-core' ),
				'condition'   => ['style' => 'style1'],
			),
			array(
				'id'      => 'title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-categories--title' => 'color: {{VALUE}}',
				),
				'condition'   => ['style' => 'style1'],
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-categories--title',
				'condition'   => ['style' => 'style1'],
			),
			array(
				'mode' => 'section_end',
				'condition'   => ['style' => 'style1'],
			),

			// Style 2 Cirkle
			array(
				'id'      => 'cat_circle_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Full Circle', 'listygo-core' ),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'id'      => 'cat_circle_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Icon Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .cat-icon-img .rtcl-cat-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .cat-icon-img svg path' => 'fill: {{VALUE}}',
				),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'name'      => 'cat_circle_bg_color',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .cat-icon-img',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Circle Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
		        'condition'   => ['style' => 'style2'],
			),
			array(
				'name'     => 'cat_circle_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'cirkle-core' ),
				'selector' => '{{WRAPPER}} .cat-icon-img',
				'condition'   => ['style' => 'style2'],
			),
			array(
				'name'      => 'cat_circle_overlay_color',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .cat-icon-img:before',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Hover Overlay', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
		        'condition'   => ['style' => 'style2'],
			),
			array(
				'id'      => 'circle_ovelay_icon_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Overlay Icon Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .cat-icon-img .listygo-rt-icon-login-icon' => 'color: {{VALUE}}',
				),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
				'condition'   => ['style' => 'style2'],
			),

			// Category Styles
			array(
				'id'      => 'category_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Category', 'listygo-core' ),
			),
			array(
				'id'        => 'cats_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array(
					'{{WRAPPER}} .cat-style' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'icon_color',
				'mode'     => 'group',
				'type'     => Group_Control_Css_Filter::get_type(),
				'label'    => esc_html__( 'Icon Color Filter', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-categoriesBlock img, {{WRAPPER}} .hero-categoriesBlock svg',
				'condition'   => ['style' => 'style1'],
			),
			array(
				'name'     => 'cats_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .cat-style',
			),
			array(
				'name'      => 'cat_bg_color',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .cat-style',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Category Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
			),
			array(
				'name'     => 'cat_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .cat-style',
			),
			// Hover
			array(
				'id'      => 'border_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-categoriesBlock:hover' => 'border-color: {{VALUE}}', 
				),
				'condition'   => ['style' => 'style1'],
			),
			array(
				'id'      => 'border_h_color2',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-categoriesBlock:hover .cat-style' => 'border-color: {{VALUE}}', 
				),
				'condition'   => ['style' => 'style2'],
			),
			array(
				'name'     => 'icon_h_color',
				'mode'     => 'group',
				'type'     => Group_Control_Css_Filter::get_type(),
				'label'    => esc_html__( 'Hover Icon Color', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-categoriesBlock:hover img, {{WRAPPER}} .hero-categoriesBlock:hover svg',
				'condition'   => ['style' => 'style1'],
			),
			array(
				'name'      => 'cat_hbg_color',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-categoriesBlock:hover',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Category Hover Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
		        'condition'   => ['style' => 'style1'],
			),
			array(
				'name'      => 'cat_hbg_color2',
				'mode'    => 'group',
				'type'    => Group_Control_Background::get_type(),
				'types' => [ 'classic', 'gradient', 'video' ],
				'label'   => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-categoriesBlock:hover .cat-style',
				'fields_options' => [
		            'background' => [
		                'label' => esc_html__('Category Hover Background', 'listygo-core'),
		                'default' => 'classic',
		            ]
		        ],
		        'condition'   => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	public function custom_fonts(){
		wp_enqueue_style( 'custom-fonts' );
	}

	public function swiper(){
		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );
		wp_enqueue_script( 'slider-func' );
	}

	public function rt_term_post_count( $term_id ){
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

		if ( $data['style'] == 'style2' ) {
			$this->swiper();
			$swiper_data = array(
				'col_xl'   => absint($data['slide_to_show']),
				'autoplay' => $data['slider_autoplay'] == 'yes' ? true : false,
				'speed'    => $data['slider_autoplay_speed'],
				'autoplay' => $data['slider_autoplay'] == 'yes' ? true : false,
				'col_lg'  => absint($data['col_lg']),
				'col_md'  => absint($data['col_md']),
				'col_sm'  => absint($data['col_sm']),
				'col_xs'  => absint($data['col_xs']),
			);
			$data['swiper_data'] = json_encode( $swiper_data );
		}

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
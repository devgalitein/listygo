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
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use radiustheme\listygo\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Listing_Search extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Listing Search', 'listygo-core' );
		$this->rt_base = 'rt-listing-search';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_general',
				'mode'    => 'section_start',
				'label'   => __( 'Banner Search', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Style 1', 'listygo-core' ),
					'style2' => esc_html__( 'Style 2', 'listygo-core' ),
					'style3' => esc_html__( 'Style 3', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			array(
				'id'      => 'title',
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Title', 'listygo-core' ),
				'default' => 'Let’s Discover This City',
				'label_block' => true,
			),
			array(
				'id'      => 'subtitle',
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Sub Title', 'listygo-core' ),
				'default' => 'Discover & connect with great places around the world',
				'label_block' => true,
			),
			array(
				'id'      => 'cattitle',
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Category Title', 'listygo-core' ),
				'default' => 'Let’s Discover All Places',
				'label_block' => true,
			),
			array(
				'id'      => 'catsubtitle',
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Category Sub Title', 'listygo-core' ),
				'default' => 'Our Popular Search:',
				'label_block' => true,
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'id'      => 'highlight_categories',
				'label'   => esc_html__( 'Highlight Categories', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => Helper::listing_categories_slug(),
				'multiple' => true,
				'label_block' => true,
			),
			array(
				'id'      => 'bgimage',
				'type'    => Controls_Manager::MEDIA,
				'label'   => esc_html__( 'Background Image', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Banner background image should be full width.', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'listing_banner_search_style',
				'label'   => esc_html__( 'Listing Search Type', 'listygo-core' ),
				'default' => '',
				'options' => array(
					'popup'      => esc_html__( 'Popup', 'listygo' ),
                    'standard'   => esc_html__( 'Standard', 'listygo' ),
                    'suggestion' => esc_html__( 'Auto Suggestion', 'listygo' ),
                    'dependency' => esc_html__( 'Dependency Selection', 'listygo' ),
				),
			),
			array(
				'id'      => 'slider_padding',
				'mode' 	  => 'responsive',
				'type'    => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'label'   => esc_html__( 'Padding', 'listygo-core' ),                 
				'selectors' => array(
					'{{WRAPPER}} .hero__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',                 
				),
			),
			array(
				'mode' => 'section_end',
			),

			/* = Item Styles
			==========================================*/

			// Title Styles
			array(
				'id'      => 'title_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Title', 'listygo-core' ),
			),
			array(
				'id'      => 'title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-content__main-title.title-v1' => 'color: {{VALUE}}', 
					'{{WRAPPER}} .hero-content__main-title.title-v2' => 'color: {{VALUE}}', 
				),
			),
			array(
				'name'     => 'title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-content__main-title.title-v1, {{WRAPPER}} .hero-content__main-title.title-v2',
			),
			array(
				'mode' => 'section_end',
			),

			// Subtitle Styles
			array(
				'id'      => 'subtitle_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Sub Title', 'listygo-core' ),
			),
			array(
				'id'        => 'subtitle_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .hero-content__sub-title' => 'color: {{VALUE}}'),
			),
			array(
				'name'     => 'subtitle_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-content__sub-title',
			),
			array(
				'mode' => 'section_end',
			),
			// Category Styles
			array(
				'id'      => 'category_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Category', 'listygo-core' ),
			),
			// Cat Title
			array(
				'id'      => 'cat_title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Title Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-content--style2 .hero-content__main-title' => 'color: {{VALUE}}', 
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'name'     => 'cat_title_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => __( 'Title Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero-content--style2 .hero-content__main-title',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			// Cat Sub Title
			array(
				'id'      => 'catsubtitle_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Sub Title Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero--layout2 .hero-categories--title' => 'color: {{VALUE}}', 
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'name'     => 'catsubtitle_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Sub Title Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .hero--layout2 .hero-categories--title',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'id'        => 'cats_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-categoriesBlock a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .hero-categoriesBlock__counter' => 'color: {{VALUE}}',
					'{{WRAPPER}} .hero-categoriesBlock--style2' => 'color: {{VALUE}}',
				),
			),
			array(
				'name'     => 'cats_typo',
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .hero-categoriesBlock a,
					{{WRAPPER}} .hero-categoriesBlock__counter,
					{{WRAPPER}} .hero-categoriesBlock--style2
					',
			),
			array(
				'name'      => 'cats_icon_hb_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .hero-categoriesBlock--style2:hover
				',
				'condition'   => array( 'style' => array( 'style2' ) ),
			),
			array(
				'id'      => 'catscatborder_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .hero-categoriesBlock--style2:hover' => 'border-color: {{VALUE}}', 
				),
				'condition'   => array( 'style' => array( 'style2' ) ),
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

	protected function render() {
		$data = $this->get_settings();
		$this->custom_fonts();
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
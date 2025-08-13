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

class Rt_Search_Form extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Search Form', 'listygo-core' );
		$this->rt_base = 'rt-search-form';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_general',
				'mode'    => 'section_start',
				'label'   => __( 'Search Form', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Style 1', 'listygo-core' ),
					'style2' => esc_html__( 'Style 2', 'listygo-core' ),
					'style3' => esc_html__( 'Style 3', 'listygo-core' ),
					'style4' => esc_html__( 'Style 4', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			array(
				'type'    => Controls_Manager::SELECT,
				'id'      => 'listing_banner_search_style',
				'label'   => esc_html__( 'Listing Search Type', 'listygo-core' ),
				'default' => 'standard',
				'options' => array(
					'popup'      => esc_html__( 'Popup', 'listygo' ),
               'standard'   => esc_html__( 'Standard', 'listygo' ),
               'suggestion' => esc_html__( 'Auto Suggestion', 'listygo' ),
               'dependency' => esc_html__( 'Dependency Selection', 'listygo' ),
				),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'search_keyword',
				'label'       => esc_html__( 'Search by Keyword', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable search by keyword. Default: On', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'search_location',
				'label'       => esc_html__( 'Search by Location', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable search by location. Default: On', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'search_category',
				'label'       => esc_html__( 'Search by Category', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable search by category. Default: On', 'listygo-core' ),
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
				'id'      => 'css_class',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Css class', 'listygo-core' ),
				'description' => esc_html__( 'Extra class for css, if need', 'listygo-core' ),			
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
			case 'style4':
				$template = 'view-4';
				break;
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
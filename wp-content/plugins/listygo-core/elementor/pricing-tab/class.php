<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/pricing-tab/class.php
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

class Rt_Pricing_Tab extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Pricing Tab', 'listygo-core' );
		$this->rt_base = 'rt-pricing-tag';
		parent::__construct( $data, $args );
	}

	private function listygo_elementor_template($type=''){ 
        $type = $type ? $type :'elementor_library';
        global $post;
        $args = array('numberposts' => -1,'post_type' => $type, );
        $posts = get_posts($args);  
        $categories = array(
        	''  => __( 'Select', 'listygo-core' ),
        );
        foreach ($posts as $pn_cat) {
            $categories[$pn_cat->ID] = get_the_title($pn_cat->ID);
        }
        return $categories;   
	}

	public function rt_fields(){

		$fields = array(
			array(
				'id'      => 'sec_rt_price',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Pricing Table', 'listygo-core' ),
			),
			/* = Monthly = */
			array(
				'id'      => 'monthly_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Monthly', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'monthly_label',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Label', 'listygo-core' ),
				'default' => 'Monthly',
				'label_block' => true,
			),
			array(
				'id'      => 'monthly_template',
				'type' => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Pricing Type', 'listygo-core' ),
				'default' => 'Monthly',
				'options' => $this->listygo_elementor_template('elementor_library'),
				'label_block' => true,
			),        
			
			/* = Yearly = */
			array(
				'id'      => 'yearly_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Yearly', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'yearly_label',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Label', 'listygo-core' ),
				'default' => 'Yearly',
				'label_block' => true,
			),
			array(
				'id'      => 'yearly_template',
				'type' => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Pricing Type', 'listygo-core' ),
				'default' => 'Yearly',
				'options' => $this->listygo_elementor_template('elementor_library'),
				'label_block' => true,
			),
			array(
				'mode' => 'section_end',
			),
		);

		$styles = array(
			// Style
			array(
				'id'      => 'label_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Tab label', 'listygo-core' ),
			),
			array(
				'id'      => 'label_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Normal', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'label_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .nav-link' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'label_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .nav-link',
			),
			// Active
			array(
				'id'      => 'active_label_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Active', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'active_label_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( '{{WRAPPER}} .nav-link.active' => 'color: {{VALUE}}' ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'active_label_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .nav-link.active',
			),

			// Switch
			array(
				'id'      => 'switch_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Switch', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'name'     => 'switch_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .pricing-plan-tab .nav-tabs .tab-switcher',
			),
			array(
				'id'      => 'switch_dotbgcolor',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Cirkle Color', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'name'     => 'switch_dotbgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .pricing-plan-tab .nav-tabs .nav-link.active span',
			),

			// Discount Text
			array(
				'id'      => 'discount_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Discount text', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'discount_width',
				'label'   => esc_html__( 'Width', 'listygo-core' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 145,
				],
				'selectors' => [
					'{{WRAPPER}} .discount-badge span' => 'width: {{SIZE}}{{UNIT}};',
				],
				'label_block' => true,
			),
			array(
				'id'      => 'badge_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .discount-badge span' => 'color: {{VALUE}}' 
				),
			),
			array(
				'id'      => 'badge_bgcolor',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Background Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .discount-badge span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .discount-badge span:before' => 'border-color: transparent {{VALUE}} transparent transparent',
				),
			),
			// Switch
			array(
				'id'      => 'pricing_switch_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Full Box', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'name'     => 'pricing_box_bgcolor',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => __( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .pricing-plan-layout-1',
			),
			array(
				'id'      => 'pricing_box_bcolor',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .pricing-plan-layout-1' => 'border-color: {{VALUE}}',
				),
			),
			array(
				'mode' => 'section_end',
			),
			
		);

		$fields = array_merge( $fields, $styles );
		return $fields;
	}

	public function custom_fonts(){
		wp_enqueue_style( 'custom-fonts' );
	}

	protected function render() {
		$data = $this->get_settings();
		$this->custom_fonts();
		
		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}
<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/animated-image/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;

class Rt_Image_Box extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Image Box', 'listygo-core' );
		$this->rt_base = 'rt-image-box';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_image_box',
				'mode'    => 'section_start',
				'label'   => __( 'Image Box', 'listygo-core' ),
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
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'line_image',
				'label'   => esc_html__( 'Line Image', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended image size 810×176 px', 'listygo-core' ),
				'condition' => ['style' => ['style1']],
			),
			/* = Image 1
			============================================*/
			array(
				'id'      => 'image1_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Image Box 1', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image_c1',
				'label'   => esc_html__( 'Image Clip 1', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended image size 58×68 px', 'listygo-core' ),
				'condition' => ['style' => ['style1']],
			),
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image1',
				'label'   => esc_html__( 'Image 1', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
			),
			// Title
			array(
				'id'      => 'title1',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'default' => 'Image Title',
				'label_block' => true,
				'condition' => ['style' => ['style1']],
			),
			// Title Link
			array(
				'id'      => 'title_link',
				'type'    => Controls_Manager::URL,
				'label'   => esc_html__( 'Title Link', 'listygo-core' ),
				'placeholder' => 'https://your-link.com',
				'label_block' => true,
				'condition' => ['style' => ['style1']],
			),

			/* = Image 2
			============================================*/
			array(
				'id'      => 'image2_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Image Box 2', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image_c2',
				'label'   => esc_html__( 'Image Clip 2', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended image size 58×68 px', 'listygo-core' ),
				'condition' => ['style' => ['style1']],
			),
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image2',
				'label'   => esc_html__( 'Image 2', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
			),
			// Title
			array(
				'id'      => 'title2',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'default' => 'Image Title',
				'label_block' => true,
				'condition' => ['style' => ['style1']],
			),
			// Title Link
			array(
				'id'      => 'title_link2',
				'type'    => Controls_Manager::URL,
				'label'   => esc_html__( 'Title Link', 'listygo-core' ),
				'placeholder' => 'https://your-link.com',
				'label_block' => true,
				'condition' => ['style' => ['style1']],
			),

			/* = Image 3
			============================================*/
			array(
				'id'      => 'image3_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => __( 'Image Box 3', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image_c3',
				'label'   => esc_html__( 'Image Clip 3', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended image size 58×68 px', 'listygo-core' ),
				'condition' => ['style' => ['style1']],
			),
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image3',
				'label'   => esc_html__( 'Image 3', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
			),
			// Title
			array(
				'id'      => 'title3',
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'default' => 'Image Title',
				'label_block' => true,
				'condition' => ['style' => ['style1']],
			),
			// Title Link
			array(
				'id'      => 'title_link3',
				'type'    => Controls_Manager::URL,
				'label'   => esc_html__( 'Title Link', 'listygo-core' ),
				'placeholder' => 'https://your-link.com',
				'label_block' => true,
				'condition' => ['style' => ['style1']],
			),

			/* = Image 4
			============================================*/
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image4',
				'label'   => esc_html__( 'Image 4', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
				'condition' => ['style' => ['style2', 'style3', 'style4']]
			),
			array(
				'id'      => 'video_url',
				'type'    => Controls_Manager::URL,
				'label'   => esc_html__( 'Video URL', 'listygo-core' ),
				'placeholder' => 'https://your-link.com',
				'label_block' => true,
				'condition' => ['style' => ['style2']],
			),
			/* = Image 5
			============================================*/
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image5',
				'label'   => esc_html__( 'Image 5', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
				'condition' => ['style' => ['style3', 'style4']]
			),
			/* = Image 6
			============================================*/
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image6',
				'label'   => esc_html__( 'Image 6', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
				'condition' => ['style' => ['style3']]
			),
			/* = Image 7
			============================================*/
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image7',
				'label'   => esc_html__( 'Image 7', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
				'condition' => ['style' => ['style3']]
			),
			/* = Image 8
			============================================*/
			array(
				'type'    => Controls_Manager::MEDIA,
				'id'      => 'image8',
				'label'   => esc_html__( 'Image 8', 'listygo-core' ),
				'default' => [
                    'url' => $this->rt_placeholder_image(),
                ],
				'description' => esc_html__( 'Recommended actual image size that you need', 'listygo-core' ),
				'condition' => ['style' => ['style3']]
			),
			array(
				'mode' => 'section_end',
			),
			
			/* = Styles
			============================================*/
			array(
				'id'      => 'sec_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'condition' => ['style' => ['style1', 'style2']],
			),
			array(
				'id'        => 'title_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .latestListing-block .latestListing-block__name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .latestListing-block .latestListing-block__name a' => 'color: {{VALUE}}'
				),
				'condition' => ['style' => 'style1'],
			),
			array(
				'id'        => 'icon_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Play Icon Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .event-figure__play svg path' => 'fill: {{VALUE}}'
				),
				'condition' => ['style' => 'style2'],
			),
			array(
				'id'        => 'title_h_color',
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .latestListing-block:hover .latestListing-block__name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .latestListing-block:hover .latestListing-block__name a' => 'color: {{VALUE}}'
				),
				'condition' => ['style' => 'style1'],
			),
			array(
				'name'      => 'item_bg_color',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .latestListing-block, {{WRAPPER}} .latestListing-block::before',
				'condition' => ['style' => 'style1'],
			),
			array(
				'id'      => 'bbtn_animbg_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Ripple Shape Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .ripple-shape circle' => 'fill: {{VALUE}}',
				),
				'condition' => ['style' => 'style2'],
			),
			array(
				'mode' => 'section_end',
				'condition' => ['style' => ['style1', 'style2']],
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
		
		if ($data['style'] == 'style2') {
			$this->magnific();
		}

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
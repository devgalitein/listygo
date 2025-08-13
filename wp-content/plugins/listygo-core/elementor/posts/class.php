<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/posts/class.php
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

class Rt_Post extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Posts', 'listygo-core' );
		$this->rt_base = 'rt-post';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'id'      => 'sec_rt_post',
				'mode'    => 'section_start',
				'label'   => esc_html__( 'Posts', 'listygo-core' ),
			),
			array(
				'type'    => Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Style', 'listygo-core' ),
				'options' => array(
					'style1' => esc_html__( 'Layout 1', 'listygo-core' ),
					'style2' => esc_html__( 'Layout 2', 'listygo-core' ),
					'style3' => esc_html__( 'Layout 3', 'listygo-core' ),
				),
				'default' => 'style1',
			),
			
			//Query Type
			array(
				'id'      => 'query_type',
				'type'    => Controls_Manager::SELECT2,
				'label'   => esc_html__( 'Get Posts by cats or title', 'listygo-core' ),
				'options' => array(
					'cats' => esc_html__( 'Posts by Categories', 'listygo-core' ),
					'titles' => esc_html__( 'Posts by Titles', 'listygo-core' ),
				),
				'label_block' => true,
			),
			array(
				'id'      => 'postbycats',
				'label'   => esc_html__( 'Posts By Category', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_blog_categories(),
				'multiple' => true,
				'label_block' => true,
				'condition'   => array( 'query_type' => array( 'cats' ) ),
			),
			array(
				'id'      => 'postbytitle',
				'label'   => esc_html__( 'Posts By Title', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_blog_posts_title(),
				'multiple' => true,
				'label_block' => true,
				'condition'   => array( 'query_type' => array( 'titles' ) ),
			),
			array(
				'id'      => 'orderby',
				'label'   => esc_html__( 'Order By', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_post_orderby(),
				'default' => 'date',
				'label_block' => true,
			),
			// Post per page
			array(
				'id'      => 'number',
				'label'   =>esc_html__( 'Total number of post', 'listygo-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'description' =>esc_html__( 'Write -1 to show all', 'listygo-core' ),
				'label_block' => true,
			),
			array(
				'id'      => 'offset',
				'label'   =>esc_html__( 'Post Offset', 'listygo-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'description' =>esc_html__( 'offset means start showing post not from the first post', 'listygo-core' ),
				'label_block' => true,
			),
			array(
				'id'      => 'excerpt',
				'label'   =>esc_html__( 'Content excerpt number', 'listygo-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '30',
				'label_block' => true,
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'post_cat',
				'label'       => esc_html__( 'Category', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable post category. Default: On', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'post_date',
				'label'       => esc_html__( 'Date', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable post date. Default: On', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'post_admin',
				'label'       => esc_html__( 'Author', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable post author. Default: On', 'listygo-core' ),
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'post_comnt',
				'label'       => esc_html__( 'Comments', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable post comments. Default: On', 'listygo-core' ),
			),
			array(
				'id'      => 'cols',
				'label'   => esc_html__( 'Grid Columns', 'listygo-core' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->rt_grid_options(),
				'default' => '4',
				'label_block' => true,
			),
			array(
				'type'        => Controls_Manager::SWITCHER,
				'id'          => 'gutters',
				'label'       => esc_html__( 'Gutters', 'listygo-core' ),
				'label_on'    => esc_html__( 'On', 'listygo-core' ),
				'label_off'   => esc_html__( 'Off', 'listygo-core' ),
				'return_value' => 'on',
				'default' 	   => 'on',
				'description' => esc_html__( 'Enable or disable grid gutter. Default: off', 'listygo-core' ),
			),
			array(
				'mode' => 'section_end',
			),
			
			// Title
			array(
				'id'      => 'post_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
			),
			array(
				'id'      => 'title_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Title', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'title_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .post-title a' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .blog-block__heading a' => 'color: {{VALUE}} !important',
				),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'name_typo',
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .post-title a, {{WRAPPER}} .blog-block__heading a',
			),
			array(
				'id'      => 'title_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .post-title a:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .blog-block__heading a:hover' => 'color: {{VALUE}} !important',
				),
			),
			array(
				'mode' => 'section_end',
			),
			// Category
			array(
				'id'      => 'cat_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Category', 'listygo-core' ),
			),
			array(
				'id'      => 'cat_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Category Style', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'cat_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .blog-block__tag a' => 'color: {{VALUE}} !important'
				),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'cat_typo',
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '
					{{WRAPPER}} .blog-block__tag a
				',
			),
			array(
				'name'     => 'cat_bgc',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .blog-block__tag a',
			),
			array(
				'name'     => 'cat_border',
				'mode'     => 'group',
				'type'     => Group_Control_Border::get_type(),
				'label'    => __( 'Border', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .blog-block__tag a',
			),
			array(
				'id'      => 'cat_h_heading',
				'type' => Controls_Manager::HEADING,
				'label'   => esc_html__( 'Category Hover', 'listygo-core' ),
				'separator' => 'before',
			),
			array(
				'id'      => 'category_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .blog-block:hover .blog-block__tag a' => 'color: {{VALUE}} !important', 
					'{{WRAPPER}} .blog-box-layout1:hover .blog-block__tag a' => 'color: {{VALUE}} !important' 
				),
			),
			array(
				'name'     => 'cat_hbgc',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .blog-block:hover .blog-block__tag a, {{WRAPPER}} .blog-box-layout1:hover .blog-block__tag a',
			),
			array(
				'id'      => 'category_h_bc',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Border Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .blog-block:hover .blog-block__tag a' => 'border-color: {{VALUE}} !important', 
					'{{WRAPPER}} .blog-box-layout1:hover .blog-block__tag a' => 'border-color: {{VALUE}} !important' 
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Admin 
			array(
				'id'      => 'author_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => esc_html__( 'Author', 'listygo-core' ),
			),
			array(
				'id'      => 'author_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} li.entry-admin' => 'color: {{VALUE}} !important'
				),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'author_typo',
				'label'    => esc_html__( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} li.entry-admin',
			),
			array(
				'mode' => 'section_end',
			),
			// Date
			array(
				'id'      => 'date_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Date', 'listygo-core' ),
			),
			array(
				'id'      => 'date_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} li.entry-date' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} li.entry-date span.date' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .blog-block__date' => 'color: {{VALUE}} !important',
				),
			), 
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'date_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} li.entry-date, {{WRAPPER}} li.entry-date span.date, {{WRAPPER}} .blog-block__date',
			),
			array(
				'name'     => 'date_bgc',
				'mode'     => 'group',
				'type'     => Group_Control_Background::get_type(),
				'label'    => esc_html__( 'Background', 'listygo-core' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .blog-block__date',
				'condition'   => array(
					'style' => array( 'style3' )
				),
			),
			array(
				'mode' => 'section_end',
			),

			// Desc
			array(
				'id'      => 'desc_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Description', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'id'      => 'desc_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .content p' => 'color: {{VALUE}} !important'
				),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'desc_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .content p',
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style1' ) ),
			),

			// Link
			array(
				'id'      => 'link_style',
				'mode'    => 'section_start',
				'tab'     => Controls_Manager::TAB_STYLE,
				'label'   => __( 'Link', 'listygo-core' ),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'id'      => 'link_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .btn-text' => 'color: {{VALUE}} !important'
				),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'mode'     => 'group',
				'type'     => Group_Control_Typography::get_type(),
				'name'     => 'link_typo',
				'label'    => __( 'Typography', 'listygo-core' ),
				'selector' => '{{WRAPPER}} .btn-text',
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'id'      => 'link_h_color',
				'type'    => Controls_Manager::COLOR,
				'label'   => __( 'Hover Color', 'listygo-core' ),
				'selectors' => array( 
					'{{WRAPPER}} .btn-text:hover' => 'color: {{VALUE}} !important'
				),
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
			array(
				'mode' => 'section_end',
				'condition'   => array( 'style' => array( 'style1' ) ),
			),
		);
		return $fields;
	}

	protected function render() {
		$data = $this->get_settings();

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
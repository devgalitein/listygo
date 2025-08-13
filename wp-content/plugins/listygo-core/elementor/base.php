<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

use \ReflectionClass;
use Elementor\Widget_Base;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Custom_Widget_Base extends Widget_Base {
	public $rt_name;
	public $rt_base;
	public $rt_category;
	public $rt_icon;
	public $rt_translate;
	public $rt_dir;

	public function __construct( $data = [], $args = null ) {
		$this->rt_category = LISTYGO_CORE_THEME_PREFIX . '-widgets'; // Category /@dev
		$this->rt_icon     = 'rdtheme-el-custom';
		$this->rt_dir      = dirname( ( new ReflectionClass( $this ) )->getFileName() );
		parent::__construct( $data, $args );
	}

	abstract public function rt_fields();

	public function get_name() {
		return $this->rt_base;
	}

	public function get_title() {
		return $this->rt_name;
	}

	public function get_icon() {
		return $this->rt_icon;
	}

	public function get_categories() {
		return array( $this->rt_category );
	}

	protected function register_controls() {
		$fields = $this->rt_fields();
		foreach ( $fields as $field ) {
			if ( isset( $field['mode'] ) && $field['mode'] == 'section_start' ) {
				$id = $field['id'];
				unset( $field['id'] );
				unset( $field['mode'] );
				$this->start_controls_section( $id, $field );
			}
			elseif ( isset( $field['mode'] ) && $field['mode'] == 'section_end' ) {
				$this->end_controls_section();
			}
			elseif ( isset( $field['mode'] ) && $field['mode'] == 'group' ) {
				$type = $field['type'];
				unset( $field['mode'] );
				unset( $field['type'] );
				$this->add_group_control( $type, $field );
			}
			elseif ( isset( $field['mode'] ) && $field['mode'] == 'responsive' ) {
				$id = $field['id'];
				unset( $field['id'] );
				unset( $field['mode'] );
				$this->add_responsive_control( $id, $field );
			}
			else {
				$id = $field['id'];
				unset( $field['id'] );
				$this->add_control( $id, $field );
			}
		}
	}

	public function rt_template( $template, $data ) {
		$template_name = '/elementor-custom/' . basename( $this->rt_dir ) . '/' . $template . '.php';
		if ( file_exists( STYLESHEETPATH . $template_name ) ) {
			$file = STYLESHEETPATH . $template_name;
		}
		elseif ( file_exists( TEMPLATEPATH . $template_name ) ) {
			$file = TEMPLATEPATH . $template_name;
		}
		else {
			$file = $this->rt_dir . '/' . $template . '.php';
		}

		ob_start();
		include $file;
		echo ob_get_clean();
	}

	public function rt_alignment_options(){
		return array(
			'left'    => array(
				'title' => __( 'Left', 'listygo-core' ),
				'icon' => 'eicon-text-align-left',
			),
			'center' => array(
				'title' => __( 'Center', 'listygo-core' ),
				'icon' => 'eicon-text-align-center',
			),
			'right' => array(
				'title' => __( 'Right', 'listygo-core' ),
				'icon' => 'eicon-text-align-right',
			),
			'justify' => array(
				'title' => __( 'Justified', 'listygo-core' ),
				'icon' => 'eicon-text-align-justify',
			),
		);
	}

	public function rt_grid_options(){
		return [
			'1'  => esc_html__( '1 Columns', 'listygo-core' ),
			'2'  => esc_html__( '2 Columns', 'listygo-core' ),
			'3'  => esc_html__( '3 Columns', 'listygo-core' ),
			'4'  => esc_html__( '4 Columns', 'listygo-core' ),
			'5'  => esc_html__( '5 Columns', 'listygo-core' ),
			'6'  => esc_html__( '6 Columns', 'listygo-core' ),
		];
	}

	public function rt_number_options(){
		return [
			'1'  => esc_html__( '1', 'listygo-core' ),
			'2'  => esc_html__( '2', 'listygo-core' ),
			'3'  => esc_html__( '3', 'listygo-core' ),
			'4'  => esc_html__( '4', 'listygo-core' ),				
			'5'  => esc_html__( '5', 'listygo-core' ),
			'6'  => esc_html__( '6', 'listygo-core' ),
			'7'  => esc_html__( '7', 'listygo-core' ),
			'8'  => esc_html__( '8', 'listygo-core' ),
		];
	}

	public function rt_autoplay_speed(){
		return [
			'500'  => esc_html__( '500', 'listygo-core' ),
			'1000' => esc_html__( '1000', 'listygo-core' ),
			'1500' => esc_html__( '1500', 'listygo-core' ),
			'2000' => esc_html__( '2000', 'listygo-core' ),
			'2500' => esc_html__( '2500', 'listygo-core' ),
			'3000' => esc_html__( '3000', 'listygo-core' ),
		];
	}

	public function rt_anim_delay(){
		return [
			'200' => esc_html__( '200', 'listygo-core' ),
			'300' => esc_html__( '300', 'listygo-core' ),
			'400' => esc_html__( '400', 'listygo-core' ),
			'500' => esc_html__( '500', 'listygo-core' ),
			'600' => esc_html__( '600', 'listygo-core' ),
			'700' => esc_html__( '700', 'listygo-core' ),
			'800' => esc_html__( '800', 'listygo-core' ),
			'900' => esc_html__( '900', 'listygo-core' ),
		];
	}

	public function rt_placeholder_image(){
		$placeholder_image = LISTYGO_CORE_BASE_URL.'assets/imgs/placeholder.png';

		/**
		 * Get placeholder image source.
		 *
		 * Filters the source of the default placeholder image used by Elementor.
		 *
		 * @since 1.0.0
		 *
		 * @param string $placeholder_image The source of the default placeholder image.
		 */
		$placeholder_image = apply_filters( 'elementor/utils/get_placeholder_image_src', $placeholder_image );

		return $placeholder_image;
	}

	public function rt_post_orderby(){
		return [
			'ID'  => esc_html__( 'Post Id', 'traveldo' ),
			'author' => esc_html__( 'Post Author', 'traveldo' ),
			'title' => esc_html__( 'Title', 'traveldo' ),
			'date' => esc_html__( 'Date', 'traveldo' ),
			'modified' => esc_html__( 'Modified', 'traveldo' ),
			'parent' => esc_html__( 'Parent', 'traveldo' ),
			'rand' => esc_html__( 'Random', 'traveldo' ),
			'comment_count' => esc_html__( 'Comment Count', 'traveldo' ),
			'menu_order' => esc_html__( 'Menu Order', 'traveldo' ),
		];
	}
	public function rt_blog_categories() {
	    $categories = get_categories( array(
		    'orderby' => 'name',
		    'parent'  => 0
		) );
	    if(!empty( $categories )){
	    	$category_links = array();
	    	foreach ($categories as $key => $value) {
	        	$category_links[$value->term_id] = $value->name;  
	    	}
	    	return $category_links;
	    }
	}
	public function rt_blog_posts_title() {
		$args = array(
		    'post_type'      => 'post',
		    'post_status'    => 'publish',
		    'taxonomy'       => 'category'
		);
		$post_title = array();
		$grid_query = new \WP_Query( $args );
		if ( $grid_query->have_posts() ) : 
		    while ( $grid_query->have_posts() ) : $grid_query->the_post();
		    $post_title[get_the_ID()] = get_the_title();
		    endwhile; wp_reset_postdata();
		endif;
		return $post_title;
	}
	public function rt_service_posts_title() {
		$prefix = LISTYGO_CORE_THEME_PREFIX;
		$args = array(
		    'post_type'      => $prefix.'_service',
		    'post_status'    => 'publish',
		    'taxonomy'       => $prefix.'_service_category',
		    'posts_per_page' => -1
		);
		$post_title = array();
		$grid_query = new \WP_Query( $args );
		if ( $grid_query->have_posts() ) : 
		    while ( $grid_query->have_posts() ) : $grid_query->the_post();
		    $post_title[get_the_ID()] = get_the_title();
		    endwhile; wp_reset_postdata();
		endif;
		return $post_title;
	}
	public function rt_posts_title($post) {
		$prefix = LISTYGO_CORE_THEME_PREFIX;
		$args = array(
		    'post_type'    => $post,
		    'post_status'  => 'publish',
		    'posts_per_page' => -1,
		);
		$post_title = array();
		$grid_query = new \WP_Query( $args );
		if ( $grid_query->have_posts() ) : 
		    while ( $grid_query->have_posts() ) : $grid_query->the_post();
		    $post_title[get_the_ID()] = get_the_title();
		    endwhile; wp_reset_postdata();
		endif;
		return $post_title;
	}
	public function rt_team_posts_title() {
		$prefix = LISTYGO_CORE_THEME_PREFIX;
		$args = array(
			'post_type'    => $prefix.'_team',
			'post_status'  => 'publish',
			'taxonomy'     => $prefix.'_team_category',
			'posts_per_page' => -1,
		);
		$post_title = array();
		$grid_query = new \WP_Query( $args );
		if ( $grid_query->have_posts() ) : 
			while ( $grid_query->have_posts() ) : $grid_query->the_post();
			$post_title[get_the_ID()] = get_the_title();
			endwhile; wp_reset_postdata();
		endif;
		return $post_title;
	}

	//Get Custom post terms:
	protected function rt_get_terms_by_id( $term, $text ) {
		$terms   = get_terms( [
			'taxonomy'   => $term,
			'hide_empty' => true,
		] );
		$options = [ '0' => __( $text, 'listygo-core' ) ];
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
			return $options;
		}
	}

	//Get location terms:
	protected function rt_get_rtcl_location_by_id( $term ) {
		$terms   = get_terms( [
			'taxonomy'   => $term,
			'hide_empty' => true,
		] );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
			return $options;
		}
	}

	//Get listing types:
	protected function rt_get_listing_types() {

		$listing_types = Functions::get_listing_types();
        $listing_types = empty( $listing_types ) ? [] : $listing_types;

		if ( ! empty( $listing_types ) ) {
			foreach ( $listing_types as $key => $listing_type ) {
				$options[ $key ] = $listing_type;
			}
			return $options;
		}
	}

}
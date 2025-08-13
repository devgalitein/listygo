<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\Listygo_Core;

use radiustheme\listygo\Helper;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Kits\Documents\Kit;
use radiustheme\listygo\Listing_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once LISTYGO_CORE_BASE_DIR. '/elementor/controls/traits-icons.php';

// Elementor default widget control
require_once __DIR__ . '/el-extend.php';

class Custom_Widget_Init {

	public function __construct() {
		add_action( 'elementor/widgets/register', array( $this, 'init' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categoty' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_style' ) );
		add_action( 'elementor/icons_manager/additional_tabs', array( $this, 'listygo_flaticon_tab' ) );
		add_action( 'after_switch_theme', [$this, 'listygo_add_cpt_support'] );
		// add_action( 'after_switch_theme', [$this, 'set_elementor_default_options'] );
	}


	function listygo_add_cpt_support() {
	    //if exists, assign to $cpt_support var
		$cpt_support = get_option( 'elementor_cpt_support' );
		
		//check if option DOESN'T exist in db
		if( ! $cpt_support ) {
		    $cpt_support = [ 'page', 'post', 'listygo_portfolio', 'listygo_service' ]; //create array of our default supported post types
		    update_option( 'elementor_cpt_support', $cpt_support ); //write it to the database
		}
	}


	public function set_elementor_default_options() {
		$data = array(
			'elementor_disable_typography_schemes' => 'yes',
			'elementor_disable_color_schemes'      => 'yes',
			'elementor_css_print_method'           => 'internal',
			'elementor_container_width'            => '1200',
			'elementor_viewport_lg'                => '992',
			'_elementor_general_settings'          => array(
				'default_generic_fonts' => 'Sans-serif',
				'global_image_lightbox' => 'yes',
				'container_width'       => '1200',
			),
			'_elementor_global_css' 	=> array(
				'time'   => '1534145031',
				'fonts'  => array(),
				'status' => 'inline',
				'0'      => false,
				'css'    => '.elementor-section.elementor-section-boxed > .elementor-container { max-width:1200px; }',
			),
		);

		foreach ( $data as $key => $value ) {
			update_layout_settings( $key, $value );
		}
	}

	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function listygo_flaticon_tab( $tabs = array() ) {
		// Append new icons
		$flat_icons = ElementorIconTrait::flaticon_icons();
		$fontello = ElementorIconTrait::fontello_icons();
		
		$tabs['listygo-flaticon-icons'] = array(
			'name'          => 'listygo-flaticon-icons',
			'label'         => esc_html__( 'Flat Icons', 'listygo-core' ),
			'labelIcon'     => 'demo-icon rt-icon-home-icon',
			'prefix'        => '',
			'displayPrefix' => '',
			'url'           => Helper::get_nomin_css( 'flaticon' ),
			'icons'         => $flat_icons,
			'ver'           => '1.0',
		);

		$tabs['listygo-fontello-icons'] = array(
			'name'          => 'listygo-fontello-icons',
			'label'         => esc_html__( 'Fontello Icons', 'listygo-core' ),
			'labelIcon'     => 'demo-icon rt-icon-home-icon',
			'prefix'        => '',
			'displayPrefix' => '',
			'url'           => Helper::get_nomin_css( 'fontello' ),
			'icons'         => $fontello,
			'ver'           => '1.0',
		);

		// array_merge();
		return $tabs;
	}
	public function editor_style() {
		$img = plugins_url( 'icon.svg', __FILE__ );
		wp_add_inline_style( 'elementor-editor', '.elementor-element .icon .rdtheme-el-custom {content: url( '.$img.');width: 28px;}' );
		wp_add_inline_style( 'elementor-editor', '.select2-container--default .select2-selection--single {min-width: 126px !important; min-height: 30px !important;}' );
	}

	public function init() {
		require_once __DIR__ . '/base.php';
		$widgets = '';
		$widgets1 = array(
			'title'       		   => 'Rt_Title',
			'button'      		   => 'Rt_Button',
			'working-steps' 	   => 'Rt_Working_Step',
			'posts'  	  		   => 'Rt_Post',
			'testimonial' 		   => 'Rt_Testimonial',
			'image-box'   		   => 'Rt_Image_Box',
			'count'   		   	   => 'Rt_Count',
			'price' 	      	   => 'Rt_Price',
			'pricing-tab'     	   => 'Rt_Pricing_Tab',
			'accordion'   		   => 'Rt_Accordion',
			'team' 		  		   => 'Rt_Team',
		);
		$widgets2 = array(
			'listing-search'       => 'Rt_Listing_Search',
			'search-form'          => 'Rt_Search_Form',
			'listings' 			   => 'Rt_Listings',
			'listing-isotope'      => 'Rt_Listing_Isotope',
			'listing-single-box'   => 'Rt_Listing_Single_Box',
			'locations' 		   => 'Rt_Locations',
			'location-single-box'  => 'Rt_Location_Single_Box',
			'listing-categories'   => 'Rt_Listing_Categories',
			'listing-category-box' => 'Rt_Listing_Category_Box',
		);

		$widgets = $widgets1;


		if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
			$widgets = array_merge($widgets, $widgets2);
		}
		foreach ( $widgets as $dirname => $class ) {
			$template_name = '/elementor-custom/' . $dirname . '/class.php';
			if ( file_exists( get_stylesheet_directory() . $template_name ) ) {
				$file = get_stylesheet_directory() . $template_name;
			}
			elseif ( file_exists( get_stylesheet_directory() . $template_name ) ) {
				$file = get_stylesheet_directory() . $template_name;
			}
			else {
				$file = __DIR__ . '/' . $dirname . '/class.php';
			}

			require_once $file;
			
			$classname = __NAMESPACE__ . '\\' . $class;
			Plugin::instance()->widgets_manager->register( new $classname );
		}
	}

	public function widget_categoty( $elements_manager ) {
		$id         = LISTYGO_CORE_THEME_PREFIX . '-widgets';
		$categories[$id] = array(
			'title' => __('Listygo Elements', 'listygo-core'),
			'icon'  => 'fa fa-plug',
		);
		$old_categories = $elements_manager->get_categories();
		$categories = array_merge($categories, $old_categories);
		$set_categories = function ($categories) {
			$this->categories = $categories;
		};
		$set_categories->call( $elements_manager, $categories );
	}

}

new Custom_Widget_Init();
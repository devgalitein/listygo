<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control;
use radiustheme\listygo\Customizer\Controls\Customize_Control_Checkbox_Multiple;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Search_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_listing_post_controls' ) );
	}

    /**
     * Portfolio Post Controls
     */
    public function register_listing_post_controls( $wp_customize ) {
        $group_list = $this->custom_field_group_list();

        // Listing Banner Search Items
        $wp_customize->add_setting('banner_search', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'banner_search', array(
            'label' => esc_html__( 'Banner Search', 'listygo' ),
            'section' => 'listing_search_section',
        )));

        $wp_customize->add_setting( 'listing_banner_search_items', 
            array(
                'default'           => $this->defaults['listing_banner_search_items'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_multiple_checkbox',
            ) 
        );
        $wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'listing_banner_search_items', 
            array(
                'label'    => __( 'Search Elements', 'listygo' ),
                'description'    => __( 'Select style which you want.', 'listygo' ),
                'section'  => 'listing_search_section',
                'type'  => 'checkbox-multiple',
                'choices'  => array(
                    'keyword'   => 'Keyword',
                    'location'  => 'Location',
                    'category'  => 'Category',
                ),
            ) 
        ) );

        // Listing Search Banner
        $wp_customize->add_setting( 'listing_banner_search_style',
        array(
            'default' => $this->defaults['listing_banner_search_style'],
            'transport' => 'refresh',
            'sanitize_callback' => 'rttheme_radio_sanitization'
        )
        );
        $wp_customize->add_control( 'listing_banner_search_style',
            array(
                'label'    => esc_html__( 'Listing Search Type', 'listygo' ),
                'section' => 'listing_search_section',
                'description'    => esc_html__( 'Listing Search style.', 'listygo' ),
                'type' => 'select',
                'choices'  => array(
                    'popup'      => esc_html__( 'Popup', 'listygo' ),
                    'standard'   => esc_html__( 'Standard', 'listygo' ),
                    'suggestion' => esc_html__( 'Auto Suggestion', 'listygo' ),
                    'dependency' => esc_html__( 'Dependency Selection', 'listygo' ),
                ),
            )
        );      
        
        // Map Search custom advanced fields
        $wp_customize->add_setting('advanced_search_fields', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'advanced_search_fields', array(
            'label' => esc_html__( 'Advanced Search Custom Fields', 'listygo' ),
            'section' => 'listing_search_section',
        )));

        $wp_customize->add_setting( 'custom_fields_search_items', 
            array(
                'default'           => $this->defaults['custom_fields_search_items'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_multiple_checkbox',
            ) 
        );
        $wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'custom_fields_search_items', 
            array(
                'label'    => __( 'Custom Fields', 'listygo' ),
                'description'    => __( 'Select which type you want to display in filter search.', 'listygo' ),
                'section'  => 'listing_search_section',
                'type'  => 'checkbox-multiple',
                'choices'  => $group_list,
            ) 
        ) );

    }

    // public function custom_field_group_list() {
    //     $list = [];
    //     $group_ids = Functions::get_cfg_ids();
    //     foreach ( $group_ids as $id ) {
    //         $list[ $id ] = get_the_title( $id );
    //     }
    //     return $list;
    // }

    public function custom_field_group_list() {
        $list = [];
        $field = '';
        $group_ids = Functions::get_cfg_ids();
        foreach ( $group_ids as $id ) {
            $atts = [
                'group_ids' => $id
            ];
            if ( $id ) {
                $field_ids   = Functions::get_cf_ids( $atts );
            }
            foreach ( $field_ids as $single_field ) {
                $field = new RtclCFGField( $single_field );
            }

            if ( !empty( $field ) ) {
                $list[ $id ] = get_the_title( $id );
            } else {}
        }
        return $list;
    }

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Search_Settings();
}

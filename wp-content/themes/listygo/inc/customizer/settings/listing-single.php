<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.6
 */

namespace radiustheme\listygo\Customizer\Settings;
use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control2;
use radiustheme\listygo\Customizer\Controls\Customize_Control_Checkbox_Multiple;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Single_Settings extends RDTheme_Customizer {

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
        $fields_list = $this->custom_fields_list();
        $categories_list = $this->rt_get_categories_by_id('rtcl_category');

        /**
         * Heading for listing details
         * ================================================================================================*/
        $wp_customize->add_setting('listing_details_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'listing_details_heading', array(
            'label' => esc_html__( 'Listing Details', 'listygo' ),
            'section' => 'listing_details_section',
        )));

        // Listing Details Styles
        $wp_customize->add_setting( 'single_listing_style',
            array(
                'default' => $this->defaults['single_listing_style'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'single_listing_style',
            array(
                'label' => esc_html__( 'Listing Style', 'listygo' ),
                'section' => 'listing_details_section',
                'description' => esc_html__( 'This is listing details style.', 'listygo' ),
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( 'Layout 1', 'listygo' ),
                    '2' => esc_html__( 'Layout 2', 'listygo' ),
                ),
            )
        );

        // Listing Details Styles
        $wp_customize->add_setting( 'single_listing_header_banner',
            array(
                'default' => $this->defaults['single_listing_header_banner'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'single_listing_header_banner',
            array(
                'label' => esc_html__( 'Listing Banner', 'listygo' ),
                'section' => 'listing_details_section',
                'description' => esc_html__( 'This is listing details banner style. Default style is "Full Width Image"', 'listygo' ),
                'type' => 'select',
                'choices' => array(
                    'image' => esc_html__( 'Full Width Image', 'listygo' ),
                    'slider' => esc_html__( 'Grid Slider Image', 'listygo' ),
                ),
            )
        );

        $wp_customize->add_setting( 'slider_per_view',
            array(
                'default' => $this->defaults['slider_per_view'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer'
            )
        );
        $wp_customize->add_control( 'slider_per_view',
            array(
                'label' => esc_html__( 'Slider per view', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'number'
            )
        );

        $wp_customize->add_setting( 'listygo_review_btn_title',
            array(
                'default' => $this->defaults['listygo_review_btn_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization'
            )
        );
        $wp_customize->add_control( 'listygo_review_btn_title',
            array(
                'label' => esc_html__( 'Review Button Text', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'text'
            )
        );

        $wp_customize->add_setting( 'listygo_desc_title',
            array(
                'default' => $this->defaults['listygo_desc_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization'
            )
        );
        $wp_customize->add_control( 'listygo_desc_title',
            array(
                'label' => esc_html__( 'Description Title', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'text'
            )
        );

        $wp_customize->add_setting( 'listygo_gallery_title',
            array(
                'default' => $this->defaults['listygo_gallery_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization'
            )
        );
        $wp_customize->add_control( 'listygo_gallery_title',
            array(
                'label' => esc_html__( 'Gallery Title', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'text'
            )
        );

        // Gallery Slider 
        $wp_customize->add_setting( 'show_gallery_slider',
            array(
                'default' => $this->defaults['show_gallery_slider'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_gallery_slider',
            array(
                'label' => esc_html__( 'Show Gallery Slider', 'listygo' ),
                'section' => 'listing_details_section',
            )
        ) );
        $wp_customize->add_setting( 'listygo_map_title',
            array(
                'default' => $this->defaults['listygo_map_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization'
            )
        );
        $wp_customize->add_control( 'listygo_map_title',
            array(
                'label' => esc_html__( 'Map Title', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'text'
            )
        );

        $wp_customize->add_setting( 'listygo_video_title',
            array(
                'default' => $this->defaults['listygo_video_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization'
            )
        );
        $wp_customize->add_control( 'listygo_video_title',
            array(
                'label' => esc_html__( 'Video Title', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'text'
            )
        );

        $wp_customize->add_setting( 'listygo_rating_title',
            array(
                'default' => $this->defaults['listygo_rating_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization'
            )
        );
        $wp_customize->add_control( 'listygo_rating_title',
            array(
                'label' => esc_html__( 'Rating Title', 'listygo' ),
                'section' => 'listing_details_section',
                'type' => 'text'
            )
        );

        $wp_customize->add_setting( 'custom_fields_group_types', 
            array(
                'default'           => $this->defaults['custom_fields_group_types'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_multiple_checkbox',
            ) 
        );
        $wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'custom_fields_group_types', 
            array(
                'label'    => __( 'Top Group Fields', 'listygo' ),
                'description'    => __( 'Top Priority groups fields display up', 'listygo' ),
                'section'  => 'listing_details_section',
                'type'  => 'checkbox-multiple',
                'choices'  => $group_list,
            ) 
        ) );

        $wp_customize->add_setting( 'custom_fields_list_types', 
            array(
                'default'           => $this->defaults['custom_fields_list_types'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_multiple_checkbox',
            ) 
        );
        $wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'custom_fields_list_types', 
            array(
                'label'    => __( 'Bottom Group Fields', 'listygo' ),
                'description'    => __( 'Low priority groups fields display down', 'listygo' ),
                'section'  => 'listing_details_section',
                'type'  => 'checkbox-multiple',
                'choices'  => $fields_list,
            ) 
        ) );

        /**
         * Heading for listing details related post
         * ================================================================================================*/
        $wp_customize->add_setting('listing_details_related_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'listing_details_related_heading', array(
            'label' => esc_html__( 'Related Posts', 'listygo' ),
            'section' => 'listing_details_section',
        )));

        // Listing related post
        $wp_customize->add_setting( 'show_related_listing',
            array(
                'default' => $this->defaults['show_related_listing'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_related_listing',
            array(
                'label' => esc_html__( 'Show Related Posts', 'listygo' ),
                'section' => 'listing_details_section',
            )
        ) );

        // Title text
        $wp_customize->add_setting( 'related_post_title',
            array(
                'default' => $this->defaults['related_post_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'related_post_title',
            array(
                'label' => esc_html__( 'Title Text', 'listygo' ),
                'type' => 'text',
                'section' => 'listing_details_section',
            )
        );

        // Related Post Columns
        $wp_customize->add_setting( 'related_post_slider_cols',
            array(
                'default' => $this->defaults['related_post_slider_cols'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer'
            )
        );
        $wp_customize->add_control( 'related_post_slider_cols',
            array(
                'label' => esc_html__( 'Post Columns', 'listygo' ),
                'type' => 'number',
                'section' => 'listing_details_section',
            )
        );

        /**
        * Heading for listing details sidebar
        * ================================================================================================*/
        $wp_customize->add_setting('listing_details_sidebar_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'listing_details_sidebar_heading', array(
            'label' => esc_html__( 'Sidebar Settings', 'listygo' ),
            'section' => 'listing_details_section',
        )));

        // Listing contact/information
        $wp_customize->add_setting( 'listing_list_information',
            array(
                'default' => $this->defaults['listing_list_information'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_list_information',
            array(
                'label' => esc_html__( 'Contact/Information Widget', 'listygo' ),
                'section' => 'listing_details_section',
            )
        ) );

        // Listing open/close time
        $wp_customize->add_setting( 'listing_list_timing',
            array(
                'default' => $this->defaults['listing_list_timing'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_list_timing',
            array(
                'label' => esc_html__( 'Open/Close Timing', 'listygo' ),
                'section' => 'listing_details_section',
            )
        ) );

        // Listing Social Profile
        $wp_customize->add_setting( 'listing_list_socials',
            array(
                'default' => $this->defaults['listing_list_socials'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_list_socials',
            array(
                'label' => esc_html__( 'Social Profiles', 'listygo' ),
                'section' => 'listing_details_section',
            )
        ) );

        // Listing Map Location
        $wp_customize->add_setting( 'listing_list_map_location',
            array(
                'default' => $this->defaults['listing_list_map_location'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_list_map_location',
            array(
                'label' => esc_html__( 'Map Location', 'listygo' ),
                'section' => 'listing_details_section',
            )
        ) );

        // Listing Restaurant
        $wp_customize->add_setting( 'restaurant_foodMenu_lists_category',
            array(
                'default' => $this->defaults['restaurant_foodMenu_lists_category'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'restaurant_foodMenu_lists_category',
            array(
                'label' => esc_html__( 'Select category for food menu', 'listygo' ),
                'section' => 'listing_restaurant_section',
                'type' => 'select',
                'choices' => $categories_list,
            )
        );

        // Listing Event
        $wp_customize->add_setting( 'custom_events_fields_types',
            array(
                'default' => $this->defaults['custom_events_fields_types'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'custom_events_fields_types',
            array(
                'label' => esc_html__( 'Event Fields', 'listygo' ),
                'section' => 'listing_event_section',
                'type' => 'select',
                'choices' => $fields_list,
            )
        );

        // Listing Doctor
        $wp_customize->add_setting( 'custom_doctor_fields_types',
            array(
                'default' => $this->defaults['custom_doctor_fields_types'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'custom_doctor_fields_types',
            array(
                'label' => esc_html__( 'Doctor Fields', 'listygo' ),
                'section' => 'listing_doctor_section',
                'type' => 'select',
                'choices' => $fields_list,
            )
        );

        $wp_customize->add_setting( 'doctor_hospital_lists_category',
            array(
                'default' => $this->defaults['doctor_hospital_lists_category'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'doctor_hospital_lists_category',
            array(
                'label' => esc_html__( 'Select category for doctor clinic list', 'listygo' ),
                'section' => 'listing_doctor_section',
                'type' => 'select',
                'choices' => $categories_list,
            )
        );

    }

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

    public function custom_fields_list() {
        $list = [];
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
	            $list[ '0' ] = __( 'Select Field', 'listygo' );
                $list[ $id ] = get_the_title( $id );
            } else {}
        }
        return $list;
    }

    //Get Custom post category:
	protected function rt_get_categories_by_id( $cat ) {
		$terms   = get_terms( [
			'taxonomy'   => $cat,
			'hide_empty' => false,
		] );
		$options = [ '0' => __( 'Select Category', 'listygo' ) ];
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
			return $options;
		}
	}
}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Single_Settings();
}

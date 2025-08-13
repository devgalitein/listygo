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

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Archive_Settings extends RDTheme_Customizer {

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
        // Listing grid columns
        $wp_customize->add_setting( 'listing_grid_cols',
            array(
                'default' => $this->defaults['listing_grid_cols'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'listing_grid_cols',
            array(
                'label' => esc_html__( 'Grid Columns (Depricated)', 'listygo' ),
                'section' => 'listing_archive_section',
                'description' => esc_html__( 'Width is defined by the number of columns.', 'listygo' ),
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1 Columns', 'listygo' ),
                    '2' => esc_html__( '2 Columns', 'listygo' ),
                    '3' => esc_html__( '3 Columns', 'listygo' ),
                    '4' => esc_html__( '4 Columns', 'listygo' ),
                ),
            )
        );
        $wp_customize->add_setting( 'show_listing_custom_fields',
            array(
                'default' => $this->defaults['show_listing_custom_fields'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_listing_custom_fields',
            array(
                'label' => __( 'Show Listing Custom Field', 'listygo' ),
                'section' => 'listing_archive_section',
            )
        ) );

        $wp_customize->add_setting( 'show_custom_fields_label',
            array(
                'default' => $this->defaults['show_custom_fields_label'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_custom_fields_label',
            array(
                'label' => __( 'Show Custom Field Icon or Label', 'listygo' ),
                'section' => 'listing_archive_section',
            )
        ) );

        // Listing content excerpt
        $wp_customize->add_setting( 'listygo_listing_excerpt',
            array(
                'default' => $this->defaults['listygo_listing_excerpt'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer'
            )
        );
        $wp_customize->add_control( 'listygo_listing_excerpt',
            array(
                'label' => esc_html__( 'Listing Content Excerpt', 'listygo' ),
                'section' => 'listing_archive_section',
                'type' => 'number'
            )
        );

        $wp_customize->add_setting( 'listing_archive_box_layout',
            array(
                'default' => $this->defaults['listing_archive_box_layout'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_archive_box_layout',
            array(
                'label' => esc_html__( 'Archive Page Layout', 'listygo' ),
                'section' => 'listing_archive_section',
                'type' => 'select',
                'choices' => array(
                    'container' => esc_html__( 'Select Layout', 'listygo' ),
                    'container' => esc_html__( 'Box layout', 'listygo' ),
                    'container-fluid custom-padding' => esc_html__( 'Full width layout', 'listygo' ),
                ),
            )
        );

        /**
            * Listing map archive settings
        * ===================================================================================================*/
        $wp_customize->add_setting('listing_map_archive_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'listing_map_archive_heading', array(
            'label' => esc_html__( 'Listing Map Archive', 'listygo' ),
            'section' => 'listing_archive_section',
        )));

        $wp_customize->add_setting( 'map_search_widget_title',
            array(
                'default' => $this->defaults['map_search_widget_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'map_search_widget_title',
            array(
                'label' => esc_html__( 'Advanced Search Title', 'listygo' ),
                'section' => 'listing_archive_section',
                'type' => 'text',
            )
        );

        // Listing map grid columns
        $wp_customize->add_setting( 'listing_map_grid_cols',
            array(
                'default' => $this->defaults['listing_map_grid_cols'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'listing_map_grid_cols',
            array(
                'label' => esc_html__( 'Map Search Page Grid Columns', 'listygo' ),
                'section' => 'listing_archive_section',
                'description' => esc_html__( 'This is working for map search page layout.', 'listygo' ),
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1 Columns', 'listygo' ),
                    '2' => esc_html__( '2 Columns', 'listygo' ),
                    '3' => esc_html__( '3 Columns', 'listygo' ),
                    '4' => esc_html__( '4 Columns', 'listygo' ),
                ),
            )
        );
    }
}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Archive_Settings();
}

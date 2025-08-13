<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Separator_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Image_Radio_Control;
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;
/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Post_Layout_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Register Page Controls
        add_action( 'customize_register', array( $this, 'register_listing_post_layout_controls' ) );
	}

    public function register_listing_post_layout_controls( $wp_customize ) {

        /**
         * Separator
         */
        $wp_customize->add_setting('separator_page', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'separator_page', array(
            'settings' => 'separator_page',
            'section' => 'listing_single_layout_section',
        )));

        // Banner enable/disable option
        $wp_customize->add_setting( 'listing_post_banner',
            array(
                'default' => $this->defaults['listing_post_banner'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_post_banner',
            array(
                'label' => __( 'Banner', 'listygo' ),
                'section' => 'listing_single_layout_section',
            )
        ) );
        
        // Banner padding top
        $wp_customize->add_setting( 'listing_post_padding_top',
            array(
                'default' => $this->defaults['listing_post_padding_top'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'listing_post_padding_top',
            array(
                'label' => esc_html__( 'Padding Top', 'listygo' ),
                'section' => 'listing_single_layout_section',
                'type' => 'number',
                'description' => esc_html__( 'This is banner padding top', 'listygo' ),
            )
        );
        // Banner padding bottom
        $wp_customize->add_setting( 'listing_post_padding_bottom',
            array(
                'default' => $this->defaults['listing_post_padding_bottom'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'listing_post_padding_bottom',
            array(
                'label' => esc_html__( 'Padding Bottom', 'listygo' ),
                'section' => 'listing_single_layout_section',
                'type' => 'number',
                'description' => esc_html__( 'This is banner padding bottom', 'listygo' ),
            )
        );
        // Breadcrumb enable/disable option 
        $wp_customize->add_setting( 'listing_post_breadcrumb',
            array(
                'default' => $this->defaults['listing_post_breadcrumb'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_post_breadcrumb',
            array(
                'label' => __( 'Breadcrumb', 'listygo' ),
                'section' => 'listing_single_layout_section',
            )
        ) );
        // Banner background image
        $wp_customize->add_setting( 'listing_post_bgimg',
            array(
                'default' => $this->defaults['listing_post_bgimg'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'listing_post_bgimg',
            array(
                'label' => __( 'Banner Background Image', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'listing_single_layout_section',
                'mime_type' => 'image',
                'button_labels' => array(
                    'select' => __( 'Select File', 'listygo' ),
                    'change' => __( 'Change File', 'listygo' ),
                    'default' => __( 'Default', 'listygo' ),
                    'remove' => __( 'Remove', 'listygo' ),
                    'placeholder' => __( 'No file selected', 'listygo' ),
                    'frame_title' => __( 'Select File', 'listygo' ),
                    'frame_button' => __( 'Choose File', 'listygo' ),
                ),
            )
        ) );

        // Banner background color
        $wp_customize->add_setting('listing_post_bgcolor', 
            array(
                'default' => $this->defaults['listing_post_bgcolor'],
                'type' => 'theme_mod', 
                'capability' => 'edit_theme_options', 
                'transport' => 'refresh', 
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'listing_post_bgcolor',
            array(
                'label' => esc_html__('Banner Background Color', 'listygo'),
                'settings' => 'listing_post_bgcolor', 
                'priority' => 10, 
                'section' => 'listing_single_layout_section', 
            )
        ));

        // Banner background color opacity
        $wp_customize->add_setting( 'listing_post_bgopacity',
            array(
                'default' => $this->defaults['listing_post_bgopacity'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'listing_post_bgopacity',
            array(
                'label' => esc_html__( 'Background Opacity', 'listygo' ),
                'section' => 'listing_single_layout_section',
                'type' => 'number',
            )
        );
        
        // Page background image
        $wp_customize->add_setting( 'listing_post_page_bgimg',
            array(
                'default' => $this->defaults['listing_post_page_bgimg'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'listing_post_page_bgimg',
            array(
                'label' => __( 'Page / Post Background Image', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'listing_single_layout_section',
                'mime_type' => 'image',
                'button_labels' => array(
                    'select' => __( 'Select File', 'listygo' ),
                    'change' => __( 'Change File', 'listygo' ),
                    'default' => __( 'Default', 'listygo' ),
                    'remove' => __( 'Remove', 'listygo' ),
                    'placeholder' => __( 'No file selected', 'listygo' ),
                    'frame_title' => __( 'Select File', 'listygo' ),
                    'frame_button' => __( 'Choose File', 'listygo' ),
                ),
            )
        ) );
        
        $wp_customize->add_setting('listing_post_page_bgcolor', 
            array(
                'default' => $this->defaults['listing_post_page_bgcolor'],
                'type' => 'theme_mod', 
                'capability' => 'edit_theme_options', 
                'transport' => 'refresh', 
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'listing_post_page_bgcolor',
            array(
                'label' => esc_html__('Page / Post Background Color', 'listygo'),
                'settings' => 'listing_post_page_bgcolor', 
                'section' => 'listing_single_layout_section', 
            )
        ));

    }

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Post_Layout_Settings();
}

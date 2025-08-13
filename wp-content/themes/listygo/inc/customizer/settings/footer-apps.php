<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Gallery_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control2;
use radiustheme\listygo\Customizer\Controls\Customizer_Image_Radio_Control;
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Footer_Apps_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_footer_apps_controls' ) );
	}

    public function register_footer_apps_controls( $wp_customize ) {

        /* = App section on/off
        ==========================================*/
        $wp_customize->add_setting( 'footer_apps',
            array(
                'default' => $this->defaults['footer_apps'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_apps',
            array(
                'label' => __( 'Apps Section', 'listygo' ),
                'section' => 'footer_apps',
            )
        ) );

        /* = App Title
        ==========================================*/
        $wp_customize->add_setting( 'apps_title',
            array(
                'default' => $this->defaults['apps_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'apps_title',
            array(
                'label' => esc_html__( 'App Title', 'listygo' ),
                'section' => 'footer_apps',
                'type' => 'text',
            )
        );
        /* = App Description
        ==========================================*/
        $wp_customize->add_setting( 'apps_desc',
            array(
                'default' => $this->defaults['apps_desc'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'apps_desc',
            array(
                'label' => esc_html__( 'App Description', 'listygo' ),
                'section' => 'footer_apps',
                'type' => 'textarea',
            )
        );

        /* = App Link 1
        ==========================================*/
        $wp_customize->add_setting( 'play_store',
            array(
                'default' => $this->defaults['play_store'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'play_store',
            array(
                'label' => esc_html__( 'Google Play Store link', 'listygo' ),
                'section' => 'footer_apps',
                'type' => 'text',
            )
        );

        /* = App Link 1
        ==========================================*/
        $wp_customize->add_setting( 'apps_store',
            array(
                'default' => $this->defaults['apps_store'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'apps_store',
            array(
                'label' => esc_html__( 'App Store Link', 'listygo' ),
                'section' => 'footer_apps',
                'type' => 'text',
            )
        );

        // Banner background image
        $wp_customize->add_setting( 'app_right_img',
        array(
                'default' => $this->defaults['app_right_img'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'app_right_img',
            array(
                'label' => __( 'Banner Background Image', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'footer_apps',
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

    }

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Footer_Apps_Settings();
}

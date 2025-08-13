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
class RDTheme_Footer_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_footer_controls' ) );
	}

    public function register_footer_controls( $wp_customize ) {

        // Footer off & on
        $wp_customize->add_setting( 'footer_area',
            array(
                'default' => $this->defaults['footer_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_area',
            array(
                'label' => __( 'Footer On/Off', 'listygo' ),
                'section' => 'footer_section',
            )
        ) );
        
        /**
         * Footer Style
        * ===================================================================================================*/
        $wp_customize->add_setting( 'footer_style',
            array(
                'default' => $this->defaults['footer_style'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'footer_style',
            array(
                'label' => esc_html__( 'Footer Layout', 'listygo' ),
                'description' => esc_html__( 'You can set default footer form here.', 'listygo' ),
                'section' => 'footer_common',
                'choices' => array(
                    '1' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/footer-1.jpg',
                        'name' => esc_html__( 'Layout 1', 'listygo' )
                    ),                  
                    '2' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/footer-2.jpg',
                        'name' => esc_html__( 'Layout 2', 'listygo' )
                    ),
                )
            )
        ) );

        /**
        * Copyright Text
        * ======================*/
        $wp_customize->add_setting( 'copyright_text',
            array(
                'default' => $this->defaults['copyright_text'],
                'transport' => 'refresh',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        $wp_customize->add_control( 'copyright_text',
            array(
                'label' => esc_html__( 'Copyright Text', 'listygo' ),
                'section' => 'footer_common',
                'type' => 'textarea',
            )
        );

        /**
        * Footer 1 Settiings
        * ==================================================================================================*/
        $wp_customize->add_setting('footer_widgets_area_cols', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'footer_widgets_area_cols', array(
            'label' => esc_html__( 'Columns Settings', 'listygo' ),
            'section' => 'footer_1',
        )));
        $wp_customize->add_setting( 'f1_widgets_area',
            array(
                'default' => $this->defaults['f1_widgets_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_widgets_area',
            array(
                'label' => esc_html__( 'Widget Area', 'listygo' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                ),
            )
        );
        $wp_customize->add_setting( 'f1_area1_column',
            array(
                'default' => $this->defaults['f1_area1_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area1_column',
            array(
                'label' => esc_html__( 'Area 1 Columns', 'listygo' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );
        $wp_customize->add_setting( 'f1_area2_column',
            array(
                'default' => $this->defaults['f1_area2_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area2_column',
            array(
                'label' => esc_html__( 'Area 2 Columns', 'listygo' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );
        $wp_customize->add_setting( 'f1_area3_column',
            array(
                'default' => $this->defaults['f1_area3_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area3_column',
            array(
                'label' => esc_html__( 'Area 3 Columns', 'listygo' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );
        $wp_customize->add_setting( 'f1_area4_column',
            array(
                'default' => $this->defaults['f1_area4_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area4_column',
            array(
                'label' => esc_html__( 'Area 4 Columns', 'listygo' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );

        /**
        * Footer 2 Settiings
        * ==================================================================================================*/
        $wp_customize->add_setting('footer2_widgets_area_cols', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'footer2_widgets_area_cols', array(
            'label' => esc_html__( 'Columns Settings', 'listygo' ),
            'section' => 'footer_2',
        )));
        $wp_customize->add_setting( 'f2_widgets_area',
            array(
                'default' => $this->defaults['f2_widgets_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_widgets_area',
            array(
                'label' => esc_html__( 'Widget Area', 'listygo' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                ),
            )
        );
        $wp_customize->add_setting( 'f2_area1_column',
            array(
                'default' => $this->defaults['f2_area1_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area1_column',
            array(
                'label' => esc_html__( 'Area 1 Columns', 'listygo' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );
        $wp_customize->add_setting( 'f2_area2_column',
            array(
                'default' => $this->defaults['f2_area2_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area2_column',
            array(
                'label' => esc_html__( 'Area 2 Columns', 'listygo' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );
        $wp_customize->add_setting( 'f2_area3_column',
            array(
                'default' => $this->defaults['f2_area3_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area3_column',
            array(
                'label' => esc_html__( 'Area 3 Columns', 'listygo' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );
        $wp_customize->add_setting( 'f2_area4_column',
            array(
                'default' => $this->defaults['f2_area4_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area4_column',
            array(
                'label' => esc_html__( 'Area 4 Columns', 'listygo' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1', 'listygo' ),
                    '2' => esc_html__( '2', 'listygo' ),
                    '3' => esc_html__( '3', 'listygo' ),
                    '4' => esc_html__( '4', 'listygo' ),
                    '5' => esc_html__( '5', 'listygo' ),
                    '6' => esc_html__( '6', 'listygo' ),
                    '7' => esc_html__( '7', 'listygo' ),
                    '8' => esc_html__( '8', 'listygo' ),
                    '9' => esc_html__( '9', 'listygo' ),
                    '10' => esc_html__( '10', 'listygo' ),
                    '11' => esc_html__( '11', 'listygo' ),
                    '12' => esc_html__( '12', 'listygo' ),
                ),
                'description' => esc_html__( 'Total Columns 12', 'listygo' ),
            )
        );

    }

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Footer_Settings();
}

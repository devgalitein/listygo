<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control2;
use radiustheme\listygo\Customizer\Controls\Customizer_Separator_Control;
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Colors_Settings extends RDTheme_Customizer {

	public function __construct() {
	    parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_general_controls' ) );
	}

    public function register_general_controls( $wp_customize ) {


        /**
        * Site Primary Color Controls
        * ==================================================================*/
        $wp_customize->add_setting( 'listygo_primary_color',
            array(
                'default' => $this->defaults['listygo_primary_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listygo_primary_color',
            array(
                'label' => esc_html__( 'Listygo Primary Color', 'listygo' ),
                'section' => 'site_color_section',
                'type' => 'color',
            )
        );

        /**
        * Site Primary Dark Color Controls
        * ==================================================================*/
        $wp_customize->add_setting( 'listygo_secondary_color',
            array(
                'default' => $this->defaults['listygo_secondary_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listygo_secondary_color',
            array(
                'label' => esc_html__( 'Listygo Secondary Color', 'listygo' ),
                'section' => 'site_color_section',
                'type' => 'color',
            )
        );

        /**
        * Site Body Color Controls
        * ==================================================================*/
        $wp_customize->add_setting( 'listygo_body_color',
            array(
                'default' => $this->defaults['listygo_body_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listygo_body_color',
            array(
                'label' => esc_html__( 'Listygo Body Color', 'listygo' ),
                'section' => 'site_color_section',
                'type' => 'color',
            )
        );

        /**
        * Site Heading Color Controls
        * ==================================================================*/
        $wp_customize->add_setting( 'listygo_heading_color',
            array(
                'default' => $this->defaults['listygo_heading_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listygo_heading_color',
            array(
                'label' => esc_html__( 'Listygo Heading Color', 'listygo' ),
                'section' => 'site_color_section',
                'type' => 'color',
            )
        );

        /**
        * Normal Menu BG Color Controls
        * =================================================================*/
        $wp_customize->add_setting('menu_color_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'menu_color_heading', array(
            'label' => esc_html__( 'Menu Color', 'listygo' ),
            'section' => 'menu_color_section',
        )));

        // Menu BG Color
        $wp_customize->add_setting( 'menu_bg_color',
            array(
                'default' => $this->defaults['menu_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'menu_bg_color',
            array(
                'label' => esc_html__( 'Menu Background Color', 'listygo' ),
                'section' => 'menu_color_section',
                'type' => 'color',
                'default' => '#ffffff',
            )
        );

        // Menu Text Color
        $wp_customize->add_setting( 'menu_text_color',
            array(
                'default' => $this->defaults['menu_text_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'menu_text_color',
            array(
                'label' => esc_html__( 'Normal Color', 'listygo' ),
                'section' => 'menu_color_section',
                'type' => 'color',
                'default' => '#111111',
            )
        );
        // Menu Text Hover Color
        $wp_customize->add_setting( 'menu_text_hover_color',
            array(
                'default' => $this->defaults['menu_text_hover_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'menu_text_hover_color',
            array(
                'label' => esc_html__( 'Hover Color', 'listygo' ),
                'section' => 'menu_color_section',
                'type' => 'color',
                'default' => '#ff3c48',
            )
        );

        /**
        * Sub Menu Color Controls
        * ======================================================================*/
        $wp_customize->add_setting('dropdown_menu_color_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'dropdown_menu_color_heading', array(
            'label' => esc_html__( 'Sub Menu Color', 'listygo' ),
            'section' => 'menu_color_section',
        )));

        // Submenu BG Color
        $wp_customize->add_setting( 'submenu_bg_color',
            array(
                'default' => $this->defaults['submenu_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'submenu_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'listygo' ),
                'section' => 'menu_color_section',
                'type' => 'color',
                'default' => '#ffffff',
            )
        );

        // Submenu Text Color
        $wp_customize->add_setting( 'submenu_text_color',
            array(
                'default' => $this->defaults['submenu_text_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'submenu_text_color',
            array(
                'label' => esc_html__( 'Text Color', 'listygo' ),
                'section' => 'menu_color_section',
                'type' => 'color',
                'default' => '#646464',
            )
        );

         // Submenu Hover Text Color
        $wp_customize->add_setting( 'submenu_htext_color',
            array(
                'default' => $this->defaults['submenu_htext_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'submenu_htext_color',
            array(
                'label' => esc_html__( 'Hover Text Color', 'listygo' ),
                'section' => 'menu_color_section',
                'type' => 'color',
                'default' => '#ff3c48',
            )
        );

        /**
        * Transparent Menu Color Controls 
        * =================================================================*/
        $wp_customize->add_setting('menu2_color_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'menu2_color_heading', array(
            'label' => esc_html__( 'Menu Color', 'listygo' ),
            'section' => 'menu2_color_section',
        )));

        // Menu Text Color
        $wp_customize->add_setting( 'menu2_text_color',
            array(
                'default' => $this->defaults['menu2_text_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'menu2_text_color',
            array(
                'label' => esc_html__( 'Normal Color', 'listygo' ),
                'section' => 'menu2_color_section',
                'type' => 'color',
                'default' => '#ffffff',
            )
        );
        // Menu Text Hover Color
        $wp_customize->add_setting( 'menu2_text_hover_color',
            array(
                'default' => $this->defaults['menu2_text_hover_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'menu2_text_hover_color',
            array(
                'label' => esc_html__( 'Hover Color', 'listygo' ),
                'section' => 'menu2_color_section',
                'type' => 'color',
                'default' => '#ff3c48',
            )
        );

        /**
        * Sub Menu Color Controls
        * ======================================================================*/
        $wp_customize->add_setting('dropdown_menu2_color_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'dropdown_menu2_color_heading', array(
            'label' => esc_html__( 'Sub Menu Color', 'listygo' ),
            'section' => 'menu2_color_section',
        )));

        // Submenu BG Color
        $wp_customize->add_setting( 'submenu2_bg_color',
            array(
                'default' => $this->defaults['submenu2_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'submenu2_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'listygo' ),
                'section' => 'menu2_color_section',
                'type' => 'color',
                'default' => '#ffffff',
            )
        );

        // Submenu Text Color
        $wp_customize->add_setting( 'submenu2_text_color',
            array(
                'default' => $this->defaults['submenu2_text_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'submenu2_text_color',
            array(
                'label' => esc_html__( 'Text Color', 'listygo' ),
                'section' => 'menu2_color_section',
                'type' => 'color',
                'default' => '#646464',
            )
        );

         // Submenu Hover Text Color
        $wp_customize->add_setting( 'submenu2_htext_color',
            array(
                'default' => $this->defaults['submenu2_htext_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'submenu2_htext_color',
            array(
                'label' => esc_html__( 'Hover Text Color', 'listygo' ),
                'section' => 'menu2_color_section',
                'type' => 'color',
                'default' => '#ff3c48',
            )
        );

        /**
        * Preloader Color Controls
        * ================================================================*/
        $wp_customize->add_setting('scrollup_color_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'scrollup_color_heading', array(
            'label' => esc_html__( 'Scroll Up Color', 'listygo' ),
            'section' => 'others_color_section',
        )));
        $wp_customize->add_setting( 'scroll_color',
            array(
                'default' => $this->defaults['preloader_circle_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'scroll_color',
            array(
                'label' => esc_html__( 'Icon Color', 'listygo' ),
                'section' => 'others_color_section',
                'type' => 'color',
            )
        );

        /**
        * Preloader Color Controls
        * ================================================================*/
        $wp_customize->add_setting('preloader_color_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'preloader_color_heading', array(
            'label' => esc_html__( 'Preloader Color', 'listygo' ),
            'section' => 'others_color_section',
        )));
        // Menu Text Color
        $wp_customize->add_setting( 'preloader_bg_color',
            array(
                'default' => $this->defaults['preloader_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'preloader_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'listygo' ),
                'section' => 'others_color_section',
                'type' => 'color',
            )
        );
        // Menu Text Hover Color
        $wp_customize->add_setting( 'preloader_circle_color',
            array(
                'default' => $this->defaults['preloader_circle_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'preloader_circle_color',
            array(
                'label' => esc_html__( 'Circle Color', 'listygo' ),
                'section' => 'others_color_section',
                'type' => 'color',
            )
        );

        $wp_customize->add_setting( 'preloader_gif',
            array(
                'default' => $this->defaults['preloader_gif'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'preloader_gif',
            array(
                'label' => esc_html__( 'Preloader Image', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'others_color_section',
                'mime_type' => 'image',
                'button_labels' => array(
                    'select' => esc_html__( 'Select File', 'listygo' ),
                    'change' => esc_html__( 'Change File', 'listygo' ),
                    'default' => esc_html__( 'Default', 'listygo' ),
                    'remove' => esc_html__( 'Remove', 'listygo' ),
                    'placeholder' => esc_html__( 'No file selected', 'listygo' ),
                    'frame_title' => esc_html__( 'Select File', 'listygo' ),
                    'frame_button' => esc_html__( 'Choose File', 'listygo' ),
                )
            )
        ) );

    }

}

/**
 * Initialise our Customizer settings only when they're required  
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Colors_Settings();
}

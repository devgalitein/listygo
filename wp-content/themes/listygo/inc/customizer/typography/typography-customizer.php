<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Typography;

use radiustheme\listygo\Customizer\Controls\Customizer_Separator_Control;
/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */

class Customizer_Typography_settings {
	// Get our default values
	private $defaults;

	public function __construct() {
		// Get our Customizer defaults
		$this->defaults = rttheme_generate_defaults();
		// Register Panels
		add_action( 'customize_register', array( $this, 'add_customizer_panels' ) );
		// Register Section
		add_action( 'customize_register', array( $this, 'register_typography_sections' ) );
        // Register Controls
		add_action( 'customize_register', array( $this, 'register_typography_controls' ) );
	}


	/**
	 * Register The Tupography Panels
	 * ========================================================================================*/
	public function add_customizer_panels( $wp_customize ) {

	    // Add Layput Panel
		$wp_customize->add_panel( 'rttheme_typography_defaults',
			array(
				'title' => __( 'Typography', 'listygo' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'listygo' ),
				'priority' => 5,
			)
		);
		
	}


    /**
    * Register the Typography sections
    * ========================================================================================*/
	public function register_typography_sections( $wp_customize ) {
		
		// Body Typo
		$wp_customize->add_section( 'typography_body_section',
			array(
				'title' => esc_html__( 'Body', 'listygo' ),
				'priority' => 1,
				'panel' => 'rttheme_typography_defaults',
			)
		);

		// Menu Typo
		$wp_customize->add_section( 'typography_menu_section',
			array(
				'title' => esc_html__( 'Menu', 'listygo' ),
				'priority' => 2,
				'panel' => 'rttheme_typography_defaults',
			)
		);

		// Heading Typo
		$wp_customize->add_section( 'typography_heading_section',
			array(
				'title' => esc_html__( 'Heading', 'listygo' ),
				'priority' => 3,
				'panel' => 'rttheme_typography_defaults',
			)
		);

	}

	/**
	 * Register General Controls
	 * ========================================================================================*/
	public function register_typography_controls( $wp_customize ) {

		/**
		* Body Controls
		* ======================================================================*/
	 	// Font Family
		$wp_customize->add_setting( 'typo_body',
			array(
				'default' => $this->defaults['typo_body'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_body',
			array(
				'label' => esc_html__( 'Body', 'listygo' ),
				'section' => 'typography_body_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );
		// Font Size	
		$wp_customize->add_setting( 'typo_body_size',
			array(
				'default' => $this->defaults['typo_body_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_body_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_body_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',				
				),
			)
		);
		// Line height
		$wp_customize->add_setting( 'typo_body_height',
			array(
				'default' => $this->defaults['typo_body_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_body_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_body_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',					
				),
			)
		);


		/**
		* Menu Controls
		* ======================================================================*/
	 	// Font Family
		$wp_customize->add_setting( 'typo_menu',
			array(
				'default' => $this->defaults['typo_menu'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_menu',
			array(
				'label' => esc_html__( 'Menu', 'listygo' ),
				'section' => 'typography_menu_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );
		// Font Size	
		$wp_customize->add_setting( 'typo_menu_size',
			array(
				'default' => $this->defaults['typo_menu_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_menu_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_menu_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		// Line Height
		$wp_customize->add_setting( 'typo_menu_height',
			array(
				'default' => $this->defaults['typo_menu_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_menu_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_menu_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

		/**
         * Sub Menu Typography
         */
        $wp_customize->add_setting('typo_submenu_separator', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_submenu_separator', array(
            'settings' => 'typo_submenu_separator',
            'section' => 'typography_menu_section',
        )));
		// Font Size	
		$wp_customize->add_setting( 'typo_submenu_size',
			array(
				'default' => $this->defaults['typo_submenu_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_submenu_size',
			array(
				'label' => esc_html__( 'Sub Menu Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_menu_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		// Line Height
		$wp_customize->add_setting( 'typo_submenu_height',
			array(
				'default' => $this->defaults['typo_submenu_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_submenu_height',
			array(
				'label' => esc_html__( 'Sub Menu Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_menu_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

		/**
		* Heading Controls
		* ======================================================================*/
        // All Heading Typography
		$wp_customize->add_setting( 'typo_heading',
			array(
				'default' => $this->defaults['typo_heading'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_heading',
			array(
				'label' => esc_html__( 'All Heading Typography (H1-H6)', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );


        /**
         * Heading Typography h1
         */
        $wp_customize->add_setting('typo_separator_general1', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general1', array(
            'settings' => 'typo_separator_general1',
            'section' => 'typography_heading_section',
        )));

		// H1 Google Font Select Control
		$wp_customize->add_setting( 'typo_h1',
			array(
				'default' => $this->defaults['typo_h1'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_h1',
			array(
				'label' => esc_html__( 'Header h1 ', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );	
		$wp_customize->add_setting( 'typo_h1_size',
			array(
				'default' => $this->defaults['typo_h1_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h1_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		$wp_customize->add_setting( 'typo_h1_height',
			array(
				'default' => $this->defaults['typo_h1_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h1_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

        /**
         * Separator Separator for h2
         */
        $wp_customize->add_setting('typo_separator_general2', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general2', array(
            'settings' => 'typo_separator_general2',
            'section' => 'typography_heading_section',
        )));

		$wp_customize->add_setting( 'typo_h2',
			array(
				'default' => $this->defaults['typo_h2'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_h2',
			array(
				'label' => esc_html__( 'Header h2 ', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );	
		$wp_customize->add_setting( 'typo_h2_size',
			array(
				'default' => $this->defaults['typo_h2_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h2_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		$wp_customize->add_setting( 'typo_h2_height',
			array(
				'default' => $this->defaults['typo_h2_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h2_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

        /**
         * Separator Separator for h3
         */
        $wp_customize->add_setting('typo_separator_general3', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general3', array(
            'settings' => 'typo_separator_general3',
            'section' => 'typography_heading_section',
        )));

		$wp_customize->add_setting( 'typo_h3',
			array(
				'default' => $this->defaults['typo_h3'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_h3',
			array(
				'label' => esc_html__( 'Header h3 ', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );	
		$wp_customize->add_setting( 'typo_h3_size',
			array(
				'default' => $this->defaults['typo_h3_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h3_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		$wp_customize->add_setting( 'typo_h3_height',
			array(
				'default' => $this->defaults['typo_h3_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h3_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

        /**
         * Separator for h4
         */
        $wp_customize->add_setting('typo_separator_general4', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general4', array(
            'settings' => 'typo_separator_general4',
            'section' => 'typography_heading_section',
        )));

		$wp_customize->add_setting( 'typo_h4',
			array(
				'default' => $this->defaults['typo_h4'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_h4',
			array(
				'label' => esc_html__( 'Header h4 ', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );	
		$wp_customize->add_setting( 'typo_h4_size',
			array(
				'default' => $this->defaults['typo_h4_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h4_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		$wp_customize->add_setting( 'typo_h4_height',
			array(
				'default' => $this->defaults['typo_h4_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h4_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

        /**
         * Separator for h5
         */
        $wp_customize->add_setting('typo_separator_general5', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general5', array(
            'settings' => 'typo_separator_general5',
            'section' => 'typography_heading_section',
        )));

		$wp_customize->add_setting( 'typo_h5',
			array(
				'default' => $this->defaults['typo_h5'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_h5',
			array(
				'label' => esc_html__( 'Header h5 ', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );	
		$wp_customize->add_setting( 'typo_h5_size',
			array(
				'default' => $this->defaults['typo_h5_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h5_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		$wp_customize->add_setting( 'typo_h5_height',
			array(
				'default' => $this->defaults['typo_h5_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h5_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

        /**
         * Separator  for h6
         */
        $wp_customize->add_setting('typo_separator_general6', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general6', array(
            'settings' => 'typo_separator_general6',
            'section' => 'typography_heading_section',
        )));
        
		$wp_customize->add_setting( 'typo_h6',
			array(
				'default' => $this->defaults['typo_h6'],
				'sanitize_callback' => 'rttheme_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, 'typo_h6',
			array(
				'label' => esc_html__( 'Header h6 ', 'listygo' ),
				'section' => 'typography_heading_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'popular',
				),
			)
		) );	
		$wp_customize->add_setting( 'typo_h6_size',
			array(
				'default' => $this->defaults['typo_h6_size'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h6_size',
			array(
				'label' => esc_html__( 'Font Size', 'listygo' ),
				'description' => esc_html__( 'Font Size (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);
		$wp_customize->add_setting( 'typo_h6_height',
			array(
				'default' => $this->defaults['typo_h6_height'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( 'typo_h6_height',
			array(
				'label' => esc_html__( 'Line Height', 'listygo' ),
				'description' => esc_html__( 'Line Height (px)', 'listygo' ),
				'section' => 'typography_heading_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'rtt-txt-box',
				),
			)
		);

        /**
         * Separator
         */
        $wp_customize->add_setting('typo_separator_general7', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Separator_Control($wp_customize, 'typo_separator_general7', array(
            'settings' => 'typo_separator_general7',
            'section' => 'typography_heading_section',
        )));

	}
}
/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	$rttheme_settings = new Customizer_Typography_settings();
}

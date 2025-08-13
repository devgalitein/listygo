<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Image_Radio_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Blog_Post_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_blog_post_controls' ) );
	}

    /**
     * Blog Post Controls
     */
    public function register_blog_post_controls( $wp_customize ) {

        // Blog Post Style
        $wp_customize->add_setting( 'blog_style',
            array(
                'default' => $this->defaults['blog_style'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );

        $wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'blog_style',
            array(
                'label' => esc_html__( 'Post Layout', 'listygo' ),
                'description' => esc_html__( 'Blog Post layout variation you can selecr and use.', 'listygo' ),
                'section' => 'blog_post_settings_section',
                'choices' => array(
                    '1' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/list.png',
                        'name' => esc_html__( 'List Layout', 'listygo' )
                    ),
                    '2' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/grid.png',
                        'name' => esc_html__( 'Grid Layout 1', 'listygo' )
                    )
                )
            )
        ) );


        // Blog grid area
        $wp_customize->add_setting( 'blog_grid',
            array(
                'default' => $this->defaults['blog_grid'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'blog_grid',
            array(
                'label' => esc_html__( 'Grid layput Columns', 'listygo' ),
                'section' => 'blog_post_settings_section',
                'description' => esc_html__( 'This grid system work only for large devices', 'listygo' ),
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1 Column', 'listygo' ),
                    '2' => esc_html__( '2 Columns', 'listygo' ),
                    '3' => esc_html__( '3 Columns', 'listygo' ),
                    '4' => esc_html__( '4 Columns', 'listygo' ),
                    '5' => esc_html__( '5 Columns', 'listygo' ),
                    '6' => esc_html__( '6 Columns', 'listygo' ),
                ),
            )
        );

        // Blog grid medium devices
        $wp_customize->add_setting( 'blog_medium_grid',
            array(
                'default' => $this->defaults['blog_medium_grid'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'blog_medium_grid',
            array(
                'label' => esc_html__( 'Medium Devices Columns', 'listygo' ),
                'section' => 'blog_post_settings_section',
                'description' => esc_html__( 'This grid system work only for medium devices', 'listygo' ),
                'type' => 'select',
                'choices' => array(
                    '1' => esc_html__( '1 Column', 'listygo' ),
                    '2' => esc_html__( '2 Columns', 'listygo' ),
                    '3' => esc_html__( '3 Columns', 'listygo' ),
                    '4' => esc_html__( '4 Columns', 'listygo' ),
                    '5' => esc_html__( '5 Columns', 'listygo' ),
                    '6' => esc_html__( '6 Columns', 'listygo' ),
                ),
            )
        );

        // Post Admin
        $wp_customize->add_setting( 'post_meta_admin',
            array(
                'default' => $this->defaults['post_meta_admin'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_meta_admin',
            array(
                'label' => esc_html__( 'Display Meta Admin', 'listygo' ),
                'section' => 'blog_post_settings_section',
            )
        ) );

        // Post Date
        $wp_customize->add_setting( 'post_meta_date',
            array(
                'default' => $this->defaults['post_meta_date'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_meta_date',
            array(
                'label' => esc_html__( 'Display Meta Date', 'listygo' ),
                'section' => 'blog_post_settings_section',
            )
        ) );

        // Post Comments
        $wp_customize->add_setting( 'post_meta_comnts',
            array(
                'default' => $this->defaults['post_meta_comnts'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_meta_comnts',
            array(
                'label' => esc_html__( 'Display Meta Comments', 'listygo' ),
                'section' => 'blog_post_settings_section',
            )
        ) );

        // Post Categories
        $wp_customize->add_setting( 'post_meta_cats',
            array(
                'default' => $this->defaults['post_meta_cats'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_meta_cats',
            array(
                'label' => esc_html__( 'Display Meta Category', 'listygo' ),
                'section' => 'blog_post_settings_section',
            )
        ));

        // Excerpt Length
        $wp_customize->add_setting( 'excerpt_length',
            array(
                'default' => $this->defaults['excerpt_length'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer'
            )
        );
        $wp_customize->add_control( 'excerpt_length',
            array(
                'label' => esc_html__( 'Excerpt Length', 'listygo' ),
                'section' => 'blog_post_settings_section',
                'type' => 'number'
            )
        );
    }
}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Blog_Post_Settings();
}

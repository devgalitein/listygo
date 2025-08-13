<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Rt_Blog_Archive_Before_All_Post_Ad_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_blog_archive_before_all_post_ad_controls' ) );
	}

    public function register_blog_archive_before_all_post_ad_controls( $wp_customize ) {
        //Add Heading
        $wp_customize->add_setting('blog_archive_before_all_post', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'blog_archive_before_all_post', array(
            'label' => esc_html__( 'Before All Post', 'listygo' ),
            'section' => 'blog_archive_page_advertisements',
        )));

        // Add our Checkbox switch setting and control for opening URLs in a new tab
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_activate',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_activate'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_archive_before_all_post_ad_activate',
            array(
                'label' => esc_html__( 'Activate Ad', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
            )
        ) );

        //Add type
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_type',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_type'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'blog_archive_before_all_post_ad_type',
            array(
                'label' => esc_html__( 'Add Type', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
                'type' => 'select',
                'choices' => array(
                    'image' => esc_html__( 'Image', 'listygo' ),
                    'code' => esc_html__( 'Code', 'listygo' ),
                ),
            )
        );

        //Advertisements
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_image',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_image'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'blog_archive_before_all_post_ad_image',
            array(
                'label' => esc_html__( 'Before all post ad image', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
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
        // Advertisements Lunk
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_link',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_link'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'blog_archive_before_all_post_ad_link',
            array(
                'label' => esc_html__( 'Link', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
                'type' => 'url',
            )
        );
        // Open Link in New Tab
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_newtab',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_newtab'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_archive_before_all_post_ad_newtab',
            array(
                'label' => esc_html__( 'Open Link in New Tab', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
            )
        ) );
        // Open Link in New Tab
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_nofollow',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_nofollow'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_archive_before_all_post_ad_nofollow',
            array(
                'label' => esc_html__( 'Nofollow', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
            )
        ) );

        /**
        * Code ad
        * ===========================*/
        $wp_customize->add_setting( 'blog_archive_before_all_post_ad_code',
            array(
                'default' => $this->defaults['blog_archive_before_all_post_ad_code'],
                'transport' => 'refresh',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        $wp_customize->add_control( 'blog_archive_before_all_post_ad_code',
            array(
                'label' => esc_html__( 'Custom Code Ad', 'listygo' ),
                'section' => 'blog_archive_page_advertisements',
                'type' => 'textarea',
                'description' => esc_html__( 'Supports: Shortcode, Adsense, Text, HTML, Scripts', 'listygo' ),
            )
        );

    }
}

/**
 * Initialise our Customizer settings only when they're required 
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new Rt_Blog_Archive_Before_All_Post_Ad_Settings();
}

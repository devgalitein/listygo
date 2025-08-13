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
class RDTheme_General_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_general_controls' ) );
	}

    public function register_general_controls( $wp_customize ) {
        /**
         * Heading
         */
        $wp_customize->add_setting('site_logo', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'site_logo', array(
            'label' => esc_html__( 'Site Logo', 'listygo' ),
            'section' => 'general_section',
        )));

        $wp_customize->add_setting( 'logo',
            array(
                'default' => $this->defaults['logo'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'logo',
            array(
                'label' => esc_html__( 'Main Logo', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'general_section',
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

        $wp_customize->add_setting( 'logo_dark',
            array(
                'default' => $this->defaults['logo_dark'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'logo_dark',
            array(
                'label' => esc_html__( 'Logo 2', 'listygo' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'listygo' ),
                'section' => 'general_section',
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

        /**
         * Heading
         */
        $wp_customize->add_setting('site_switching', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'site_switching', array(
            'label' => esc_html__( 'Site Switch Control', 'listygo' ),
            'section' => 'general_section',
        )));


        // Add our Checkbox switch setting and control for opening URLs in a new tab
        $wp_customize->add_setting( 'preloader',
            array(
                'default' => $this->defaults['preloader'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'preloader',
            array(
                'label' => esc_html__( 'Preloader', 'listygo' ),
                'section' => 'general_section',
            )
        ) );

        // Switch for back to top button
        $wp_customize->add_setting( 'page_scrolltop',
            array(
                'default' => $this->defaults['page_scrolltop'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'page_scrolltop',
            array(
                'label' => esc_html__( 'Back to Top', 'listygo' ),
                'section' => 'general_section',
            )
        ) );

        // Switch for custom cursor
        $wp_customize->add_setting( 'sticky_header',
            array(
                'default' => $this->defaults['sticky_header'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'sticky_header',
            array(
                'label' => esc_html__( 'Sticky Header', 'listygo' ),
                'section' => 'general_section',
            )
        ) );


        // Switch for admin bar
        $wp_customize->add_setting( 'restrict_admin_area',
            array(
                'default' => $this->defaults['restrict_admin_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'restrict_admin_area',
            array(
                'label' => esc_html__( 'Admin Bar', 'listygo' ),
                'description' => esc_html__( 'Admin bar for subscribers', 'listygo' ),
                'section' => 'general_section',
            )
        ) );

        /**
         * Contact and Social
         */
        $wp_customize->add_setting('site_contact_socials', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'site_contact_socials', array(
            'label' => esc_html__( 'Contact & Socials', 'listygo' ),
            'section' => 'general_section',
        )));
        // Phone label
        $wp_customize->add_setting( 'phone_label',
            array(
                'default' => $this->defaults['phone_label'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'phone_label',
            array(
                'label' => esc_html__( 'Phone Label', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );
        // Phone Number
        $wp_customize->add_setting( 'phone_number',
            array(
                'default' => $this->defaults['phone_number'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'phone_number',
            array(
                'label' => esc_html__( 'Phone Number', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );
        // Email label
        $wp_customize->add_setting( 'mail_label',
            array(
                'default' => $this->defaults['mail_label'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'mail_label',
            array(
                'label' => esc_html__( 'Email Label', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );
        // Email Number
        $wp_customize->add_setting( 'mail_number',
            array(
                'default' => $this->defaults['mail_number'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'mail_number',
            array(
                'label' => esc_html__( 'Email Number', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );
        // Social label
        $wp_customize->add_setting( 'social_label',
            array(
                'default' => $this->defaults['social_label'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'social_label',
            array(
                'label' => esc_html__( 'Social Label', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );
        // Facebook
        $wp_customize->add_setting( 'rt_facebook',
            array(
                'default' => $this->defaults['rt_facebook'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_facebook',
            array(
                'label' => esc_html__( 'Facebook', 'listygo' ),
                'section' => 'general_section',
                'type' => 'url',
            )
        );
        // Twitter
        $wp_customize->add_setting( 'rt_twitter',
            array(
                'default' => $this->defaults['rt_twitter'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_twitter',
            array(
                'label' => esc_html__( 'Twitter', 'listygo' ),
                'section' => 'general_section',
                'type' => 'url',
            )
        );
        // Linkedin
        $wp_customize->add_setting( 'rt_linkedin',
            array(
                'default' => $this->defaults['rt_linkedin'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_linkedin',
            array(
                'label' => esc_html__( 'Linkedin', 'listygo' ),
                'section' => 'general_section',
                'type' => 'url',
            )
        );
        // Instagram
        $wp_customize->add_setting( 'rt_instagram',
            array(
                'default' => $this->defaults['rt_instagram'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_instagram',
            array(
                'label' => esc_html__( 'Instagram', 'listygo' ),
                'section' => 'general_section',
                'type' => 'url',
            )
        );
        // Pinterest
        $wp_customize->add_setting( 'rt_pinterest',
            array(
                'default' => $this->defaults['rt_pinterest'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_pinterest',
            array(
                'label' => esc_html__( 'Pinterest', 'listygo' ),
                'section' => 'general_section',
                'type' => 'url',
            )
        );
        // Youtube
        $wp_customize->add_setting( 'rt_youtube',
            array(
                'default' => $this->defaults['rt_youtube'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_youtube',
            array(
                'label' => esc_html__( 'Youtube', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );
        // Tiktok
        $wp_customize->add_setting( 'rt_tiktok',
            array(
                'default' => $this->defaults['rt_tiktok'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'rt_tiktok',
            array(
                'label' => esc_html__( 'Tiktok', 'listygo' ),
                'section' => 'general_section',
                'type' => 'text',
            )
        );

    }

}

/**
 * Initialise our Customizer settings only when they're required 
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_General_Settings();
}

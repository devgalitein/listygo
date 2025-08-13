<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer\Settings;

use radiustheme\listygo\Customizer\RDTheme_Customizer;
use radiustheme\listygo\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control;
use radiustheme\listygo\Customizer\Controls\Customizer_Heading_Control2;
use radiustheme\listygo\Customizer\Controls\Customizer_Image_Radio_Control;
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Header_Settings extends RDTheme_Customizer {

	public function __construct() {
        parent::instance();
        $this->populated_default_data();
        // Add Controls
        add_action( 'customize_register', array( $this, 'register_header_controls' ) );
	}

    public function register_header_controls( $wp_customize ) {

        // Header Style
        $wp_customize->add_setting( 'header_style',
            array(
                'default' => $this->defaults['header_style'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );

        $wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'header_style',
            array(
                'label' => esc_html__( 'Header Layout', 'listygo' ),
                'description' => esc_html__( 'You can override this settings only Mobile', 'listygo' ),
                'section' => 'header_common',
                'choices' => array(
                    '1' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/header1.jpg',
                        'name' => esc_html__( 'Layout 1', 'listygo' )
                    ),                  
                    '2' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/header2.jpg',
                        'name' => esc_html__( 'Layout 2', 'listygo' )
                    ),
                )
            )
        ) );

        $wp_customize->add_setting( 'header_area',
            array(
                'default' => $this->defaults['header_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_area',
            array(
                'label' => __( 'Header On/Off', 'listygo' ),
                'section' => 'header_common',
            )
        ) );

        $wp_customize->add_setting( 'tr_header',
            array(
                'default' => $this->defaults['tr_header'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'tr_header',
            array(
                'label' => __( 'Transparent Header', 'listygo' ),
                'section' => 'header_common',
            )
        ) );

        $wp_customize->add_setting( 'menu_box_layout',
            array(
                'default' => $this->defaults['menu_box_layout'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'menu_box_layout',
            array(
                'label' => esc_html__( 'Header Area', 'listygo' ),
                'section' => 'header_common',
                'type' => 'select',
                'choices' => array(
                    'container-fluid custom-padding' => esc_html__( 'Full width layout', 'listygo' ),
                    'container' => esc_html__( 'Box layout', 'listygo' ),
                ),
            )
        );

        // Header login button
        $wp_customize->add_setting( 'header_login',
            array(
                'default' => $this->defaults['header_login'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_login',
            array(
                'label' => esc_html__( 'Login Button', 'listygo' ),
                'section' => 'header_common',
            )
        ) );
        // Header login button text
        $wp_customize->add_setting( 'header_login_text',
            array(
                'default' => $this->defaults['header_login_text'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'header_login_text',
            array(
                'label' => esc_html__( 'Login Button Text', 'listygo' ),
                'section' => 'header_common',
                'type' => 'text',
            )
        );
        // Header login button link
        $wp_customize->add_setting( 'login_btn_link',
            array(
                'default' => $this->defaults['login_btn_link'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_url_sanitization',
            )
        );
        $wp_customize->add_control( 'login_btn_link',
            array(
                'label' => esc_html__( 'Button Url', 'listygo' ),
                'section' => 'header_common',
                'type' => 'url',
                'active_callback' => 'rttheme_is_menu_btn_enabled',
            )
        );
        // Header listing button
        $wp_customize->add_setting( 'header_listing',
            array(
                'default' => $this->defaults['header_listing'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_listing',
            array(
                'label' => esc_html__( 'Listing Button', 'listygo' ),
                'section' => 'header_common',
            )
        ) );

        // Link button text
        $wp_customize->add_setting( 'menu_link_btn_text',
            array(
                'default' => $this->defaults['menu_link_btn_text'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'menu_link_btn_text',
            array(
                'label' => esc_html__( 'Button Text', 'listygo' ),
                'section' => 'header_common',
                'type' => 'text',
                'active_callback' => 'rttheme_is_menu_btn_enabled',
            )
        );
        // Link button Link
        $wp_customize->add_setting( 'menu_link_btn_link',
            array(
                'default' => $this->defaults['menu_link_btn_link'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_url_sanitization',
            )
        );
        $wp_customize->add_control( 'menu_link_btn_link',
            array(
                'label' => esc_html__( 'Button Url', 'listygo' ),
                'section' => 'header_common',
                'type' => 'url',
                'active_callback' => 'rttheme_is_menu_btn_enabled',
            )
        );
        $wp_customize->add_setting( 'header_top',
        array(
            'default' => $this->defaults['header_top'],
            'transport' => 'refresh',
            'sanitize_callback' => 'rttheme_switch_sanitization',
        ));
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_top',
            array(
                'label' => esc_html__( 'Header Top Bar', 'listygo' ),
                'section' => 'header_common',
            )
        ) );

        /**
         * Header Offcanvas Menu
        * ===================================================================================================*/
        $wp_customize->add_setting('toggle_bar_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'toggle_bar_heading', array(
            'label' => esc_html__( 'Offcanvas Settings', 'listygo' ),
            'section' => 'header_toggle_section',
        )));
        $wp_customize->add_setting( 'header_offcanvas_menu',
        array(
            'default' => $this->defaults['header_offcanvas_menu'],
            'transport' => 'refresh',
            'sanitize_callback' => 'rttheme_switch_sanitization',
        ));
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_offcanvas_menu',
            array(
                'label' => esc_html__( 'Offcanvas Area', 'listygo' ),
                'section' => 'header_toggle_section',
            )
        ) );
        $wp_customize->add_setting( 'offcanvas_logo',
            array(
                'default' => $this->defaults['offcanvas_logo'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'offcanvas_logo',
            array(
                'label' => esc_html__( 'Logo', 'listygo' ),
                'description' => esc_html__( 'This is the event logo uploader', 'listygo' ),
                'section' => 'header_toggle_section',
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
        $wp_customize->add_setting( 'offcanvas_title',
            array(
                'default' => $this->defaults['offcanvas_title'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_title',
            array(
                'label' => esc_html__( 'Title', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_date',
            array(
                'default' => $this->defaults['offcanvas_date'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_date',
            array(
                'label' => esc_html__( 'Date', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_time',
            array(
                'default' => $this->defaults['offcanvas_time'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_time',
            array(
                'label' => esc_html__( 'Time', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_location',
            array(
                'default' => $this->defaults['offcanvas_location'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_location',
            array(
                'label' => esc_html__( 'Location', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_address',
            array(
                'default' => $this->defaults['offcanvas_address'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_address',
            array(
                'label' => esc_html__( 'Address', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_contact_no',
            array(
                'default' => $this->defaults['offcanvas_contact_no'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_contact_no',
            array(
                'label' => esc_html__( 'Contact Number', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_btn_txt',
            array(
                'default' => $this->defaults['offcanvas_btn_txt'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_btn_txt',
            array(
                'label' => esc_html__( 'Button Text', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );
        $wp_customize->add_setting( 'offcanvas_btn_link',
            array(
                'default' => $this->defaults['offcanvas_btn_link'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'offcanvas_btn_link',
            array(
                'label' => esc_html__( 'Button Link', 'listygo' ),
                'section' => 'header_toggle_section',
                'type' => 'text',
            )
        );

        /**
         * Mobile Devices Menu Heading
        * ===================================================================================================*/
        $wp_customize->add_setting('mobile_header_switching', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'mobile_header_switching', array(
            'label' => esc_html__( 'Mobile Menu Control', 'listygo' ),
            'section' => 'header_mobile_section',
        )));

        // Mobile Devices Search
        $wp_customize->add_setting( 'header_mobile_login',
            array(
                'default' => $this->defaults['header_mobile_login'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_login',
            array(
                'label' => esc_html__( 'Login Button', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
        // Mobile Devices Button
        $wp_customize->add_setting( 'header_mobile_listing',
            array(
                'default' => $this->defaults['header_mobile_listing'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_listing',
            array(
                'label' => esc_html__( 'Listing Button', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
        // Mobile Devices Toggle
        $wp_customize->add_setting( 'header_mobile_toggle',
            array(
                'default' => $this->defaults['header_mobile_toggle'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_toggle',
            array(
                'label' => esc_html__( 'Toggle Button', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
        
        $wp_customize->add_setting('mobile_header_top_switching', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control2($wp_customize, 'mobile_header_top_switching', array(
            'label' => esc_html__( 'Mobile Header Top', 'listygo' ),
            'section' => 'header_mobile_section',
        )));
        // Mobile Devices Phone
        $wp_customize->add_setting( 'header_mobile_topbar',
            array(
                'default' => $this->defaults['header_mobile_topbar'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_topbar',
            array(
                'label' => esc_html__( 'Top Bar', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
        // Mobile Devices Top Bar Phone
        $wp_customize->add_setting( 'header_mobile_topbar_phone',
            array(
                'default' => $this->defaults['header_mobile_topbar_phone'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_topbar_phone',
            array(
                'label' => esc_html__( 'Top Bar Phone', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
        // Mobile Devices Top Bar Email
        $wp_customize->add_setting( 'header_mobile_topbar_email',
            array(
                'default' => $this->defaults['header_mobile_topbar_email'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_topbar_email',
            array(
                'label' => esc_html__( 'Top Bar Email', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
        // Mobile Devices Top Bar Socias
        $wp_customize->add_setting( 'header_mobile_topbar_social',
            array(
                'default' => $this->defaults['header_mobile_topbar_social'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_switch_sanitization',
            )
        );
        $wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_topbar_social',
            array(
                'label' => esc_html__( 'Top Bar Social', 'listygo' ),
                'section' => 'header_mobile_section',
            )
        ) );
    }
}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Header_Settings();
}

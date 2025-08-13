<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo\Customizer;
/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Customizer {
	// Get our default values
	protected $defaults;
    protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', array( $this, 'add_customizer_panels' ) );
		// Register sections
		add_action( 'customize_register', array( $this, 'add_customizer_sections' ) );
	}

    public static function instance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function populated_default_data() {
        $this->defaults = rttheme_generate_defaults();
    }

	/**
	 * Customizer Panels
	 */
	public function add_customizer_panels( $wp_customize ) {

        // Add Header Panel
        $wp_customize->add_panel( 'rttheme_header_settings',
            array(
                'title' => esc_html__( 'Header', 'listygo' ),
                'description' => esc_html__( 'Headers.', 'listygo' ),
                'priority' => 3,
            )
        );

        // Add Footer Panel
        $wp_customize->add_panel( 'rttheme_footer_settings',
            array(
                'title' => esc_html__( 'Footer', 'listygo' ),
                'description' => esc_html__( 'Footers.', 'listygo' ),
                'priority' => 4,
            )
        );

        // Add Color Panel
        $wp_customize->add_panel( 'rttheme_colors_defaults',
            array(
                'title' => esc_html__( 'Color Settings', 'listygo' ),
                'description' => esc_html__( 'Listygo overall colors for your site.', 'listygo' ),
                'priority' => 5,
            )
        );

	    // Add Laypout Panel
		$wp_customize->add_panel( 'rttheme_layouts_defaults',
            array(
				'title' => esc_html__( 'Layout Settings', 'listygo' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'listygo' ),
				'priority' => 6,
			)
		);

        // Add General Panel
        $wp_customize->add_panel( 'rttheme_blog_settings',
            array(
                'title' => esc_html__( 'Blog Settings', 'listygo' ),
                'description' => esc_html__( 'Blog settings for your site.', 'listygo' ),
                'priority' => 7,
            )
        );

        // Add General Panel
        $wp_customize->add_panel( 'rttheme_listing_settings',
            array(
                'title' => esc_html__( 'Listing Settings', 'listygo' ),
                'description' => esc_html__( 'All listing settings here.', 'listygo' ),
                'priority' => 10,
            )
        );
        // Advertisements Panel
		$wp_customize->add_panel( 'rttheme_Advertisements_defaults',
        [
            'title'       => esc_html__( 'Advertisements Settings', 'listygo' ),
            'description' => esc_html__( 'Advertisements settings options here.', 'listygo' ),
            'priority'    => 11,
        ]
    );
		
	}

    /**
    * Customizer sections
    */
	public function add_customizer_sections( $wp_customize ) {

		// Rename the default Colors section
		$wp_customize->get_section( 'colors' )->title = 'Background';

		// Move the default Colors section to our new Colors Panel
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';

		// Change the Priority of the default Colors section so it's at the top of our Panel
		$wp_customize->get_section( 'colors' )->priority = 10;

		// Add General Section
		$wp_customize->add_section( 'general_section',
			array(
				'title' => esc_html__( 'General', 'listygo' ),
				'priority' => 1,
			)
		);

        // Add Contact Section
        $wp_customize->add_section( 'contact_section',
            array(
                'title' => esc_html__( 'Contact & Socials', 'listygo' ),
                'priority' => 2,
            )
        );

		// Add Header Main Section
		$wp_customize->add_section( 'header_section',
			array(
				'title' => esc_html__( 'Header', 'listygo' ),
				'priority' => 3,
			)
		);
        // Add Header Common
        $wp_customize->add_section( 'header_common',
            array(
                'title' => esc_html__( 'All Header', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_header_settings',
            )
        );
        
        // Add Header Offcanvas
        $wp_customize->add_section( 'header_toggle_section',
            array(
                'title' => esc_html__( 'Header Offcanvas', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_header_settings',
            )
        );
        // Add Header Mobile
        $wp_customize->add_section( 'header_mobile_section',
            array(
                'title' => esc_html__( 'Header Mobile', 'listygo' ),
                'priority' => 3,
                'panel' => 'rttheme_header_settings',
            )
        );
        // Add Header Banner
        $wp_customize->add_section( 'header_banner_section',
            array(
                'title' => esc_html__( 'Header Banner', 'listygo' ),
                'priority' => 4,
                'panel' => 'rttheme_header_settings',
            )
        );

        // Add Footer Common
        $wp_customize->add_section( 'footer_common',
            array(
                'title' => esc_html__( 'All Footer', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_footer_settings',
            )
        );
        // Add Footer 1
        $wp_customize->add_section( 'footer_1',
            array(
                'title' => esc_html__( 'Footer 1', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_footer_settings',
            )
        );
        // Add Footer 2
        $wp_customize->add_section( 'footer_2',
            array(
                'title' => esc_html__( 'Footer 2', 'listygo' ),
                'priority' => 3,
                'panel' => 'rttheme_footer_settings',
            )
        );
        // Add Footer 3
        $wp_customize->add_section( 'footer_apps',
            array(
                'title' => esc_html__( 'Footer Apps', 'listygo' ),
                'priority' => 4,
                'panel' => 'rttheme_footer_settings',
            )
        );
        // Add Color Section
        $wp_customize->add_section( 'site_color_section',
            array(
                'title' => esc_html__( 'Site Base Color', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_colors_defaults',
            )
        );
        $wp_customize->add_section( 'menu_color_section',
            array(
                'title' => esc_html__( 'Normal Menu Color', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_colors_defaults',
            )
        );
        $wp_customize->add_section( 'menu2_color_section',
            array(
                'title' => esc_html__( 'Transparent Menu Color', 'listygo' ),
                'priority' => 3,
                'panel' => 'rttheme_colors_defaults',
            )
        );
        $wp_customize->add_section( 'others_color_section',
            array(
                'title' => esc_html__( 'Others Color', 'listygo' ),
                'priority' => 4,
                'panel' => 'rttheme_colors_defaults',
            )
        );

        /* All Layouts Settings 
        =================================================================*/
        //Pages Layout Section
        $wp_customize->add_section( 'page_layout_section',
            array(
                'title' => esc_html__( 'Pages Layout', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_layouts_defaults',
            )
        );
        //Blog Page Layout Section
        $wp_customize->add_section( 'blog_layout_section',
            array(
                'title' => esc_html__( 'Blog Archive Layout', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_layouts_defaults',
            )
        );
        //Blog Single Page Layout Section
        $wp_customize->add_section( 'post_single_layout_section',
            array(
                'title' => esc_html__( 'Blog Single Layout', 'listygo' ),
                'priority' => 3,
                'panel' => 'rttheme_layouts_defaults',
            )
        );

        // Add Listing Archive Layout Section
        $wp_customize->add_section( 'listing_layout_section',
            array(
                'title' => __( 'Listing Archive Layout', 'listygo' ),
                'priority' => 4,
                'panel' => 'rttheme_layouts_defaults',
            )
        );
        
        // Add Listing Single Layout Section
        $wp_customize->add_section( 'listing_single_layout_section',
            array(
                'title' => __( 'Listing Single Layout', 'listygo' ),
                'priority' => 5,
                'panel' => 'rttheme_layouts_defaults',
            )
        );

        // Add Team Archive Layout Section
        $wp_customize->add_section( 'team_layout_section',
            array(
                'title' => __( 'Team Archive Layout', 'listygo' ),
                'priority' => 6,
                'panel' => 'rttheme_layouts_defaults',
            )
        );
        
        // Add Team Single Layout Section
        $wp_customize->add_section( 'team_single_layout_section',
            array(
                'title' => __( 'Team Single Layout', 'listygo' ),
                'priority' => 7,
                'panel' => 'rttheme_layouts_defaults',
            )
        );

        // Add Search Layout Section
        $wp_customize->add_section( 'search_layout_section',
            array(
                'title' => __( 'Search Layout', 'listygo' ),
                'priority' => 6,
                'panel' => 'rttheme_layouts_defaults',
            )
        );
        
        // Add Error Layout Section
        $wp_customize->add_section( 'error_layout_section',
            array(
                'title' => __( 'Error Layout', 'listygo' ),
                'priority' => 7,
                'panel' => 'rttheme_layouts_defaults',
            )
        );
        // Add Blog Settings Section
        $wp_customize->add_section( 'blog_post_settings_section',
            array(
                'title' => esc_html__( 'Blog Settings', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_blog_settings',
            )
        );
        // Add Single Blog Settings Section
        $wp_customize->add_section( 'single_post_secttings_section',
            array(
                'title' => esc_html__( 'Single Post Settings', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_blog_settings',
            )
        );

        // Add Listing Section
        $wp_customize->add_section( 'listing_archive_section',
            array(
                'title' => esc_html__( 'Listing Archive', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_listing_settings',
            )
        );
        // Add Listing Section
        $wp_customize->add_section( 'listing_details_section',
            array(
                'title' => esc_html__( 'Listing Details', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_listing_settings',
            )
        );
        // Add Listing Search Section
        $wp_customize->add_section( 'listing_search_section',
            array(
                'title' => esc_html__( 'Listing Search', 'listygo' ),
                'priority' => 3,
                'panel' => 'rttheme_listing_settings',
            )
        );

        // Add Listing Restaurant Section
        $wp_customize->add_section( 'listing_restaurant_section',
            array(
                'title' => esc_html__( 'Listing Restaurant', 'listygo' ),
                'priority' => 4,
                'panel' => 'rttheme_listing_settings',
            )
        );
        // Add Listing Event Section
        $wp_customize->add_section( 'listing_event_section',
            array(
                'title' => esc_html__( 'Listing Event', 'listygo' ),
                'priority' => 5,
                'panel' => 'rttheme_listing_settings',
            )
        );

        // Add Listing Event Section
        $wp_customize->add_section( 'listing_doctor_section',
            array(
                'title' => esc_html__( 'Listing Doctor', 'listygo' ),
                'priority' => 6,
                'panel' => 'rttheme_listing_settings',
            )
        );

        // Add Error Page Section
        $wp_customize->add_section( 'error_section',
            array(
                'title' => esc_html__( 'Error Page', 'listygo' ),
                'priority' => 14,
            )
        );

        /* = Advertisements Panel
		===================================================================*/
		$wp_customize->add_section( 'listing_archive_page_advertisements',
            array(
                'title' => esc_html__( 'Listing Archive Page', 'listygo' ),
                'priority' => 1,
                'panel' => 'rttheme_Advertisements_defaults',
            )
        );
        $wp_customize->add_section( 'listing_single_page_advertisements',
            array(
                'title' => esc_html__( 'Listing Single Page', 'listygo' ),
                'priority' => 2,
                'panel' => 'rttheme_Advertisements_defaults',
            )
        );
        $wp_customize->add_section( 'blog_archive_page_advertisements',
            array(
                'title' => esc_html__( 'Blog/Archive Page', 'listygo' ),
                'priority' => 3,
                'panel' => 'rttheme_Advertisements_defaults',
            )
        );
        $wp_customize->add_section( 'single_post_advertisements',
            array(
                'title' => esc_html__( 'Single Post', 'listygo' ),
                'priority' => 4,
                'panel' => 'rttheme_Advertisements_defaults',
            )
        );
        $wp_customize->add_section( 'page_advertisements',
            array(
                'title' => esc_html__( 'Page', 'listygo' ),
                'priority' => 5,
                'panel' => 'rttheme_Advertisements_defaults',
            )
        );

	}

}

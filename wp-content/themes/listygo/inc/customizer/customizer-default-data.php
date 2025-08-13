<?php
// Customizer Default Data
if ( ! function_exists( 'rttheme_generate_defaults' ) ) {
    function rttheme_generate_defaults() {
        $customizer_defaults = array(

            /* = General Area
            =======================================================*/ 
            'logo'                => '',
            'logo_dark'           => '',            
            'preloader'           => '',
            'page_scrolltop'      => '',
            'sticky_header'       => '',
            'listygo_breadcrumb'  => '',
            'sticky_sidebar'      => 1,
            'restrict_admin_area' => '',

            /* = Contact & Social Area
            =======================================================*/
            'header_top'     => '',
            'phone_label'    => '',
            'phone_number'   => '',
            'mail_label'     => '',
            'mail_number'    => '',
            'social_label'   => '',
            'rt_facebook'    => '',
            'rt_twitter'     => '',
            'rt_linkedin'    => '',
            'rt_instagram'   => '',
            'rt_pinterest'   => '',
            'rt_youtube'     => '',
            'rt_tiktok'      => '',

            /* = Header Area
            =======================================================*/
            'header_top'            => '',
            'header_style'          => 1,
            'header_area'           => '',
            'tr_header'             => '',
            'menu_box_layout'       => 'container',
            'header_login'          => '',
            'header_login_text'     => 'Login',
            'login_btn_link'        => '',
            'header_listing'        => '',
            'menu_link_btn_text'    => 'Add Listing',
            'menu_link_btn_link'    => '',
            'header_offcanvas_menu' => '',

            // Offcanvas Menu
            'offcanvas_logo'       => '',
            'offcanvas_title'      => '',
            'offcanvas_date'       => '',
            'offcanvas_time'       => '',
            'offcanvas_location'   => '',
            'offcanvas_address'    => '',
            'offcanvas_contact_no' => '',
            'offcanvas_btn_txt'    => '',
            'offcanvas_btn_link'   => '',

            //Mobile Control
            'header_mobile_login'   => '',
            'header_mobile_listing' => '',
            'header_mobile_toggle'  => '',           
            'header_mobile_topbar'  => '', 
            //TopBar
            'header_mobile_topbar_phone'  => '', 
            'header_mobile_topbar_email'  => '', 
            'header_mobile_topbar_social' => '', 
            

            /* = Pages Area
            =======================================================*/
            'page_layout'        => 'full-width',
            'page_padding_top'    => '',
            'page_padding_bottom' => '',
            'page_banner' => 1,
            'page_breadcrumb' => 1,
            'page_bgcolor' => '#111111',
            'page_bgopacity' => '50',
            'page_bgimg' => '',
            'page_page_bgimg' => '',
            'page_page_bgcolor' => '',

            /* = Blog Archive
            =======================================================*/
            // Layout
            'blog_layout' => 'right-sidebar',
            'blog_padding_top'    => '',
            'blog_padding_bottom' => '',
            'blog_banner' => 1,
            'blog_breadcrumb' => 1, 
            'blog_bgcolor' => '#111111',
            'blog_bgopacity' => '50',
            'blog_bgimg' => '',
            'blog_page_bgimg' => '',
            'blog_page_bgcolor' => '',
            //Options
            'blog_style'         => 1,
            'blog_grid'          => 1,
            'blog_medium_grid'   => 1,
            'post_meta_admin'    => 1, 
            'post_meta_date'     => 1, 
            'post_meta_comnts'   => '', 
            'post_meta_cats'     => 1,
            'excerpt_length'     => 36,

            /* = Blog Single
            =======================================================*/
            //Layout
            'single_post_layout' => 'right-sidebar',
            'single_post_padding_top'    => '',
            'single_post_padding_bottom' => '',
            'single_post_banner' => 1,
            'single_post_breadcrumb' => 1,
            'single_post_bgcolor' => '#111111',
            'single_post_bgopacity' => '50',
            'single_post_bgimg' => '',
            'single_post_page_bgimg' => '',
            'single_post_page_bgcolor' => '',
            //Options
            'post_feature_img'  => 1,
            'post_admin'        => 1,
            'post_date'         => 1,
            'post_comnts'       => 1,
            'post_cats'         => '',
            'post_tags'         => 1,
            'post_share'        => '',
            'blog_related_post' => '',
            'post_navigation'   => 1,            

            /* = Search Layout 
            =======================================================*/
            //Layout 
            'search_layout' => 'right-sidebar',
            'search_padding_top'    => '',
            'search_padding_bottom' => '',
            'search_banner' => 1,
            'search_breadcrumb' => 1,
            'search_bgcolor' => '#111111',
            'search_bgopacity' => '50',
            'search_bgimg' => '',
            'search_page_bgimg' => '',
            'search_page_bgcolor' => '',
            'search_excerpt_length' => 40,

            'listing_search_items' => '',

            /* = Error Layout 
            =======================================================*/
            //Layout 
            'error_padding_top'    => '',
            'error_padding_bottom' => '',
            'error_banner' => 1,
            'error_breadcrumb' => 1,
            'error_bgcolor' => '#111111',
            'error_bgopacity' => '50',
            'error_bgimg' => '',
            'error_page_bgimg' => '',
            'error_page_bgcolor' => '',
            //Options
            'error_bg_img' => '',
            'error_page_title' => 'Oops... Page Not Found!',
            'error_page_subtitle' => 'Sorry We Can not Find That Page!',
            'error_buttontext' => 'Take Me Home', 

            /* = Listing Archive
            =======================================================*/
            // Layout
            'listing_archive_box_layout' => 'container',
            'listing_archive_title' => 'Listing Archive',
            'listing_layout' => 'left-sidebar',
            'listing_padding_top'    => '',
            'listing_padding_bottom' => '',
            'listing_banner' => '',
            'listing_breadcrumb' => 1, 
            'listing_bgcolor' => '#111111',
            'listing_bgopacity' => '70',
            'listing_bgimg' => '',
            'listing_page_bgimg' => '',
            'listing_page_bgcolor' => '',
            //Options
            'listing_archive_breadcrumb' => 1,
            'listing_archive_style'      => 1,
            'listing_grid_cols'          => 2,
            'listing_map_grid_cols'      => 2,
            'map_search_widget_title'    => 'Advanced Search',
            'show_custom_fields_label'   => '',
            'show_listing_custom_fields' => '',
            'listygo_listing_excerpt'    => '10',
            
            /* = Single Listing
            =======================================================*/
            // Layout
            'listing_post_banner' => '',
            'listing_post_padding_top'    => '',
            'listing_post_padding_bottom' => '',
            'listing_post_breadcrumb' => 1,
            'listing_post_bgimg' => '',
            'listing_post_bgcolor' => '#111111',
            'listing_post_bgopacity' => '70',
            'listing_post_page_bgimg' => '',
            'listing_post_page_bgcolor' => '',
            //Options
            'single_listing_header_banner' => 'image',
            'single_listing_style'         => 1,
            'slider_per_view'              => 3,
            'listing_single_breadcrumb'    => 1,
            'listygo_review_btn_title'     => 'Write Review',
            'listygo_desc_title'           => 'Description',
            'listygo_gallery_title'        => 'Gallery',
            'show_gallery_slider'          => '',
            'listygo_map_title'            => 'Map View',
            'listygo_video_title'          => 'Video',
            'listygo_rating_title'         => 'Rating',
            'custom_fields_group_types'    => ['166'],
            'custom_fields_list_types'     => [],
            'show_related_listing'         => 1,
            'related_post_title'           => 'Similar Listing',
            'related_post_slider_cols'     => 3,

            // Listing Search Form
            // Baneer
            'listing_banner_search_items'  => ['category', 'location', 'keyword'],
            'listing_banner_search_style'  => ['popup'],
            // Map
            'listing_map_search_items'    => ['keyword', 'location', 'category', 'price', 'radius'],
			'custom_fields_search_items'   => [],
            'listing_widget_min_price'    => '0',
			'listing_widget_max_price'    => '20000',
			'listing_price_search_type'   => 'input',

            //Sidebar
            'listing_list_information'    => 1,
            'listing_list_timing'         => 1,
            'listing_list_socials'        => 1,
            'listing_list_map_location'   => 1,

            // Listing Restaurant Fields
            'restaurant_foodMenu_lists_category'  => '',
            // Listing Event Fields
            'custom_events_fields_types'  => [],
            // Listing Doctor Fields
            'custom_doctor_fields_types'      => [],
            'doctor_hospital_lists_category'  => '',

            /* = Footer Area 
            =======================================================*/
            //Footer
            'footer_area'    => 1,
            'footer_style'   => 1,
            'copyright_text' => 'Copyright © '. date( 'Y' ) .' Listygo by RadiusTheme.',
            //Footer 1
            'fnsubtitle' => '',
            'fntitle' => '',
            'fnshortcode' => '',
            //Footer 1
            'footer1_bg_color' => '#001e56',
            'footer1_bg_opacity' => '',
            'f1_widgets_area' => '4',
            'f1_area1_column' => '3',
            'f1_area2_column' => '3',
            'f1_area3_column' => '3',
            'f1_area4_column' => '3',

            //Footer 2
            'footer2_bg_color' => '#001E56',
            'footer2_bg_opacity' => '90',
            'f2_widgets_area' => '4',
            'f2_area1_column' => '3',
            'f2_area2_column' => '3',
            'f2_area3_column' => '3',
            'f2_area4_column' => '3',

            //Apps
            'footer_apps'   => 1,
            'apps_title'    => '',
            'apps_desc'     => '',
            'play_store'    => '',
            'apps_store'    => '',
            'app_right_img' => '',
            
            /* = Body Typo Area
            =======================================================*/
            'typo_body' => json_encode(
                array(
                    'font' => 'Outfit',
                    'regularweight' => 'normal',
                )
            ),
            'typo_body_size' => '16px',
            'typo_body_height'=> '30px',

            /* = Menu Typo Area
            =======================================================*/
            //Menu Typography
            'typo_menu' => json_encode(
                array(
                    'font' => 'Outfit',
                    'regularweight' => '500',
                )
            ),
            'typo_menu_size' => '16px',
            'typo_menu_height'=> '28px',

            //Sub Menu Typography
            'typo_submenu_size' => '14px',
            'typo_submenu_height'=> '26px',

            /* = Heading Typo Area
            =======================================================*/
            //Heading Typography
            'typo_heading' => json_encode(
                array(
                    'font' => 'Outfit',
                    'regularweight' => '600',
                )
            ),

            //H1
            'typo_h1' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '600',
                )
            ),
            'typo_h1_size' => '52px',
            'typo_h1_height' => '56px',

            //H2
            'typo_h2' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '600',
                )
            ),
            'typo_h2_size' => '36px',
            'typo_h2_height'=> '46px',

            //H3
            'typo_h3' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '600',
                )
            ),
            'typo_h3_size' => '28px',
            'typo_h3_height'=> '34px',

            //H4
            'typo_h4' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '600',
                )
            ),
            'typo_h4_size' => '22px',
            'typo_h4_height'=> '30px',

            //H5
            'typo_h5' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '600',
                )
            ),
            'typo_h5_size' => '18px',
            'typo_h5_height'=> '28px',

            //H6
            'typo_h6' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '600',
                )
            ),
            'typo_h6_size' => '14px',
            'typo_h6_height'=> '24px',

            /* = Site Color Area
            =======================================================*/
            // Base Color
            'listygo_primary_color' => '',
            'listygo_secondary_color' => '',
            'listygo_body_color' => '',
            'listygo_heading_color' => '',

            /* = Menu Color
            =======================================*/
            // Normal Menu
            'menu_bg_color' => '',
            'menu_text_color' => '',
            'menu_text_hover_color' => '',
            
            // Submenu
            'submenu_bg_color' => '',
            'submenu_text_color' => '',
            'submenu_htext_color' => '',

            // Transparent Menu
            'menu2_text_color' => '',
            'menu2_text_hover_color' => '',

            // Submenu
            'submenu2_bg_color' => '',
            'submenu2_text_color' => '',
            'submenu2_htext_color' => '',

            // Others light
            'scroll_color' => '',
            'preloader_bg_color' => '',
            'preloader_circle_color' => '',
            'preloader_gif' => '',

            /* = Listing Archive Ad 
            =======================================================*/
            // Listing Archive Header
            'listing_archive_header_ad_activate' => '',
            'listing_archive_header_ad_type' => 'image',
            'listing_archive_header_ad_image'        => '',
            'listing_archive_header_ad_link'   => '',
            'listing_archive_header_ad_newtab' => '',
            'listing_archive_header_ad_nofollow' => '',
            'listing_archive_header_ad_code' => '',
            // Listing Archive Before Sidebar
            'listing_archive_before_sidebar_ad_activate' => '',
            'listing_archive_before_sidebar_ad_type' => 'image',
            'listing_archive_before_sidebar_ad_image'        => '',
            'listing_archive_before_sidebar_ad_link'   => '',
            'listing_archive_before_sidebar_ad_newtab' => '',
            'listing_archive_before_sidebar_ad_nofollow' => '',
            'listing_archive_before_sidebar_ad_code' => '',
            // Listing Archive After Sidebar
            'listing_archive_after_sidebar_ad_activate' => '',
            'listing_archive_after_sidebar_ad_type' => 'image',
            'listing_archive_after_sidebar_ad_image'        => '',
            'listing_archive_after_sidebar_ad_link'   => '',
            'listing_archive_after_sidebar_ad_newtab' => '',
            'listing_archive_after_sidebar_ad_nofollow' => '',
            'listing_archive_after_sidebar_ad_code' => '',
            // Listing Archive Footer
            'listing_archive_footer_ad_activate' => '',
            'listing_archive_footer_ad_type' => 'image',
            'listing_archive_footer_ad_image'        => '',
            'listing_archive_footer_ad_link'   => '',
            'listing_archive_footer_ad_newtab' => '',
            'listing_archive_footer_ad_nofollow' => '',
            'listing_archive_footer_ad_code' => '',
            // Listing Archive Before All Listing
            'listing_archive_before_all_listing_ad_activate' => '',
            'listing_archive_before_all_listing_ad_type' => 'image',
            'listing_archive_before_all_listing_ad_image'        => '',
            'listing_archive_before_all_listing_ad_link'   => '',
            'listing_archive_before_all_listing_ad_newtab' => '',
            'listing_archive_before_all_listing_ad_nofollow' => '',
            'listing_archive_before_all_listing_ad_code' => '',
            // Listing Archive After All Listing
            'listing_archive_after_all_listing_ad_activate' => '',
            'listing_archive_after_all_listing_ad_type' => 'image',
            'listing_archive_after_all_listing_ad_image'        => '',
            'listing_archive_after_all_listing_ad_link'   => '',
            'listing_archive_after_all_listing_ad_newtab' => '',
            'listing_archive_after_all_listing_ad_nofollow' => '',
            'listing_archive_after_all_listing_ad_code' => '',

            /* = Listing Single Ad
            =======================================================*/
            // Listing Single Header
            'listing_single_header_ad_activate' => '',
            'listing_single_header_ad_type' => 'image',
            'listing_single_header_ad_image'        => '',
            'listing_single_header_ad_link'   => '',
            'listing_single_header_ad_newtab' => '',
            'listing_single_header_ad_nofollow' => '',
            'listing_single_header_ad_code' => '',
            // Listing Single Before Sidebar
            'listing_single_before_sidebar_ad_activate' => '',
            'listing_single_before_sidebar_ad_type' => 'image',
            'listing_single_before_sidebar_ad_image'        => '',
            'listing_single_before_sidebar_ad_link'   => '',
            'listing_single_before_sidebar_ad_newtab' => '',
            'listing_single_before_sidebar_ad_nofollow' => '',
            'listing_single_before_sidebar_ad_code' => '',
            // Listing Single After Sidebar
            'listing_single_after_sidebar_ad_activate' => '',
            'listing_single_after_sidebar_ad_type' => 'image',
            'listing_single_after_sidebar_ad_image'        => '',
            'listing_single_after_sidebar_ad_link'   => '',
            'listing_single_after_sidebar_ad_newtab' => '',
            'listing_single_after_sidebar_ad_nofollow' => '',
            'listing_single_after_sidebar_ad_code' => '',
            // Listing Single Footer
            'listing_single_footer_ad_activate' => '',
            'listing_single_footer_ad_type' => 'image',
            'listing_single_footer_ad_image'        => '',
            'listing_single_footer_ad_link'   => '',
            'listing_single_footer_ad_newtab' => '',
            'listing_single_footer_ad_nofollow' => '',
            'listing_single_footer_ad_code' => '',
            // Listing Single Before Content
            'listing_single_before_content_ad_activate' => '',
            'listing_single_before_content_ad_type' => 'image',
            'listing_single_before_content_ad_image'        => '',
            'listing_single_before_content_ad_link'   => '',
            'listing_single_before_content_ad_newtab' => '',
            'listing_single_before_content_ad_nofollow' => '',
            'listing_single_before_content_ad_code' => '',
            // Listing Single After Content
            'listing_single_after_content_ad_activate' => '',
            'listing_single_after_content_ad_type' => 'image',
            'listing_single_after_content_ad_image'        => '',
            'listing_single_after_content_ad_link'   => '',
            'listing_single_after_content_ad_newtab' => '',
            'listing_single_after_content_ad_nofollow' => '',
            'listing_single_after_content_ad_code' => '',

            /* = Blog Archive Ad
            =======================================================*/
            // Blog Archive Header
            'blog_archive_header_ad_activate' => '',
            'blog_archive_header_ad_type' => 'image',
            'blog_archive_header_ad_image'        => '',
            'blog_archive_header_ad_link'   => '',
            'blog_archive_header_ad_newtab' => '',
            'blog_archive_header_ad_nofollow' => '',
            'blog_archive_header_ad_code' => '',

            // Blog Archive Before Sidebar
            'blog_archive_before_sidebar_ad_activate' => '',
            'blog_archive_before_sidebar_ad_type' => 'image',
            'blog_archive_before_sidebar_ad_image'        => '',
            'blog_archive_before_sidebar_ad_link'   => '',
            'blog_archive_before_sidebar_ad_newtab' => '',
            'blog_archive_before_sidebar_ad_nofollow' => '',
            'blog_archive_before_sidebar_ad_code' => '',
            // Blog Archive After Sidebar
            'blog_archive_after_sidebar_ad_activate' => '',
            'blog_archive_after_sidebar_ad_type' => 'image',
            'blog_archive_after_sidebar_ad_image'        => '',
            'blog_archive_after_sidebar_ad_link'   => '',
            'blog_archive_after_sidebar_ad_newtab' => '',
            'blog_archive_after_sidebar_ad_nofollow' => '',
            'blog_archive_after_sidebar_ad_code' => '',
            
            // Blog Archive Before Content
            'blog_archive_before_all_post_ad_activate' => '',
            'blog_archive_before_all_post_ad_type' => 'image',
            'blog_archive_before_all_post_ad_image'        => '',
            'blog_archive_before_all_post_ad_link'   => '',
            'blog_archive_before_all_post_ad_newtab' => '',
            'blog_archive_before_all_post_ad_nofollow' => '',
            'blog_archive_before_all_post_ad_code' => '',
            // Blog Archive After Content
            'blog_archive_after_all_post_ad_activate' => '',
            'blog_archive_after_all_post_ad_type' => 'image',
            'blog_archive_after_all_post_ad_image'        => '',
            'blog_archive_after_all_post_ad_link'   => '',
            'blog_archive_after_all_post_ad_newtab' => '',
            'blog_archive_after_all_post_ad_nofollow' => '',
            'blog_archive_after_all_post_ad_code' => '',

            // Blog Archive Footer
            'blog_archive_footer_ad_activate' => '',
            'blog_archive_footer_ad_type' => 'image',
            'blog_archive_footer_ad_image'        => '',
            'blog_archive_footer_ad_link'   => '',
            'blog_archive_footer_ad_newtab' => '',
            'blog_archive_footer_ad_nofollow' => '',
            'blog_archive_footer_ad_code' => '',

            /* = Single Post Ad
            =======================================================*/
            // Single Post Header
            'single_post_header_ad_activate' => '',
            'single_post_header_ad_type' => 'image',
            'single_post_header_ad_image'        => '',
            'single_post_header_ad_link'   => '',
            'single_post_header_ad_newtab' => '',
            'single_post_header_ad_nofollow' => '',
            'single_post_header_ad_code' => '',

            // Single Post Before Sidebar
            'single_post_before_sidebar_ad_activate' => '',
            'single_post_before_sidebar_ad_type' => 'image',
            'single_post_before_sidebar_ad_image'        => '',
            'single_post_before_sidebar_ad_link'   => '',
            'single_post_before_sidebar_ad_newtab' => '',
            'single_post_before_sidebar_ad_nofollow' => '',
            'single_post_before_sidebar_ad_code' => '',
            // Single Post After Sidebar
            'single_post_after_sidebar_ad_activate' => '',
            'single_post_after_sidebar_ad_type' => 'image',
            'single_post_after_sidebar_ad_image'        => '',
            'single_post_after_sidebar_ad_link'   => '',
            'single_post_after_sidebar_ad_newtab' => '',
            'single_post_after_sidebar_ad_nofollow' => '',
            'single_post_after_sidebar_ad_code' => '',

            // Single Post Before Content
            'single_post_before_content_ad_activate' => '',
            'single_post_before_content_ad_type' => 'image',
            'single_post_before_content_ad_image'        => '',
            'single_post_before_content_ad_link'   => '',
            'single_post_before_content_ad_newtab' => '',
            'single_post_before_content_ad_nofollow' => '',
            'single_post_before_content_ad_code' => '',
            // Single Post After Content
            'single_post_after_content_ad_activate' => '',
            'single_post_after_content_ad_type' => 'image',
            'single_post_after_content_ad_image'        => '',
            'single_post_after_content_ad_link'   => '',
            'single_post_after_content_ad_newtab' => '',
            'single_post_after_content_ad_nofollow' => '',
            'single_post_after_content_ad_code' => '',

            // Single Post Footer
            'single_post_footer_ad_activate' => '',
            'single_post_footer_ad_type' => 'image',
            'single_post_footer_ad_image'        => '',
            'single_post_footer_ad_link'   => '',
            'single_post_footer_ad_newtab' => '',
            'single_post_footer_ad_nofollow' => '',
            'single_post_footer_ad_code' => '',

            /* = Page Ad
            =======================================================*/
            // Page Header
            'page_header_ad_activate'            => '',
            'page_header_ad_type'       => 'image',
            'page_header_ad_image'      => '',
            'page_header_ad_link' => '',
            'page_header_ad_newtab'     => '',
            'page_header_ad_nofollow'   => '',
            'page_header_ad_code'       => '',

            // Page Before Sidebar
            'page_before_sidebar_ad_activate' => '',
            'page_before_sidebar_ad_type' => 'image',
            'page_before_sidebar_ad_image'        => '',
            'page_before_sidebar_ad_link'   => '',
            'page_before_sidebar_ad_newtab' => '',
            'page_before_sidebar_ad_nofollow' => '',
            'page_before_sidebar_ad_code' => '',
            // Page After Sidebar
            'page_after_sidebar_ad_activate' => '',
            'page_after_sidebar_ad_type' => 'image',
            'page_after_sidebar_ad_image'        => '',
            'page_after_sidebar_ad_link'   => '',
            'page_after_sidebar_ad_newtab' => '',
            'page_after_sidebar_ad_nofollow' => '',
            'page_after_sidebar_ad_code' => '',

            // Page Before Content
            'page_before_content_ad_activate' => '',
            'page_before_content_ad_type' => 'image',
            'page_before_content_ad_image'        => '',
            'page_before_content_ad_link'   => '',
            'page_before_content_ad_newtab' => '',
            'page_before_content_ad_nofollow' => '',
            'page_before_content_ad_code' => '',
            // Page Before Content
            'page_after_content_ad_activate' => '',
            'page_after_content_ad_type' => 'image',
            'page_after_content_ad_image'        => '',
            'page_after_content_ad_link'   => '',
            'page_after_content_ad_newtab' => '',
            'page_after_content_ad_nofollow' => '',
            'page_after_content_ad_code' => '',

             // Page Footer
             'page_footer_ad_activate' => '',
             'page_footer_ad_type' => 'image',
             'page_footer_ad_image'        => '',
             'page_footer_ad_link'   => '',
             'page_footer_ad_newtab' => '',
             'page_footer_ad_nofollow' => '',
             'page_footer_ad_code' => '',
        );

        return apply_filters( 'rttheme_customizer_defaults', $customizer_defaults );
    }
}
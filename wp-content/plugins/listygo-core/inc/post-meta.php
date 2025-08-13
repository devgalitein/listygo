<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

// use radiustheme\listygo\Helper;
// use radiustheme\listygo\inc\RDTheme;

use radiustheme\listygo\Listing_Functions;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'RT_Postmeta' ) ) {
	return;
}

$Postmeta = \RT_Postmeta::getInstance();

$prefix = LISTYGO_CORE_THEME_PREFIX;

$ctp_socials = array(
	'facebook' => array(
		'label' => esc_html__( 'Facebook', 'listygo-core' ),
		'type'  => 'text',
		'icon'  => 'flaticon-facebook',
		'color' => '#3b5998',
	),
	'twitter' => array(
		'label' => esc_html__( 'Twitter', 'listygo-core' ),
		'type'  => 'text',
		'icon'  => 'flaticon-twitter',
		'color' => '#1da1f2',
	),
	'instagram' => array(
		'label' => esc_html__( 'Instagram', 'listygo-core' ),
		'type'  => 'text',
		'icon'  => 'flaticon-instagram',
		'color' => '#AA3DB2',
	),
);
$listygo_ctp_socials = apply_filters( 'ctp_socials', $ctp_socials );

/*---------------------------------------------------------------------
#. = Layout Settings
-----------------------------------------------------------------------*/
$nav_menus = wp_get_nav_menus( array( 'fields' => 'id=>name' ) );
$nav_menus = array( 'default' => esc_html__( 'Default', 'listygo-core' ) ) + $nav_menus;

$Postmeta->add_meta_box( "{$prefix}_page_settings", esc_html__( 'Layout Settings', 'listygo-core' ), array( 'page', 'post', 'listygo_team', 'rtcl_builder' ), '', '', 'high', array(
	'fields' => array(
	
		"{$prefix}_layout_settings" => array(
			'label'   => esc_html__( 'Layouts', 'listygo-core' ),
			'type'    => 'group',
			'value'  => array(	
			
				"{$prefix}_layout" => array(
					'label'   => esc_html__( 'Layout', 'listygo-core' ),
					'type'    => 'select',
					'options' => array(
						'default'       => esc_html__( 'Default', 'listygo-core' ),
						'full-width'    => esc_html__( 'Full Width', 'listygo-core' ),
						'left-sidebar'  => esc_html__( 'Left Sidebar', 'listygo-core' ),
						'right-sidebar' => esc_html__( 'Right Sidebar', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_header_top" => array(
					'label'    	  => esc_html__( 'Header top', 'listygo-core' ),
					'type'     	  => 'select',
					'options'  	  => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'      => esc_html__( 'Enabled', 'listygo-core' ),
						'off'     => esc_html__( 'Disabled', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_tr_header" => array(
					'label'    	  => esc_html__( 'Transparent Header', 'listygo-core' ),
					'type'     	  => 'select',
					'options'  	  => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'      => esc_html__( 'Enabled', 'listygo-core' ),
						'off'     => esc_html__( 'Disabled', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_header_area" => array(
					'label' 	  => esc_html__( 'Header Area On/Off', 'listygo-core' ),
					'type'  	  => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'      => esc_html__( 'Enabled', 'listygo-core' ),
						'off'     => esc_html__( 'Disabled', 'listygo-core' ),
					),
					'default'  	  => 'default',
				),
				"{$prefix}_header" => array(
					'label'   => esc_html__( 'Header Layout', 'listygo-core' ),
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'1'       => esc_html__( 'Layout 1', 'listygo-core' ),
						'2'       => esc_html__( 'Layout 2', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_header_ad" => array(
					'label' 	  => esc_html__( 'Header Area Ad On/Off', 'listygo-core' ),
					'type'  	  => 'select',
					'options' => array(
						'off'     => esc_html__( 'Disabled', 'listygo-core' ),
						'on'      => esc_html__( 'Enabled', 'listygo-core' ),
					),
					'default'  	  => 'default',
				),
				"{$prefix}_header_menu" => array(
					'label'   => esc_html__( 'Menu Layout', 'listygo-core' ),
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'container-fluid custom-padding' => esc_html__( 'Full width layout', 'listygo-core' ),
						'container'       => esc_html__( 'Box layout', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_footer_area" => array(
					'label' 	  => esc_html__( 'Footer Area', 'listygo-core' ),
					'type'  	  => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'      => esc_html__( 'Enabled', 'listygo-core' ),
						'off'     => esc_html__( 'Disabled', 'listygo-core' ),
					),
					'default'  	  => 'default',
				),
				"{$prefix}_footer" => array(
					'label'   => esc_html__( 'Footer Layout', 'listygo-core' ),
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'1'       => esc_html__( 'Layout 1', 'listygo-core' ),
						'2'       => esc_html__( 'Layout 2', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_copyright_area" => array(
					'label' 	  => esc_html__( 'Copyright Area', 'listygo-core' ),
					'type'  	  => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'      => esc_html__( 'Enabled', 'listygo-core' ),
						'off'     => esc_html__( 'Disabled', 'listygo-core' ),
					),
					'default'  	  => 'default',
				),
				"{$prefix}_banner" => array(
					'label'   => esc_html__( 'Banner Area', 'listygo-core' ),
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'	  => esc_html__( 'Enable', 'listygo-core' ),
						'off'	  => esc_html__( 'Disable', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_breadcrumb" => array(
					'label'   => esc_html__( 'Banner Breadcrumb', 'listygo-core' ),
					'type'    => 'select',
					'options' => array(
						'default' => esc_html__( 'Default', 'listygo-core' ),
						'on'	  => esc_html__( 'Enable', 'listygo-core' ),
						'off'	  => esc_html__( 'Disable', 'listygo-core' ),
					),
					'default'  => 'default',
				),
				"{$prefix}_banner_bgimg" => array(
					'label' => esc_html__( 'Banner Background Image', 'listygo-core' ),
					'type'  => 'image',
					'desc'  => esc_html__( 'If not selected, default will be used', 'listygo-core' ),
				),
				"{$prefix}_banner_bgcolor" => array(
					'label' => esc_html__( 'Banner Background Color', 'listygo-core' ),
					'type'  => 'color_picker',
					'desc'  => esc_html__( 'If not selected, default will be used', 'listygo-core' ),
				),
				"{$prefix}_banner_bgopacity" => array(
					'label' => esc_html__( 'Banner Background Opacity', 'listygo-core' ),
					'type'  => 'number',
					'desc'  => esc_html__( 'Max input number will be 100', 'listygo-core' ),
				),
				"{$prefix}_padding_top" => array(
					'label' => esc_html__( 'Padding Top', 'listygo-core' ),
					'type'  => 'text',
					'desc'  => esc_html__( 'Default banner padding top will be used 100 "px"', 'listygo-core' ),
				),
				"{$prefix}_padding_bottom" => array(
					'label' => esc_html__( 'Padding Bottom', 'listygo-core' ),
					'type'  => 'text',
					'desc'  => esc_html__( 'Default banner padding bottom will be used 100 "px"', 'listygo-core' ),
				),
				"{$prefix}_page_bgimg" => array(
					'label' => esc_html__( 'Page/Post Background Image', 'listygo-core' ),
					'type'  => 'image',
					'desc'  => esc_html__( 'If not selected, default will be used', 'listygo-core' ),
				),
				"{$prefix}_page_bgcolor" => array(
					'label' => esc_html__( 'Page/Post Background Color', 'listygo-core' ),
					'type'  => 'color_picker',
					'desc'  => esc_html__( 'If not selected, default will be used', 'listygo-core' ),
				),
			)
		)
	),
) );


// if ( class_exists('Rtcl')) {
//     $Postmeta->add_meta_box('listing_variation_layout', esc_html__('Listing Custom Information', 'listygo-core'), array("rtcl_listing"), '', '', 'high', array(
//         'fields' => array(
// 			"listing_logo_img" => array(
// 				'label' => esc_html__( 'Author/Company/Brand Logo', 'listygo-core' ),
// 				'type'  => 'image',
// 				'desc'  => esc_html__( 'This is listing company brand logo.', 'listygo-core' ),
// 			),
//         )
//     ));
// }

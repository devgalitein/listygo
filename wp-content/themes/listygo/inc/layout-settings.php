<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
class Layouts {

	public function __construct() {
		add_action( 'template_redirect', array( $this, 'layout_settings' ) );
	}
	public function layout_settings() {
		// Single Pages
        if( is_single() || is_page() ) {
            $post_type = get_post_type();
            $post_id   = get_the_id();
            switch( $post_type ) {
                case 'page':
                $prefix = 'page';
                break;
                default:
                $prefix = 'single_post';
                break;
            }
			
			$layout_settings    = get_post_meta( $post_id, 'listygo_layout_settings', true );
            
            RDTListygo::$layout = ( empty( $layout_settings['listygo_layout'] ) || $layout_settings['listygo_layout']  == 'default' ) ? RDTListygo::$options[$prefix . '_layout'] : $layout_settings['listygo_layout'];
			
            RDTListygo::$header_top = ( empty( $layout_settings['listygo_header_top'] ) || $layout_settings['listygo_header_top'] == 'default' ) ? RDTListygo::$options['header_top'] : $layout_settings['listygo_header_top'];

			RDTListygo::$tr_header = ( empty( $layout_settings['listygo_tr_header'] ) || $layout_settings['listygo_tr_header'] == 'default' ) ? RDTListygo::$options['tr_header'] : $layout_settings['listygo_tr_header'];
			
			RDTListygo::$header_area = ( empty( $layout_settings['listygo_header_area'] ) || $layout_settings['listygo_header_area'] == 'default' ) ? RDTListygo::$options['header_area'] : $layout_settings['listygo_header_area'];
            
            RDTListygo::$header_style = ( empty( $layout_settings['listygo_header'] ) || $layout_settings['listygo_header'] == 'default' ) ? RDTListygo::$options['header_style'] : $layout_settings['listygo_header'];

            RDTListygo::$footer_area = ( empty( $layout_settings['listygo_footer_area'] ) || $layout_settings['listygo_footer_area'] == 'default' ) ? RDTListygo::$options['footer_area'] : $layout_settings['listygo_footer_area'];
			
            RDTListygo::$footer_style = ( empty( $layout_settings['listygo_footer'] ) || $layout_settings['listygo_footer'] == 'default' ) ? RDTListygo::$options['footer_style'] : $layout_settings['listygo_footer'];
			
            $padding_top = ( empty( $layout_settings['listygo_padding_top'] ) || $layout_settings['listygo_padding_top'] == '' ) ? RDTListygo::$options[$prefix . '_padding_top'] : $layout_settings['listygo_padding_top'];
			RDTListygo::$padding_top = (int) $padding_top;

			$padding_bottom = ( empty( $layout_settings['listygo_padding_bottom'] ) || $layout_settings['listygo_padding_bottom'] == '' ) ? RDTListygo::$options[$prefix . '_padding_bottom'] : $layout_settings['listygo_padding_bottom'];
			RDTListygo::$padding_bottom = (int) $padding_bottom;
            
            RDTListygo::$has_banner = ( empty( $layout_settings['listygo_banner'] ) || $layout_settings['listygo_banner'] == 'default' ) ? RDTListygo::$options[$prefix . '_banner'] : $layout_settings['listygo_banner'];
            RDTListygo::$has_breadcrumb = ( empty( $layout_settings['listygo_breadcrumb'] ) || $layout_settings['listygo_breadcrumb'] == 'default' ) ? RDTListygo::$options[$prefix . '_breadcrumb'] : $layout_settings['listygo_breadcrumb'];
            
            RDTListygo::$bgcolor = empty( $layout_settings['listygo_banner_bgcolor'] ) ? RDTListygo::$options[$prefix . '_bgcolor'] : $layout_settings['listygo_banner_bgcolor'];

            RDTListygo::$opacity = empty( $layout_settings['listygo_banner_bgopacity'] ) ? RDTListygo::$options[$prefix . '_bgopacity'] : $layout_settings['listygo_banner_bgopacity'];

            if( is_singular( 'rtcl_listing' ) ) {
                $padding_top = RDTListygo::$options['listing_post_padding_top'];
                $padding_bottom = RDTListygo::$options['listing_post_padding_bottom'];
                $attch_url = wp_get_attachment_image_src( RDTListygo::$options['listing_post_bgimg'], 'full', true ); 
                RDTListygo::$has_banner = RDTListygo::$options['listing_post_banner'];
                RDTListygo::$has_breadcrumb = RDTListygo::$options['listing_post_breadcrumb'];
                RDTListygo::$padding_top = (int) $padding_top;
                RDTListygo::$padding_bottom = (int) $padding_bottom;
                RDTListygo::$bgimg = $attch_url[0];
                RDTListygo::$bgcolor = RDTListygo::$options['listing_post_bgcolor'];
                RDTListygo::$opacity = RDTListygo::$options['listing_post_bgopacity']; 
			} elseif( !empty( $layout_settings['listygo_banner_bgimg'] ) ) {
                $attch_url      = wp_get_attachment_image_src( $layout_settings['listygo_banner_bgimg'], 'full', true );
                RDTListygo::$bgimg = $attch_url[0];
            } elseif( !empty( RDTListygo::$options[$prefix . '_bgimg'] ) ) {
                $attch_url      = wp_get_attachment_image_src( RDTListygo::$options[$prefix . '_bgimg'], 'full', true );
                RDTListygo::$bgimg = $attch_url[0];
            } else {
                RDTListygo::$bgimg = '';
            }
			
            RDTListygo::$pagebgcolor = empty( $layout_settings['listygo_page_bgcolor'] ) ? RDTListygo::$options[$prefix . '_page_bgcolor'] : $layout_settings['listygo_page_bgcolor'];			
            
            if( !empty( $layout_settings['listygo_page_bgimg'] ) ) {
                RDTListygo::$pagebgimg = wp_get_attachment_image_src( $layout_settings['listygo_page_bgimg'], 'full', true );
            } elseif( !empty( RDTListygo::$options[$prefix . '_page_bgimg'] ) ) {
                RDTListygo::$pagebgimg = wp_get_attachment_image_src( RDTListygo::$options[$prefix . '_page_bgimg'], 'full', true );
            }
        }
        
        // Blog and Archive
        elseif( is_home() || is_archive() || is_search() || is_404() ) {
            if( is_search() ) {
                $prefix = 'search';
            } else if( is_404() ) {
                $prefix                                = 'error';
                RDTListygo::$options[$prefix . '_layout'] = 'full-width';
            } elseif( is_post_type_archive( "rtcl_listing" ) || is_tax( "rtcl_category" ) ) {
                $prefix = 'listing'; 
            } elseif( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
                $prefix = 'shop';
			} elseif( is_post_type_archive( "listygo_team" ) || is_tax( "listygo_team_category" ) ) {
                $prefix = 'team_archive'; 
			} else {
                $prefix = 'blog';
            }
            
            RDTListygo::$layout         = RDTListygo::$options[$prefix . '_layout'];
            RDTListygo::$header_top     = RDTListygo::$options['header_top'];
            RDTListygo::$tr_header      = RDTListygo::$options['tr_header'];
            RDTListygo::$header_area    = RDTListygo::$options['header_area'];
            RDTListygo::$footer_area    = RDTListygo::$options['footer_area'];
            RDTListygo::$header_style   = RDTListygo::$options['header_style'];
            RDTListygo::$footer_style   = RDTListygo::$options['footer_style'];
            RDTListygo::$padding_top    = RDTListygo::$options[$prefix . '_padding_top'];
            RDTListygo::$padding_bottom = RDTListygo::$options[$prefix . '_padding_bottom'];
            RDTListygo::$has_banner     = RDTListygo::$options[$prefix . '_banner'];
            RDTListygo::$has_breadcrumb     = RDTListygo::$options[$prefix . '_breadcrumb'];
            RDTListygo::$bgcolor        = RDTListygo::$options[$prefix . '_bgcolor'];
            RDTListygo::$opacity        = RDTListygo::$options[$prefix . '_bgopacity'];
			
            if( !empty( RDTListygo::$options[$prefix . '_bgimg'] ) ) {
                $attch_url      = wp_get_attachment_image_src( RDTListygo::$options[$prefix . '_bgimg'], 'full', true );
                RDTListygo::$bgimg = $attch_url[0];
            } else {
                RDTListygo::$bgimg = '';
            }
			
            RDTListygo::$pagebgcolor = RDTListygo::$options[$prefix . '_page_bgcolor'];
            if( !empty( RDTListygo::$options[$prefix . '_page_bgimg'] ) ) {
                $attch_url  = wp_get_attachment_image_src( RDTListygo::$options[$prefix . '_page_bgimg'], 'full', true );
                RDTListygo::$pagebgimg = wp_get_attachment_image_src( RDTListygo::$options[$prefix . '_page_bgimg'], 'full', true );
            }			
			
        }
	}
}
new Layouts;

<?php

/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php
        if (RDTListygo::$options['preloader']) {
            do_action('site_prealoader');
        }
        $header_style = RDTListygo::$header_style ? RDTListygo::$header_style : 1;
    ?>

    <div id="wrapper" class="wrapper">
        <div id="masthead" class="site-header">
            <?php get_template_part('template-parts/header/header', $header_style ); ?>
        </div>
        <?php
            $listygo_settings = get_post_meta( get_the_ID(), 'listygo_layout_settings', true );
            
            if ( is_array($listygo_settings) && in_array( "listygo_header_ad", array_keys( $listygo_settings ) ) ){
                $header_ad = ( !empty( $listygo_settings['listygo_header_ad'] ) ) ? $listygo_settings['listygo_header_ad']: '';
            } else {
                $header_ad = '';
            }

            if ( $header_ad == 'on' ) {
                do_action( 'listygo_header_ad' );
            }

            if ( RDTListygo::$has_banner == '1' || RDTListygo::$has_banner === "on" ) {
                get_template_part( 'template-parts/content', 'banner' );
            }
        ?>
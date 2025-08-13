<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

$theme_data = wp_get_theme( get_template() );
define( 'LISTYGO_THEME_VERSION',     ( WP_DEBUG ) ? time() : $theme_data->get( 'Version' ) );
define( 'LISTYGO_THEME_AUTHOR_URI',  $theme_data->get( 'AuthorURI' ) );
define( 'LISTYGO_THEME_PREFIX',      'listygo' );
define( 'LISTYGO_THEME_PREFIX_VAR',  'listygo' );
define( 'LISTYGO_WIDGET_PREFIX',     'listygo' );
define( 'LISTYGO_THEME_CPT_PREFIX',  'listygo' );

// DIR
define( 'LISTYGO_THEME_BASE_DIR',    get_template_directory(). '/' );
define( 'LISTYGO_THEME_INC_DIR',     LISTYGO_THEME_BASE_DIR . 'inc/' );
define( 'LISTYGO_THEME_VIEW_DIR',    LISTYGO_THEME_INC_DIR . 'views/' );
define( 'LISTYGO_THEME_PLUGINS_DIR', LISTYGO_THEME_BASE_DIR . 'inc/plugin-bundle/' );
define( 'LISTYGO_THEME_ASSETS_URL', get_template_directory_uri() . '/assets/img/theme/' );
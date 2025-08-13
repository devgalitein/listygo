<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;

require_once LISTYGO_THEME_INC_DIR . 'helper-traits/layout-trait.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/data-trait.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/resource-load-trait.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/custom-query-trait.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/icon-trait.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/socials-share.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/svg-trait.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/lc-helper.php';
require_once LISTYGO_THEME_INC_DIR . 'helper-traits/lc-utility.php';
require_once LISTYGO_THEME_INC_DIR . 'helper.php';

Helper::requires( 'class-tgm-plugin-activation.php' );
Helper::requires( 'custom-header.php' );
Helper::requires( 'tgm-config.php' );
Helper::requires( 'general.php' );
Helper::requires( 'scripts.php' );
Helper::requires( 'layout-settings.php' );

Helper::requires( 'customizer/customizer-default-data.php' );
Helper::requires( 'customizer/init.php');
Helper::requires( 'rdtheme.php' );

Helper::requires( 'ad-management.php' );

if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
	Helper::requires( 'custom/functions.php', 'classified-listing' );
}
if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) && Listing_Functions::is_enable_car_listing() ) {
	Helper::requires( 'category.php' );
}

/* = Api Setup File = */
Helper::requires( 'api.php' );
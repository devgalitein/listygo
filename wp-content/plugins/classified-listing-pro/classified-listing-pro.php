<?php

/**
 * @wordpress-plugin
 * Plugin Name:             Classified Listing Pro – Classified ads & Business Directory Plugin
 * Plugin URI:              https://radiustheme.com/demo/wordpress/classifiedpro
 * Description:             This is the Add-on plugin for Classified Listing – Classified ads & Business Directory Plugin. By using this Addon you can get pro feature of all others Add-ons.
 * Version:                 4.0.2
 * Requires PHP:            7.4
 * Requires at least:       6
 * Tested up to:            6.8
 * Requires Plugins: 		classified-listing
 * Author:                  Business Directory Team by RadiusTheme
 * Author URI:              https://radiustheme.com
 * Text Domain:             classified-listing-pro
 * Domain Path:             /languages
 */

defined( 'ABSPATH' ) || die( 'Keep Silent' );

define( 'RTCL_PRO_VERSION', '4.0.2' );
define( 'RTCL_PRO_PLUGIN_FILE', __FILE__ );
define( 'RTCL_PRO_PATH', plugin_dir_path( RTCL_PRO_PLUGIN_FILE ) );
define( 'RTCL_PRO_URL', plugins_url( '', RTCL_PRO_PLUGIN_FILE ) );

require_once 'app/RtclPro.php';
<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Classified Listing Store
 * Plugin URI:        https://radiustheme.com/demo/wordpress/classifiedpro
 * Description:       This is the Add-on plugin for classified listing pro. By using this Add-on you can create store and able to create membership.
 * Version:           3.0.1
 * Author:            RadiusTheme
 * Author URI:        https://radiustheme.com
 * Requires Plugins:  classified-listing
 * Text Domain:       classified-listing-store
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RTCL_STORE_VERSION', '3.0.1' );
define( 'RTCL_STORE_PLUGIN_FILE', __FILE__ );
define( 'RTCL_STORE_PATH', plugin_dir_path( RTCL_STORE_PLUGIN_FILE ) );
define( 'RTCL_STORE_URL', plugins_url( '', RTCL_STORE_PLUGIN_FILE ) );

require_once 'app/RtclStore.php';

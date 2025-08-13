<?php
/**
 * @wordpress-plugin
 * Plugin Name:     Classified Listing - Booking
 * Plugin URI:      https://www.radiustheme.com/downloads/classified-listing-booking/
 * Description:     Booking addon for Classified Listing
 * Version:         3.1.0
 * Author:          RadiusTheme
 * Author URI:      https://radiustheme.com
 * Text Domain:     rtcl-booking
 * Domain Path:     /languages
 */

defined( 'ABSPATH' ) || die( 'Keep Silent' );

define( 'RTCL_BOOKING_VERSION', '3.1.0' );
define( 'RTCL_BOOKING_PLUGIN_FILE', __FILE__ );
define( 'RTCL_BOOKING_PATH', plugin_dir_path( RTCL_BOOKING_PLUGIN_FILE ) );

require_once 'app/RtclBooking.php';
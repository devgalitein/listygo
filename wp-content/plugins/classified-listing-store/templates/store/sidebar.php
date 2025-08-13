<?php
/**
 * Sidebar
 *
 * @package     ClassifiedListing/Templates
 * @version     1.2.31
 */

use RtclStore\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( Functions::is_single_store() ) {
	global $store;
	?>
    <div id="rtcl-sidebar" class="rtcl-sidebar-wrapper">
        <div class="rtcl-store-info-wrap">
            <div class="store-info">
				<?php do_action( 'rtcl_single_store_information', $store ); ?>
            </div>
        </div>
		<?php get_sidebar( 'store' ); ?>
    </div>
	<?php
} else if ( Functions::is_store() ) {
	?>
    <div id="rtcl-sidebar" class="rtcl-sidebar-wrapper">
		<?php get_sidebar( 'store' ); ?>
    </div>
	<?php
} else {
	get_sidebar( 'store' );
}

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

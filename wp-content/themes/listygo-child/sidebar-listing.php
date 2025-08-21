<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;

?>

<div class="<?php echo Helper::listing_sidebar_class(); ?>">
	<div class="listing-sidebar-widgets">
		<?php 
			do_action( 'listygo_before_sidebar_ad' );
			dynamic_sidebar( 'listing-archive-sidebar-without-ajax' );
			do_action( 'listygo_after_sidebar_ad' );
		?>
    </div>
</div>

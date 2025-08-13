<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;
use radiustheme\listygo\Listing_Functions;

?>

<!-- Search form 2 -->
<div class="listygo-search-form search-form-2 search-form-4 <?php echo esc_attr( $data['css_class'] ); ?>">
	<?php Helper::get_custom_listing_template( 'listing-search-2', true, compact('data') ); ?>
</div>
<!-- Search form 2 End -->
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

<!-- Search form 1 -->
<div class="listygo-search-form search-form-3 <?php echo esc_attr( $data['css_class'] ); ?>">
	<?php Helper::get_custom_listing_template( 'listing-search', true, compact('data') ); ?>
</div>
<!-- Search form 1 End -->
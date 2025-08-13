<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

$footer_apps = RDTListygo::$options['footer_apps'];

$footer_style = RDTListygo::$footer_style ? RDTListygo::$footer_style : 1;

?>
<div class="container">
<?php
echo do_shortcode('[city_state_list]');
echo do_shortcode('[specific_category_city_state_list category="dentist"]');
?>
</div>
<?php 
    if ($footer_apps) {
        get_template_part( 'template-parts/footer/apps'); 
    }
    do_action( 'listygo_footer_ad' );
    get_template_part( 'template-parts/footer/footer', $footer_style ); 

    if ( !class_exists( 'RtclPro' ) && !is_singular('rtcl_listing') ) {
        get_template_part( 'template-parts/footer/photoswipe'); 
    }
    if ( class_exists( 'Rtcl' ) && class_exists( 'RtclPro' ) ) {
	    if (Functions::is_enable_favourite()){ 
		    Listing_Functions::logout_user_favourite(); 
		}
	}
?>
<?php wp_footer(); ?>

</div> 
</body>
</html>

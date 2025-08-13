<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;
$prefix = LISTYGO_THEME_PREFIX_VAR;
?>
<div class="<?php Helper::the_sidebar_class();?>">
	<?php do_action( 'listygo_before_sidebar_ad' ); ?>
	<aside class="sidebar-widget-area <?php  echo esc_attr( RDTListygo::$layout ) ?>">
		<?php
		if ( RDTListygo::$sidebar ) {
			if ( is_active_sidebar( RDTListygo::$sidebar ) ){
				dynamic_sidebar( RDTListygo::$sidebar );
			}
		} elseif (is_singular( $prefix.'_service' )) {
			if ( is_active_sidebar( 'service-widgets' ) ) {
				dynamic_sidebar( 'service-widgets' );
			} else {
				//No Sidebar
			}
		} else {
			if ( is_active_sidebar( 'sidebar' ) ){
				dynamic_sidebar( 'sidebar' );
			}
		}
		?>
	</aside>
	<?php do_action( 'listygo_after_sidebar_ad' ); ?>
</div>

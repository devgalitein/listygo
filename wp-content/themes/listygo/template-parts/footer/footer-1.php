<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;
$nav_menu_args   = Helper::copyright_menu_args();
$rdtheme_light_logo = empty( Helper::rt_the_logo_light() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_light();

$widgets_items1 = RDTListygo::$options['f1_widgets_area'];

if ( has_nav_menu( 'crmenu' ) ) {
    $cfcols = '6';
} else {
    $cfcols = '12 text-center';
}
?>

<!--=====================================-->
<!--=         Footer Area Start         =-->
<!--=====================================-->
<footer class="footer footer--layout1">
    <?php if ( is_active_sidebar( 'footer-widget-1-1' ) || is_active_sidebar( 'footer-widget-1-2' ) || is_active_sidebar( 'footer-widget-1-3' ) || is_active_sidebar( 'footer-widget-1-4' ) ) { ?>
    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between">
                <?php for ( $i = 1; $i <= $widgets_items1; $i++ ) { ?>
                <div class="col-lg-<?php echo esc_attr(RDTListygo::$options['f1_area'.$i.'_column']); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widget-1-'.esc_attr($i) ); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-<?php echo esc_attr( $cfcols ); ?>">
                    <p class="footer-copyright mb-0"><?php echo wp_kses_stripslashes( RDTListygo::$options['copyright_text'] ); ?></p>
                </div>
                <?php if ( has_nav_menu( 'crmenu' ) ) { ?>
                    <div class="col-lg-6">
                        <div class="footer-menu footer-menu--style2">
                            <?php wp_nav_menu( $nav_menu_args ); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</footer>
<!--=====================================-->
<!--=          Footer Area End          =-->
<!--=====================================-->    
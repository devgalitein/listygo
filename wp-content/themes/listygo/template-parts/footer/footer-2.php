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

$widgets_area = RDTListygo::$options['f2_widgets_area'];

if ( has_nav_menu( 'crmenu' ) ) {
	$cfcols = '4';
} else {
	$cfcols = '12 text-center';
}

?>

<!--=====================================-->
<!--=         Footer Area Start         =-->
<!--=====================================-->
<footer class="footer footer--layout2">
    <?php if ( is_active_sidebar( 'footer-widget-2-1' ) || is_active_sidebar( 'footer-widget-2-2' ) || is_active_sidebar( 'footer-widget-2-3' ) || is_active_sidebar( 'footer-widget-2-4' ) ) { ?>
    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between">
                <?php for ( $i = 1; $i <= $widgets_area; $i++ ) { ?>
                <div class="col-xl-<?php echo esc_attr(RDTListygo::$options['f2_area'.$i.'_column']); ?> col-lg-3 col-md-6">
                    <?php dynamic_sidebar( 'footer-widget-2-'.esc_attr($i) ); ?>
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
                    <div class="footer-logo mb-10">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-logo">
                            <?php if ( !empty( Helper::rt_the_logo_light() ) ){
                                echo Helper::rt_the_logo_light();
                            } else {
                                echo wp_kses_post( $rdtheme_light_logo );
                            } ?>
                        </a>
                    </div>
                    <p class="footer-copyright mb-0"><?php echo wp_kses_stripslashes( RDTListygo::$options['copyright_text'] ); ?></p>
                </div>
                <?php if ( has_nav_menu( 'crmenu' ) ) { ?>
                    <div class="col-lg-8">
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
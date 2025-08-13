<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;

$nav_menu_args   = Helper::nav_menu_args();
$header_login = RDTListygo::$options['header_login'] ? true : false;
$header_listing = RDTListygo::$options['header_listing'] ? true : false;

if ( $header_login == true && $header_listing == true ) {
    $align_class = 'justify-content-center';
} else {
    $align_class = 'justify-content-end';
}

?>
<nav class="site-nav <?php echo esc_attr( $align_class ); ?>">
    <?php if ( has_nav_menu( 'primary' ) ) { 
        wp_nav_menu( $nav_menu_args );
    } else {
        if ( is_user_logged_in() ) {
            echo '<ul id="menu" class="menu fallbackcd-menu-item"><li class="menu-item"><a class="fallbackcd" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'listygo' ) . '</a></li></ul>';
        }
    } ?>
</nav>	
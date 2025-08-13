<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;
$nav_menu_args   = Helper::nav_menu_args();

?>
<div class="rt-mobile-menu mean-container" id="meanmenu">
    <?php if ( has_nav_menu( 'primary' ) ) { ?>
        <div class="mean-bar headerBurgerMenu">
            <button class="headerBurgerMenu__button sidebarBtn" onclick="this.classList.toggle('opened');this.setAttribute('aria-expanded', this.classList.contains('opened'))" aria-label="Main Menu" aria-expanded="true">
                <svg width="50" height="50" viewBox="0 0 100 100">
                <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058">
                </path>
                <path class="line line2" d="M 20,50 H 80"></path>
                <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942">
                </path>
                </svg>
            </button>
        </div>
    <?php } ?>
    <div class="rt-slide-nav">
        <div class="offscreen-navigation">
            <?php if ( has_nav_menu( 'primary' ) ) { 
        		wp_nav_menu( $nav_menu_args );
		    } else {
		        if ( is_user_logged_in() ) {
		            echo '<ul id="menu" class="menu fallbackcd-menu-item"><li class="menu-item"><a class="fallbackcd" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'listygo' ) . '</a></li></ul>';
		        }
		    } ?>
        </div>
    </div>
</div>

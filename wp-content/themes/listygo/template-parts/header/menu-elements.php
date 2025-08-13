<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;

$rdtheme_light_logo = empty( Helper::rt_the_logo_light() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_light();
$header_login = RDTListygo::$options['header_login'] ? true : false;
$login_text = RDTListygo::$options['header_login_text'];
$login_btn_link = RDTListygo::$options['login_btn_link'];
$header_listing = RDTListygo::$options['header_listing'] ? true : false;

$offcanvas_menu = RDTListygo::$options['header_offcanvas_menu'] ? true : false;
$btn_text = RDTListygo::$options['menu_link_btn_text'];
$btn_link = RDTListygo::$options['menu_link_btn_link'];

$header_mobile_login = RDTListygo::$options['header_mobile_login'] ? true : false;
$header_mibile_listing = RDTListygo::$options['header_mobile_listing'] ? true : false;
$offcanvas_mobile_menu = RDTListygo::$options['header_mobile_toggle'] ? true : false;


if ( $header_login == true || $header_listing == true && $btn_link && class_exists( 'Rtcl' ) || $offcanvas_menu == true ) {
?>
    <div class="nav-action-elements">
        <ul>
            <?php if ( $header_login == true ) { ?>
            <li class="header-login">
                <a href="<?php echo esc_url( $login_btn_link ); ?>" class="login-btn">
                    <span class="user-login__icon">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.8036 4.22794C11.8036 1.89338 9.87618 0 7.49969 0C5.1232 0 3.19581 1.89338 3.19581 4.22794C3.19581 6.5625 5.1232 8.45588 7.49969 8.45588C9.87618 8.45588 11.8036 6.5625 11.8036 4.22794ZM4.31856 4.22794C4.31856 2.5 5.74072 1.10294 7.49969 1.10294C9.25867 1.10294 10.6808 2.5 10.6808 4.22794C10.6808 5.95588 9.25867 7.35294 7.49969 7.35294C5.74072 7.35294 4.31856 5.95588 4.31856 4.22794Z"
                                fill="white" />
                            <path
                                d="M0.295372 14.9265C0.388934 14.9816 0.482497 15 0.576059 15C0.763185 15 0.969022 14.9081 1.06258 14.7243C2.37246 12.4449 4.84251 11.0294 7.49969 11.0294C10.1569 11.0294 12.6269 12.4449 13.9555 14.7243C14.1052 14.9816 14.4607 15.0735 14.7227 14.9265C14.9847 14.7794 15.0783 14.4301 14.9286 14.1728C13.4128 11.5625 10.5685 9.92647 7.49969 9.92647C4.43084 9.92647 1.58654 11.5625 0.0708215 14.1728C-0.0788786 14.4301 0.0146841 14.7794 0.295372 14.9265Z"
                                fill="white" />
                        </svg>
                    </span> 
                    <?php echo esc_html( $login_text ); ?>
                </a>
                
            </li>
            <?php } if ( $header_listing == true && $btn_link && class_exists( 'Rtcl' ) ) { ?>
                <li class="header-btn">
                    <a href="<?php echo esc_url( $btn_link ); ?>" class="listygo-btn listygo-btn--style1">
                        <span class="listygo-btn__icon">
                            <i class="fa-solid fa-plus"></i>
                        </span>
                        <span class="listygo-btn__text"><?php echo esc_html( $btn_text ); ?></span>
                    </a>
                </li>
            <?php } if ( $offcanvas_menu == true ) { ?>
            <li class="offcanvas-btn">
                <div class="humbarger-menu">
                    <div class="nav-icon offcanvas-menu-btn menu-status-open">
                        <div></div>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php 
    if ( $offcanvas_menu == true ) {
        get_template_part( 'template-parts/header/offcanvas' );
    }
}
?>
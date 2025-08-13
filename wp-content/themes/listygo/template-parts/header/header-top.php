<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;
$socials = Helper::socials();

$menu_layout_settings = get_post_meta( get_the_id(), 'listygo_layout_settings', true );
$menu_layout = ( empty( $menu_layout_settings['listygo_header_menu'] ) || $menu_layout_settings['listygo_header_menu'] == 'default' ) ? RDTListygo::$options['menu_box_layout'] : $menu_layout_settings['listygo_header_menu'];

if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
    if (is_post_type_archive( rtcl()->post_type )) {
        $menu_layout = RDTListygo::$options['listing_archive_box_layout'] ? RDTListygo::$options['listing_archive_box_layout'] : $menu_layout;
    }
}

?>

<div id="topbar-wrap" class="topbar topbar--layout1">
    <div class="<?php echo esc_attr( $menu_layout ); ?>">
        <div class="row align-items-center">
            <?php if (!empty(RDTListygo::$options['phone_number'] || RDTListygo::$options['mail_number'])) { ?>
            <div class="col-lg-7">
                <div class="topbar-contact">
                    <ul>
                        <?php if (!empty(RDTListygo::$options['phone_number'])) { ?>
                        <li class="htop-phone">
                            <?php echo Helper::phone_icon2(); ?>
                            <a href="tel:<?php echo esc_html( RDTListygo::$options['phone_number'] ); ?>">
                                <?php echo esc_html( RDTListygo::$options['phone_label'].' '.RDTListygo::$options['phone_number'] ); ?>
                            </a>
                        </li>
                        <?php } if (!empty(RDTListygo::$options['mail_number'])) { ?>
                        <li class="htop-email">
                            <?php echo Helper::mail_icon(); ?>
                            <a href="mailto:<?php echo esc_html( RDTListygo::$options['mail_number'] ); ?>">
                                <?php echo esc_html( RDTListygo::$options['mail_label'].' '.RDTListygo::$options['mail_number'] ); ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } if ( !empty( $socials ) ) { ?>
            <div class="col-lg-5">
                <div class="topbar-follows">
                    <div class="topbar-follows__header">
                        <?php echo Helper::share_icon(); ?>
                        <span><?php echo esc_html( RDTListygo::$options['social_label'] ); ?></span>
                    </div>
                    <ul>
                        <?php foreach ( $socials as $social ): ?>
                        <li class="<?php echo esc_attr( $social['class'] ); ?>">
                            <a class="<?php echo esc_attr( $social['class'] ); ?>" href="<?php echo esc_url( $social['url'] ); ?>" target="_blank">
                                <i class="<?php echo esc_attr( $social['icon'] ); ?>"></i>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

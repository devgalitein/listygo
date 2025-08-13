<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

$count_html = sprintf( _nx( '%s Listing', '%s Listings', $data['count'], 'Number of Listings', 'listygo-core' ), number_format_i18n( $data['count'] ) );

$link_start = $data['enable_link'] ? '<a href="'.$data['permalink'].'">' : '';
$link_end   = $data['enable_link'] ? '</a>' : '';

$class = $data['display_count'] ? 'rtin-has-count' : '';

?>
<div class="feature-box-layout1 version2 <?php echo esc_attr( $class ); ?>">
    <div class="item-content">
        <h3 class="item-title"><?php echo wp_kses_post( $link_start ); ?><?php echo esc_html( $data['title'] ); ?><?php echo wp_kses_post( $link_end ); ?></h3>
        <?php if ( $data['display_count'] ): ?>
        <p><?php echo wp_kses_post( $link_start ); ?><?php echo esc_html( $count_html ); ?><?php echo wp_kses_post( $link_end ); ?></p>
        <?php endif; ?>
    </div>
    <div class="inline-bg-img-parent-box">
        <div class="item-img"></div>
    </div>
</div>
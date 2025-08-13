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
<div class="feature-box-layout1 <?php echo esc_attr( $class ); ?>">
	<?php echo wp_kses_post( $link_start ); ?>
    <div class="item-img"></div>
    <?php echo wp_kses_post( $link_end ); ?>

    <div class="item-content">
		<?php if ( $data['display_count'] ): ?>
            <div class="listing-number"><?php echo esc_html( $count_html ); ?></div>
		<?php endif; ?>
        <h4 class="item-title"><?php echo esc_html( $data['title'] ); ?></h4>
    </div>
</div>
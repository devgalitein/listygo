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

$heading_tag_html = sprintf( '<%1$s %2$s class="item-title">%3$s %4$s %5$s</%1$s>', $data['heading_tag'], $this->get_render_attribute_string( 'title' ), $link_start, $data['title'], $link_end );

$class = $data['display_count'] ? 'rtin-has-count' : '';


$cat_img = $cat_icon = null;
if (!empty($data['id'])) {
    $image_id = get_term_meta($data['id'], '_rtcl_image', true);
    if ($image_id) {
        $image_attributes = wp_get_attachment_image_src((int)$image_id, 'medium');
        $image = $image_attributes[0];
        if ('' !== $image) {
            $cat_img = sprintf('<img src="%s" class="rtcl-cat-img" alt="%s"/>', $image, esc_attr__('Category Image', 'listygo'));
        }
    }
    $icon_id = get_term_meta($data['id'], '_rtcl_icon', true);
    if ($icon_id) {
        $cat_icon = sprintf('<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $icon_id);
    }
}
?>

<div class="category-box-layout2 common-style">
    <figure class="item-thumb bg--gradient-60">
    </figure>
    <div class="item-content">
        <?php if ($data['cat_icon'] != 'none') { ?>
            <div class="item-icon">
                <?php
                    if ($data['cat_icon'] == 'image') {
                        echo wp_kses_post( $cat_img );
                    } else {
                        echo wp_kses_post( $cat_icon ); 
                    }
                ?>
            </div>
        <?php } ?>
        <?php echo $heading_tag_html; ?>
        <?php if ( $data['display_count'] ): ?>
        <div class="listing-number"><?php echo wp_kses_post( $link_start ); ?><?php echo esc_html( $count_html ); ?><?php echo wp_kses_post( $link_end ); ?></div>
        <?php endif; ?>
    </div>
</div>
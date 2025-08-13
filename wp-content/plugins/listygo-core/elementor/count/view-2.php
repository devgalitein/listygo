<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/listing/view-1.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
// use radiustheme\listygo\Helper;
use Elementor\Icons_Manager;
$prefix = LISTYGO_CORE_THEME_PREFIX;
extract( $data );
?>
<div class="listing-count-content full_box">
  <div class="listing-content__box">
    <?php echo wp_get_attachment_image( $image['id'], 'full', '', array('class' => 'text-overlay-img') ); ?>
    <span class="listing-count-content__icon icon">
      <?php Icons_Manager::render_icon( $data['icon_class'], [ 'aria-hidden' => 'true' ] ); ?>
    </span>
    <span class="listing-count-content__number number"><?php echo esc_html( $counts ); ?></span>
    <span class="listing-count-content__title title"><?php echo esc_html( $title ); ?>
    </span>
  </div>
</div>
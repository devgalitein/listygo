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
<div class="about-block__listing__block full_box">
  <span class="about-block__listing__number number"><?php echo esc_html( $counts ); ?></span>
  <h4 class="about-block__listing__title title"><?php echo esc_html( $title ); ?></h4>
  <button class="about-block__listing__button icon">
    <?php Icons_Manager::render_icon( $data['icon_class'], [ 'aria-hidden' => 'true' ] ); ?>
  </button>
</div>
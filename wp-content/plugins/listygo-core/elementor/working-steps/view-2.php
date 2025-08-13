<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/working-steps/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Elementor\Icons_Manager;

?>
<div class="working-steps v2 row">
    <?php 
      $i = 0;
      foreach ( $data['wstep_repeat'] as $item ) {

      $i++;
      if ($i< 10 ) {
        $i = '0'.$i;
      } else {
        $i = $i;
      }
    ?>
    <div class="col-lg-4">
      <div class="working-step-item">
        <?php if (!empty($item['icon_class']['value'])) { ?>
          <div class="icon">
            <?php Icons_Manager::render_icon( $item['icon_class'], [ 'aria-hidden' => 'true' ] ); ?>
          </div>
        <?php } ?>
        <div class="sl-number"><span><?php echo esc_html( $i ); ?></span></div>
        <h3 class="item-title"><?php echo esc_html( $item['title'] ); ?></h3>
        <p class="text"><?php echo esc_html( $item['description'] ); ?></p>
      </div>
    </div>
    <?php } ?>
</div>
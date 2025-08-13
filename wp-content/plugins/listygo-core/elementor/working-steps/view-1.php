<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/working-steps/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;
use Elementor\Icons_Manager;

?>
<div class="working-steps row">
    <?php 
      $i = $j = 0;
      foreach ( $data['wstep_repeat'] as $item ) {
      $i++;
      $j = $j+300;
      if ($i< 10 ) {
        $i = '0'.$i;
      } else {
        $i = $i;
      }

      if ($i == 1) {
        $j = 300;
      } else if ($i == 2) {
        $j = 600;
      } else if ($i == 3) {
        $j = 1200;
      }
    ?>
    <div class="col-lg-4">
      <div class="working-step-item animated" data-animation-delay="<?php echo esc_attr( $j ); ?>">
        <?php if (!empty($item['icon_class']['value'])) { ?>
          <div class="icon">
            <?php Icons_Manager::render_icon( $item['icon_class'], [ 'aria-hidden' => 'true' ] ); ?>
            <div class="sl-number"><?php echo esc_html( $i ); ?></div>
          </div>
        <?php } ?>
        <h3 class="item-title"><?php echo esc_html( $item['title'] ); ?></h3>
        <p class="text"><?php echo esc_html( $item['description'] ); ?></p>
      </div>
    </div>
    <?php } ?>
    <span class="workflow-progress animated">
      <span class="dot"><img src="<?php echo Helper::get_img('theme/dot.png') ?>" alt="<?php esc_attr_e( 'Shape', 'listygo-core' ) ?>"></span>
      <span class="map-icon"><img src="<?php echo Helper::get_img('theme/location2.png') ?>" alt="<?php esc_attr_e( 'Shape', 'listygo-core' ) ?>"></span>
    </span>
</div>
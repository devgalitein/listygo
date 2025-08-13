<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/pricing-tab/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Elementor\Plugin;
extract( $data );

$monthly = Plugin::instance()->frontend->get_builder_content_for_display($monthly_template);
$yearly = Plugin::instance()->frontend->get_builder_content_for_display($yearly_template);

?>

<div class="row pricing-wrapper">
   <div class="col-12 text-center">
      <div class="pricing-billing-duration-tab">
         <ul class="nav nav-tabs">
            <li class="nav-item">
               <a href="#home" class="nav-link active" data-bs-toggle="tab"><?php echo esc_html( $monthly_label ); ?></a>
            </li>
            <li class="nav-item">
               <a href="#profile" class="nav-link" data-bs-toggle="tab"><?php echo esc_html( $yearly_label ); ?></a>
            </li>
         </ul>
      </div>
   </div>
   <div class="tab-content">
      <div class="tab-pane fade show active" id="home">
         <?php echo $monthly; ?>
      </div>
      <div class="tab-pane fade" id="profile">
         <?php echo $yearly; ?>
      </div>
   </div>
</div>
<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/price/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use Elementor\Icons_Manager;
extract( $data );

?>

<div class="pricing-plan-layout-1 common-pricing">
    <div class="pricing-header">
        <h3 class="item-title"><?php echo esc_html( $title ); ?></h3>
        <div class="pricing-wrap">
            <span class="price-amount"><?php echo esc_html( $price ); ?></span>
            <span class="price-duration"><?php echo esc_html( $period ); ?></span>
        </div>
    </div>
    <ul class="pricing-features">
        <?php foreach ( $data['price_list'] as $value ) { ?>
        <li class="<?php echo esc_attr( $value['feature']); ?>">
            <?php Icons_Manager::render_icon( $value['icon'], [ 'aria-hidden' => 'true' ] ); ?>
            <?php echo esc_html( $value['list'] ); ?> 
        </li>
        <?php } ?>
    </ul>
    <?php if ( !empty( $buttonurl ) ) { ?>
    <div class="pricing-btn">
        <a href="<?php echo esc_url( $buttonurl ); ?>" class="item-btn"><?php echo esc_html( $buttontext ); ?> <i class="flaticon-right-arrow-angle"></i></a>
    </div>
    <?php } ?>
</div>
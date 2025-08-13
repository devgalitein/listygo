<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/animated-image/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;

extract( $data );

$img1 = $image1['url'];
$img2 = $image2['url'];
$img3 = $image3['url'];
$img4 = $image4['url'];
	
?>

<div class="listing-wrapper image-box-v4">
    <?php echo wp_get_attachment_image( $image4['id'], 'full', '', ['class' => 'listing-wrapper__shape one'] ); ?>
    <?php echo wp_get_attachment_image( $image5['id'], 'full', '', ['class' => 'listing-wrapper__shape two'] ); ?>
    <?php if (!empty( $img1 && $img2 )) { ?>
    <div class="listing-item listing-item--one">
        <div class="listing-thumb">
            <?php echo wp_get_attachment_image( $image1['id'], 'full' ); ?>
            <?php echo wp_get_attachment_image( $image2['id'], 'full' ); ?>
        </div>
    </div>
    <?php } if (!empty( $img3 )) { ?>
    <div class="listing-item listing-item--two">
        <div class="listing-thumb">
            <?php echo wp_get_attachment_image( $image3['id'], 'full' ); ?>
        </div>
    </div>
    <?php } ?>
</div>

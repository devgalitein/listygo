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

<div class="listing-wrapper image-box-v3">
    <?php echo wp_get_attachment_image( $image4['id'], 'full', '', ['class' => 'listing-wrapper__shape one'] ); ?>
    <?php echo wp_get_attachment_image( $image5['id'], 'full', '', ['class' => 'listing-wrapper__shape two'] ); ?>
    <?php echo wp_get_attachment_image( $image6['id'], 'full', '', ['class' => 'listing-wrapper__shape three'] ); ?>
    <?php echo wp_get_attachment_image( $image7['id'], 'full', '', ['class' => 'listing-wrapper__shape four'] ); ?>
    <?php echo wp_get_attachment_image( $image8['id'], 'full', '', ['class' => 'listing-wrapper__shape five'] ); ?>
    <?php if (!empty( $img1 )) { ?>
    <div class="listing-item listing-item--one">
        <div class="listing-thumb">
            <?php echo wp_get_attachment_image( $image1['id'], 'full' ); ?>
        </div>
    </div>
    <?php } if (!empty( $img2 )) { ?>
    <div class="listing-item listing-item--two">
        <div class="listing-thumb">
            <?php echo wp_get_attachment_image( $image2['id'], 'full' ); ?>
        </div>
    </div>
    <?php } if (!empty( $img3 )) { ?>
    <div class="listing-item listing-item--three">
        <div class="listing-thumb">
            <?php echo wp_get_attachment_image( $image3['id'], 'full' ); ?>
        </div>
    </div>
    <?php } ?>
</div>

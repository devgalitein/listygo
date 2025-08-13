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

$attr = '';
if ( !empty( $video_url['url'] ) ) {
	$attr  = 'href="' . $data['video_url']['url'] . '"';
}	

?>
<div class="event-right position-relative image-box-v2">
    <?php if (!empty( $img2 || $img3 || $img4 )) { ?>
        <div class="event-figures position-absolute">
            <ul>
                <?php if (!empty( $img2 )) { ?>
                    <li><?php echo wp_get_attachment_image( $image2['id'], 'full' ); ?></li>
                <?php } if (!empty( $img3 )) { ?>
                <li><?php echo wp_get_attachment_image( $image3['id'], 'full' ); ?></li>
                <?php } if (!empty( $img4 )) { ?>
                <li><?php echo wp_get_attachment_image( $image4['id'], 'full' ); ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    <figure class="event-figure">
        <?php echo wp_get_attachment_image( $image1['id'], 'full' ); ?>
        <?php if ( !empty( $video_url['url'] ) ) { ?>
            <a class="event-figure__play play-btn" href="<?php echo esc_url( $video_url['url'] ); ?>"  data-toggle="modal">
                <svg width="24" height="28" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 14L6.95983e-07 27.8564L1.90735e-06 0.143554L24 14Z" fill="#FF6168" />
                </svg>
                <svg class="ripple-shape" viewBox="0 0 320 320">
                  <defs>
                    <circle id="circle-clip" cx="50%" cy="50%" r="25%" />
                    <clipPath id="avatar-clip">
                      <use href="#circle-clip" />
                    </clipPath>
                  </defs>

                  <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
                    <animate attributeName="r" values="25%;50%" dur="4s" repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" values="1;0" dur="4s" repeatCount="indefinite" />
                  </circle>

                  <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
                    <animate attributeName="r" values="25%;50%" dur="4s" begin="1s" repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" values="1;0" dur="4s" begin="1s" repeatCount="indefinite" />
                  </circle>

                  <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
                    <animate attributeName="r" values="25%;50%" dur="4s" begin="2s" repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" values="1;0" dur="4s" begin="2s" repeatCount="indefinite" />
                  </circle>

                  <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
                    <animate attributeName="r" values="25%;50%" dur="4s" begin="3s" repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" values="1;0" dur="4s" begin="3s" repeatCount="indefinite" />
                  </circle>
                </svg>
            </a>
        <?php } ?>
        
    </figure> 
</div>

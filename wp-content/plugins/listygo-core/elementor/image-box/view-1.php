<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/animated-image/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

extract( $data );

$line   = $line_image['url'];
$clip1  = $image_c1['url'];
$img1 = $image1['url'];
$clip2  = $image_c2['url'];
$img2 = $image2['url'];
$clip3  = $image_c3['url'];
$img3 = $image3['url'];

?>

<div class="latestListing-wrapper image-box-v1">
    <?php if ( !empty( $line ) ) { ?>
    <div class="section-element section-element--one">
        <?php echo wp_get_attachment_image( $line_image['id'], 'full' ); ?>
    </div>
    <?php } if ( !empty( $clip1 || $img1 || $title1 ) ) { 
        $attr1 = '';
        if ( !empty( $title_link['url'] ) ) {
            $attr1  = 'href="' . $data['title_link']['url'] . '"';
            $attr1 .= !empty( $data['title_link']['is_external'] ) ? ' target="_blank"' : '';
            $attr1 .= !empty( $data['title_link']['nofollow'] ) ? ' rel="nofollow"' : '';
        }
    ?>
    <div class="latestListing-item latestListing-item--one">
        <?php if ( !empty( $clip1 ) ) { ?>
        <div class="latestListing-item__clip">
            <?php echo wp_get_attachment_image( $image_c1['id'], 'full' ); ?>
        </div>
        <?php } if ( !empty( $img1 || $title1 ) ) { ?>
        <div class="latestListing-block">
            <?php if ( !empty( $img1 ) ) { ?>
                <figure class="latestListing-block__figure">
                    <?php if ( !empty( $title_link['url'] ) ) { ?>
                    <a  <?php echo $attr1; ?>>
                        <?php echo wp_get_attachment_image( $image1['id'], 'full' ); ?>
                    </a>
                    <?php } else {
                        echo wp_get_attachment_image( $image1['id'], 'full' );
                    } ?>
                </figure>
            <?php } if ( !empty( $title1 ) ) { ?>
                <div class="latestListing-block__content">
                    <h3 class="latestListing-block__name">
                        <?php if ( !empty( $title_link['url'] ) ) { ?>
                            <a  <?php echo $attr1; ?>><?php echo esc_html( $title1 ); ?></a>
                        <?php } else {
                            echo esc_html( $title1 );
                        } ?>
                    </h3>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <?php } if ( !empty( $clip2 || $img2 || $title2 ) ) { 
        $attr2 = '';
        if ( !empty( $title_link2['url'] ) ) {
            $attr2  = 'href="' . $data['title_link2']['url'] . '"';
            $attr2 .= !empty( $data['title_link2']['is_external'] ) ? ' target="_blank"' : '';
            $attr2 .= !empty( $data['title_link2']['nofollow'] ) ? ' rel="nofollow"' : '';
        }
    ?>
    <div class="latestListing-item latestListing-item--two">
        <?php if ( !empty( $clip2 ) ) { ?>
            <div class="latestListing-item__clip">
                <?php echo wp_get_attachment_image( $image_c2['id'], 'full' ); ?>
            </div>
            <?php } if ( !empty( $img2 || $title2 ) ) { ?>
            <div class="latestListing-block">
                <?php if ( !empty( $img2 ) ) { ?>
                    <figure class="latestListing-block__figure">
                        <?php if ( !empty( $title_link2['url'] ) ) { ?>
                        <a  <?php echo $attr2; ?>>
                            <?php echo wp_get_attachment_image( $image2['id'], 'full' ); ?>
                        </a>
                        <?php } else {
                            echo wp_get_attachment_image( $image2['id'], 'full' );
                        } ?>
                    </figure>
                <?php } if ( !empty( $title2 ) ) { ?>
                    <div class="latestListing-block__content">
                        <h3 class="latestListing-block__name">
                            <?php if ( !empty( $title_link2['url'] ) ) { ?>
                                <a  <?php echo $attr2; ?>><?php echo esc_html( $title2 ); ?></a>
                            <?php } else {
                                echo esc_html( $title2 );
                            } ?>
                        </h3>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php } if ( !empty( $clip3 || $img3 || $title3 ) ) {
        $attr3 = '';
        if ( !empty( $title_link3['url'] ) ) {
            $attr3  = 'href="' . $data['title_link3']['url'] . '"';
            $attr3 .= !empty( $data['title_link3']['is_external'] ) ? ' target="_blank"' : '';
            $attr3 .= !empty( $data['title_link3']['nofollow'] ) ? ' rel="nofollow"' : '';
        }
    ?>
    <div class="latestListing-item latestListing-item--three">
        <?php if ( !empty( $clip3 ) ) { ?>
            <div class="latestListing-item__clip">
                <?php echo wp_get_attachment_image( $image_c3['id'], 'full' ); ?>
            </div>
            <?php } if ( !empty( $img3 || $title3 ) ) { ?>
            <div class="latestListing-block">
                <?php if ( !empty( $img3 ) ) { ?>
                    <figure class="latestListing-block__figure">
                        <?php if ( !empty( $title_link3['url'] ) ) { ?>
                        <a  <?php echo $attr3; ?>>
                            <?php echo wp_get_attachment_image( $image3['id'], 'full' ); ?>
                        </a>
                        <?php } else {
                            echo wp_get_attachment_image( $image3['id'], 'full' );
                        } ?>
                    </figure>
                <?php } if ( !empty( $title3 ) ) { ?>
                    <div class="latestListing-block__content">
                        <h3 class="latestListing-block__name">
                            <?php if ( !empty( $title_link3['url'] ) ) { ?>
                                <a  <?php echo $attr3; ?>><?php echo esc_html( $title3 ); ?></a>
                            <?php } else {
                                echo esc_html( $title3 );
                            } ?>
                        </h3>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/testimonial/view-2.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0.19
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;
if ($data['layout'] == 'grid') {

?>
<div class="row row-cols-lg-<?php echo esc_attr( $data['cols'] ); ?> row-cols-sm-2 row-cols-1 testimonial-layout-3 grid-layout">
    <?php foreach ( $data['testimonials'] as $item ) { ?>
    <div class="col">
        <div class="testimonial-content testimonial-content__v3">
            <div class="testi-block__header">
                <div class="testi-block__poster">
                    <div class="testi-block__poster__info">
                        <h3 class="testi-block__poster__name"><?php echo wp_kses_stripslashes( $item['testi_name'] ); ?></h3>
                        <span class="testi-block__poster__designation"><?php echo wp_kses_stripslashes( $item['testi_desig'] ); ?></span>
                    </div>
                </div>
            </div>
            <div class="testi-block__content">
                <div class="rating" data-swiper-parallax-x="-100" data-swiper-parallax-duration="1000">
                    <?php echo Helper::rt_rating( $item['rating'] ); ?>
                </div>
                <div class="tes-desc" data-swiper-parallax-x="-150" data-swiper-parallax-duration="1200"><?php echo wp_kses_stripslashes( $item['content'] ); ?></div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php } else { ?>

<div class="testimonial-layout-3">
    <div class="swiper-container3 swiper-container">
        <div class="swiper-wrapper" data-carousel-options="<?php echo esc_attr( $data['swiper_data'] ); ?>">
            <?php foreach ( $data['testimonials'] as $slide ) { ?>
                <div class="swiper-slide">
                    <div class="testimonial-content testimonial-content__v2">
                        <div class="testi-block__header">
                            <div class="testi-block__poster">
                                <div class="testi-block__poster__thumb">
                                    <?php echo wp_get_attachment_image( $slide['picture']['id'], 'thumbnail' ); ?>
                                </div>
                                <div class="testi-block__poster__info">
                                    <h3 class="testi-block__poster__name"><?php echo wp_kses_stripslashes( $slide['testi_name'] ); ?></h3>
                                    <span class="testi-block__poster__designation"><?php echo wp_kses_stripslashes( $slide['testi_desig'] ); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="testi-block__content">
                            <div class="rating" data-swiper-parallax-x="-100" data-swiper-parallax-duration="1000">
                                <?php echo Helper::rt_rating( $slide['rating'] ); ?>
                            </div>
                            <div class="tes-desc" data-swiper-parallax-x="-150" data-swiper-parallax-duration="1200"><?php echo wp_kses_stripslashes( $slide['content'] ); ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if ($data['slider_dots']) { ?>
            <div class="swiper-pagination"></div>
        <?php } if ( $data['slider_arrow'] ) { ?>
            <div class="nav-wrap">
                <div class="swiper-button-prev"><i class="fas fa-chevron-left"></i></div>
                <div class="swiper-button-next"><i class="fas fa-chevron-right"></i></div>
            </div>
        <?php } ?>
    </div>
</div>

<?php } ?>
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
<div class="row row-cols-lg-<?php echo esc_attr( $data['cols'] ); ?> row-cols-sm-2 row-cols-1 testimonial-layout testimonial-layout-2 grid-layout">
    <?php foreach ( $data['testimonials'] as $item ) { ?>
    <div class="col testimonial-content-wrap">
        <div class="testimonial-content">
            <div class="rating">
                <?php echo Helper::rt_rating( $item['rating'] ); ?>
            </div>
            <div class="tes-desc"><?php echo wp_kses_stripslashes( $item['content'] ); ?></div>
            <div class="poster-info">
                <h3 class="item-title"><?php echo wp_kses_stripslashes( $item['testi_name'] ); ?></h3>
                <h4 class="item-designation"><?php echo wp_kses_stripslashes( $item['testi_desig'] ); ?></h4>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php } else { ?>

<div class="swiper-container2 testimonial-layout testimonial-layout-2" data-scroll>    
    <div class="swiper-container slider-content testimonial-content-wrap">
        <div class="swiper-wrapper" data-carousel-options="<?php echo esc_attr( $data['swiper_data'] ); ?>">
            <?php foreach ( $data['testimonials'] as $slide ) { ?>
            <div class="swiper-slide">
                <div class="testimonial-content">
                    <div class="rating" data-swiper-parallax-x="-100" data-swiper-parallax-duration="1000">
                        <?php echo Helper::rt_rating( $slide['rating'] ); ?>
                    </div>
                    <div class="tes-desc" data-swiper-parallax-x="-150" data-swiper-parallax-duration="1100"><?php echo wp_kses_stripslashes( $slide['content'] ); ?></div>
                    <div class="poster-info">
                        <h3 class="item-title" data-swiper-parallax-x="-200" data-swiper-parallax-duration="1200"><?php echo wp_kses_stripslashes( $slide['testi_name'] ); ?></h3>
                        <h4 class="item-designation" data-swiper-parallax-x="-250" data-swiper-parallax-duration="1300"><?php echo wp_kses_stripslashes( $slide['testi_desig'] ); ?></h4>
                        <div class="shape-icon" data-swiper-parallax-x="-300" data-swiper-parallax-duration="1400">
                            <?php echo Helper::shape_icon(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php if ($data['slider_dots']){ ?>
        <div class="swiper-pagination"></div>
    <?php } if ($data['slider_arrow']){ ?>
    <div class="nav-wrap">
        <div class="swiper-button-prev"><i class="fas fa-chevron-left"></i></div>
        <div class="swiper-button-next"><i class="fas fa-chevron-right"></i></div>
    </div>
    <?php } ?>
</div>

<?php } ?>
<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;
$socials = Helper::socials();
    
?>
<!-- Off Canvas Side Information -->
<div class="offcanvas-menu-wrap" id="offcanvas-wrap" data-position="right">
   <div class="close-btn offcanvas-close close-hover">
        <span><?php esc_html_e( 'Close', 'listygo' ); ?></span>
        <span class="animation-shape-lines">
            <span class="animation-shape-line eltdf-line-1"></span>
            <span class="animation-shape-line eltdf-line-2"></span>
        </span>
   </div>
    <div class="offcanvas-content">
      <div class="offcanvas-logo">
         <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-logo rt-anima rt-anima--one">
            <?php echo Helper::rt_the_logo_light(); ?>
         </a>
      </div>
      <div class="offcanvas-info">
         <?php if (!empty(RDTListygo::$options['offcanvas_title'])) { ?>
         <span class="title rt-anima rt-anima--two"><?php echo esc_html( RDTListygo::$options['offcanvas_title'] ); ?></span>
         <?php } if (!empty(RDTListygo::$options['offcanvas_date'] || RDTListygo::$options['offcanvas_time'])) { ?>
         <p class="text rt-anima rt-anima--three">
            <?php echo esc_html( RDTListygo::$options['offcanvas_date'] ); ?>
            <br />
            <span><?php echo esc_html( RDTListygo::$options['offcanvas_time'] ); ?></span>
         </p>
         <?php } if (!empty(RDTListygo::$options['offcanvas_location'] || RDTListygo::$options['offcanvas_address'])) { ?>
         <p class="text rt-anima rt-anima--four">
            <?php echo esc_html( RDTListygo::$options['offcanvas_location'] ); ?>
            <br />
            <?php echo esc_html( RDTListygo::$options['offcanvas_address'] ); ?>
         </p>
         <?php } if (!empty(RDTListygo::$options['offcanvas_contact_no'] )) { ?>
         <p class="text rt-anima rt-anima--five"><a href="tel:<?php echo esc_attr( RDTListygo::$options['offcanvas_contact_no'] ); ?>"><?php echo esc_html( RDTListygo::$options['offcanvas_contact_no'] ); ?></a></p>
         <?php } if (!empty(RDTListygo::$options['offcanvas_btn_link'] )) { ?>
         <a class="offcanvas-info__link rt-anima rt-anima--six" href="<?php echo esc_url( RDTListygo::$options['offcanvas_btn_link'] ); ?>"><?php echo esc_html( RDTListygo::$options['offcanvas_btn_txt'] ); ?></a>
         <?php } ?>
      </div>
      <?php if ( !empty( $socials ) ) { ?>
      <div class="offcanvas-social rt-anima rt-anima--seven social-block">
         <ul>
            <?php foreach ( $socials as $social ): ?>
               <li class="<?php echo esc_attr( $social['class'] ); ?>-wrap">
                  <a href="<?php echo esc_url( $social['url'] ); ?>" class="<?php echo esc_attr( $social['class'] ); ?>" target="_blank">
                     <i class="<?php echo esc_html( $social['icon'] ); ?>"></i>
                  </a>
               </li>
            <?php endforeach; ?>
         </ul>
      </div>
      <?php } ?>
   </div>
</div>
<!-- Off Canvas Side Information End -->
<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
use radiustheme\listygo\Helper;
use radiustheme\listygo\Listing_Functions;

$hcs = $data['highlight_categories'];
?>

<!-- Categories -->
<div class="hero-categories-wrap v2">
   <?php
   $terms = $hcs;
   if(!empty(Helper::listing_categories_slug())){ ?>
      <div class="hero-categories hero-categories--style3 swiper-container4 swiper-container">
         <div class="swiper-wrapper" data-carousel-options="<?php echo esc_attr( $data['swiper_data'] ); ?>">
            <?php    
               foreach ($terms as $key => $value) {
                  if (!empty(get_term( $value )->term_id)) {
                     $term_id = get_term( $value )->term_id;
                     $term_icon = get_term_meta( $term_id, '_rtcl_icon', true );

                     $count = $this->rt_term_post_count($term_id);

                     if ($count < 10 && $count > 0 ) {
                        $count = '<span class="count">(0'.$count.')</span>';
                     } else {
                        $count = '<span class="count">('.$count.')</span>';
                     }
            ?>
            <div class="swiper-slide">
               <a href="<?php echo esc_url( get_term_link( get_term( $value ), get_term( $value )->name ) ); ?>" class="hero-categoriesBlock hero-categoriesBlock--style3">
                  <span class="cat-icon-img">
                     <?php echo wp_kses_post( Listing_Functions::listygo_cat_icon( $term_id, $data['icon_type'] ) ); ?>
                     <i class="listygo-rt-icon-login-icon"></i>
                  </span>
                  <span class="cat-name-count cat-style">
                     <?php echo esc_html( get_term( $value )->name ); ?>
                     <?php echo $count; ?>
                  </span>

               </a>
            </div>
            <?php 
                  } 
               } 
            ?>
         </div>
      </div>
   <?php } ?>
   <!-- Categories End -->
   <?php if ($data['slider_dots']) { ?>
   <div class="swiper-pagination"></div>
   <?php } if ( $data['slider_arrow'] ) { ?>
   <div class="nav-wrap">
       <div class="swiper-button-prev"></div>
       <div class="swiper-button-next"></div>
   </div>
   <?php } ?>
</div>

<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/posts/view-1.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;

extract( $data );

$post_id = get_the_ID();
$excerpt = $excerpt;

$pcats = $post_cat;
$padmin = $post_admin;
$pdate = $post_date;

$cols = $cols;

$grid_query= null;
if ( $query_type == 'cats' && !empty( $postbycats ) ) {
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $number,
        'orderby'        => $orderby,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $postbycats
            )
        ),
    );
} elseif ( $query_type == 'titles' && !empty( $postbytitle ) ) {
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $number,
        'orderby'        => $orderby,
        'taxonomy'       => 'category',
        'post__in'       => $postbytitle
    );
} else {
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $number,
        'orderby'        => $orderby,
        'offset'         => $offset,
        'taxonomy'       => 'category'
    );
}

$grid_query = new \WP_Query( $args );

if ( $grid_query->have_posts() ): 

?>

<div class="row row-cols-lg-<?php echo esc_attr( $cols ); ?> row-cols-sm-2 row-cols-1 elementor-addon justify-content-center">
   <?php 
      while ( $grid_query->have_posts() ) : $grid_query->the_post(); 
      $post_id = get_the_ID();

      if ( has_post_thumbnail() ){
         $thumb_img = '';
      } else {
         $thumb_img = 'no-image';
      }
   ?>
   <div class="col">
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout1 blog-list '.$thumb_img ); ?>>
         <?php if ( has_post_thumbnail() ){ ?>
            <div class="post-thumb">
               <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('rtcl-gallery'); ?></a>
               <?php if ($post_cat) { ?>
                  <div class="blog-block__tag">
                     <?php the_category( ', ' ); ?>
                  </div>
               <?php } ?>
            </div>
            <?php } ?>
            <div class="post-content">
               <?php echo Helper::listygo_get_post_meta( $post_id, $padmin, $pdate, $pcom = NULL, $pcats = NULL ); ?>
               <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
               <div class="content">
                  <p><?php echo Helper::listygo_excerpt( $excerpt ); ?></p>
               </div>
               <div class="btn-wrap btn-v2">
                    <a href="<?php the_permalink(); ?>" class="item-btn">
                        <span class="btn__icon">
                            <?php echo Helper::btn_right_icon(); ?>
                        </span>
                        <span class="btn__text"><?php esc_html_e( 'Read More', 'listygo' ); ?></span>
                        <span class="btn__icon">
                            <?php echo Helper::btn_right_icon(); ?>
                        </span>
                    </a>
               </div>
            </div>
            <?php if ( is_sticky() ) {
                echo '<sup class="meta-featured-post"> <i class="fas fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'listygo' ) . ' </sup>';
            } ?>
        </article>

    </div>
    <?php endwhile; wp_reset_postdata(); ?> 
</div>
<?php endif; ?>



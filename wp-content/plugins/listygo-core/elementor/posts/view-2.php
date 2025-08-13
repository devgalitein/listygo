<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/posts/view-2.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;

extract( $data );

$cols = $cols;
$pcats = $post_cat;
$padmin = $post_admin;
$pdate = $post_date;

if ($cols > 1) {
    $image_size = 'listygo-size-3';
} else {
    $image_size = 'full';
}

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

<div class="row row-cols-lg-<?php echo esc_attr( $cols ); ?> row-cols-sm-2 row-cols-1 justify-content-center">
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
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-layout-2 blog-grid-box '.$thumb_img  ); ?>>

            <div class="blog-block">
                <?php if ( has_post_thumbnail() ){ ?>
                    <figure class="blog-block__figure">
                        <a class="blog-block__link--image" href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( $image_size ); ?>
                        </a>
                    </figure>
                <?php } ?>
                <div class="blog-block__content">
                    <?php if ($pcats) { ?>
                    <div class="blog-block__tag">
                        <?php the_category( ', ' ); ?>
                    </div>
                    <?php } ?>
                    <h3 class="blog-block__heading text-white bold-underline">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <div class="blog-block__meta">
                        <ul>
                            <?php if ($padmin) { ?>
                            <li class="entry-admin">
                                <?php echo Helper::user_icon(); ?>
                                <span>
                                    <span><?php esc_html_e( 'by', 'listygo-core' ); ?></span> 
                                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                                </span>
                            </li>
                            <?php } if ($pdate) { ?>
                            <li class="entry-date">
                                <?php echo Helper::calendar_icon(); ?>
                                <span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

        </article>

    </div>
    <?php endwhile; wp_reset_postdata(); ?> 
</div>
<?php endif; ?>
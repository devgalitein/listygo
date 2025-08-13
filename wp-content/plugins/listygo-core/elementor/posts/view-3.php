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
$gutter = '';
$post_id = get_the_ID();
$excerpt = $excerpt;

$padmin = $post_admin;
$pcom = $post_comnt;
$cols = $cols;

if ($gutters != 'on') {
    $gutter = 'no-gutters';
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

<div class="row row-cols-lg-<?php echo esc_attr( $cols ); ?> row-cols-sm-2 row-cols-1 <?php echo esc_attr( $gutter ); ?> elementor-addon justify-content-center">
    <?php 
        while ( $grid_query->have_posts() ) : $grid_query->the_post(); 
        $post_id = get_the_ID();

        $comments_number = get_comments_number($post_id);
        $comments_text   = sprintf( _n( '%s', '%s', $comments_number, 'listygo' ), number_format_i18n( $comments_number ) );

        if ( has_post_thumbnail() ){
            $thumb_img = '';
        } else {
            $thumb_img = 'no-image';
        }

        $post_meta  = $padmin || $pcom ? true : false;
    ?>
    <div class="col">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout1 v3 '.$thumb_img ); ?>>
            <?php if ( has_post_thumbnail() ){ ?>
            <div class="post-thumb">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('rtcl-gallery'); ?></a>
                <?php if ($post_date) { ?>
                    <div class="blog-block__date">
                        <?php echo get_the_date('d M'); ?>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
            <div class="post-content">
                <?php if ($post_cat) { ?>
                    <div class="blog-block__tag">
                        <?php the_category( ', ' ); ?>
                    </div>
                <?php } ?>
                <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="content">
                    <p><?php echo Helper::listygo_excerpt( $excerpt ); ?></p>
                </div>
                <?php if ( $post_meta ){ ?>
                <ul class="entry-meta"> 
                    <?php if ( $padmin ){ ?>     
                    <li class="entry-admin">
                        <?php echo Helper::user_icon2(); ?>
                        <span>
                            <span><?php esc_html_e( 'by', 'listygo-core' ); ?></span> 
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                        </span>
                    </li>
                    <?php } if ( $pcom ){ ?> 
                    <li class="entry-admin">
                        <span class="meta-icon"><?php echo Helper::comments_icon(); ?></span><?php echo wp_kses_stripslashes( $comments_text ); ?>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            <?php if ( is_sticky() ) {
                echo '<sup class="meta-featured-post"> <i class="fas fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'listygo' ) . ' </sup>';
            } ?>
        </article>

    </div>
    <?php endwhile; wp_reset_postdata(); ?> 
</div>
<?php endif; ?>



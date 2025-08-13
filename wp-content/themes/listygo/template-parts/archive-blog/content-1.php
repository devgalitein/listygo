<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

$post_id = get_the_ID();
$excerpt_length = RDTListygo::$options['excerpt_length'];
$padmin = RDTListygo::$options['post_meta_admin'];
$pdate = RDTListygo::$options['post_meta_date'];
$pcom = RDTListygo::$options['post_meta_comnts'];
$pcats = RDTListygo::$options['post_meta_cats'];

if ( has_post_thumbnail() ){
    $thumb_img = '';
} else {
    $thumb_img = 'no-image';
}
global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout1 blog-list '.$thumb_img ); ?>>
    <?php if ( has_post_thumbnail() ){ ?>
    <div class="post-thumb">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
        <?php if ($pcats) { ?>
            <div class="blog-block__tag">
                <?php the_category( ' ' ); ?>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="post-content">
        <?php echo Helper::listygo_get_post_meta( $post_id, $padmin, $pdate, $pcom, $pcats = NULL ); ?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="content">
            <p><?php echo Helper::listygo_excerpt( $excerpt_length ); ?></p>
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
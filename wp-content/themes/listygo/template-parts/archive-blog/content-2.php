<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

$padmin = RDTListygo::$options['post_meta_admin'];
$pdate = RDTListygo::$options['post_meta_date'];
$pcats = RDTListygo::$options['post_meta_cats'];
$cols = RDTListygo::$options['blog_grid'];
if ( has_post_thumbnail() ){
    $thumb_img = '';
} else {
    $thumb_img = 'no-image';
}
if ($cols > 1) {
    $image_size = 'listygo-size-3';
} else {
    $image_size = 'full';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout2 blog-grid '.$thumb_img ); ?>>
    <div class="blog-block">
        <?php if ( has_post_thumbnail() ){ ?>
            <figure class="blog-block__figure">
                <a class="blog-block__link--image" href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail($image_size); ?>
                </a>
            </figure>
        <?php } ?>
        <div class="blog-block__content">
            <?php if ($pcats) { ?>
            <div class="blog-block__tag">
                <?php the_category( ' ' ); ?>
            </div>
            <?php } ?>
            <h2 class="blog-block__heading text-white bold-underline">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php if ($padmin || $pdate) { ?>
            <div class="blog-block__meta">
                <ul>
                    <?php if ($padmin) { ?>
                    <li>
                        <?php echo Helper::user_icon(); ?>
                        <span>
                            <span><?php esc_html_e( 'by', 'listygo' ); ?></span> 
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                        </span>
                    </li>
                    <?php } if ( $pdate ) { ?>
                    <li>
                        <?php echo Helper::calendar_icon(); ?>
                        <span><?php the_time( get_option( 'date_format' ) ); ?></span>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</article>
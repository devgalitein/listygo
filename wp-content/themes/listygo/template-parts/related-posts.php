<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;

$rt_post_cat = wp_get_object_terms( $post->ID, 'category', [ 'fields' => 'ids' ] );
// arguments
$args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'tax_query'      => [
		[
			'taxonomy' => 'category',
			'field'    => 'id',
			'terms'    => $rt_post_cat,
		],
	],
	'post__not_in'   => [ $post->ID ],
];

$query = new \WP_Query( $args );
if ( $query->have_posts() ) :
?>

<div class="related-post-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="related-listing-header">
                    <div class="section-heading">
                        <h4><?php echo esc_html__( 'Related Blogs', 'listygo' ); ?></h4>    
                    </div>
                </div>
                <div class="section-title-wrapper">
                    <div class="row">
            			<?php
            				while ( $query->have_posts() ) : 
                                $query->the_post();
                                if ( has_post_thumbnail() ){
                                    $thumb_img = '';
                                } else {
                                    $thumb_img = 'no-image';
                                }
                        ?>
                        <div class="col-lg-4 col-md-6">
            				<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout2 blog-grid '.$thumb_img ); ?>>
                                <div class="blog-block">
                                    <?php if ( has_post_thumbnail() ){ ?>
                                        <figure class="blog-block__figure">
                                            <a class="blog-block__link--image" href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('listygo-size-3'); ?>
                                            </a>
                                        </figure>
                                    <?php } ?>
                                    <div class="blog-block__content">
                                        <div class="blog-block__tag">
                                            <?php the_category( ', ' ); ?>
                                        </div>
                                        <h3 class="blog-block__heading text-white bold-underline">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="blog-block__meta">
                                            <ul>
                                                <li>
                                                    <?php echo Helper::user_icon(); ?>
                                                    <span>
                                                        <span><?php esc_html_e( 'by', 'listygo' ); ?></span> 
                                                        <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
                                                    </span>
                                                </li>
                                                <li>
                                                    <?php echo Helper::calendar_icon(); ?>
                                                    <span><?php the_time( get_option( 'date_format' ) ); ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
            				endwhile;
                            wp_reset_postdata();
            			?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
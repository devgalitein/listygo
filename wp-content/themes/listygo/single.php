<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;

?>

<?php get_header(); ?>

<!--=====================================-->
<!--=         Blog Details Start    	=-->
<!--=====================================-->
<section class="blog-details-page bg--accent">
    <div class="container">
        <?php do_action( 'listygo_before_content_ad' ); ?>
        <div class="row justify-content-center gutters-40">
            <div class="<?php Helper::the_layout_class(); ?>">
                <div id="main" class="site-main">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content-single' ); ?>
					<?php endwhile; 
                    wp_reset_postdata();
                    ?>
				</div>
            </div>
            <?php Helper::listygo_sidebar(); ?>
        </div>
        <?php do_action( 'listygo_after_content_ad' ); ?>
    </div>
    <?php
        if ( RDTListygo::$options['blog_related_post'] ) {
            get_template_part( 'template-parts/related', 'posts' );
        }
    ?>
</section>
<?php get_footer(); ?>
<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;
?>
<?php get_header(); ?>

<?php 
$blog_style = RDTListygo::$options['blog_style'];
$cols = RDTListygo::$options['blog_grid'];
$cols2 = RDTListygo::$options['blog_medium_grid'];

?>

<!--=====================================-->
<!--=              Blog Start    	    =-->
<!--=====================================-->
<section class="blog-posts-layout bg--accent">
  <div class="container">
  	<?php do_action( 'listygo_before_content_ad' ); ?>
  	<div class="row justify-content-center gutters-40">
    	<div class="<?php Helper::the_layout_class(); ?>">
    		<?php if ( have_posts() ) : ?>
			<?php
				if ( ( is_home() || is_archive() ) ) {
					echo '<div class="row row-cols-1 row-cols-lg-'.esc_attr( $cols ).' row-cols-md-'.esc_attr( $cols2 ).'">';
					while ( have_posts() ) : the_post();
						echo '<div class="col">';
						get_template_part( 'template-parts/archive-blog/content',  $blog_style ); 
						echo '</div>';
					endwhile;
					echo '</div>';
					Helper::pagination();
				}
				else {
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content' );
					endwhile;
				}
			?>
			<?php else: ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
        </div>
        <?php Helper::listygo_sidebar(); ?>
  	</div>
	<?php do_action( 'listygo_after_content_ad' ); ?>
  </div>
</section>

<?php get_footer(); ?>

<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTheme;
use radiustheme\listygo\Helper;

?>
<?php get_header(); ?>

<!--=====================================-->
<!--=         Search page wrapper    	=-->
<!--=====================================-->
<section class="section blog-fluid-grid search-page bg--accent">
    <div class="container">
    	<div class="row justify-content-center gutters-40">
	    	<div class="<?php Helper::the_layout_class(); ?>">
	    		<?php if ( have_posts() ) :?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content', 'search' ); ?>
					<?php endwhile; ?>
					<?php Helper::pagination(); ?>
				<?php else:?>
					<?php get_template_part( 'template-parts/content', 'none' );?>
				<?php endif;?>
	        </div>
	        <?php Helper::listygo_sidebar(); ?>
    	</div>
    </div>
</section>
<?php get_footer(); ?>
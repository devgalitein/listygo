<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\Helper;
?>
<?php get_header(); ?>
<div id="primary" class="page-details-wrap-layout bg--accent">
	<div class="container">
		<?php do_action( 'listygo_before_content_ad' ); ?>
		<div class="row justify-content-center gutters-40">		
			<div class="<?php Helper::the_layout_class(); ?>">		
				<main id="main" class="site-main">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content', 'page' ); ?>
					<?php endwhile; ?>
				</main>
			</div>
			<?php Helper::listygo_sidebar(); ?>
		</div>
		<?php do_action( 'listygo_after_content_ad' ); ?>
	</div>
</div>
<?php get_footer(); ?>
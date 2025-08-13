<?php
/**
 * Template Name: Blank Page With Container
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

?>
<?php get_header(); ?>
<div class="boxed-page-wrap">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
</div>
<?php get_footer(); ?>
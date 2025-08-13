<?php
/**
 * Template Name: Blank Page
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo;
?>
<?php get_header(); ?>
<div id="primary" class="content-area">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>
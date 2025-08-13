<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ): ?>
		<div class="page-thumbnail"><?php the_post_thumbnail( 'rdtheme-size1' );?></div>
	<?php endif; ?>
	<div class="page-content-main">
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		<?php wp_link_pages( array(
			'before'      => '<div class="listygo-page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'listygo' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			) );
		?>
	</div>
	<?php
		if ( comments_open() || get_comments_number() ){ ?>
			<div class="blog-comment-form">
				<?php comments_template(); ?>  
			</div>
		<?php }
	?>
</article>
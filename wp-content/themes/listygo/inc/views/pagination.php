<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

echo '<div class="pagination"><ul>' . "\n";

/**	Previous Post Link */
if ( get_previous_posts_link() )
	printf( '<li class="prev">%s</li>' . "\n", get_previous_posts_link( '<i class="fa-solid fa-angle-left" aria-hidden="true"></i>' ) );

/**	Link to first page, plus ellipses if necessary */
if ( ! in_array( 1, $links ) ) {
	$class = 1 == $paged ? ' class="active"' : '';

	printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

	if ( ! in_array( 2, $links ) )
		echo '<li>...</li>';
}

/**	Link to current page, plus 2 pages in either direction if necessary */
sort( $links );
foreach ( (array) $links as $link ) {
	$class = $paged == $link ? ' class="active"' : '';
	printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
}

/**	Link to last page, plus ellipses if necessary */
if ( ! in_array( $max, $links ) ) {
	$class = $paged == $max ? ' class="active"' : '';
	printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
}

/**	Next Post Link */
if ( get_next_posts_link() )
	printf( '<li class="next">%s</li>' . "\n", get_next_posts_link( '<i class="fa-solid fa-angle-right" aria-hidden="true"></i>' ) );

echo '</ul></div>' . "\n";
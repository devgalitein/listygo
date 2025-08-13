<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;
$prefix = LISTYGO_THEME_PREFIX_VAR;

if ( is_404() ) {
	$rdtheme_title = RDTListygo::$options['error_page_title'];
	$rdtheme_title = $rdtheme_title . get_search_query();
}
elseif ( is_search() ) {
	$rdtheme_title = esc_html__( 'Search Results for : ', 'listygo' ) . get_search_query();
}
elseif ( is_home() ) {
	if (!empty(RDTListygo::$options['blog_breadcrumb_title'])) {
		$rdtheme_title = RDTListygo::$options['blog_breadcrumb_title'];
	} elseif ( get_option( 'page_for_posts' ) ) {
		$rdtheme_title = get_the_title( get_option( 'page_for_posts' ) );
	}
	else {
		$rdtheme_title = apply_filters( "{$prefix}_blog_title", esc_html__( 'All Posts', 'listygo' ) );
	}
}
elseif ( is_archive() ) {
	if ( is_post_type_archive( "rtcl_listing" ) ) {
		$rdtheme_title = RDTListygo::$options['listing_archive_title'];
	} else {
		$rdtheme_title = get_the_archive_title();
	}
} elseif (is_singular( 'rtcl_listing' )) {
	$rdtheme_title  = esc_html__( 'Listing Details', 'listygo' );
} elseif (is_single()) {
	$rdtheme_title  = get_the_title();
} else {
	$id                        = $post->ID;
	$listygo_custom_page_title = get_post_meta( $id, 'listygo_custom_page_title', true );
	if (!empty($listygo_custom_page_title)) {
		$rdtheme_title = get_post_meta( $id, 'listygo_custom_page_title', true );
	} else { 
		$rdtheme_title = get_the_title();                 
	}
}

$bgimg = RDTListygo::$bgimg;

?>

<section class="breadcrumbs-banner">
   <div class="container">
      <div class="breadcrumbs-area">
        <h1 class="heading-title"><?php echo wp_kses( $rdtheme_title, 'alltext_allow' ); ?></h1>
		<?php
			if ( RDTListygo::$has_breadcrumb == '1' || RDTListygo::$has_breadcrumb === "on" ) {
				get_template_part( 'template-parts/content', 'breadcrumb' ); 
			}
		?>
      </div>
   </div>
</section>

<!--=====================================-->
<!--=         Inner Banner Start    	=-->
<!--=====================================-->
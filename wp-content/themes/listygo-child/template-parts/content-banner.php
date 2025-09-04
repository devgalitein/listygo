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

// Get widget content
ob_start();
dynamic_sidebar('archive-banner');
$widget_content = ob_get_clean();

// Extract image URL from widget HTML
$image_url = '';
if (preg_match('/wp-image-([0-9]+)/', $widget_content, $matches)) {
    $attachment_id = intval($matches[1]);
    $image_url = wp_get_attachment_image_url($attachment_id, 'full');
}

if (!$image_url && preg_match('/<img[^>]+src="([^"]+)"/i', $widget_content, $matches)) {
    $image_url = $matches[1];
}

$text_content = '';
if (preg_match('/<div[^>]*class="[^"]*textwidget[^"]*"[^>]*>(.*?)<\/div>/is', $widget_content, $matches)) {
    $text_content = trim($matches[1]); // Keep HTML formatting
}

if ( is_archive() && !is_category() && !is_tag() ) {

    $listing_category_name = '';
    $listing_location_name = '';

    // If we are on a category archive
    if ( is_tax( 'rtcl_category' ) ) {
        $term = get_queried_object();
        $listing_category_name = $term->name;
    }

    // If we are on a location archive
    if ( is_tax( 'rtcl_location' ) ) {
        $term = get_queried_object();
        $listing_location_name = $term->name;
    }

    // If we have both in query vars (when using multiple taxonomy filters)
    if ( get_query_var('rtcl_category') ) {
        $term = get_term_by( 'slug', get_query_var('rtcl_category'), 'rtcl_category' );
        if ( $term && ! is_wp_error($term) ) {
            $listing_category_name = $term->name;
        }
    }
    if ( get_query_var('rtcl_location') ) {
        $term = get_term_by( 'slug', get_query_var('rtcl_location'), 'rtcl_location' );
        if ( $term && ! is_wp_error($term) ) {
            $listing_location_name = $term->name;
        }
    }

    // Determine if we are on a filtered archive
    $is_filtered_archive = false;

    // Check taxonomy archives or query vars
    if ( is_tax('rtcl_category') || is_tax('rtcl_location')
        || get_query_var('rtcl_category')
        || get_query_var('rtcl_location') ) {
        $is_filtered_archive = true;
    }
    ?>
    <section class="banner" style="background-image: url('<?php echo esc_url($image_url); ?>');";>
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-1 banner-content">
                   <?php if ( $is_filtered_archive ) { ?>
                    <h1 class="m-0">
                        <?php
                        if ( $listing_category_name && $listing_location_name ) {
                            echo wp_kses_post( "Best <span class='theme-primary-color text-capitalize fw-bold'>{$listing_category_name}</span><br> In {$listing_location_name}" );
                        } elseif ( $listing_category_name ) {
                            echo esc_html( "Best {$listing_category_name}" );
                        } elseif ( $listing_location_name ) {
                            echo wp_kses_post( "Best <span class='theme-primary-color text-capitalize fw-bold'>Doctors</span><br> In {$listing_location_name}" );
                        }
                        ?>
                    </h1>
                    <?php } else {
                       echo $text_content;
                   }
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php
}
if (is_page('best-listing') || is_page('city-listings') ) {
    ?>
    <section class="banner" style="background-image: url('<?php echo esc_url($image_url); ?>');";>
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-1 banner-content">
                    <?php
                        echo $text_content;
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php
} elseif ( !is_archive() || is_tag() || is_category()) { ?>
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

<?php } ?>

<!--=====================================-->
<!--=         Inner Banner Start    	=-->
<!--=====================================-->
<?php
/* ---------------------------------------------------
* Theme: Listygo - WordPress Theme
* Author: RadiusTheme
* Author URI: https://www.radiustheme.com/
  -------------------------------------------------- */

use radiustheme\listygo\Helper;

function listygo_theme_enqueue_styles(){

	$parent_style = 'parent-style';

	if(!wp_style_is( $parent_style, $list = 'enqueued' )){
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array() );
	}
	wp_enqueue_style('child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style )
	);
    wp_enqueue_style( 'custom-css', get_stylesheet_directory_uri() . '/custom.css', array(), '' );
	wp_enqueue_script( 'child-custom', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), '', true );

}
add_action('wp_enqueue_scripts', 'listygo_theme_enqueue_styles');

add_action('add_meta_boxes', function () {
    add_meta_box(
        'rtcl_listing_logo_meta',
        __('Listing Logo', 'listygo'),
        'rtcl_listing_logo_meta_callback',
        'rtcl_listing',
        'side',
        'default'
    );
});

function rtcl_listing_logo_meta_callback($post) {
    // Detect which meta key to use based on existing data
    $logo_array = get_post_meta($post->ID, 'listing_logo', true);
    $logo_img   = get_post_meta($post->ID, 'listing_logo_img', true);

    if (!empty($logo_array) && is_array($logo_array)) {
        $meta_key = 'listing_logo';
        $logo_id  = isset($logo_array[0]) ? intval($logo_array[0]) : '';
    } else {
        $meta_key = 'listing_logo_img';
        $logo_id  = intval($logo_img);
    }

    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';

    wp_nonce_field('rtcl_listing_logo_meta_save', 'rtcl_listing_logo_meta_nonce');
    ?>
    <div>
        <img id="rtcl-listing-logo-preview" src="<?php echo esc_url($logo_url); ?>" style="max-width:100%; <?php echo $logo_url ? '' : 'display:none;'; ?>" />
        <input type="hidden" id="rtcl-listing-logo-id" name="<?php echo esc_attr($meta_key); ?><?php echo ($meta_key === 'listing_logo') ? '[]' : ''; ?>" value="<?php echo esc_attr($logo_id); ?>" />
        <p>
            <button type="button" class="button" id="rtcl-listing-logo-upload"><?php _e('Select Logo', 'listygo'); ?></button>
            <button type="button" class="button" id="rtcl-listing-logo-remove" style="<?php echo $logo_url ? '' : 'display:none;'; ?>"><?php _e('Remove', 'listygo'); ?></button>
        </p>
    </div>

    <script>
        jQuery(document).ready(function($){
            var frame;
            $('#rtcl-listing-logo-upload').on('click', function(e){
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({
                    title: '<?php _e("Select or Upload Listing Logo", "listygo"); ?>',
                    button: { text: '<?php _e("Use this logo", "listygo"); ?>' },
                    multiple: false
                });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#rtcl-listing-logo-id').val(attachment.id);
                    $('#rtcl-listing-logo-preview').attr('src', attachment.url).show();
                    $('#rtcl-listing-logo-remove').show();
                });
                frame.open();
            });

            $('#rtcl-listing-logo-remove').on('click', function(){
                $('#rtcl-listing-logo-id').val('');
                $('#rtcl-listing-logo-preview').hide();
                $(this).hide();
            });
        });
    </script>
    <?php
}

add_action('save_post_rtcl_listing', function ($post_id) {
    if (!isset($_POST['rtcl_listing_logo_meta_nonce']) ||
        !wp_verify_nonce($_POST['rtcl_listing_logo_meta_nonce'], 'rtcl_listing_logo_meta_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save for listing_logo (array) or listing_logo_img (single)
    if (isset($_POST['listing_logo']) && is_array($_POST['listing_logo']) && !empty($_POST['listing_logo'][0])) {
        update_post_meta($post_id, 'listing_logo', [intval($_POST['listing_logo'][0])]);
    } elseif (isset($_POST['listing_logo_img']) && $_POST['listing_logo_img'] !== '') {
        update_post_meta($post_id, 'listing_logo_img', intval($_POST['listing_logo_img']));
    } else {
        delete_post_meta($post_id, 'listing_logo');
        delete_post_meta($post_id, 'listing_logo_img');
    }
});

// Shortcode: [city_state_list]
function my_city_state_list_shortcode() {
    ob_start();

    echo '<div class="city-state-section py-5">'; // Section with padding and background
    echo '<div class="container text-center mb-4">';
    echo '<div class="section-heading">';
    echo '<div class="heading-subtitle">Doctors Near You</div>';
    echo '</div>';
    echo '<div class="title-underline mx-auto"></div>';
    echo '</div>';

    echo '<div class="container">';
    echo '<ul class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-1 m-0">';

    // Get all parent terms (states)
    $states = get_terms(array(
        'taxonomy'   => 'rtcl_location',
        'hide_empty' => false,
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC'
    ));

    foreach ($states as $state) {
        $abbreviation = get_term_meta($state->term_id, 'abbreviation', true);
        if (!$abbreviation) {
            $abbreviation = $state->name;
        }

        $cities = get_terms(array(
            'taxonomy'   => 'rtcl_location',
            'hide_empty' => false,
            'parent'     => $state->term_id,
            'orderby'    => 'name',
            'order'      => 'ASC'
        ));

        foreach ($cities as $city) {
            $city_link = get_term_link($city);
            if (!is_wp_error($city_link)) {
                echo '<li class="col">';
                echo '<a href="' . esc_url($city_link) . '" class="d-block text-decoration-none">';
                echo esc_html($city->name) . ', ' . esc_html($abbreviation);
                echo '</a>';
                echo '</li>';
            }
        }
    }

    echo '</ul>';
    echo '</div>';
    echo '</div>';

    return ob_get_clean();
}
add_shortcode('city_state_list', 'my_city_state_list_shortcode');

// Shortcode: [specific_category_city_state_list category="dentist"]
function specific_category_city_state_list_shortcode($atts) {
    $atts = shortcode_atts([
        'category' => ''
    ], $atts, 'specific_category_city_state_list');

    if (empty($atts['category'])) {
        return '<p>No category provided.</p>';
    }

    $category_term = get_term_by('slug', $atts['category'], 'rtcl_category');
    if (!$category_term) {
        return '<p>Category not found.</p>';
    }

    $category_term_title = esc_html($category_term->name);

    // 1. Get all listings for the category
    $listings = get_posts([
        'post_type'      => 'rtcl_listing',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [
            [
                'taxonomy' => 'rtcl_category',
                'field'    => 'term_id',
                'terms'    => $category_term->term_id,
            ]
        ]
    ]);

    if (empty($listings)) {
        return '<p>No listings found for ' . $category_term_title . '.</p>';
    }

    // 2. Get all locations from those listings
    $all_terms = wp_get_object_terms($listings, 'rtcl_location', ['orderby' => 'name', 'order' => 'ASC']);

    if (empty($all_terms) || is_wp_error($all_terms)) {
        return '<p>No locations found.</p>';
    }

    // 3. Organize terms by state > city
    $locations = [];
    foreach ($all_terms as $term) {
        if ($term->parent) {
            // This is a city
            $state = get_term($term->parent, 'rtcl_location');
            if ($state && !is_wp_error($state)) {
                $locations[$state->term_id]['state'] = $state;
                $locations[$state->term_id]['cities'][] = $term;
            }
        }
    }

    // 4. Build HTML
    ob_start();
    ?>
    <div class="city-state-section py-5">
        <div class="container text-center mb-4">
            <div class="section-heading">
                <div class="heading-subtitle"><?php echo $category_term_title; ?>s Near You</div>
            </div>
            <div class="title-underline mx-auto"></div>
        </div>

        <div class="container">
            <ul class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-1 m-0">
                <?php foreach ($locations as $state_data):
                    $state = $state_data['state'];
                    $abbr = get_term_meta($state->term_id, 'abbreviation', true) ?: $state->name;

                    foreach ($state_data['cities'] as $city):
                        $city_slug = $city->slug;
                        $link = home_url("/listings/listing-category/{$atts['category']}/location/{$city_slug}/?rtcl_category={$atts['category']}&rtcl_location={$city_slug}");
                        ?>
                        <li class="col">
                            <a href="<?php echo esc_url($link); ?>" class="d-block text-decoration-none">
                                <?php echo esc_html($city->name) . ', ' . esc_html($abbr); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('specific_category_city_state_list', 'specific_category_city_state_list_shortcode');

// Shortcode: [city_category_grid]
function my_city_category_grid_shortcode($atts) {
    $atts = shortcode_atts([
        'categories' => '', // comma-separated slugs or IDs of categories
        'per_page'   => 10,  // default cities per page (3 cols × 2 rows)
    ], $atts, 'city_category_grid');

    ob_start();

    // Pagination: get current page
    $paged = isset($_GET['ccg_page']) ? max(1, intval($_GET['ccg_page'])) : 1;

    // Get the categories from shortcode attribute
    $child_cats = [];
    if (!empty($atts['categories'])) {
        $cat_terms = array_map('trim', explode(',', $atts['categories']));
        foreach ($cat_terms as $term) {
            $cat = is_numeric($term)
                ? get_term_by('id', intval($term), 'rtcl_category')
                : get_term_by('slug', $term, 'rtcl_category');
            if ($cat) {
                $child_cats[] = $cat;
            }
        }
    }

    // Fallback: if no categories passed, get Doctor's 6 children
    if (empty($child_cats)) {
        $parent_cat = get_term_by('slug', 'doctor', 'rtcl_category');
        if (!$parent_cat) {
            return '<p>Doctor category not found.</p>';
        }
        $child_cats = get_terms([
            'taxonomy'   => 'rtcl_category',
            'parent'     => $parent_cat->term_id,
            'hide_empty' => false,
            'orderby'    => 'term_id',
            'number'     => 6
        ]);
    }

    // Collect all cities that have listings in at least one of these categories
    $cities_list = [];

    $states = get_terms([
        'taxonomy'   => 'rtcl_location',
        'hide_empty' => false,
        'parent'     => 0
    ]);

    foreach ($states as $state) {
        $state_abbr = get_term_meta($state->term_id, 'abbreviation', true) ?: $state->name;

        $city_terms = get_terms([
            'taxonomy'   => 'rtcl_location',
            'hide_empty' => true,
            'parent'     => $state->term_id
        ]);

        foreach ($city_terms as $city) {
            $listing_count = new WP_Query([
                'post_type'      => 'rtcl_listing',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'tax_query'      => [
                    [
                        'taxonomy' => 'rtcl_category',
                        'field'    => 'term_id',
                        'terms'    => wp_list_pluck($child_cats, 'term_id')
                    ],
                    [
                        'taxonomy' => 'rtcl_location',
                        'field'    => 'term_id',
                        'terms'    => $city->term_id
                    ]
                ]
            ]);

            if ($listing_count->found_posts > 0) {
                $cities_list[] = [
                    'city'   => $city,
                    'state'  => $state_abbr,
                    'count'  => $listing_count->found_posts
                ];
            }
            wp_reset_postdata();
        }
    }

    // Sort alphabetically by city name
    usort($cities_list, function($a, $b) {
        return strcasecmp($a['city']->name, $b['city']->name);
    });

    // Pagination slice
    $total_cities = count($cities_list);
    $total_pages  = ceil($total_cities / $atts['per_page']);
    $cities_list  = array_slice($cities_list, ($paged - 1) * $atts['per_page'], $atts['per_page']);

    // Output cities
    foreach ($cities_list as $entry) {
        $city  = $entry['city'];
        $state = $entry['state'];
        $count = $entry['count'];

        echo '<div class="city-block mb-4 bg-light rounded">';
        echo '<div class="city-block-header text-white p-1 px-2 rounded-top">';
        echo esc_html($city->name) . ', ' . esc_html($state);
        echo ' <span class="fw-bold">(' . intval($count) . ')</span>';
        echo '</div>';

        echo '<div class="row bg-light m-2 pb-2">';
        foreach ($child_cats as $cat) {
            $link = home_url("/listings/listing-category/{$cat->slug}/location/{$city->slug}/?rtcl_category={$cat->slug}&rtcl_location={$city->slug}");
            echo '<div class="col-4">';
            echo '<a href="' . esc_url($link) . '" class="text-decoration-none">';
            echo '<i class="rtcl-icon rtcl-icon- listygo-rt-icon-check"></i>';
            echo 'Top ' . esc_html($cat->name) . ' in ' . esc_html($city->name) . ', ' . esc_html($state);
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    }

    // Pagination links
    if ($total_pages > 1) {
        echo '<nav class="custom-pagination"><ul class="pagination">';
        if ($paged > 1) {
            echo '<li class="page-item"><a class="page-link" href="' . esc_url(add_query_arg('ccg_page', $paged - 1)) . '"><i class="fa-solid fa-angle-left" aria-hidden="true"></i></a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $paged) ? 'active' : '';
            echo '<li class="page-item ' . $active . '">';
            echo '<a class="page-link" href="' . esc_url(add_query_arg('ccg_page', $i)) . '">' . $i . '</a>';
            echo '</li>';
        }
        if ($paged < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="' . esc_url(add_query_arg('ccg_page', $paged + 1)) . '"><i class="fa-solid fa-angle-right" aria-hidden="true"></i></a></li>';
        }
        echo '</ul></nav>';
    }

    return ob_get_clean();
}
add_shortcode('city_category_grid', 'my_city_category_grid_shortcode');

// Shortcode: [related_properties]
function my_related_properties_shortcode() {

    // First, check if ?rtcl_location is set
    if ( isset( $_GET['rtcl_location'] ) && ! empty( $_GET['rtcl_location'] ) ) {
        $location_slug = sanitize_text_field( $_GET['rtcl_location'] );
    } else {
        // Fallback: detect from URL
        $location_slug = get_current_city_from_location_url();
    }
    ob_start();

    if ( ! empty( $location_slug ) ) {
        $city_term = get_term_by( 'slug', $location_slug, 'rtcl_location' );

        if ( $city_term && ! is_wp_error( $city_term ) ) {
            // Get state (parent term)
            $state_term = get_term( $city_term->parent, 'rtcl_location' );
            $state_name = ( $state_term && ! is_wp_error( $state_term ) ) ? $state_term->name : '';
            $state_abbr = get_term_meta($state_term->term_id, 'abbreviation', true) ?: $state_name;

            // Count posts in this city
            $listing_query = new WP_Query( array(
                'post_type'      => 'rtcl_listing',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'rtcl_location',
                        'field'    => 'term_id',
                        'terms'    => $city_term->term_id
                    )
                )
            ) );
            $count = $listing_query->found_posts;
            wp_reset_postdata();

            // Output
            ?>
            <div class="related-properties">
                <div class="title-btn">
                    <h3 class="widget-title">Related Properties</h3>
                </div>
                <div class="p-3 bg-light rounded">
                    <a href="<?php echo esc_url( get_term_link( $city_term ) ); ?>">
                        <?php echo '<i class="fa-solid fa-angle-right" aria-hidden="true"></i> '.esc_html( $city_term->name );
                        if ($state_abbr) echo ', ' . esc_html(strtoupper($state_abbr));
                        ?>
                    </a>
                    <span class="text-muted">(<?php echo intval( $count ); ?> Properties)</span>
                </div>
            </div>
            <?php
        }
    }

    return ob_get_clean();
}
add_shortcode( 'related_properties', 'my_related_properties_shortcode' );

function get_current_city_from_location_url() {
    // Get queried term object
    $term = get_queried_object();

    // Make sure it's a term and from the right taxonomy
    if ( $term && ! is_wp_error( $term ) && $term->taxonomy === 'rtcl_location' ) {
        return $term->slug; // returns 'los-angeles'
        // Or $term->name for 'Los Angeles'
    }

    return '';
}

add_action('wp_footer', function () {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.widget_text').forEach(function (widget) {
                if (widget.textContent.trim() === '') {
                    widget.style.display = 'none';
                }
            });
        });
    </script>
    <?php
});

// Shortcode: [latest_rtcl_listings]
function my_latest_rtcl_listings_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'count' => 3
    ), $atts, 'latest_rtcl_listings' );

    $query = new WP_Query( array(
        'post_type'      => 'rtcl_listing',
        'posts_per_page' => intval( $atts['count'] ),
        'orderby'        => 'date',
        'order'          => 'DESC'
    ) );

    ob_start();

    if ( $query->have_posts() ) {
        echo '<div id="listygo_post-3" class="widget_listygo_post latest-properties-widget">';
        echo '<div class="widget-recent">';
        echo '<h3 class="widget-title">Latest Properties</h3>';
        echo '<ul class="recent-post">';

        while ( $query->have_posts() ) {
            $query->the_post();

            $first_image_url = '';
            $images_order = get_post_meta( get_the_ID(), '_rtcl_attachments_order', true );
            $images_order = maybe_unserialize( $images_order );

            if ( ! empty( $images_order ) && is_array( $images_order ) ) {
                $first_image_id  = reset( $images_order );
                $first_image_url = wp_get_attachment_image_url( $first_image_id, 'thumbnail' );
            }

            ?>
            <li class="media">
                <div class="item-img">
                    <a href="<?php the_permalink(); ?>" class="item-figure">
                        <?php
                        if ( $first_image_url ) {
                            echo '<img src="' . esc_url( $first_image_url ) . '" alt="' . esc_attr( get_the_title() ) . '" class="attachment-thumbnail size-thumbnail wp-post-image">';
                        } else {
                            // Placeholder fallback if no image
                            echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/assets/img/placeholder.png' ) . '" alt="No Image">';
                        }
                        ?>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <span>
                        <img src="<?php echo Helper::get_img('theme/icon-calendar.png'); ?>" alt="<?php esc_attr_e( 'Calendar', 'listygo' ); ?>">
                        <?php the_time( get_option( 'date_format' ) ); ?>
                    </span>
                </div>
            </li>
            <?php
        }

        echo '</ul>';
        echo '</div>';
        echo '</div>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'latest_rtcl_listings', 'my_latest_rtcl_listings_shortcode' );

// Start listing gallery thumbnail generate
add_action( 'add_attachment', function( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    if ( empty( $meta ) ) {
        $file = get_attached_file( $attachment_id );
        $attach_data = wp_generate_attachment_metadata( $attachment_id, $file );
        wp_update_attachment_metadata( $attachment_id, $attach_data );
    }
});

add_filter('rtcl_image_sizes', function($sizes) {
    $sizes['listygo-size-1'] = ['width'=>350,'height'=>270,'crop'=>true];
    $sizes['listygo-size-2'] = ['width'=>330,'height'=>360,'crop'=>true];
    $sizes['listygo-size-3'] = ['width'=>350,'height'=>420,'crop'=>true];
    $sizes['listygo-size-4'] = ['width'=>580,'height'=>560,'crop'=>true];
    $sizes['thumbnail'] = [
        'width'  => (int) get_option( "thumbnail_size_w", 150 ),
        'height' => (int) get_option( "thumbnail_size_h", 150 ),
        'crop'   => (int) get_option( "thumbnail_crop", 1 ),
    ];
    $sizes['medium'] = [
        'width'  => (int) get_option( "medium_size_w", 300 ),
        'height' => (int) get_option( "medium_size_h", 300 ),
        'crop'   => false,
    ];
    $sizes['large'] = [
        'width'  => (int) get_option( "large_size_w", 1024 ),
        'height' => (int) get_option( "large_size_h", 1024 ),
        'crop'   => false,
    ];
    return $sizes;
}, 20);
// End listing gallery thumbnail generate

function my_custom_archive_banner_widget() {
    register_sidebar([
        'name' => 'Archive Banner',
        'id' => 'archive-banner',
        'before_widget' => '<div class="archive-banner-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name' => 'Listing Archive Sidebar without Ajax',
        'id' => 'listing-archive-sidebar-without-ajax',
        'before_widget' => '<div id="%1$s" class="widget %2$s sidebar-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

}
add_action('widgets_init', 'my_custom_archive_banner_widget');

<?php 
use Rtrs\Models\Review; 
use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;
global $listing;

$field_ids = '';
$can_report_abuse = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;

$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

if (!empty(RDTListygo::$options['listygo_desc_title'])) {
    $desc_title = RDTListygo::$options['listygo_desc_title'];
} else {
    $desc_title = esc_html__('Description', 'listygo');
}

if (!empty(RDTListygo::$options['listygo_gallery_title'])) {
	$gallery_title = RDTListygo::$options['listygo_gallery_title'];
} else {
	$gallery_title = esc_html__('Gallery', 'listygo');
}

if (!empty(RDTListygo::$options['listygo_map_title'])) {
	$map_title = RDTListygo::$options['listygo_map_title'];
} else {
	$map_title = esc_html__('Map', 'listygo');
}

if (!empty(RDTListygo::$options['listygo_video_title'])) {
	$video_title = RDTListygo::$options['listygo_video_title'];
} else {
	$video_title = esc_html__('Video', 'listygo');
}

if (!empty(RDTListygo::$options['listygo_rating_title'])) {
	$rating_title = RDTListygo::$options['listygo_rating_title'];
} else {
	$rating_title = esc_html__('Rating', 'listygo');
}

// Rating
if( class_exists( Review::class ) ){
    $average_rating = Review::getAvgRatings( get_the_ID() );
    $rating_count   = Review::getTotalRatings(  get_the_ID() );
} else {
    $average_rating   = $listing->get_average_rating();
    $rating_count     = $listing->get_rating_count();
}

$group_id = isset( RDTListygo::$options['custom_group_individual'] ) ? RDTListygo::$options['custom_group_individual'] : 0;
if ( $group_id ) {
    $field_ids = Functions::get_cf_ids_by_cfg_id( $group_id );
    $group_title = get_the_title( $group_id );
}

$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );
$thumb_slider = RDTListygo::$options['show_gallery_slider'];

$video_urls       = [];
if ( ! Functions::is_video_urls_disabled() ) {
    $video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
    $video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}

if ( !empty(get_the_content() )) {
    do_action( 'listygo_single_listing_before_contents_ad' );
?>
<div class="listingDetails-block mb-30">
    <div class="listingDetails-block__header">
        <h2 class="listingDetails-block__heading"><?php echo esc_html( $desc_title ); ?></h2>
    </div>
    <div class="listingDetails-block__des">
        <div class="listingDetails-block__des__text">
            <?php $listing->the_content(); ?>
        </div>
    </div>
</div>
<?php } if ( $thumb_slider == 1 ) { ?>
<div class="listingDetails-block mb-30">
    <div class="listingDetails-block__header mb-15">
        <h2 class="listingDetails-block__heading"><?php echo esc_html( $gallery_title ); ?></h2>
    </div>
    <div class="productDetailsGallery">
        <?php $listing->the_gallery(); ?>
    </div>
</div>

<?php } ?>
<?php
$args = [
    'post_type' => 'doctor',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => 'clinic',
            'value' => $listing->get_id(),
            'compare' => 'LIKE'
        ]
    ],
];
$doctors = new WP_Query($args);

if($doctors->have_posts()): ?>
<div class="listingDetails-block listingDetails-doctors mb-30">
    <div class="listingDetails-block__header mb-15">
        <h2 class="listingDetails-block__heading">Our Team</h2>
    </div>
    <div class="row g-4">
        <?php while($doctors->have_posts()): $doctors->the_post();
            $doctor_id = get_the_ID();
            $doctor_name = get_the_title();
            $doctor_content = get_the_content();
            $doctor_degree = get_post_meta($doctor_id, 'degree', true); // replace with your ACF field if needed
            $doctor_image = get_the_post_thumbnail_url($doctor_id, 'medium');
            ?>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card doctor-card" data-bs-toggle="modal" data-bs-target="#doctorModal<?php echo $doctor_id; ?>" style="cursor:pointer;">
                    <?php if($doctor_image): ?>
                        <img src="<?php echo esc_url($doctor_image); ?>" class="card-img-top" alt="<?php echo esc_attr($doctor_name); ?>">
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo esc_html($doctor_name); ?></h5>
                        <?php if($doctor_degree): ?>
                            <p class="card-text"><?php echo esc_html($doctor_degree); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="doctorModal<?php echo $doctor_id; ?>" tabindex="-1" aria-labelledby="doctorModalLabel<?php echo $doctor_id; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row">
                            <?php if($doctor_image): ?>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <img src="<?php echo esc_url($doctor_image); ?>" class="img-fluid rounded" alt="<?php echo esc_attr($doctor_name); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="col-md-8">
                                <?php if($doctor_degree): ?>
                                    <div><?php echo esc_html($doctor_degree); ?></div>
                                <?php endif; ?>
                                <h5 class="modal-title" id="doctorModalLabel<?php echo $doctor_id; ?>"><?php echo esc_html($doctor_name); ?></h5>
                                <div><?php echo wp_kses_post(wpautop($doctor_content)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</div>
    <?php wp_reset_postdata(); ?>
<?php else: ?>
<?php endif; ?>

<?php
    $category = $listing->get_categories();
    $category = end( $category ); 
    $term_id = $category->term_id;
    $args = [
        'category_id' => $term_id
    ];
?>

<!-- Custom Fields -->
<?php 
if (  Functions::isEnableFb() && Listing_Functions::form_builder_custom_group_field_check()){
	Helper::get_custom_listing_template( 'form-builder-cfg' );
} else {
    Helper::get_custom_listing_template( 'cfg-individual' );
    Helper::get_custom_listing_template( 'cfg-details' );
    Helper::get_custom_listing_template( 'food-list', true, $args );
    Helper::get_custom_listing_template( 'doctor-chamber', true, $args );
}
?>

<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
    
<div class="listingDetails-block mb-30">
    <div class="listingDetails-block__header mb-0">
        <h2 class="listingDetails-block__heading mb-10"><?php echo esc_html( $map_title ); ?></h2>
    </div>
    <figure class="listingDetails-map">
        <!-- Map -->
        <div class="product-map" id="map">
            <?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
        </div>
    </figure>
</div>
<?php } if ( in_array('video_url', $detailOption) && ! empty( $video_urls ) ){ ?>
    <div class="listingDetails-block mb-30">
        <div class="listingDetails-block__header">
            <h2 class="listingDetails-block__heading mb-30"><?php echo esc_html( $video_title ); ?></h2>
        </div>
        <div class="video-info ratio-16x9">
            <iframe class="rtcl-lightbox-iframe" src="<?php echo Functions::get_sanitized_embed_url( $video_urls[0] ) ?>"></iframe>
        </div>
    </div>
<?php } ?>
<div class="listingDetails-block seller-info mb-30">
    <?php 
        if (RDTListygo::$options['listing_list_information']) {
            $listing->the_user_info();
        }
    ?>
</div>
<?php if (Functions::get_option_item('rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox')) { ?>
<div class="listingDetails-block">
    <div class="listingDetails-block__header">
        <h2 class="listingDetails-block__heading mb-30"><?php echo esc_html( $rating_title ); ?></h2>
    </div>
    <div class="listingDetails-block__rating listingDetails-block__rating--style2">
        <?php do_action( 'rtcl_single_listing_review' ); ?>
    </div>
</div>

<?php } if ( $can_report_abuse ){ ?>

<div class="modal fade rtcl-bs-modal" id="rtcl-report-abuse-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rtcl-report-abuse-form" class="form-vertical">
                <div class="modal-header">
                    <h5 class="modal-title" id="rtcl-report-abuse-modal-label"><?php esc_html_e('Report Abuse', 'listygo'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img src="<?php echo Helper::get_img('theme/cross.svg'); ?>" alt="<?php esc_attr_e( 'Cross', 'listygo' ); ?>"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rtcl-report-abuse-message"><?php esc_html_e('Your Complaint', 'listygo'); ?>
                        <span class="rtcl-star">*</span></label>
                        <textarea class="form-control" name="message" id="rtcl-report-abuse-message" rows="3" placeholder="<?php esc_attr_e('Message... ', 'listygo'); ?>" required></textarea>
                    </div>
                    <div id="rtcl-report-abuse-g-recaptcha"></div>
                    <div id="rtcl-report-abuse-message-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Submit', 'listygo' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php } ?>

<?php do_action( 'listygo_single_listing_after_contents_ad' ); ?>
<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var number $id Random id
 */

namespace radiustheme\listygo;

use Rtcl\Helpers\Text;
use Rtcl\Resources\Options;
use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;
use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Controllers\Hooks\AppliedBothEndHooks;
use Rtcl\Controllers\BusinessHoursController as BHS;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;

class Listing_Functions {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'listygo_rtcl_filter' ] );
		add_action( 'init', [ $this, 'listygo_rtcl_action' ] );
		add_action( 'widgets_init', [ $this, 'listygo_theme_unregister_widgets' ], 11 );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_get_icon_list_modify' ] );

		//Old Custom Fields ajax callback
		add_action( 'wp_ajax_rtcl_cf_by_category', [ $this, 'rtcl_cf_by_category_func' ] );
		add_action( 'wp_ajax_nopriv_rtcl_cf_by_category', [ $this, 'rtcl_cf_by_category_func' ] );

		add_action( 'rtcl_map_localized_options', function ( $options ) {
			$options['cluster_options']['center'] = [
				"lat" => 38.0166667,
				"lng" => - 102.5378564
			];
			$options['cluster_options']['zoom']   = 4;

			return $options;
		} );

		add_action( 'admin_init', [ $this, 'set_default_listings_per_row' ], 1 );
	}

	public function set_default_listings_per_row() {
		if ( get_option( 'listygo_desktop_column_migrated' ) ) {
			return;
		}
		// Get existing options
		$options              = get_option( 'rtcl_archive_listing_settings', [] );
		$theme_column_setting = RDTListygo::$options['listing_grid_cols'];

		$default_values = [
			'desktop' => $theme_column_setting ? intval( $theme_column_setting ) : 3,
			// Default desktop value
			'tablet'  => 2,
			// Default tablet value
			'mobile'  => 1
			// Default mobile value
		];

		$options['listings_per_row'] = $default_values;

		update_option( 'rtcl_archive_listing_settings', $options );
		update_option( 'listygo_desktop_column_migrated', 1 );
	}


	public function listygo_rtcl_action() {

		/* = Listing Archive Hooks
		=====================================================================================================*/
		//Remove Hooks
		remove_action( 'rtcl_listing_meta_buttons', [ TemplateHooks::class, 'add_favourite_button' ], 10 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 40 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_meta_buttons' ], 60 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );
		remove_action( 'rtcl_edit_account_form_end', [ TemplateHooks::class, 'edit_account_form_submit_button' ], 10 );


		//Action Hooks 
		add_action( 'rtcl_listing_loop_item', [ $this, 'listygo_listing_excerpt' ], 30 );
		add_action( 'wp_footer', [ $this, 'listygo_photoswipe_placeholder' ] );
		add_action( 'rtcl_edit_account_form_end', [ $this, 'edit_account_form_submit_button' ], 10 );

		/* = Listing Details Hooks
		=====================================================================================================*/
		//Remove Hooks
		remove_action( 'rtcl_review_after_meta', [ ProTemplateHooks::class, 'review_display_rating' ], 10 );
		remove_action( 'rtcl_review_comment_text', [ ProTemplateHooks::class, 'review_display_comment_title' ], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_action'
		], 20 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_custom_field'
		], 10 );
		remove_action( 'rtcl_single_listing_content_end', [ ProTemplateHooks::class, 'single_map_content' ] );

		//Action Hooks
		add_action( 'rtcl_review_after_meta', [ ProTemplateHooks::class, 'review_display_comment_title' ], 10 );
		add_action( 'rtcl_review_after_meta', [ ProTemplateHooks::class, 'review_display_rating' ], 20 );
		add_action( 'listygo_listing_grid_search_filter', [ $this, 'listing_map_filter' ] );

		/* = Override plugin options
		=====================================================================================================*/
		add_filter( 'rtcl_get_listing_detail_page_display_options', [ $this, 'listygo_details_fields_options' ] );
		add_filter( 'rtcl_get_listing_display_options', [ $this, 'listing_extra_options' ] );
		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );

		/* = Save form extra additional field data
		=====================================================================================================*/
		add_action( 'wp_ajax_delete_listing_logo_attachment', [ $this, 'delete_listing_logo_attachment' ] );
		add_action( 'wp_ajax_delete_food_attachment', [ $this, 'delete_food_attachment' ] );
		add_action( 'wp_ajax_delete_chamber_attachment', [ $this, 'delete_chamber_attachment' ] );
		add_action( 'rtcl_listing_form_after_save_or_update', [
			$this,
			'listing_form_save'
		], 12, 5 ); // save extra listing form fields

		add_action( 'wp_ajax_delete_clinic_attachment', [ $this, 'delete_clinic_attachment' ] );

		add_filter( 'comment_form_defaults', function ( $defaults ) {
			$defaults['title_reply_before'] = '<h2 id="reply-title" class="comment-reply-title">';
			$defaults['title_reply_after']  = '</h2>';

			return $defaults;
		} );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function listygo_rtcl_filter() {

		// Change Grid Column for listing
		add_filter( 'rtcl_listings_grid_columns_class', function () {
			$per_row   = Functions::get_listings_per_row();
			$col_class = sprintf(
				'columns-%d tab-columns-%d mobile-columns-%d',
				intval( $per_row['desktop'] ?? 3 ),
				intval( $per_row['tablet'] ?? 2 ),
				intval( $per_row['mobile'] ?? 1 )
			);
			if ( is_page_template( 'templates/listing-map.php' ) ) {
				$columns = 'columns-' . RDTListygo::$options['listing_map_grid_cols'];
			} else {
				$columns = $col_class;
			}

			return $columns;

		} );

		// Change add to favorite text
		add_filter( 'rtcl_text_add_to_favourite', function ( $text ) {
			$text = esc_html__( 'Favourite', 'listygo' );

			return $text;
		} );
		// Change remove from favorite text
		add_filter( 'rtcl_text_remove_from_favourite', function ( $text ) {
			$text = esc_html__( 'Remove', 'listygo' );

			return $text;
		} );
		// Change report abuse text
		add_filter( 'rtcl_text_report_abuse', function ( $text ) {
			$text = esc_html__( 'Report Listing', 'listygo' );

			return $text;
		} );

		add_filter( 'rtcl_review_gravatar_size', function () {
			return 100;
		} );

		// Override Related Listing Item Number
		add_filter( 'rtcl_related_slider_options', function ( $options ) {
			$rpc                     = RDTListygo::$options['related_post_slider_cols'];
			$options['breakpoints']  = [
				0    => [
					"slidesPerView" => 1
				],
				576  => [
					"slidesPerView" => 2
				],
				1200 => [
					"slidesPerView" => $rpc
				]
			];
			$options['spaceBetween'] = 30;

			return $options;
		} );

		add_filter( 'rtcl_single_listing_contact_info_title', function ( $text ) {
			$text = esc_html__( 'Contact info', 'listygo' );

			return $text;
		} );

		add_filter( 'listygo_single_listing_header_rating_text', function ( $text ) {
			$text = esc_html__( 'Avarage Rating', 'listygo' );

			return $text;
		} );

		// Pagination
		add_filter( 'rtcl_pagination_args', function ( $args ) {
			$args['prev_text'] = '<i class="fa-solid fa-angle-left" aria-hidden="true"></i>';
			$args['next_text'] = '<i class="fa-solid fa-angle-right" aria-hidden="true"></i>';

			return $args;
		} );
		// Listing/Directory Settings
		add_filter( 'rtcl_general_settings_options', [ $this, 'listygo_custom_listing_options' ] );
		add_filter( 'rtcl_store_get_ad_count_html', [ $this, 'listygo_store_counts' ], 10, 3 );

		add_filter( 'rtcl_listing_form_phone_is_required', function () {
			return false;
		} );

		add_filter( 'rtcl_archive_listing_settings_options', [ $this, 'enable_map' ] );

	}

	public function listygo_theme_unregister_widgets() {
		unregister_sidebar( 'rtcl-archive-sidebar' );
		unregister_sidebar( 'rtcl-single-sidebar' );
	}

	public function listing_extra_options( $options ) {
		$options['address'] = esc_html__( 'Address', 'listygo' );
		$options['phone']   = esc_html__( 'Phone', 'listygo' );
		$options['website'] = esc_html__( 'Website', 'listygo' );

		return $options;
	}

	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons = [
			" flaticon-chef",
			" flaticon-dish",
			" flaticon-supermarket",
			" flaticon-musical-note",
			" flaticon-hotel",
			" flaticon-coffee-cup",
			" flaticon-earth",
			" flaticon-bed",
			" flaticon-spa",
			" flaticon-search",
			" flaticon-right-arrow",
			" flaticon-back",
			" flaticon-down-arrow",
			" flaticon-up-arrow",
			" flaticon-upload",
			" flaticon-phone-call",
			" flaticon-next",
			" flaticon-list",
			" flaticon-profile",
			" flaticon-placeholder",
			" flaticon-play-button",
			" flaticon-refresh",
			" demo-icon listygo-rt-icon-check-icon",
			" demo-icon listygo-rt-icon-email-icon",
			" demo-icon listygo-rt-icon-cog",
			" demo-icon listygo-rt-icon-button-arrow-icon-left",
			" demo-icon listygo-rt-icon-button-arrow-icon-right",
			" demo-icon listygo-rt-icon-home-icon",
			" demo-icon listygo-rt-icon-icon-cafe",
			" demo-icon listygo-rt-icon-icon-restaurant",
			" demo-icon listygo-rt-icon-login-icon",
			" demo-icon listygo-rt-icon-cafe-small-icon",
			" demo-icon listygo-rt-icon-phone-icon",
			" demo-icon listygo-rt-icon-museum-small-icon",
			" demo-icon listygo-rt-icon-icon-home",
			" demo-icon listygo-rt-icon-icon",
			" demo-icon listygo-rt-icon-star-icon",
			" demo-icon listygo-rt-icon-restaurant-small-icon",
			" demo-icon listygo-rt-icon-share-icon",
			" demo-icon listygo-rt-icon-check",
			" demo-icon listygo-rt-icon-element4",
			" demo-icon listygo-rt-icon-element5",
			" demo-icon listygo-rt-icon-element6",
			" demo-icon listygo-rt-icon-vector-22",
			" demo-icon listygo-rt-icon-element8",
			" demo-icon listygo-rt-icon-search-normal",
			" demo-icon listygo-rt-icon-gym",
			" demo-icon listygo-rt-icon-shop",
			" demo-icon listygo-rt-icon-icon-home-1",
			" demo-icon listygo-rt-icon-01-map-1",
			" demo-icon listygo-rt-icon-02-star-2",
			" demo-icon listygo-rt-icon-03-calendar-check",
			" demo-icon listygo-rt-icon-vector-24",
			" demo-icon listygo-rt-icon-vector-25",
			" demo-icon listygo-rt-icon-vector-3",
			" demo-icon listygo-rt-icon-vector-4",
			" demo-icon listygo-rt-icon-vector-5",
			" demo-icon listygo-rt-icon-vector-17",
			" demo-icon listygo-rt-icon-vector-18",
			" demo-icon listygo-rt-icon-vector-20",
			" demo-icon listygo-rt-icon-vector-21",
			" demo-icon listygo-rt-icon-r-cat7",
			" demo-icon listygo-rt-icon-r-cat8",
			" demo-icon listygo-rt-icon-r-cat9",
			" demo-icon listygo-rt-icon-r-cat10",
			" demo-icon listygo-rt-icon-r-cat11",
			" demo-icon listygo-rt-icon-r-cat12",
			" demo-icon listygo-rt-icon-d-book",
			" demo-icon listygo-rt-icon-d-brain",
			" demo-icon listygo-rt-icon-d-calendar",
			" demo-icon listygo-rt-icon-d-eye",
			" demo-icon listygo-rt-icon-d-heart",
			" demo-icon listygo-rt-icon-d-heart1",
			" demo-icon listygo-rt-icon-d-teeth",
			" demo-icon listygo-rt-icon-d-teeth1",
			" demo-icon listygo-rt-icon-d-user1",
			" demo-icon listygo-rt-icon-d-user2",
			" demo-icon listygo-rt-icon-d-user3",
			" demo-icon listygo-rt-icon-right-circled2",
		];

		return array_merge( $icons_lists, $new_icons );
	}

	public function listygo_listing_excerpt() {
		global $listing;
		$excerpt_length = RDTListygo::$options['listygo_listing_excerpt'];

		if ( $listing->can_show_excerpt() ) {
			?>
            <p><?php echo Helper::listygo_excerpt( $excerpt_length ); ?></p>
		<?php }
	}

	public static function open_close_status( $listing_id ) {
		$business_hours = BHS::get_business_hours( $listing_id );
		if ( BHS::openStatus( $business_hours ) ) {
			return 'open';
		} else {
			return 'close';
		}
	}

	public static function listygo_cat_icon( $term_id, $icon_type = null ) {
		$cat_img  = $cat_icon = $icon = null;
		$image_id = get_term_meta( $term_id, '_rtcl_image', true );
		if ( $image_id ) {
			$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'medium' );
			$image            = $image_attributes[0];
			if ( '' !== $image ) {
				$cat_img = sprintf( '<img src="%s" class="rtcl-cat-img" alt="%s"/>', $image, esc_attr__( 'Category Image', 'listygo' ) );
			} else {
				$cat_img = '';
			}
		}
		$icon_id = get_term_meta( $term_id, '_rtcl_icon', true );
		if ( $icon_id ) {
			$cat_icon = sprintf( '<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $icon_id );
		} else {
			$cat_icon = '';
		}

		$icon = $icon_type == 'icon' ? $cat_icon : $cat_img;

		return $icon;
	}

	public static function listing_single_style() {
		$opt_layout = ! empty( RDTListygo::$options['single_listing_style'] ) ? RDTListygo::$options['single_listing_style'] : '1';

		return $opt_layout;
	}

	public static function listing_single_banner_option() {
		$opt_layout = ! empty( RDTListygo::$options['single_listing_header_banner'] ) ? RDTListygo::$options['single_listing_header_banner'] : '1';

		return $opt_layout;
	}

	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;
		$type          = $listing->get_ad_type();
		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags'
			];
		} else {
			$result = false;
		}

		return $result;
	}

	public static function listing_details_slider() {
		global $listing;
		$imgNone              = '';
		$total_gallery_image  = '';
		$total_gallery_videos = '';
		$detailOption         = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$images = $listing->get_images();
		$videos = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
		$rand   = substr( md5( mt_rand() ), 0, 7 );

		$slider_per_views = RDTListygo::$options['slider_per_view'];

		$slider_data = [
			'allowSlideNext' => true,
			'allowSlidePrev' => true,
			"navigation"     => [
				"nextEl" => ".swiper-button-next",
				"prevEl" => ".swiper-button-prev",
			],
			"loop"           => false,
//			"autoplay"       => [
//				"delay"                => 3000,
//				"disableOnInteraction" => false,
//				"pauseOnMouseEnter"    => true
//			],
			"speed"          => 1000,
			"spaceBetween"   => 0,
			"breakpoints"    => [
				0    => [
					"slidesPerView" => 1
				],
				576  => [
					"slidesPerView" => 2
				],
				800  => [
					"slidesPerView" => 3
				],
				1200 => [
					"slidesPerView" => $slider_per_views
				]
			]
		];
		if ( is_rtl() ) {
			$slider_data['rtl'] = true;
		}
		$data['slider_data'] = json_encode( $slider_data );

		if ( ! empty( $images ) ) {
			$total_gallery_image = count( $images );
		}
		if ( ! empty( $videos ) ) {
			$total_gallery_videos = count( $videos );
		}

		if ( ! empty( $videos ) ) {
			$total_gallery_item = $total_gallery_image + $total_gallery_videos;
		} else {
			$total_gallery_item = $total_gallery_image;
		}
		if ( $total_gallery_item ) :
			$owl_class = $total_gallery_item > 3 && Functions::is_gallery_slider_enabled() ? " slick-navigation-layout2" : 'not-slider';
			if ( $total_gallery_image === 0 ) {
				$imgNone = 'image-not-set';
			}
			?>
            <!-- Listing Banner Area Start Here -->
            <section class="single-listing-carousel-wrap <?php echo esc_attr( $imgNone ); ?>">
				<?php if ( $total_gallery_item > 3 && Functions::is_gallery_slider_enabled() ) { ?>
                    <div class="<?php echo esc_attr( $owl_class ); ?>"
                         data-carousel-options='<?php echo esc_attr( $data['slider_data'] ); ?>'>
                        <div class="rtcl-related-slider rtcl-carousel-slider" id="rtcl-related-slider-banner"
                             data-options="<?php echo esc_attr( $data['slider_data'] ); ?>">
                            <div class="swiper-wrapper">
								<?php
								if ( ! in_array( 'video_url', $detailOption ) ) {
									if ( $total_gallery_videos ) {
										foreach ( $videos as $index => $video_url ) { ?>
                                            <div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
                                                <iframe class="rtcl-lightbox-iframe"
                                                        src="<?php echo Functions::get_sanitized_embed_url( $video_url ) ?>"
                                                        frameborder="0" webkitAllowFullScreen
                                                        mozallowfullscreen allowFullScreen></iframe>
                                            </div>
											<?php
										}
									}
								}
								if ( $total_gallery_image ) {
									foreach ( $images as $index => $image ) :
										$imagesize = wp_get_attachment_image_src( $image->ID, 'full' );
										$img_url = $imagesize[0];
										$width = $imagesize[1];
										$height = $imagesize[2];
										?>
                                        <div class="swiper-slide nav-item photoswip-item">
                                            <a href="<?php echo esc_url( $img_url ); ?>"
                                               width="<?php echo esc_attr( $width ); ?>"
                                               height="<?php echo esc_attr( $height ); ?>">
												<?php echo wp_get_attachment_image( $image->ID, 'listygo-size-4' ); ?>
                                            </a>
                                        </div>
									<?php endforeach;
								}
								?>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
				<?php } elseif ( $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ) {
					if ( $total_gallery_item >= 3 ) {
						$cols = '4';
					} else {
						$cols = '6';
					}
					?>
                    <div class="row no-gutters justify-content-center">
						<?php if ( ! in_array( 'video_url', $detailOption ) ) {
							if ( $total_gallery_videos ) {
								foreach ( $videos as $index => $video_url ) { ?>
                                    <div class="col-md-<?php echo esc_attr( $cols ); ?> image-fit">
                                        <div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
                                            <iframe
                                                    class="rtcl-lightbox-iframe"
                                                    src="<?php echo Functions::get_sanitized_embed_url( $video_url ) ?>"
                                                    webkitAllowFullScreen
                                                    mozallowfullscreen allowFullScreen></iframe>
                                        </div>
                                    </div>
									<?php
								}
							}
						}
						?>
						<?php foreach ( $images as $index => $image ) {
							$img_url = wp_get_attachment_image_url( $image->ID, $size = 'full' );
							?>
                            <div class="col-md-<?php echo esc_attr( $cols ); ?> image-fit">
                                <div class="swiper-slide nav-item photoswip-item">
                                    <a href="<?php echo esc_url( $img_url ); ?>">
										<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery' ); ?>
                                    </a>
                                </div>
                            </div>
						<?php } ?>
                    </div>
				<?php } else { ?>
                    <div class="row">
						<?php if ( ! in_array( 'video_url', $detailOption ) ) {
							if ( $total_gallery_videos ) {
								foreach ( $videos as $index => $video_url ) { ?>
                                    <div class="col-md-12 image-fit-full">
                                        <div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
                                            <iframe class="rtcl-lightbox-iframe"
                                                    src="<?php echo Functions::get_sanitized_embed_url( $video_url ) ?>"
                                                    webkitAllowFullScreen
                                                    mozallowfullscreen allowFullScreen></iframe>
                                        </div>
                                    </div>
									<?php
								}
							}
						}
						?>
						<?php foreach ( $images as $index => $image ) { ?>
                            <div class="col-md-12 image-fit-full text-center">
								<?php echo wp_get_attachment_image( $image->ID, 'full' ); ?>
                            </div>
						<?php } ?>
                    </div>
				<?php } ?>
            </section>
            <!-- Listing Banner Area End Here -->
		<?php endif;
	}

	public static function listing_details_banner() {
		global $listing;
		$imgUrl    = get_the_post_thumbnail_url( $listing->get_id(), 'full' );
		$videosUrl = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
		if ( empty( $imgUrl ) ) {
			$images = $listing->get_images();
			if ( ! empty( $images ) ) {
				$total_gallery_image = count( $images );
				$total_gallery_item  = $total_gallery_image;
				if ( $total_gallery_item ) {
					foreach ( $images as $index => $image ) {
						$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
					}
				}
			} else {
				$img_url = '';
			}
		} elseif ( ! empty( $imgUrl ) ) {
			$img_url = $imgUrl;
		} else {
			$img_url = '';
		}
		//Inner Page Banner Area Start Here
		if ( ! empty( $img_url ) ) {
			echo '<section class="inner-page-banner1 bg-common inner-page-top-margin" data-bg-image="' . $img_url . '"></section>';
		} else {
			echo '<div class="rtcl-video-item ratio-16x9">
				<iframe class="rtcl-lightbox-iframe"
					src="' . Functions::get_sanitized_embed_url( $videosUrl[0] ) . '"
					frameborder="0" webkitAllowFullScreen
					mozallowfullscreen allowFullScreen></iframe>
			</div>';
		}
	}

	public static function listygo_listing_gallery() {
		global $listing;

		$video_urls = [];
		if ( ! Functions::is_video_urls_disabled() ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		// Image Gallery
		$images              = $listing->get_images();
		$total_gallery_image = count( $images );
		if ( $total_gallery_image ) {
			?>

            <div class="row photo-swip-gallery-wrap">
				<?php if ( ! empty( $video_urls ) ) { ?>
                    <div class="col-md-4 col-sm-6 col-6">
                        <div class="video-info">
                            <iframe class="rtcl-lightbox-iframe"
                                    src="<?php echo Functions::get_sanitized_embed_url( $video_urls[0] ) ?>"
                                    style="height: 235px" webkitAllowFullScreen
                                    mozallowfullscreen allowFullScreen></iframe>
                        </div>
                    </div>
				<?php }
				foreach ( $images as $index => $image ) {
					?>
                    <div class="col-md-4 col-sm-6 col-6 video-info listing-gallery-item">
						<?php $img_url = wp_get_attachment_image_url( $image->ID, 'full' ); ?>
                        <a class="listing-popup-btn" href="<?php echo esc_url( $img_url ); ?>" data-width="1200"
                           data-height="900">
							<?php echo wp_get_attachment_image( $image->ID, 'listygo-size-2' ); ?>
                        </a>
                    </div>
					<?php
				} ?>
            </div>
		<?php }
	}

	public function custom_field_group_list() {
		$group_ids = Functions::get_cfg_ids();

		$list[0] = esc_html__( 'Skip Individual View', 'listygo' );

		foreach ( $group_ids as $id ) {
			$list[ $id ] = get_the_title( $id );
		}

		return $list;
	}

	public function listing_map_filter( $atts ) {

		$loc_text = esc_attr__( 'Select Location', 'listygo' );
		$cat_text = esc_attr__( 'Select Category', 'listygo' );
		global $wp;
		$current_url = home_url( add_query_arg( array(), $wp->request ) );

		?>
        <div class="listing-grid-box listygo-custom-map-search">
            <div class="title-btn">
                <h3 class="widget-title"><?php echo esc_html( RDTListygo::$options['map_search_widget_title'] ); ?></h3>
                <button class="reset-btn">
                    <a href="<?php echo esc_url( get_permalink( Functions::get_page_id( 'listings' ) ) ); ?>">
						<?php esc_html_e( 'Clear All', 'listygo' ); ?>
                    </a>
                </button>
            </div>
            <form action="<?php echo esc_url( home_url( $wp->request ) ); ?>"
                  class="advance-search-form map-search-form rtcl-widget-search-form is-preloader">
                <div class="search-box">

                    <div class="search-item search-keyword w100">
                        <div class="input-group">
                            <input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
                                   placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'listygo' ); ?>"
                                   value="<?php if ( isset( $_GET['q'] ) ) {
								       echo esc_attr( $_GET['q'] );
							       } ?>"/>
                        </div>
                    </div>

					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && 'local' === Functions::location_type() ): ?>
                        <div class="search-item search-select rtin-location">
							<?php
							wp_dropdown_categories(
								[
									'show_option_none'  => $loc_text,
									'option_none_value' => '',
									'taxonomy'          => rtcl()->location,
									'name'              => 'rtcl_location',
									'id'                => 'rtcl-location-search-' . wp_rand(),
									'class'             => 'select2 rtcl-location-search',
									'selected'          => get_query_var( 'rtcl_location' ),
									'hierarchical'      => true,
									'value_field'       => 'slug',
									'depth'             => Functions::get_location_depth_limit(),
									'show_count'        => false,
									'hide_empty'        => false,
								]
							);
							?>
                        </div>
					<?php endif; ?>

                    <div class="search-item search-select rtin-category">
						<?php
						wp_dropdown_categories(
							[
								'show_option_none'  => $cat_text,
								'option_none_value' => '',
								'taxonomy'          => rtcl()->category,
								'name'              => 'rtcl_category',
								'id'                => 'rtcl-category-search-' . wp_rand(),
								'class'             => 'select2 rtcl-category-search',
								'selected'          => get_query_var( 'rtcl_category' ),
								'hierarchical'      => true,
								'value_field'       => 'slug',
								'depth'             => Functions::get_category_depth_limit(),
								'show_count'        => false,
								'hide_empty'        => false,
							]
						);
						?>
                    </div>

					<?php
					$group_id = '';
					$group_id = isset( RDTListygo::$options['custom_fields_search_items'] ) ? RDTListygo::$options['custom_fields_search_items'] : 0;
					if ( is_array( $group_id ) && count( $group_id ) < 2 ) {
						$group_id = end( $group_id );
					}
					?>
                </div>

                <div class="search-box-2">
                    <div class="distance-search">
						<?php
						$rs_data = Options::radius_search_options();
						?>
                        <div class="form-group ws-item ws-location">
                            <h4 class="radius-serarch-title"><?php echo esc_html( Text::get_select_location_text() ); ?></h4>
                            <div class="rtcl-geo-address-field">
                                <input type="text" name="geo_address" autocomplete="off"
                                       value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( $_GET['geo_address'] ) : '' ?>"
                                       placeholder="<?php esc_attr_e( "Select a location", "listygo" ) ?>"
                                       class="form-control rtcl-geo-address-input"/>
                                <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                                <input type="hidden" class="latitude" name="center_lat"
                                       value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( $_GET['center_lat'] ) : '' ?>">
                                <input type="hidden" class="longitude" name="center_lng"
                                       value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( $_GET['center_lng'] ) : '' ?>">
                            </div>
                            <div class="rtcl-range-slider-field">
                                <div class="rtcl-range-label">
                                    <h4 class="advanced-serarch-title"><?php esc_html_e( 'Radius', 'listygo' ); ?></h4>
                                    <span class="rtcl-range-value">
										<?php echo ! empty( $_GET['distance'] ) ? absint( $_GET['distance'] ) : 30 ?>
										<?php in_array( $rs_data['units'], [
											'km',
											'kilometers'
										] ) ? esc_html_e( "km", "listygo" ) : esc_html_e( "Miles", "listygo" ); ?></span>
                                </div>
                                <input type="range" class="form-control-range rtcl-range-slider-input" name="distance"
                                       min="0"
                                       max="<?php echo absint( $rs_data['max_distance'] ) ?>"
                                       value="<?php echo isset( $_GET['distance'] ) ? $_GET['distance'] : $rs_data['default_distance']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="search-item price-item-box">
						<?php if ( RDTListygo::$options['listing_price_search_type'] == 'range' ) { ?>
                            <div class="price-range">
                                <h4 class="price-filter-title"><?php esc_html_e( 'Price Range', 'listygo' ); ?></h4>
								<?php
								$currency  = Functions::get_currency_symbol();
								$data_form = '';
								$data_to   = '';
								if ( isset( $_GET['filters']['price']['min'] ) ) {
									$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
								}
								if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
									$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
								}
								$min_price = RDTListygo::$options['listing_widget_min_price'];
								$max_price = RDTListygo::$options['listing_widget_max_price'];
								?>
                                <input type="number"
                                       class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
                                       data-min="<?php echo isset( $min_price ) && ! empty( $min_price ) ? $min_price : 0; ?>"
                                       data-max="<?php echo isset( $max_price ) && ! empty( $max_price ) ? $max_price : 80000; ?>"
                                       data-prefix="<?php echo esc_html( $currency ) ?>"/>
                                <input type="hidden" class="min-volumn" name="filters[price][min]"
                                       value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
									       echo absint( $_GET['filters']['price']['min'] );
								       } ?>">
                                <input type="hidden" class="max-volumn" name="filters[price][max]"
                                       value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
									       echo absint( $_GET['filters']['price']['max'] );
								       } ?>">
                            </div>
						<?php } else { ?>
                            <!-- Price fields -->
                            <div class="form-group">
                                <h4 class="price-filter-title"><?php esc_html_e( 'Price Range', 'listygo' ); ?></h4>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <input type="text" name="filters[price][min]" class="form-control"
                                               placeholder="<?php esc_attr_e( 'min', 'listygo' ); ?>"
                                               value="<?php if ( isset( $_GET['filters']['price'] ) ) {
											       echo esc_attr( $_GET['filters']['price']['min'] );
										       } ?>">
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <input type="text" name="filters[price][max]" class="form-control"
                                               placeholder="<?php esc_attr_e( 'max', 'listygo' ); ?>"
                                               value="<?php if ( isset( $_GET['filters']['price'] ) ) {
											       echo esc_attr( $_GET['filters']['price']['max'] );
										       } ?>">
                                    </div>
                                </div>
                            </div>
						<?php } ?>
                    </div>
                    <div class="search-box">
						<?php
						if ( ! empty( $group_id ) ) {
							$field_ids = Functions::get_cf_ids_by_cfg_id( $group_id );

							if ( ! empty( $field_ids ) ) {
								?>
                                <div class="form-cf-items expanded-wrap">
                                    <div class="cf-inner">
										<?php
										$args      = [
											'is_searchable'     => true,
											'exclude_group_ids' => $group_id,
										];
										$fields_id = Functions::get_cf_ids( $args );
										$html      = '';
										foreach ( $field_ids as $field ) {
											$field_label = new RtclCFGField( $field );
											if ( $field_label->getLabel() ) {
												$html .= '<h4 class="advanced-serarch-check-title">' . $field_label->getLabel() . '</h4>';
											}
											$html .= self::get_advanced_search_field_html( $field );
										}
										Functions::print_html( $html, true );
										?>
                                    </div>
                                </div>
							<?php }
						}
						?>
                    </div>
                    <div class="search-item search-btn">
                        <button type="submit" class="submit-btn">
							<?php echo Helper::search_icon(); ?>
							<?php esc_html_e( 'Search', 'listygo' ); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
		<?php
	}

	public static function listygo_photoswipe_placeholder() {
		Functions::get_template( 'listing/photoswipe', [], '', rtclPro()->get_plugin_template_path() );
	}

	public static function listygo_selected_category( $category_id ) {
		$to_get_slug = get_term( $category_id, 'rtcl_category' );
		$parent_id   = $to_get_slug->parent;

		if ( ! empty( $parent_id ) ) {
			$to_get_slug = get_term( $parent_id, 'rtcl_category' );
		} else {
			$to_get_slug = get_term( $category_id, 'rtcl_category' );
		}
		$cat_slug = $to_get_slug->slug;

		return $cat_slug;
	}

	public static function listygo_selected_category_fields( $category_id, $has_category_id ) {
		$taxonomy = 'rtcl_category';
		$term     = get_term( $category_id, $taxonomy );

		if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
			if ( $has_category_id == $term->term_id ) {
				return true;
			}
		}

		$has_category = false;

		if ( ! empty( $term->parent ) ) {
			while ( $term->parent > 0 ) {
				$term = get_term( $term->parent, $taxonomy );
				if ( $has_category_id == $term->term_id ) {
					$has_category = true;
					break;
				}
			}
		}

		return $has_category;
	}

	public static function get_listing_restaurant_category_id() {
		$cat_id = ! empty( RDTListygo::$options['restaurant_foodMenu_lists_category'] ) ? RDTListygo::$options['restaurant_foodMenu_lists_category'] : 0;

		return $cat_id;
	}

	public static function get_listing_doctor_category_id() {
		$cat_id = ! empty( RDTListygo::$options['doctor_hospital_lists_category'] ) ? RDTListygo::$options['doctor_hospital_lists_category'] : 0;

		return $cat_id;
	}

	public function edit_account_form_submit_button() {
		?>
        <div class="form-group row">
            <div class="col-sm-12 mt-30 text-center">
                <input type="submit" name="submit" class="btn btn-primary"
                       value="<?php esc_attr_e( 'Update Account', 'listygo' ); ?>"/>
            </div>
        </div>
		<?php
	}

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						absint( $field->getFieldId() ),
						$id,
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
							<div class="price-range">
								<label>%s</label>
								<input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
								%s
							</div>
						</div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'date':
					$filters = ! empty( $_GET['filters'] ) ? $_GET['filters'] : [];
					$metaKey = $field->getMetaKey();
					$value   = ! empty( $filters[ $metaKey ] ) ? esc_attr( $filters[ $metaKey ] ) : null;

					$field_html .= ! empty( $filters[ $id ] ) ? esc_attr( $filters[ $id ] ) : null;
					$isOpen     = $value ? ' is-open' : null;
					$field_html .= sprintf(
						'<div class="form-group">
								<div class="ui-field">
									<input id="filters[%1$s]" autocomplete="off" name="filters[_field_%2$s]" type="text" value="%3$s" data-options="%4$s" class="ui-input form-control rtcl-date" placeholder="%5$s">
								</div>	
							</div>',
						esc_attr( $id ),
						absint( $field->getFieldId() ),
						esc_attr( $value ),
						htmlspecialchars(
							wp_json_encode(
								$field->getDateFieldOptions(
									[
										'singleDatePicker' => $field->getDateSearchableType() === 'single',
										'autoUpdateInput'  => false,
									]
								)
							)
						),
						esc_attr__( 'DD/MM/YYYY', 'listygo' ),
					);
					break;

			}
		}

		return $field_html;
	}

	public static function get_fm_advanced_search_field_html( $field, $fieldArr ) {

		$field_html = null;

		if ( $field ) {
			$id = "rtcl_{$field->getName()}";

			switch ( $field->getElement() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[%s]" placeholder="%s" value="" />',
						$id,
						esc_attr( $field->getName() ),
						esc_attr( $field->getLabel() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[%s]" rows="%d" placeholder="%s"></textarea>',
						$id,
						esc_attr( $field->getName() ),
						4,
						esc_attr( $field->getLabel() ),

					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $options ) ) {
						foreach ( $options as $choice ) {
							$_attr = '';
							$value = $choice['value'] ?? '';
							if ( isset( $_GET['filters'][ $field->getName() ] ) && $_GET['filters'][ $field->getName() ] == $value ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $value, $_attr, esc_attr( $choice['label'] ) );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[%s]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						esc_attr( $field->getName() ),
						$id . wp_rand(),
						esc_attr( $field->getLabel() ),
						$options_html
					);
					break;
				case 'checkbox':
					$options = $field->getOptions();

					$request_value = isset( $_GET['filters'][ $field->getName() ] ) ? $_GET['filters'][ $field->getName() ] : [];
					$check_options = null;
					if ( ! empty( $options ) ) {
						foreach ( $options as $choice ) {
							$_attr = '';
							$value = $choice['value'] ?? '';
							if ( in_array( $value, $request_value ) ) {
								$_attr .= ' checked="checked"';
							}

							$check_options .= sprintf(
								'<div class="form-check"><div class="check-inner">
                                        <input class="form-check-input" id="%1$s" type="checkbox" name="filters[%2$s][]" value="%3$s"%4$s>
                                        <label class="form-check-label" for="%5$s">%6$s</label></div></div>',
								$id . $value, //1
								$field->getName(), //2
								$value, //3
								$_attr, //4
								$id . $value, //5
								esc_attr( $choice['label'] ) //6
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$check_options = null;
					if ( ! empty( $options ) ) {
						foreach ( $options as $choice ) {
							$value         = $choice['value'] ?? '';
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%1$s" type="radio" name="filters[%2$s]" value="%3$s"><label class="form-check-label" for="%4$s">%5$s</label></div>',
								$id . $value,
								$field->getName(),
								$value,
								$id . $value,
								esc_attr( $choice['label'] )
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$number_min   = $fieldArr['validation']['min']['value'] ?? '';
					$number_max   = $fieldArr['validation']['max']['value'] ?? '';
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[%s][min]" value="%s">',
						esc_attr( $field->getName() ),
						isset( $_GET['filters'][ $field->getName() ]['min'] ) ? absint( $_GET['filters'][ $field->getName() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[%s][max]" value="%s">',
						esc_attr( $field->getName() ),
						isset( $_GET['filters'][ $field->getName() ]['max'] ) ? absint( $_GET['filters'][ $field->getName() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
                                    <div class="price-range">
                                        <label>%s</label>
                                        <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                        %s
                                    </div>
                                 </div>',
						esc_attr( $field->getLabel() ),
						$id,
						'any', //$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ $field->getName() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ $field->getName() ]['min'] )
						) : '',
						isset( $_GET['filters'][ $field->getName() ]['max'] ) && ! empty( $_GET['filters'][ $field->getName() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ $field->getName() ]['max'] )
						) : '',
						$number_min,
						$number_max,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[%s]" placeholder="%s" value="" />',
						$id,
						esc_attr( $field->getName() ),
						esc_attr( $field->getLabel() )
					);
					break;
			}
		}

		return $field_html;
	}

	public function listygo_details_fields_options( $options ) {
		$options['phone_no']  = esc_html__( 'Phone Number', 'listygo' );
		$options['video_url'] = esc_html__( 'Video url', 'listygo' );

//		$options['social_share'] = esc_html__( 'Social Share', 'listygo' );

		return $options;
	}

	public static function get_share_link() {
		global $listing;
		?>
        <!-- Modal -->
        <div class="modal fade social-share" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><img src="<?php echo Helper::get_img( 'theme/cross.svg' ); ?>"
                                                          alt="<?php esc_attr_e( 'Cross', 'listygo' ); ?>"></span>
                        </button>
                        <img src="<?php echo Helper::get_img( 'theme/popup.png' ); ?>"
                             alt="<?php esc_attr_e( 'Popup', 'listygo' ); ?>">
                        <h5 class="modal-title"
                            id="exampleModalLongTitle"><?php esc_html_e( 'Share This Link Via', 'listygo' ); ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="share-icon">
							<?php $listing->the_social_share(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}

			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites ) ) {
				return '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" title="' . esc_html__( "Favourites", 'listygo' )
				       . '" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="rtcl-icon rtcl-icon-heart"></span><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::remove_from_favourite() . '</span></a>';
			} else {
				return '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" title="' . esc_html__( "Favourites", 'listygo' )
				       . '" class="rtcl-favourites" data-id="' . $post_id . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::add_to_favourite() . '</span></a>';
			}
		} else {
			return '<a href="#" data-bs-toggle="modal" data-bs-target="#logoutModalCenter" title="' . esc_html__( "Favourites", 'listygo' )
			       . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">' . \Rtcl\Helpers\Text::add_to_favourite()
			       . '</span></a>';
		}
	}

	public static function logout_user_favourite() {
		global $listing;
		?>
        <!-- Modal -->
        <div class="modal fade" id="logoutModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLongTitle"><?php esc_html_e( 'Login', 'listygo' ); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><img src="<?php echo Helper::get_img( 'theme/cross.svg' ); ?>"
                                                          alt="<?php esc_attr_e( 'Cross', 'listygo' ); ?>"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="share-icon">
                            <form id="rtcl-login-form" class="form-horizontal" method="post">
								<?php do_action( 'rtcl_login_form_start' ); ?>
                                <div class="form-group">
                                    <label for="rtcl-user-login" class="control-label">
										<?php esc_html_e( 'Username or E-mail', 'listygo' ); ?>
                                        <strong class="rtcl-required">*</strong>
                                    </label>
                                    <input type="text" name="username" autocomplete="username"
                                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                                           id="rtcl-user-login" class="form-control" required/>
                                </div>

                                <div class="form-group">
                                    <label for="rtcl-user-pass" class="control-label">
										<?php esc_html_e( 'Password', 'listygo' ); ?>
                                        <strong class="rtcl-required">*</strong>
                                    </label>
                                    <input type="password" name="password" id="rtcl-user-pass"
                                           autocomplete="current-password"
                                           class="form-control" required/>
                                </div>

								<?php do_action( 'rtcl_login_form' ); ?>

                                <div class="form-group">
                                    <div id="rtcl-login-g-recaptcha" class="mb-2"></div>
                                    <div id="rtcl-login-g-recaptcha-message"></div>
                                </div>

                                <div class="form-group d-flex align-items-center">
                                    <button type="submit" name="rtcl-login" class="btn btn-primary" value="login">
										<?php esc_html_e( 'Login', 'listygo' ); ?>
                                    </button>
                                    <div class="form-check">
                                        <input type="checkbox" name="rememberme" id="rtcl-rememberme" value="forever">
                                        <label class="form-check-label" for="rtcl-rememberme">
											<?php esc_html_e( 'Remember Me', 'listygo' ); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p class="rtcl-forgot-password">
                                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'listygo' ); ?></a>
                                    </p>
                                </div>
								<?php do_action( 'rtcl_login_form_end' ); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public function delete_listing_logo_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'listing_logo_img' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function delete_food_attachment() {
		if ( $_POST['attachment_id'] || $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'listygo_food_list' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function delete_clinic_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			$clinic_data                                   = get_post_meta( $_POST['post_id'], 'listygo_doctor_chamber' );
			$clinic_data[ $_POST['index'] ]['chamber_img'] = '';
			delete_post_meta( $_POST['post_id'], 'listygo_doctor_chamber' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function get_listing_attachment_id( $post_id, $file ) {
		if ( $file['error'] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			get_template_part( ABSPATH . "/wp-admin" . '/includes/image.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/file.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/media.php' );
		}

		Filters::beforeUpload();
		// you can use WP's wp_handle_upload() function:
		$status = wp_handle_upload(
			$file,
			[
				'test_form' => false,
			]
		);
		Filters::afterUpload();

		if ( $status && ! isset( $status['error'] ) ) {
			// $filename should be the path to a file in the upload directory.
			$filename = $status['file'];

			// Check the type of tile. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = [
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			];
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename );
		}

		return isset( $attach_id ) ? $attach_id : 0;
	}

	public function listing_form_save( $post_id, $type, $cat_id, $new_listing_status, $data ) {
		// Listing Logo
		$files = $data['files'];

		if ( isset( $files['listing_logo_img'] ) ) {
			$logoImage = $files['listing_logo_img'];
			if ( ! empty( $logoImage ) && empty( $_POST['logo_attachment_id'] ) ) {
				$logoID = $this->get_listing_attachment_id( $post_id, $logoImage );
			} elseif ( isset( $_POST['logo_attachment_id'] ) ) {
				$logoID = $_POST['logo_attachment_id'];
			}
			if ( $logoID ) {
				update_post_meta( $post_id->get_id(), 'listing_logo_img', $logoID );
			}
		}

		// Event date
		if ( isset( $_POST['eventdatetime'] ) ) {
			$eventdatetime = $_POST['eventdatetime'];
			if ( $eventdatetime ) {
				update_post_meta( $post_id->get_id(), 'eventdatetime', $eventdatetime );
			}
		}

		// Food Menu
		$sanitized_data = [];
		if ( isset( $_POST['listygo_food_list'] ) ) {
			$raw_data    = $_POST['listygo_food_list'];
			$count       = 0;
			$food_images = [];
			if ( ! empty( $files['listygo_food_images'] ) ) {
				$attachmentData = $files['listygo_food_images'];
				foreach ( $attachmentData as $file_key => $images ) {
					foreach ( $images as $key => $values ) {
						foreach ( $values as $food_list ) {
							foreach ( $food_list as $index => $name ) {
								$food_images[ $key ][ $index ][ $file_key ] = $name;
							}
						}
					}
				}
			}

			foreach ( $raw_data as $group_no => $foods_group ) {
				$foods_menu = [];

				foreach ( $foods_group as $key => $value ) {
					if ( $key === 'gtitle' ) {
						$foods_menu[ $key ] = sanitize_text_field( $foods_group['gtitle'] );
					}

					if ( $key === 'food_list' ) {
						foreach ( $foods_group['food_list'] as $index => $data ) {

							foreach ( $data as $data_key => $value ) {

								$attach_id = 0;

								if ( $data_key === 'title' || $data_key === 'foodprice' ) {
									$foods_menu[ $key ][ $index ][ $data_key ] = sanitize_text_field( $value );
								} elseif ( $data_key === 'description' ) {
									$foods_menu[ $key ][ $index ][ $data_key ] = sanitize_textarea_field( $value );
								} elseif ( $data_key === 'attachment_id' ) {
									$attach_id = $value;
								}

								if ( ! empty( $food_images[ $group_no ][ $index ] ) && empty( $attach_id ) ) {
									$attach_id = $this->get_listing_attachment_id( $post_id->get_id(), $food_images[ $group_no ][ $index ] );
								}

								if ( ! empty( $attach_id ) ) {
									$foods_menu[ $key ][ $index ]['attachment_id'] = $attach_id;
								}
							}
						}
					}
				}

				if ( ! empty( $foods_menu ) ) {
					$sanitized_data[] = $foods_menu;
				}
			}
		}
		if ( empty( $sanitized_data ) ) {
			delete_post_meta( $post_id->get_id(), 'listygo_food_list' );
		} else {
			update_post_meta( $post_id->get_id(), 'listygo_food_list', $sanitized_data );
		}

		// Doctor Chamber
		$sanitized_data = [];
		if ( isset( $_POST['listygo_doctor_chamber'] ) ) {
			$raw_data = $_POST['listygo_doctor_chamber'];
			$count    = 0;
			foreach ( $raw_data as $chambers ) {
				$chamber_list = [];
				$attach_id    = 0;
				foreach ( $chambers as $key => $value ) {
					if ( $key === 'cname' || $key === 'time' || $key === 'phone' ) {
						$chamber_list[ $key ] = sanitize_text_field( $value );
					} elseif ( $key === 'cloaction' ) {
						$chamber_list[ $key ] = sanitize_textarea_field( $value );
					}
				}

				if ( ! empty( $files['listygo_chamber_img'] ) && empty( $chambers['attachment_id'] ) ) {
					$attachmentData = $files['listygo_chamber_img'];
					foreach ( $attachmentData as $attachmentKey => $attachmentValue ) {
						$image[ $attachmentKey ] = $attachmentValue[ $count ];
					}
					if ( ! empty( $image['name'] ) ) {
						$attach_id = $this->get_listing_attachment_id( $post_id->get_id(), $image );
					}
					$count ++;
				} elseif ( ! empty( $chambers['attachment_id'] ) ) {
					$attach_id = $chambers['attachment_id'];
					$count ++;
				}

				if ( ! empty( $attach_id ) ) {
					$chamber_list['chamber_img'] = $attach_id;
				}

				if ( ! empty( $chamber_list ) ) {
					$sanitized_data[] = $chamber_list;
				}
			}
		}
		if ( ! empty( $sanitized_data ) ) {
			update_post_meta( $post_id->get_id(), 'listygo_doctor_chamber', $sanitized_data );
		} else {
			delete_post_meta( $post_id->get_id(), 'listygo_doctor_chamber' );
		}

	}

	public function listygo_custom_listing_options( $options ) {
		$options['car_listing_section']              = [
			'title' => esc_html__( 'Listing Settings', 'listygo' ),
			'type'  => 'section',
		];
		$options['enable_event_listing']             = [
			'title' => esc_html__( 'Enable Event Listing', 'listygo' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Add event listing features.', 'listygo' ),
		];
		$options['enable_restaurant_listing']        = [
			'title' => esc_html__( 'Enable Restaurant Listing', 'listygo' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Add restaurant listing features.', 'listygo' ),
		];
		$options['listygo_food_list_section_label']  = [
			'title'   => esc_html__( 'Food List Section Label', 'listygo' ),
			'type'    => 'text',
			'default' => 'Food Menu List',
		];
		$options['enable_doctor_listing']            = [
			'title' => esc_html__( 'Enable Doctor Listing', 'listygo' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Add doctor listing features.', 'listygo' ),
		];
		$options['listygo_doctor_appointment_label'] = [
			'title'   => esc_html__( 'Doctor Appointment Label', 'listygo' ),
			'type'    => 'text',
			'default' => 'Appointment',
		];
		$options['listygo_chamber_section_label']    = [
			'title'   => esc_html__( 'Clinic List Section Label', 'listygo' ),
			'type'    => 'text',
			'default' => 'Doctor Clinic List',
		];

		return $options;
	}

	public static function is_enable_car_listing() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_car_listing', false, 'checkbox' );
	}

	public static function is_enable_event_listing() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_event_listing', false, 'checkbox' );
	}

	public static function is_enable_restaurant_listing() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_restaurant_listing', false, 'checkbox' );
	}

	public static function is_enable_doctor_listing() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_doctor_listing', false, 'checkbox' );
	}

	/* = Store Code
	================================================================ */
	public static function rt_get_post_view_count( $storeID ) {
		//Get store View Count 
		$rt_store_views_key = 'rt_post_views_count';
		$store_view_count   = get_post_meta( $storeID, $rt_store_views_key, true );

		return $store_view_count;
	}

	public function listygo_store_counts( $content, $obj, $count ) {
		$count_string = $count <= 0
			? apply_filters( 'listygo_store_no_ad_text', __( "No Listing", "listygo" ), $this, $count )
			: sprintf(
				_n( "%s Property", "%s Listings", $count, 'listygo' ),
				number_format_i18n( $count )
			);
		$content      = sprintf( '<span class="ads-count">%s</span>', $count_string );

		return $content;
	}

	public static function listygo_listing_details_cfg( $group_ids ) {
		global $listing;
		if ( count( $group_ids ) ) {
			foreach ( $group_ids as $group_id ) {
				$get_class_by_title = get_post_field( 'post_title', $group_id );
				$field_ids          = Functions::get_cf_ids_by_cfg_id( $group_id );
				if ( ! empty( $field_ids ) ) {

					ob_start();
					foreach ( $field_ids as $single_field ) {
						$field = new RtclCFGField( $single_field );
						$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
						$icon  = $field->getIconClass() ? $field->getIconClass() : ' listygo-rt-icon-check';
						if ( ! $value || empty( $value ) ) {
							continue;
						}

						if ( $field->getType() === 'checkbox' ) {
							$liclass = 'multi-check id-' . $single_field;
						} else {
							$liclass = 'single-field-list id-' . $single_field;
						}
						$fieldLabel = $field->getLabel();
						?>
                        <li class="<?php echo esc_attr( $liclass ); ?>">
							<?php if ( $field->getType() !== 'checkbox' ) { ?>
                                <div class="amenities-icon">
                                    <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $icon ) ?>"></i>
                                </div>
							<?php } ?>
                            <div class="amenities-content">
								<?php if ( $field->getLabel() ): ?>
                                    <h5 class="heading-title rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">
										<?php echo esc_html( $field->getLabel() ); ?>
                                    </h5>
								<?php endif; ?>
                                <span class="cfp-value">
										<?php
										if ( ! empty( $value ) ) { ?>
											<?php if ( $field->getType() === 'url' ) {
												$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                                                <a href="<?php echo esc_url( $value ); ?>"
                                                   target="<?php echo esc_attr( $field->getTarget() ) ?>" <?php echo esc_html( $nofollow ) ?>>
														<?php echo esc_html( $field->getLabel() ) ?>
													</a>
											<?php } elseif ( $field->getType() === 'checkbox' ) {
												$facilities = Functions::get_cf_data( $single_field );
												$data       = $facilities['value'];
												$options    = $facilities['options']['choices'];
												echo "<ul class='amenities-list'>";
												foreach ( $options as $key => $value ) {
													if ( in_array( $key, $data ) === true ) {
														if ( ! empty( $icon ) ) {
															echo '<li><i class="rtcl-icon rtcl-icon-' . $icon . '"></i> ' . $value . '</li>';
														} else {
															echo '<li class="icon-not-selected">' . $value . '</li>';
														}
													}
												}
												echo "</ul>";
											} else { ?>
												<?php Functions::print_html( $value ); ?>
											<?php } ?>
										<?php }
										?>
									</span>
                            </div>
                        </li>
						<?php
					}
					$fields_value = ob_get_clean();
					if ( ! empty( $fields_value ) ) { ?>
                        <div class="listingDetails-block listingDetails-block-general mb-30">
							<?php if ( empty( $fieldLabel ) ) { ?>
                                <h2 class="group-name listingDetails-block__heading"><?php echo $get_class_by_title; ?></h2>
							<?php } ?>
                            <ul class="group-fields-list">
								<?php echo $fields_value; ?>
                            </ul>
                        </div>
					<?php }
				}
			}
		}
	}

	public static function listygo_listing_search_widget( $group_ids ) {
		global $listing;
		if ( count( $group_ids ) ) {
			foreach ( $group_ids as $group_id ) {
				$get_class_by_title = get_post_field( 'post_title', $group_id );
				$field_ids          = Functions::get_cf_ids_by_cfg_id( $group_id );
				if ( ! empty( $field_ids ) ) {

					ob_start();
					foreach ( $field_ids as $single_field ) {
						$field = new RtclCFGField( $single_field );
						$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
						$icon  = $field->getIconClass() ? $field->getIconClass() : ' listygo-rt-icon-check';
						if ( ! $value || empty( $value ) ) {
							continue;
						}

						if ( $field->getType() === 'checkbox' ) {
							$liclass = 'multi-check id-' . $single_field;
						} else {
							$liclass = 'single-field-list id-' . $single_field;
						}

						?>
                        <li class="<?php echo esc_attr( $liclass ); ?>">
							<?php if ( $field->getType() !== 'checkbox' ) { ?>
                                <div class="amenities-icon">
                                    <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $icon ) ?>"></i>
                                </div>
							<?php } ?>
                            <div class="amenities-content">
								<?php if ( $field->getLabel() ): ?>
                                    <h5 class="heading-title rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">
										<?php echo esc_html( $field->getLabel() ); ?>
                                    </h5>
								<?php endif; ?>
                                <span class="cfp-value">
										<?php
										if ( ! empty( $value ) ) { ?>
											<?php if ( $field->getType() === 'url' ) {
												$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                                                <a href="<?php echo esc_url( $value ); ?>"
                                                   target="<?php echo esc_attr( $field->getTarget() ) ?>" <?php echo esc_html( $nofollow ) ?>>
														<?php echo esc_html( $field->getLabel() ) ?>
													</a>
											<?php } elseif ( $field->getType() === 'checkbox' ) {
												$facilities = Functions::get_cf_data( $single_field );
												$data       = $facilities['value'];
												$options    = $facilities['options']['choices'];
												echo "<ul class='amenities-list'>";
												foreach ( $options as $key => $value ) {
													if ( in_array( $key, $data ) === true ) {
														if ( ! empty( $icon ) ) {
															echo '<li><i class="rtcl-icon rtcl-icon-' . $icon . '"></i> ' . $value . '</li>';
														} else {
															echo '<li class="icon-not-selected">' . $value . '</li>';
														}
													}
												}
												echo "</ul>";
											} else { ?>
												<?php Functions::print_html( $value ); ?>
											<?php } ?>
										<?php }
										?>
									</span>
                            </div>
                        </li>
						<?php
					}
					$fields_value = ob_get_clean();
					if ( ! empty( $fields_value ) ) { ?>
                        <div class="listingDetails-block listingDetails-block-general mb-30">
                            <h2 class="group-name"><?php echo $get_class_by_title; ?></h2>
                            <ul class="group-fields-list">
								<?php echo $fields_value; ?>
                            </ul>
                        </div>
					<?php }
				}
			}
		}
	}

	public static function form_builder_custom_group_field_check() {
		global $listing;
		$form = $listing->getForm();
		if ( $form ) {
			$fields           = $form->getFieldAsGroup( FBField::CUSTOM );
			$fields_available = false;
			if ( count( $fields ) ) {
				foreach ( $fields as $fieldName => $field ) {
					$field = new FBField( $field );
					$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
					if ( ! empty( $value ) ) {
						return true;
					}
				}

				return $fields_available;
			}
		}
	}

	public static function business_open_close_status( $listing, $icon ) {
		$business_hours = $onoff = '';
		$form           = null;
		if ( FBHelper::isEnabled() ) {
			$_form = $listing->getForm();
			if ( $_form && $_form->getFieldByElement( 'business_hours' ) ) {
				$form = $_form;
			}
		}

		if ( FBHelper::isEnabled() && $form ) {
			$business_hours = BHS::get_business_hours( $listing->get_id() );
			if ( ! empty( $business_hours['bhs'] ) ) {
				$business_hours = $business_hours['bhs'];
			}
		} else {
			if ( Functions::is_enable_business_hours() ) {
				$business_hours = BHS::get_old_business_hours( $listing->get_id() );
			}
		}

		$status = BHS::openStatus( $business_hours );

		if ( Functions::is_enable_business_hours() || ( FBHelper::isEnabled() && $business_hours ) ) {
			$show_icon = $icon;
			if ( $status ) {
				$onoff = '<span class="item-status onoff-status open">';
				if ( $show_icon ) {
					$onoff .= '<i class="fas fa-check-circle"></i>';
				}
				$onoff .= esc_html__( 'Open Now', 'listygo' ) . '</span>';
			} else {
				$onoff = '<span class="item-status onoff-status close">';
				if ( $show_icon ) {
					$onoff .= '<i class="fas fa-times-circle"></i>';
				}
				$onoff .= esc_html__( 'Closed Now', 'listygo' ) . '</span>';
			}
		}

		echo wp_kses_post( $onoff );
	}

	public function enable_map( $options ) {
		$options['listing_archive_map_settings'] = [
			'title' => esc_html__( 'Listing Archive Map', 'listygo' ),
			'type'  => 'section',
		];

		$options['enable_archive_map'] = [
			'title' => esc_html__( 'Enable Listing Archive Map', 'listygo' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show map in listing archive page', 'listygo' ),
		];

		return $options;
	}


	/**
	 * Old Custom Fields ajax callback
	 *
	 * @return void
	 */
	public function rtcl_cf_by_category_func() {

		$fb_id          = ! empty( $_REQUEST['fb_id'] ) ? sanitize_text_field( $_REQUEST['fb_id'] ) : '';
		$cat_id         = ! empty( $_REQUEST['cat_id'] ) ? sanitize_text_field( $_REQUEST['cat_id'] ) : '';
		$current_cat_id = ! empty( $_REQUEST['current_cat_id'] ) ? sanitize_text_field( $_REQUEST['current_cat_id'] ) : '0';

		if ( $fb_id ) {
			self::get_cf_fields_demand( $fb_id, '', $cat_id );
		} else {
			if ( '0' == $cat_id ) {
				$args      = [ 'is_searchable' => true ];
				$fields_id = Functions::get_cf_ids( $args );
			} else {
				$fields_id = self::get_cf_ids( $_POST['cat_id'] );
			}

			$html = '';

			foreach ( $fields_id as $field ) {
			    if ($field == '8685' || $field == '9007') {
                    $field_label = new RtclCFGField($field);
                    if ($field_label->getLabel()) {
                        $html .= '<h4 class="advanced-serarch-check-title">' . $field_label->getLabel() . '</h4>';
                    }
                    $html .= self::get_advanced_search_field_html($field);
                }
			}
			printf( "%s", $html );
		}

		wp_die();
	}

	public static function get_cf_ids( $category_id ) {
		$group_id = AppliedBothEndHooks::get_custom_field_group_ids( null, $category_id );
		$args     = [
			'post_type'        => rtcl()->post_type_cf,
			'post_status'      => 'publish',
			'posts_per_page'   => - 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_parent__in'  => $group_id,
			'meta_query'       => [
				[
					'key'   => '_searchable',
					'value' => 1
				],
			]
		];

		return get_posts( $args );
	}

	public static function get_cf_fields_demand( $form_id, $fields = '', $catId = '' ) {

		$form = Form::query()->find( $form_id );

		if ( empty( $form ) ) {
			return;
		}

		$fields = $form->getFieldAsGroup( FBField::CUSTOM );

		if ( count( $fields ) ) :
			$fields = FBHelper::reOrderCustomField( $fields );

			foreach ( $fields as $index => $field ) {
				$fieldObj = new FBField( $field );
				$logics   = $fieldObj->getData( 'logics', [] );
				$status   = $logics['status'] ?? 0;

				if ( $status ) {
					$conditions     = $logics['conditions'] ?? '';
					$conditionalIds = wp_list_pluck( $conditions, 'value' );
					if ( ! in_array( $catId, $conditionalIds ) ) {
						continue;
					}
				}

				if ( ! $fieldObj->isFilterable() ) {
					continue;
				}

				echo self::get_fm_advanced_search_field_html( $fieldObj, $field );
			}
		endif;
	}


	/**
	 * Show an archive description on taxonomy archives.
	 */
	public static function listygo_taxonomy_archive_description() {
		$term = false;
		if ( Functions::is_listing_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
			$term = get_queried_object();
		} elseif ( Functions::is_listings() ) {
			$category = get_query_var( '__cat' );
			if ( ! empty( $category ) ) {
				$term = get_term_by( 'slug', $category, rtcl()->category );
			}
		}

		//public static function listygo_cat_icon( $term_id, $icon_type = NULL ) {

		if ( $term && ! is_wp_error( $term ) ) {
			$term_id  = $term->term_id;
			$cat_icon = '<div class="cat-icon">' . self::listygo_cat_icon( $term_id, '' ) . '</div>';
		}
		if ( $term && ! empty( $term->description ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div class="rtcl-term-description">' . $cat_icon . Functions::format_content( $term->description ) . '</div>';
		}
	}
}

Listing_Functions::instance();
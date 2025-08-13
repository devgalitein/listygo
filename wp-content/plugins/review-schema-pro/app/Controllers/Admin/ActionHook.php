<?php

namespace Rtrsp\Controllers\Admin;

use Rtrs\Models\Review;
use Rtrs\Helpers\Functions;
use Rtrsp\Traits\SingletonTrait;

class ActionHook {

	use SingletonTrait;

	function init() {
		add_action( 'wp_footer', [ $this, 'archive_page' ], 999 );
		add_action( 'rtrs_after_review_edit_form', [ $this, 'after_review_edit_form' ], 10, 2 );
		add_action( 'rtrs_before_review_comments_list', [ $this, 'before_review_comments_list' ], 10, 2 );
		add_action( 'rtrs_extra_rich_snippet', [ $this, 'extra_rich_snippet' ], 15, 3 );

	}

	public function extra_rich_snippet( $post_id, $p_meta, $post ) {
		$custom_snippet = get_post_meta($post_id, '_rtrs_custom_rich_snippet', true);
		$snippet_code_meta_value = get_post_meta($post_id, '_rtrs_generated_snippet_code', true);
		if( ! empty( $snippet_code_meta_value ) && $custom_snippet ){
			$arr = array( 'br' => array(), 'p' => array(), 'strong' => array() );
            ?>
			<!-- This Google structured data (Rich Snippet) custom Schema by RadiusTheme Review Schema plugin version <?php echo  RTRS_VERSION; ?> -->
            <script type="application/ld+json"> <?php echo wp_kses( $snippet_code_meta_value, $arr ); ?></script>
            <?php
		}

    }
	public function archive_page() {

		$schema_obj = new \Rtrs\Models\Schema();

		global $wp_query;

		$archive_main_home    = false;
		$product_main_home    = false;
		$classified_main_home = false;
		$blog_main_home       = false;

		if ( Functions::is_plugin_active( 'woocommerce/woocommerce.php' ) && is_shop() ) {
			$archive_main_home = true;
			$product_main_home = true;
		} elseif ( Functions::is_plugin_active( 'classified-listing/classified-listing.php' ) && \Rtcl\Helpers\Functions::is_listings() ) {
			$archive_main_home    = true;
			$product_main_home    = true;
			$classified_main_home = true;
		} elseif ( is_home() ) {
			$archive_main_home = true;
			$blog_main_home    = true;
		};

		if ( is_tax() || is_category() || is_tag() || $archive_main_home ) {
			$settings_schema = get_option( 'rtrs_schema_archive_settings' );

			$product_archive = false;
			if ( is_tax( 'product_cat' ) ||
			is_tax( 'product_tag' ) ||
			is_tax( 'download_category' ) ||
			is_tax( 'download_tag' )
			) {
				$product_archive = true;
			}

			$classified_archive = false;
			if ( is_tax( 'rtcl_category' ) ||
			is_tax( 'rtcl_location' )
			) {
				$classified_archive = true;
			}

			if ( $classified_main_home ) {
				$classified_archive = true;
			}

			$schema_type = 'article';

			$schema = false;
			if ( $product_archive && isset( $settings_schema['product_archive'] ) && $settings_schema['product_archive'] == 'yes' ) {
				$schema = true;
			}

			if ( $classified_archive && isset( $settings_schema['cl_archive'] ) && $settings_schema['cl_archive'] == 'yes' ) {
				$schema = true;
			}

			if ( ( isset( $settings_schema['archive'] ) && $settings_schema['archive'] == 'yes' ) && ( ! $product_archive && ! $classified_archive && ! $archive_main_home ) ) {
				$schema = true;
			}

			if ( $schema ) {
				if ( isset( $settings_schema['schema_type'] ) && $settings_schema['schema_type'] ) {
					$schema_type = $settings_schema['schema_type'];
				}

				if ( $product_archive || $classified_archive || $product_main_home ) {
					$schema_type = 'product';
				}
				$archive_data = [];
				$category     = get_queried_object();
				if ( ! $archive_main_home && is_object( $category ) ) {
					$category_id       = intval( $category->term_id );
					$category_link     = get_category_link( $category_id );
					$category_link     = get_term_link( $category_id );
					$category_headline = single_cat_title( '', false ) . esc_html__( ' Category', 'review-schema-pro' );
					if ( $product_archive || $classified_archive || $product_main_home ) {
						$archive_data = [
							'@context'    => 'https://schema.org',
							'@type'       => 'ItemList',
							'@id'         => trailingslashit( esc_url( $category_link ) ) . '#ItemList',
							'name'        => esc_attr( $category_headline ),
							'description' => wp_strip_all_tags( get_term( $category_id )->description ),
							'url'         => esc_url( $category_link ),
						];

					} else {
						$archive_data = [
							'@context'    => 'https://schema.org',
							'@type'       => 'CollectionPage',
							'@id'         => trailingslashit( esc_url( $category_link ) ) . '#Article',
							'headline'    => esc_attr( $category_headline ),
							'description' => wp_strip_all_tags( get_term( $category_id )->description ),
							'url'         => esc_url( $category_link ),
						];
					}
				} else {
					if ( $blog_main_home ) {
						$archive_data = [
							'@context'    => 'https://schema.org',
							'@type'       => 'CollectionPage',
							'@id'         => trailingslashit( esc_url( home_url( '/' ) ) ) . '#CollectionPage',
							'headline'    => get_bloginfo( 'name' ),
							'description' => get_bloginfo( 'description', 'display' ),
							'url'         => esc_url( home_url( '/' ) ),
						];
					} else {
						if ( $classified_main_home ) {
							$archive_data = [
								'@context'    => 'https://schema.org',
								'@type'       => 'ItemList',
								'@id'         => trailingslashit( esc_url( get_the_permalink() ) ) . '#ItemList',
								'name'        => get_the_title(),
								'description' => '',
								'url'         => esc_url( get_the_permalink() ),
							];

						} else {
							$archive_data = [
								'@context'    => 'https://schema.org',
								'@type'       => 'CollectionPage',
								'@id'         => trailingslashit( esc_url( get_post_type_archive_link( get_queried_object()->name ) ) ) . '#CollectionPage',
								'headline'    => get_queried_object()->label,
								'description' => '',
								'url'         => esc_url( get_post_type_archive_link( get_queried_object()->name ) ),
							];
						}
					}
				}

				$itemData = [];
				$per_page = get_option( 'posts_per_page' );
				if ( get_query_var( 'taxonomy' ) == 'product_cat' || get_query_var( 'taxonomy' ) == 'product_tag' ) {
					$args = [
						'post_type'      => 'product',
						'posts_per_page' => $per_page,
						'paged'          => get_query_var( 'paged' ),
						'tax_query'      => [
							[
								'taxonomy' => get_query_var( 'taxonomy' ),
								'field'    => 'slug',
								'terms'    => get_query_var( 'term' ),
							],
						],
					];

					// Set the query
					$wp_query = new \WP_Query( $args );
				}

				if ( $classified_main_home ) {
					$args = [
						'post_type'      => 'rtcl_listing',
						'posts_per_page' => $per_page,
						'paged'          => get_query_var( 'paged' ),
					];

					// Set the query
					$wp_query = new \WP_Query( $args );
				}

				while ( $wp_query->have_posts() ) {
					$wp_query->the_post();

					$prefix  = 'rtrs_';
					$post_id = get_the_ID();

					$custom_snippet = get_post_meta( $post_id, '_rtrs_custom_rich_snippet', true );

					if ( $custom_snippet ) {
						$schemaCat = get_post_meta( $post_id, '_rtrs_rich_snippet_cat', false );
						foreach ( $schemaCat as $singleCat ) {
							$metaData = get_post_meta( $post_id, $prefix . $singleCat . '_schema', true );
							foreach ( $metaData as $meta ) {
								if ( $meta['status'] == 'show' ) {
									$itemData[] = $schema_obj->schemaOutput( $singleCat, $meta, true );
								}
							}
						}
					} else { // auto generate
						$itemData[] = $schema_obj->autoSchemaOutput( $schema_type, $post_id, true );
					}
				}
				if ( $product_archive || $classified_archive || $product_main_home ) {
					$archive_data['itemListElement'] = $itemData;
				} else {
					$archive_data['hasPart'] = $itemData;
				}
				$schema_obj = $schema_obj->getJsonEncode( apply_filters( 'rtseo_archive_page', $archive_data ) );
				echo $schema_obj;
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $p_meta Metabox.
	 * @param [type] $comment Current Comment
	 * @return void
	 */
	public function after_review_edit_form( $p_meta, $comment ) {
		?>
		<?php

		$video_review   = ( isset( $p_meta['video_review'] ) && $p_meta['video_review'][0] == '1' );
		$get_attachment = get_comment_meta( $comment->comment_ID, 'rt_attachment', true );
		if ( $video_review && isset( $get_attachment['videos'] ) ) {
			?>
			<tr>
				<td class="first"><label for="rt_video"><?php esc_html_e( 'Video', 'review-schema-pro' ); ?></label></td>
				<td>
					<div class="rtrs-review-item-media">
					<?php
					$video_source = isset( $get_attachment['video_source'] ) ? $get_attachment['video_source'] : 'self';
					$self_video   = $video_source === 'self';
					foreach ( $get_attachment['videos'] as $video ) {
						?>
							<div class="rtrs-media-item rtrs-media-video rtrs-preview-videos">
								<?php
								$youtube_video_id = '';
								if ( ! $self_video ) {
									$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
									preg_match( $pattern, $video, $matches );
									$youtube_video_id = ( isset( $matches[1] ) ) ? $matches[1] : '';
								}
								$image_url = $self_video ? Functions::get_default_placeholder_url() : 'https://img.youtube.com/vi/' . $youtube_video_id . '/default.jpg';

								$video_url = $self_video ? wp_get_attachment_url( $video ) : 'https://www.youtube.com/embed/' . $youtube_video_id;
								?>
								<div class="rtrs-preview-video">
								<div style="display:none;">
									<select name="rt_video_source" id="rtrs-video-source" class="rtrs-form-control">
										<option <?php echo esc_attr( ! $self_video ? 'selected' : '' ); ?> value="external"><?php esc_html_e( 'External Video', 'review-schema-pro' ); ?></option>
										<option <?php echo esc_attr( $self_video ? 'selected' : '' ); ?> value="self"><?php esc_html_e( 'Hosted Video', 'review-schema-pro' ); ?></option>
									</select> 
								</div>
								
								<?php if ( $self_video ) { ?>
									<input type="hidden" name="rt_attachment[videos][]" value="<?php echo absint( $video ); ?>">
								<?php } else { ?>
									<input id="rt_external_video" class="rtrs-form-control" placeholder="<?php esc_attr_e( 'https://www.youtube.com/watch?v=668nUCeBHyY', 'review-schema-pro' ); ?>" name="rt_external_video" type="hidden" value="<?php echo esc_url( $video_url ); ?>">
								<?php } ?>
								
								<!-- .rtrs-preview-videos .rtrs-preview-video .rtrs-file-remove -->
								<a target="_blank" href="<?php echo esc_url( $video_url ); ?>"  class="rtrs-video-icon rtrs-play-self-video">
								<span class="name">
									<img src="<?php echo esc_url( $image_url ); ?>" style="width: 100%;" alt="<?php esc_attr_e( 'Review Schema', 'review-schema-pro' ); ?>"> 
								</span> 
								<i class="rtrs-play dashicons dashicons-controls-play"></i></a>
								<button class="rtrs-file-remove" data-id="<?php echo absint( $video ); ?>">x</button>
								</div> 
							</div>
						<?php
					}
					?>
					</div>
				</td>
			</tr>
			<?php
		}

		$highlight_review = ( isset( $p_meta['highlight_review'] ) && $p_meta['highlight_review'][0] == '1' );
		$sticky_review     = ( isset( $p_meta['sticky_review'] ) && $p_meta['sticky_review'][0] == '1' );
		if ( $highlight_review ) {
			?>
			<tr> 
				<td class="first"><label for="rt_highlight"><?php esc_html_e( 'Highlight?', 'review-schema-pro' ); ?></label></td>
				<td><input type="checkbox" 
				<?php
				if ( get_comment_meta( $comment->comment_ID, 'rt_highlight', true ) ) {
					echo 'checked';}
				?>
				name="rt_highlight" id="rt_highlight"></td>
			</tr>
			<?php
		}
		if ( $sticky_review ) {
			?>
			<tr> 
				<td class="first"><label for="rt_sticky_review"><?php esc_html_e( 'Sticky Review?', 'review-schema-pro' ); ?></label></td>
				<td><input type="checkbox" 
				<?php
				if ( get_comment_meta( $comment->comment_ID, 'rt_sticky_review', true ) ) {
					echo 'checked';}
				?>
				name="rt_sticky_review" id="rt_sticky_review"></td>
			</tr>
			<?php
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $p_meta Metabox.
	 * @param [type] $comment Current Comment
	 * @return void
	 */
	public function before_review_comments_list( $p_meta, $post_id ) {
		$sticky_review = ( isset( $p_meta['sticky_review'] ) && $p_meta['sticky_review'][0] == '1' );
		if ( $sticky_review ) {
			$args     = [
				'post_id'    => $post_id,
				'status'     => 'approve', // Change this to the type of comments to be displayed
				'meta_query' => [
					[
						'key'     => 'rt_sticky_review',
						'value'   => '1',
						'compare' => '==',
					],
				],
			];
			$comments = get_comments( $args );
			wp_list_comments(
				[
					'style'      => 'li',
					'short_ping' => true,
					'callback'   => [ Review::class, 'comment_list' ],
				],
				$comments
			);
		}
	}



}

<?php

namespace Rtrsp\Controllers\Admin;

use Rtrs\Helpers\Functions;
use Rtrsp\Traits\SingletonTrait;

class HookFilter {

	use SingletonTrait;

	function init() {
		add_filter( 'rtrs_schema_sub_sections', [ &$this, 'rtrs_schema_sub_sections' ] );
		add_filter( 'rtrs_schema_sub_organization_settings_options', [ &$this, 'rtrs_schema_sub_organization_settings_options' ] );
		add_filter( 'rtrs_schema_archive_settings_options', [ &$this, 'rtrs_schema_archive_settings_options' ] );
		add_filter( 'rtrs_media_settings_options', [ &$this, 'rtrs_media_settings_options' ] );
		add_filter( 'rtrs_register_settings_tabs', [ &$this, 'register_settings_tabs' ] );
		add_filter( 'rtrs_rich_snippet_cats', [ $this, 'rtrs_rich_snippet_cats' ] );
		add_filter( 'rtrs_each_review_classes', [ $this, 'each_review_classes' ], 10, 3 );
		add_filter( 'rtrs_comments_query_args', [ $this, 'comments_query_args' ], 10, 2 );
		add_filter( 'rtrs_review_section_style_fields', [ $this, 'review_section_style_fields' ] );
		add_filter( 'rtrs_review_sc_css', [ $this, 'rtrs_sc_css' ], 10, 4 );

		// add_action( 'wp_footer', array( $this, 'archive_page' ), 999 );
	}

	function rtrs_rich_snippet_cats( $snippet_cats ) {
		$snippet_cats['tv_series'] = esc_html__( 'TV Series', 'review-schema-pro' );
		return $snippet_cats;
	}

	function rtrs_schema_sub_sections( $default ) {
		$new     = [
			'sub_organization' => esc_html__( 'Sub Organization', 'review-schema-pro' ),
		];
		$default = array_merge( array_slice( $default, 0, 2 ), $new, array_slice( $default, 2 ) );

		$new     = [
			'archive' => esc_html__( 'Archive Page', 'review-schema-pro' ),
		];
		$default = array_merge( array_slice( $default, 0, 6 ), $new, array_slice( $default, 6 ) );

		return apply_filters( 'rtrsp_schema_sub_sections', $default );
	}

	function rtrs_schema_sub_organization_settings_options( $default ) {
		$new = [
			'sub_organization_section' => [
				'title'       => esc_html__( 'Sub Organization', 'review-schema-pro' ),
				'description' => esc_html__( 'Provide your sub organization information to a Google Knowledge panel', 'review-schema-pro' ),
				'type'        => 'title',
			],
			'sub_organization'         => [
				'type'   => 'group',
				'title'  => esc_html__( 'Sub Organization', 'review-schema-pro' ),
				'fields' => [
					'name' => [
						'type'  => 'text',
						'class' => 'regular-text',
						'title' => esc_html__( 'Name', 'review-schema-pro' ),
					],
					'url'  => [
						'type'  => 'text',
						'class' => 'regular-text',
						'title' => esc_html__( 'URL', 'review-schema-pro' ),
					],
				],
			],
		];

		return apply_filters( 'rtrsp_schema_sub_organization_settings_options', $new );
	}

	function rtrs_schema_archive_settings_options( $default ) {
		$new = [
			'archive'     => [
				'title'       => esc_html__( 'Archive?', 'review-schema-pro' ),
				'description' => esc_html__( 'This archive page is for blog post', 'review-schema-pro' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable', 'review-schema-pro' ),
			],
			'schema_type' => [
				'title'   => esc_html__( 'Schema Type', 'review-schema-pro' ),
				'type'    => 'select',
				'class'   => 'regular-text',
				'options' => [
					'article'      => esc_html__( 'Article', 'review-schema-pro' ),
					'news_article' => esc_html__( 'News Article', 'review-schema-pro' ),
					'blog_posting' => esc_html__( 'Blog Posting', 'review-schema-pro' ),
				],
				'empty'   => esc_html__( 'Select One', 'review-schema-pro' ),
			],
		];

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
			$new['product_archive'] = [
				'title'       => esc_html__( 'Product Archive?', 'review-schema-pro' ),
				'description' => esc_html__( 'This archive page is for WooCommerce and EDD product', 'review-schema-pro' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable', 'review-schema-pro' ),
			];
		}

		if ( is_plugin_active( 'classified-listing/classified-listing.php' ) ) {
			$new['cl_archive'] = [
				'title'       => esc_html__( 'Classified Listing Archive?', 'review-schema-pro' ),
				'description' => esc_html__( 'This archive page is for Classified Listing', 'review-schema-pro' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable', 'review-schema-pro' ),
			];
		}

		return apply_filters( 'rtrsp_schema_archive_settings_options', $new );
	}

	function rtrs_media_settings_options( $default ) {

		$video_type = apply_filters(
			'rtrs_media_video_type',
			[
				'video/mp4' => esc_html__( 'mp4', 'review-schema-pro' ),
				'video/m4v' => esc_html__( 'm4v', 'review-schema-pro' ),
				'video/mov' => esc_html__( 'mov', 'review-schema-pro' ),
				'video/wmv' => esc_html__( 'wmv', 'review-schema-pro' ),
				'video/avi' => esc_html__( 'avi', 'review-schema-pro' ),
				'video/mpg' => esc_html__( 'mpg', 'review-schema-pro' ),
				'video/mov' => esc_html__( 'mov', 'review-schema-pro' ),
				'video/ogv' => esc_html__( 'ogv', 'review-schema-pro' ),
				'video/3gp' => esc_html__( '3gp', 'review-schema-pro' ),
				'video/3g2' => esc_html__( '3g2', 'review-schema-pro' ),
			]
		);

		$new = [
			'video_section'  => [
				'title' => esc_html__( 'Video Upload Settings', 'review-schema-pro' ),
				'type'  => 'title',
			],
			'video_max_size' => [
				'title'       => esc_html__( 'Video Max Size', 'review-schema-pro' ),
				'type'        => 'number',
				'default'     => 2048,
				'css'         => 'width: 70px',
				'description' => esc_html__( 'Change the value as KB, Like 1M = 1024KB', 'review-schema-pro' ),
			],
			'video_type'     => [
				'title'   => esc_html__( 'Supported Video Type', 'review-schema-pro' ),
				'type'    => 'multi_checkbox',
				'default' => [
					'video/mp4',
					'video/m4v',
					'video/mov',
					'video/wmv',
					'video/avi',
					'video/mpg',
					'video/mov',
					'video/ogv',
					'video/3gp',
					'video/3g2',
				],
				'options' => $video_type,
			],
		];

		$default = array_merge( $default, $new );

		return apply_filters( 'rtrsp_media_settings_options', $default );
	}

	function register_settings_tabs( $default ) {
		$default['tools'] = esc_html__( 'Tools', 'review-schema-pro' );

		return apply_filters( 'rtrsp_register_settings_tabs', $default );
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public function each_review_classes( $classes, $p_meta, $comment ) {
		$classes[] = get_comment_meta( get_comment_ID(), 'rt_highlight', true ) == 1 ? 'rtrs-top-review' : '';
		$classes[] = get_comment_meta( get_comment_ID(), 'rt_sticky_review', true ) == 1 ? 'rtrs-sticky-review' : '';

		$purchased_badge = ( isset( $p_meta['purchased_badge'] ) && $p_meta['purchased_badge'][0] == '1' );
		if ( $purchased_badge ) {
			$classes[] = Functions::purchased_user( $comment ) ? 'verified-user' : '';
		}
		return $classes;
	}

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public function comments_query_args( $args, $p_meta ) {
		$sticky_review = ( isset( $p_meta['sticky_review'] ) && $p_meta['sticky_review'][0] == '1' );
		if ( $sticky_review ) {
			$args['meta_query'] = [
				'relation' => 'OR',
				[
					'key'     => 'rt_sticky_review',
					'value'   => '1',
					'compare' => '!=',
				],
				[
					'key'     => 'rt_sticky_review',
					'compare' => 'NOT EXISTS',
					'value'   => '',
				],
			];
		}
		return $args;
	}
	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public function review_section_style_fields( $styles ) {
		$styles[] = [
			'type'   => 'color',
			'name'   => 'sticky_background_color',
			'id'     => 'rtrs-sticky-background-color',
			'is_pro' => true,
			'label'  => esc_html__( 'sticky Background Color', 'review-schema-pro' ),
		];
		$styles[] = [
			'type'   => 'color',
			'name'   => 'sticky_boxshadow_color',
			'id'     => 'rtrs-sticky-background-color',
			'is_pro' => true,
			'label'  => esc_html__( 'sticky BoxShadow Color', 'review-schema-pro' ),
		];
		return $styles;
	}
	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	public function rtrs_sc_css( $css, $metaData, $sc_id, $filter ) {
		$sticky_background = isset( $metaData['sticky_background_color'][0] ) && ! empty( $metaData['sticky_background_color'][0] ) ? $filter->sanitize_field( 'color', $metaData['sticky_background_color'][0] ) : null;
		$sticky_shadow     = isset( $metaData['sticky_boxshadow_color'][0] ) && ! empty( $metaData['sticky_boxshadow_color'][0] ) ? $filter->sanitize_field( 'color', $metaData['sticky_boxshadow_color'][0] ) : null;
		if ( $sticky_background || $sticky_shadow ) {
			$css .= ".rtrs-review-sc-{$sc_id} .rtrs-each-review.rtrs-sticky-review{";
			if ( $sticky_background ) {
				$css .= 'background-color:' . $sticky_background . ';';
			}
			if ( $sticky_shadow ) {
				$css .= 'box-shadow: 0 1px 4px 0 ' . $sticky_shadow . ';';
			}
			$css .= '}';
		}
		return $css;
	}


}




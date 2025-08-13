<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

 namespace radiustheme\listygo;

use Rtcl\Helpers\Functions;

class Ad_Management {

	protected static $instance = null;

	public function __construct() {
		// Common
		add_action( 'listygo_header_ad',     array( $this, 'header_ad' ) );
		add_action( 'listygo_footer_ad',  array( $this, 'footer_ad' ) );
		add_action( 'listygo_before_sidebar_ad', array( $this, 'before_sidebar_ad' ) );
		add_action( 'listygo_after_sidebar_ad', array( $this, 'after_sidebar_ad' ) );
		add_action( 'listygo_before_content_ad', array( $this, 'before_content_ad' ) );
		add_action( 'listygo_after_content_ad',  array( $this, 'after_content_ad' ) );

		// Listing Archive
		add_action( 'listygo_listing_archive_before_content_ad',  array( $this, 'listing_archive_before_contents' ) );
		add_action( 'listygo_listing_archive_after_content_ad',   array( $this, 'listing_archive_after_contents' ) );

		// Listing Single
		add_action( 'listygo_single_listing_before_contents_ad', array( $this, 'single_listing_before_contents' ) );
		add_action( 'listygo_single_listing_after_contents_ad', array( $this, 'single_listing_after_contents' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function header_ad() {
		switch ( $this->get_page_type() ) {
			case 'listing_archive':
			$this->render_ad( 'listing_archive_header_ad', 'ad-header-top' );
			break;
			case 'listing_single':
			$this->render_ad( 'listing_single_header_ad', 'ad-header-top' );
			break;
			case 'blog':
			$this->render_ad( 'blog_archive_header_ad', 'ad-header-top' );
			break;
			case 'post':
			$this->render_ad( 'single_post_header_ad', 'ad-header-top' );
			break;
			case 'page':
			$this->render_ad( 'page_header_ad', 'ad-header-top' );
			break;
		}
	}

	public function footer_ad() {
		switch ( $this->get_page_type() ) {
			case 'listing_archive':
			$this->render_ad( 'listing_archive_footer_ad', 'ad-footer container' );
			break;
			case 'listing_single':
			$this->render_ad( 'listing_single_footer_ad', 'ad-footer container' );
			break;
			case 'blog':
			$this->render_ad( 'blog_archive_footer_ad', 'ad-footer container' );
			break;
			case 'post':
			$this->render_ad( 'single_post_footer_ad', 'ad-footer container' );
			break;
			case 'page':
			$this->render_ad( 'page_footer_ad', 'ad-footer container' );
			break;
		}
	}

	public function before_sidebar_ad() {
		if ( is_page_template( 'templates/listing-map.php' ) ) {
			$this->render_ad( 'listing_archive_before_sidebar_ad', 'ad-before-sidebar' );
		} else {
			switch ( $this->get_page_type() ) {
				case 'listing_archive':
					$this->render_ad( 'listing_archive_before_sidebar_ad', 'ad-before-sidebar' );
					break;
				case 'listing_single':
					$this->render_ad( 'listing_single_before_sidebar_ad', 'ad-before-sidebar' );
					break;
				case 'blog':
					$this->render_ad( 'blog_archive_before_sidebar_ad', 'ad-before-sidebar' );
					break;
				case 'post':
					$this->render_ad( 'single_post_before_sidebar_ad', 'ad-before-sidebar' );
					break;
				case 'page':
					$this->render_ad( 'page_before_sidebar_ad', 'ad-before-sidebar' );
					break;
			}
		}
	}

	public function after_sidebar_ad() {
		if ( is_page_template( 'templates/listing-map.php' ) ) {
			$this->render_ad( 'listing_archive_before_sidebar_ad', 'ad-after-sidebar' );
		} else {
			switch ( $this->get_page_type() ) {
				case 'listing_archive':
					$this->render_ad( 'listing_archive_after_sidebar_ad', 'ad-after-sidebar' );
					break;
				case 'listing_single':
					$this->render_ad( 'listing_single_after_sidebar_ad', 'ad-after-sidebar' );
					break;
				case 'blog':
					$this->render_ad( 'blog_archive_after_sidebar_ad', 'ad-after-sidebar' );
					break;
				case 'post':
					$this->render_ad( 'single_post_after_sidebar_ad', 'ad-after-sidebar' );
					break;
				case 'page':
					$this->render_ad( 'page_after_sidebar_ad', 'ad-after-sidebar' );
					break;
			}
		}
	}

	public function before_content_ad() {
		switch ( $this->get_page_type() ) {
			case 'blog':
				$this->render_ad( 'blog_archive_before_all_post_ad', 'ad-before-content' );
				break;
			case 'post':
				$this->render_ad( 'single_post_before_content_ad', 'ad-before-content' );
				break;
			case 'page':
				$this->render_ad( 'page_before_content_ad', 'ad-before-content' );
				break;
		}
	}

	public function after_content_ad() {
		switch ( $this->get_page_type() ) {
			case 'blog':
				$this->render_ad( 'blog_archive_after_all_post_ad', 'ad-after-content' );
				break;
			case 'post':
				$this->render_ad( 'single_post_after_content_ad', 'ad-before-content' );
				break;
			case 'page':
				$this->render_ad( 'page_after_content_ad', 'ad-after-content' );
				break;
		}		
	}

	public function listing_archive_before_contents() {
		$this->render_ad( 'listing_archive_before_all_listing_ad', 'ad-listing-archive-before-contents' );
	}

	public function listing_archive_after_contents() {
		$this->render_ad( 'listing_archive_after_all_listing_ad', 'ad-listing-archive-after-contents' );
	}

	public function single_listing_before_contents() {
		$this->render_ad( 'listing_single_before_content_ad', 'ad-single-listing-before-contents' );
	}

	public function single_listing_after_contents() {
		$this->render_ad( 'listing_single_after_content_ad', 'ad-single-listing-after-contents' );
	}

	private function render_ad( $base, $class='' ) {

		if ( !RDTListygo::$options[$base.'_activate'] ) {
			return;
		}
		if ( RDTListygo::$options[$base.'_type'] == 'image' ) {
			$html = $this->get_image_ad( $base );
		}
		else {
			$html = RDTListygo::$options[ $base.'_code' ];
		}

		if ( !$html ) {
			return;
		}

		$html = sprintf( '<div class="listygo-ad %1$s">%2$s</div>' , $class, $html );
		echo do_shortcode( $html );
	}

	private function get_page_type() {
		if ( class_exists( 'RtclPro' ) && Functions::is_listings() ) {
			return 'listing_archive';
		}
		if ( is_singular( 'rtcl_listing' ) ) {
			return 'listing_single';
		}
		if ( is_home() || is_archive() ) {
			return 'blog';
		}
		if ( is_singular( 'post' ) ) {
			return 'post';
		}
		if ( is_page() ) {
			return 'page';
		}
		return '';
	}

	private function get_image_ad( $base ){
		if ( empty( RDTListygo::$options[$base.'_image'] ) ) {
			return;
		}

		$img_html = wp_get_attachment_image( RDTListygo::$options[$base.'_image'], 'full' );

		if ( !$img_html ) {
			return;
		}

		if ( !RDTListygo::$options[$base.'_link'] ) {
			$html = $img_html;
		} else {
			$attr = ' href="'.esc_url(RDTListygo::$options[$base.'_link']).'"';
			if ( RDTListygo::$options[$base.'_newtab'] ) {
				$attr .= ' target="_blank"';
			}
			if ( RDTListygo::$options[$base.'_nofollow'] ) {
				$attr .= ' rel="nofollow"';
			}
			$html = sprintf( '<a%1$s>%2$s</a>' , $attr, $img_html );
		}
		return $html;
	}
}

Ad_Management::instance();
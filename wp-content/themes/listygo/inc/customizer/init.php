<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\listygo\Helper;

// Controls
Helper::requires( 'customizer/controls/switch-control.php' );
Helper::requires( 'customizer/controls/image-radio-control.php' );
Helper::requires( 'customizer/controls/heading-control.php' );
Helper::requires( 'customizer/controls/heading-control2.php' );
Helper::requires( 'customizer/controls/separator-control.php' );
Helper::requires( 'customizer/controls/gallery-control.php' );
Helper::requires( 'customizer/controls/select2-control.php' );
Helper::requires( 'customizer/controls/control-checkbox-multiple.php' );
Helper::requires( 'customizer/typography/typography-controls.php');
Helper::requires( 'customizer/typography/typography-customizer.php');
Helper::requires( 'customizer/controls/sanitization.php');
Helper::requires( 'customizer/customizer.php');

// General
Helper::requires( 'customizer/settings/general.php');
Helper::requires( 'customizer/settings/header.php');
Helper::requires( 'customizer/settings/footer.php');
Helper::requires( 'customizer/settings/footer-apps.php');
Helper::requires( 'customizer/settings/colors.php');

// Options
Helper::requires( 'customizer/settings/blog.php');
Helper::requires( 'customizer/settings/single.php');
Helper::requires( 'customizer/settings/error.php');

// Layout
Helper::requires( 'customizer/settings/blog-layouts.php');
Helper::requires( 'customizer/settings/page-layout.php');
Helper::requires( 'customizer/settings/blog-single-layout.php');

Helper::requires( 'customizer/settings/listing-layout.php');
Helper::requires( 'customizer/settings/listing-single-layout.php');

Helper::requires( 'customizer/settings/search-layout.php');
Helper::requires( 'customizer/settings/error-layout.php');

// Listing
if ( class_exists( 'Rtcl' ) ) {
	Helper::requires( 'customizer/settings/listing-archive.php');
	Helper::requires( 'customizer/settings/listing-single.php');
	Helper::requires( 'customizer/settings/listing-search.php');
}

// Listing Archive
Helper::requires( 'customizer/settings/advertisements/listing-archive/header.php');
Helper::requires( 'customizer/settings/advertisements/listing-archive/before-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/listing-archive/after-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/listing-archive/before-all-listing.php');
Helper::requires( 'customizer/settings/advertisements/listing-archive/after-all-listing.php');
Helper::requires( 'customizer/settings/advertisements/listing-archive/footer.php');
// Listing Single
Helper::requires( 'customizer/settings/advertisements/listing-single/header.php');
Helper::requires( 'customizer/settings/advertisements/listing-single/before-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/listing-single/after-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/listing-single/before-content.php');
Helper::requires( 'customizer/settings/advertisements/listing-single/after-content.php');
Helper::requires( 'customizer/settings/advertisements/listing-single/footer.php');
// Blog Archive
Helper::requires( 'customizer/settings/advertisements/blog-archive/header.php');
Helper::requires( 'customizer/settings/advertisements/blog-archive/before-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/blog-archive/after-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/blog-archive/before-all-post.php');
Helper::requires( 'customizer/settings/advertisements/blog-archive/after-all-post.php');
Helper::requires( 'customizer/settings/advertisements/blog-archive/footer.php');
// Single Post
Helper::requires( 'customizer/settings/advertisements/single-post/header.php');
Helper::requires( 'customizer/settings/advertisements/single-post/before-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/single-post/after-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/single-post/before-content.php');
Helper::requires( 'customizer/settings/advertisements/single-post/after-content.php');
Helper::requires( 'customizer/settings/advertisements/single-post/footer.php');
// Page Ad
Helper::requires( 'customizer/settings/advertisements/page/header.php');
Helper::requires( 'customizer/settings/advertisements/page/before-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/page/after-sidebar.php');
Helper::requires( 'customizer/settings/advertisements/page/before-content.php');
Helper::requires( 'customizer/settings/advertisements/page/after-content.php');
Helper::requires( 'customizer/settings/advertisements/page/footer.php');
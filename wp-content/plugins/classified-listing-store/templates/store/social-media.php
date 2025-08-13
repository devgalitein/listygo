<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.2.31
 *
 * @var Store $store
 */

use RtclStore\Models\Store;


if ( empty( $store ) ) {
	global $store;
}

if ( empty( $store ) ) {
	return;
}

$social_media = $store->get_social_media();
if ( empty( $social_media ) ) {
	return;
}
foreach ( $social_media as $key => $social_media_url ) {
	$social_media_class = 'twitter' === $key ? 'fa-brands fa-x-twitter' : 'rtcl-icon-' . $key;
	?>
    <a class="<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $social_media_url ); ?>" target="_blank"
       rel="nofollow"><i class="rtcl-icon <?php echo esc_attr( $social_media_class ); ?>"></i></a>
	<?php
}
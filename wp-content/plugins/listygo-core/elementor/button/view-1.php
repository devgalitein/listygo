<?php
/**
 *
 * This file can be overridden by copying it to yourtheme/elementor-custom/button/view-1.php
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;

extract( $data );

$attr = '';
if ( !empty( $url['url'] ) ) {
	$attr  = 'href="' . $data['url']['url'] . '"';
	$attr .= !empty( $data['url']['is_external'] ) ? ' target="_blank"' : '';
	$attr .= !empty( $data['url']['nofollow'] ) ? ' rel="nofollow"' : '';
}
if ( !empty( $url['url'] ) ) {	
?>

<div class="btn-wrap">
	<a <?php echo $attr; ?> class="item-btn"><?php echo esc_html( $data['btntext'] ); ?> <?php echo Helper::btn_right_icon(); ?></a>
</div>
<?php } ?>


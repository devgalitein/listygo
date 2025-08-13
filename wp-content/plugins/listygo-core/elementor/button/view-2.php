<?php
/**
 *
 * This file can be overridden by copying it to yourtheme/elementor-custom/button/view-2.php
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;
extract( $data );
	$attr = '';
	if ( !empty( $data['url']['url'] ) ) {
		$attr  = 'href="' . $data['url']['url'] . '"';
		$attr .= !empty( $data['url']['is_external'] ) ? ' target="_blank"' : '';
		$attr .= !empty( $data['url']['nofollow'] ) ? ' rel="nofollow"' : '';
	}
if ( !empty( $data['url']['url'] ) ) {	
?>
<div class="btn-wrap btn-v2">
	<a <?php echo $attr; ?> class="item-btn">
		<span class="btn__icon">
			<?php echo Helper::btn_right_icon(); ?>
		</span>
		<span class="btn__text"><?php echo esc_html( $data['btntext'] ); ?></span>
		<span class="btn__icon">
			<?php echo Helper::btn_right_icon(); ?>
		</span>
	</a>
</div>

<?php } ?>

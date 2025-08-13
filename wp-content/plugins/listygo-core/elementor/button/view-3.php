<?php
/**
 *
 * This file can be overridden by copying it to yourtheme/elementor-custom/button/view-3.php
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
extract( $data );
	$attr = '';
	if ( !empty( $data['url']['url'] ) ) {
		$attr  = 'href="' . $data['url']['url'] . '"';
		$attr .= !empty( $data['url']['is_external'] ) ? ' target="_blank"' : '';
		$attr .= !empty( $data['url']['nofollow'] ) ? ' rel="nofollow"' : '';
	}
if ( !empty( $data['url']['url'] ) ) {	
?>

<div class="btn-wrap btn-v3">
	<?php if ( !empty( $data['url']['url'] ) ) { ?>
		<a class="event-figure__play play-btn" href="<?php echo esc_url( $data['url']['url'] ); ?>"  data-toggle="modal">
			<svg width="24" height="28" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M24 14L6.95983e-07 27.8564L1.90735e-06 0.143554L24 14Z" />
			</svg>
			<svg class="ripple-shape" viewBox="0 0 320 320">
		      <defs>
		        <circle id="circle-clip" cx="50%" cy="50%" r="25%" />
		        <clipPath id="avatar-clip">
		          <use href="#circle-clip" />
		        </clipPath>
		      </defs>

		      <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
		        <animate attributeName="r" values="25%;50%" dur="4s" repeatCount="indefinite" />
		        <animate attributeName="fill-opacity" values="1;0" dur="4s" repeatCount="indefinite" />
		      </circle>

		      <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
		        <animate attributeName="r" values="25%;50%" dur="4s" begin="1s" repeatCount="indefinite" />
		        <animate attributeName="fill-opacity" values="1;0" dur="4s" begin="1s" repeatCount="indefinite" />
		      </circle>

		      <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
		        <animate attributeName="r" values="25%;50%" dur="4s" begin="2s" repeatCount="indefinite" />
		        <animate attributeName="fill-opacity" values="1;0" dur="4s" begin="2s" repeatCount="indefinite" />
		      </circle>

		      <circle cx="50%" cy="50%" r="25%" fill="white" fill-opacity="1">
		        <animate attributeName="r" values="25%;50%" dur="4s" begin="3s" repeatCount="indefinite" />
		        <animate attributeName="fill-opacity" values="1;0" dur="4s" begin="3s" repeatCount="indefinite" />
		      </circle>
		    </svg>
		</a>
	<?php } ?>
</div>
<?php } ?>

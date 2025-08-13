<?php
/**
 * Booking Pagination
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var int $post_per_page
 * @var int $pages
 * @var string $booking
 */
?>
<div class="rtcl-booking-pagination" data-booking="<?php echo esc_attr( $booking ); ?>"
     data-per-page="<?php echo esc_attr( $post_per_page ); ?>">
	<?php
	for ( $i = 1; $i <= $pages; $i ++ ) {
		$class = '';
		if ( $i == 1 ) {
			$class = 'current';
		}
		?>
        <a href="#" class="<?php echo esc_attr( $class ); ?>"
           data-page="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></a>
		<?php
	}
	?>
</div>
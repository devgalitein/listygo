<?php 
namespace radiustheme\listygo;
use radiustheme\listygo\Helper;
// Logo
$rdtheme_light_logo = empty( Helper::rt_the_logo_dark() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_dark();
?>
<div class="header-logo logo-black">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-logo">
		<?php echo wp_kses_post( $rdtheme_light_logo ); ?>
	</a>
	<?php 
		if ( display_header_text() == true ) {
			$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) : ?>
			<div class="site-description"><?php echo esc_html( $description ); ?></div>
		<?php endif; 
		}
	?>
</div>
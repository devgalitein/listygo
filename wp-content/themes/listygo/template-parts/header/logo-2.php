<?php 
namespace radiustheme\listygo;
use radiustheme\listygo\Helper;
// Logo
$rdtheme_sticky_logo = empty( Helper::rt_the_logo_dark() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_dark();
?>
<div class="header-logo logo-white sticky-logo">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-light">
		<?php if ( !empty( Helper::rt_the_logo_light() ) ){
			echo Helper::rt_the_logo_light();
		} else {
			echo wp_kses_post( $rdtheme_sticky_logo );
		} ?>
	</a>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-dark">
		<?php if ( !empty( Helper::rt_the_logo_dark() ) ){
			echo Helper::rt_the_logo_dark();
		} else {
			echo wp_kses_post( $rdtheme_sticky_logo );
		} ?>
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
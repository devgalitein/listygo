<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Listygo_Core;
use radiustheme\listygo\Helper;
use radiustheme\listygo\Listing_Functions;

$keyword = isset( $_GET['q'] ) ? $_GET['q'] : '';
$hcs = $data['highlight_categories'];
$search_style = $data['listing_banner_search_style'];

?>

<!-- Hero layout1 -->
<div class="hero hero--layout1 hero--layout2  position-relative">
	<div class="hero-banner hero-banner--style2 wh-100" data-bg-image="<?php echo esc_url($data['bgimage']['url']); ?>"></div>
	<div class="hero-shape"><img src="<?php echo Helper::get_img('theme/hero-two-shape.svg') ?>" alt="<?php esc_attr_e( 'Shape', 'listygo-core' ) ?>"></div>
	<div class="hero__wrapper">
		<div class="container">
			<div class="row align-items-center justify-content-between">
				<div class="col-lg-5 mb-30">
					<div class="hero-content hero-content--style2">
						<h1 class="hero-content__main-title"><?php echo esc_html( $data['title'] ); ?></h1>
						<svg class="hero-content__shape" width="309" height="23" viewBox="0 0 309 23" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M272.094 9.86682C213.205 4.37611 53.1496 11.5589 0 23.0001C93.0363 -11.3375 212.671 0.393272 272.094 9.86682C278.669 10.4799 283.983 11.2509 287.747 12.1917C289.92 12.735 291.915 13.2269 293.737 13.6702C312.582 17.3284 315.539 18.9743 293.737 13.6702C287.94 12.5449 280.64 11.2292 272.094 9.86682Z" fill="#FF3C48" />
						</svg>
					</div>
					<?php 
						$terms = $hcs;
						if(is_array($terms)){ ?>
							<span class="hero-categories--title"><?php echo esc_html( $data['catsubtitle'] ); ?></span>
							<div class="hero-categories hero-categories--style2">
								<?php     	
									foreach ($terms as $key => $value) {
										$term_id = get_term( $value )->term_id;
										$term_icon = get_term_meta( $term_id, '_rtcl_icon', true );
										$count = get_term( $value )->count;
										if ($count < 10 ) {
											$count = '0'.$count;
										} else {
											$count = $count;	
										}
								?>
								<a href="<?php echo esc_url( get_term_link( get_term( $value ), get_term( $value )->name ) ); ?>" class="hero-categoriesBlock hero-categoriesBlock--style2">
									<?php echo wp_kses_post( Listing_Functions::listygo_cat_icon( $term_id, 'icon' ) ); ?>
									<?php echo esc_html( get_term( $value )->name ); ?>
								</a>
								<?php } ?>
							</div>
					<?php } ?>
				</div>
				<div class="col-lg-5 mb-30">
					<div class="search-banner-wrap">
							<span class="hero-content__sub-title subtitle-v2"><?php echo esc_html( $data['subtitle'] ); ?></span>
							<h1 class="hero-content__main-title title-v2"><?php echo esc_html( $data['title'] ); ?></h1>
							<?php Helper::get_custom_listing_template( 'listing-search', true, compact('search_style') ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Hero layout1 End -->
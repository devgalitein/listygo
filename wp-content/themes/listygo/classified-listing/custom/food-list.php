<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use Rtcl\Helpers\Functions;
use radiustheme\listygo\Listing_Functions;
global $listing;


$restaurant_cat_id = Listing_Functions::get_listing_restaurant_category_id();
if ($restaurant_cat_id) {
	$has_restaurant = Listing_Functions::listygo_selected_category_fields( $category_id, $restaurant_cat_id );
} else {
	$has_restaurant = '';
}

if ( $has_restaurant && Listing_Functions::is_enable_restaurant_listing() ):
	$foodList = get_post_meta( $listing->get_id(), "listygo_food_list", true );
	$generalSettings = Functions::get_option( 'rtcl_general_settings' );
	$sectionLabel = !empty( $generalSettings['listygo_food_list_section_label'] ) ? $generalSettings['listygo_food_list_section_label'] : '';
?>
	<?php if ( ! empty( $foodList ) ) { ?>
	<div class="product-plan widget" id="food-plan">
		<div class="item-heading">
	     	<div class="align-items-center">
	        	<h2 class="listingDetails-block__heading"><?php echo esc_html( $sectionLabel ); ?></h2>
	     	</div>
		</div>
		<div class="accordion" id="accordionExample">
		<?php
		$count = 0;
		?>
		<?php foreach ( $foodList as $food ):
			$count ++;
			$gtitle   = $food['gtitle'] ?? '';
			$food_list   = $food['food_list'] ?? '';
			if ( $count === 1 ) {
				$show      = 'show';
				$expand    = 'true';
				$collapsed = '';
			} else {
				$show      = '';
				$expand    = 'false';
				$collapsed = ' collapsed';
			}
			?>
	      	<div class="card">
		        <div class="card-header<?php echo esc_attr( $collapsed ); ?>" data-bs-toggle="collapse"
		            data-bs-target="#collapse<?php echo esc_attr( $count ); ?>"
		            aria-expanded="<?php echo esc_attr( $expand ); ?>" role="tabpanel">

					<?php if ( ! empty( $gtitle ) ): ?>
						<div class="group-title">
							<?php if ( ! empty( $gtitle ) ): ?>
							<h3 class="group-name"><?php echo esc_html( $gtitle ); ?></h3>
							<?php endif; ?>
						</div>
					<?php endif; ?>
		        </div>
		        <div id="collapse<?php echo esc_attr( $count ); ?>" class="collapse <?php echo esc_attr( $show ); ?> tab-content" data-bs-parent="#accordionExample">
		            <div class="card-body">
		            	<?php if ( ! empty( $food_list ) ){ ?>
		            	<div class="single-listing-food-menu food-item">
		            		<?php
                            foreach ($food_list as $key => $value) {
		            			$imgID = $title = $foodprice = $desc = '';
		            			if (isset($value['attachment_id'] )) {
		            				$imgID = $value['attachment_id'];
		            			}
		            			if (isset($value['title'] )) {
										$title = $value['title'];
		            			}
		            			if (isset($value['foodprice'] )) {
										$foodprice = $value['foodprice'];
		            			}
		            			if (isset($value['description'] )) {
										$desc = $value['description'];
		            			}
								$has_attachment = ! empty( $imgID ) && !empty(wp_get_attachment_image( $imgID ));

		            		?>
								<figure>
									<?php if ( ! empty( $has_attachment ) ): ?>
									<a href="<?php echo esc_url( wp_get_attachment_image_url( $imgID, 'full' ) ); ?>" class="food-img" data-size="600x600">
								        <img src="<?php echo esc_url( wp_get_attachment_image_url( $imgID, 'full' ) ); ?>" alt="Image description" />
								    </a>
									<?php endif; ?>
									<div class="food-info">
										<div class="title-price">
											<?php if ( ! empty( $title ) ): ?>
											<h4><?php echo esc_html( $title ); ?></h4>
											<?php endif; ?>
											<?php if ( ! empty( $foodprice ) ): ?>
											<h5><?php echo esc_html( $foodprice ); ?></h5>
											<?php endif; ?>
										</div>
										<?php if ( ! empty( $desc ) ): ?>
						            	<p><?php echo esc_html( $desc ); ?></p>
										<?php endif; ?>
									</div>
								</figure>
							<?php } ?>
						</div>
						<?php } ?>
		            </div>
		        </div>
	      	</div>
		<?php endforeach; ?>
	  	</div>
	</div>
	<?php }
endif; 
?>
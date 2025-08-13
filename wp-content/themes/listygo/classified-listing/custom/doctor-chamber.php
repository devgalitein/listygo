<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */
use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;
use radiustheme\listygo\Listing_Functions;
global $listing;


$doctor_cat_id = Listing_Functions::get_listing_doctor_category_id();
$has_doctor = Listing_Functions::listygo_selected_category_fields( $category_id, $doctor_cat_id );
if ( $has_doctor && Listing_Functions::is_enable_doctor_listing() ):

$generalSettings = Functions::get_option( 'rtcl_general_settings' );
$text = ! empty( $generalSettings['listygo_chamber_section_label'] ) ? $generalSettings['listygo_chamber_section_label'] : '';
$chamberList = get_post_meta( $listing->get_id(), "listygo_doctor_chamber", true );

if ( ! empty( $chamberList ) ) {
?>

<div class="product-plan widget doctor-clinic-list" id="floor-plan">
   <div class="item-heading">
     	<div class="align-items-center">
        	<h2 class="listingDetails-block__heading"><?php echo esc_html( $text ); ?></h2>
     	</div>
	</div>
     <div class="accordion" id="accordionExample">
		<?php 
			$count = 0;
			foreach ( $chamberList as $chamber ):
				$count ++;
				$cname      = $chamber['cname'] ?? '';
				$cloaction  = $chamber['cloaction'] ?? '';
				$time       = $chamber['time'] ?? '';
				$phone      = $chamber['phone'] ?? '';
				$imgID 		= $chamber['chamber_img'] ?? '';
				$phone_url = str_replace(' ', '', $phone);

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
	               aria-bs-expanded="<?php echo esc_attr( $expand ); ?>" role="tabpanel">
						<?php if ( ! empty( $cname ) ): ?>
	                  <div class="chamber-name"><h4><?php echo esc_html( $cname ); ?></h4></div>
						<?php endif; ?>
	            </div>

	            <div id="collapse<?php echo esc_attr( $count ); ?>" class="collapse <?php echo esc_attr( $show ); ?> tab-content" data-bs-parent="#accordionExample">
	               <div class="card-body">
						<?php if ( ! empty( $imgID ) ): ?>
	                     	<div class="clinic-design blocks-gallery-item">
								<?php echo wp_get_attachment_image( $imgID, 'full' ); ?>
	                     	</div>
						<?php endif; ?>

							<?php 
							if ( ! empty( $cloaction || $time || $phone ) ): ?>
								<div class="info">
									<?php if ( ! empty( $cloaction ) ){ ?>
		                        <h6><?php echo esc_html( $cloaction ); ?></h6>
									<?php } 
									if ( ! empty( $time ) ){
									?>
	                        <div class="chamber-time">
	                         	<?php echo esc_html( $time ); ?>
	                        </div>
									<?php } if ( ! empty( $phone_url ) ){ ?>
									<p class="meta-phone">
										<b><?php esc_html_e( 'Call for appointment:', 'listygo' ); ?></b>
										<a href="tel:<?php echo esc_attr( $phone_url ); ?>">
											<?php echo esc_html( $phone ); ?>
										</a>
									</p>
									<?php } ?>
								</div>
							<?php endif; ?>
	               </div>
	            </div>
	         </div>
			<?php endforeach; ?>
    </div>
</div>

<?php } endif; ?>
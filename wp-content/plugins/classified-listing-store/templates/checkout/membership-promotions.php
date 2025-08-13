<?php
/**
 * Membership checkout
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 * @var array      $promotions
 * @var Membership $membership
 * @var int        $listing_id
 */

use Rtcl\Resources\Options;
use RtclStore\Models\Membership;

?>
<div id="rtcl-membership-promotions-table">
    <div class="rtcl-checkout-pricing-wrapper rtcl-row rtcl-form-group">
		<?php if ( ! empty( $promotions ) ) :
			$all_promotions = Options::get_listing_promotions();
			foreach ( $promotions as $promotion_key => $promotion ) {
				?>
                <div class="rtcl-col-md-4 rtcl-col-12">
                    <div class="rtcl-checkout-pricing">
                        <h3 class="rtcl-pricing-title"><?php echo ! empty( $all_promotions[ $promotion_key ] ) ? esc_html( $all_promotions[ $promotion_key ] )
								: esc_html( $promotion_key ) ?></h3>
                        <div class="rtcl-checkout-pricing-inner">
                            <div class="rtcl-pricing-features">
                                <div class="rtcl-membership-promotions">
                                    <div class="promotion-item">
                                        <span>
                                            <?php esc_html_e( "Remaining ads:", "classified-listing-store" ); ?>
                                            <?php ! empty( $promotion['ads'] ) ? esc_html_e( absint( $promotion['ads'] ) ) : esc_html_e( 0 ); ?>
                                        </span>
                                    </div>
                                    <div class="promotion-item">
                                        <span>
                                            <?php esc_html_e( 'Validation Duration:', 'classified-listing-store' ) ?>
                                            <?php printf( __( "%d Days", "classified-listing-store" ),
	                                            ! empty( $promotion['validate'] ) ? absint( $promotion['validate'] ) : 0
                                            ); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="rtcl-pricing-btn">
								<?php
								printf( '<input type="checkbox" name="%s" id="promotion_%s" value="%s" class="rtcl-membership-promotion-input" required/><label for="promotion_%s">%s</label>',
									'_rtcl_membership_promotions[]',
									esc_attr( $promotion_key ),
									esc_attr( $promotion_key ),
									esc_attr( $promotion_key ),
									! empty( $all_promotions[ $promotion_key ] ) ? esc_html( $all_promotions[ $promotion_key ] ) : esc_html( $promotion_key )
								);
								?>
                            </div>
                        </div>
                    </div>
                </div>

			<?php } ?>
		<?php endif; ?>
    </div>
</div>
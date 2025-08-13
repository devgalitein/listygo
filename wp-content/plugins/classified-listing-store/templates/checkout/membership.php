<?php
/**
 * Membership checkout
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */


use Rtcl\Helpers\Functions;

$currency        = Functions::get_order_currency();
$currency_symbol = Functions::get_currency_symbol( $currency );
?>
<div id="rtcl-checkout-pricing-option">
    <div class="rtcl-checkout-pricing-wrapper rtcl-row rtcl-form-group">
		<?php if ( ! empty( $pricing_options ) ) :
			foreach ( $pricing_options as $option ) :
				$price = get_post_meta( $option->ID, 'price', true );
				$pricing = rtcl()->factory->get_pricing( $option->ID );
				$description = $pricing->getDescription();
				?>
                <div class="rtcl-col-md-4 rtcl-col-12">
                    <div class="rtcl-checkout-pricing">
                        <h3 class="rtcl-pricing-title"><?php echo esc_html( $option->post_title ); ?></h3>
                        <div class="rtcl-checkout-pricing-inner">
							<?php if ( $description ): ?>
                                <p class="rtcl-pricing-description"><?php Functions::print_html( nl2br( $description ), true ); ?></p>
							<?php endif; ?>
                            <span class="rtcl-pricing-price"><?php echo Functions::get_payment_formatted_price_html( $price ); ?> </span>
                            <div class="rtcl-pricing-features">
								<?php do_action( 'rtcl_membership_features', $option->ID ) ?>
                            </div>
                            <div class="rtcl-pricing-btn">
								<?php
								printf( '<input type="radio" name="%s" id="pricing_id_%s" value="%s" class="rtcl-checkout-pricing" required data-price="%s"/><label for="pricing_id_%s">%s</label>',
									'pricing_id', esc_attr( $option->ID ), esc_attr( $option->ID ), esc_attr( $price ), esc_attr( $option->ID ),
									esc_html__( 'Select This Package', 'classified-listing-store' ) );
								?>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endforeach;
		else: ?>
            <div>
                <span><?php esc_html_e( "No plan found.", "classified-listing-store" ); ?></span>
            </div>
		<?php endif; ?>
    </div>
</div>

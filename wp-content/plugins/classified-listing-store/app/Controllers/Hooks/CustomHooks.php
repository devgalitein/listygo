<?php

namespace RtclStore\Controllers\Hooks;

use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;
use Rtcl\Resources\Options as RtclOptions;

class CustomHooks {

	public static function init() {
		RtclApplyHook::init();
		add_action( 'rtcl_membership_features', [ __CLASS__, 'membership_features' ] );
		add_action( 'rtcl_widget_ajax_filter_end', [ __CLASS__, 'ajax_filter_store_id' ] );
	}

	public static function ajax_filter_store_id() {
		if ( ! StoreFunctions::is_single_store() ) {
			return;
		}
		?>
        <input type="hidden" id="rtcl_store_id" name="rtcl_store_id" value="<?php echo get_the_ID(); ?>">
		<?php
	}

	public static function membership_features( $pricing_id ) {
		$pricing = rtcl()->factory->get_pricing( $pricing_id );
		if ( $pricing ) {
			$promotions     = get_post_meta( $pricing->getId(), '_rtcl_membership_promotions', true );
			$promotion_list = RtclOptions::get_listing_promotions();
			?>
            <div class="rtcl-membership-promotions">
                <div class="promotion-item">
                    <span>
                        <?php echo absint( get_post_meta( $pricing_id, 'regular_ads', true ) ); ?>
	                    <?php esc_html_e( 'Regular', "classified-listing-store" ); ?>
	                    <?php esc_html_e( 'Ads', "classified-listing-store" ); ?>,
                        <?php echo absint( $pricing->getVisible() ); ?>
	                    <?php esc_html_e( 'Days', "classified-listing-store" ); ?>
                    </span>
                </div>
				<?php
				if ( is_array( $promotions ) && ! empty( $promotions ) ) {
					foreach ( $promotions as $promotion_key => $promotion ) {
						?>
                        <div class="promotion-item">
                            <span>
                                <?php echo absint( $promotion['ads'] ); ?>
	                            <?php esc_html_e( $promotion_list[ $promotion_key ] ); ?>
	                            <?php esc_html_e( 'Ads', "classified-listing-store" ); ?>,
                                <?php echo absint( $promotion['validate'] ); ?>
	                            <?php esc_html_e( 'Days', "classified-listing-store" ); ?>
                            </span>
                        </div>
						<?php
					}
				}
				?>
            </div>
			<?php
		}
	}

}

<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5.3
 */

    use Rtcl\Helpers\Functions;
    use radiustheme\listygo\Listing_Functions;

    $doctor_cat_id = Listing_Functions::get_listing_doctor_category_id();
    $has_doctor = Listing_Functions::listygo_selected_category_fields( $category_id, $doctor_cat_id );
    if ( $has_doctor && Listing_Functions::is_enable_doctor_listing() ):

    $generalSettings = Functions::get_option( 'rtcl_general_settings' );
    $text = isset( $generalSettings['listygo_chamber_section_label'] ) && ! empty( $generalSettings['listygo_chamber_section_label'] ) ? $generalSettings['listygo_chamber_section_label'] : '';
    ?>
    <div class="additional-input-wrap">
      <div class="form-group">
        <label></label>
      </div>
      <div class="rtcl-post-section-title">
        <h3><i class="rtcl-icon rtcl-icon-clock"></i><?php echo esc_html( $text ); ?>:</h3>
      </div>

      <div class="doctor-chamber-wrapper">
        <div class="dr-clinic-wrap">
          <?php
          $chamberList = get_post_meta( $post_id, "listygo_doctor_chamber", true );
          if ( ! empty( $chamberList ) ) {
            $count = 0;
              foreach ( $chamberList as $chamber ) {
                $cname        = $chamber['cname'] ?? '';
                $cloac        = $chamber['cloaction'] ?? '';
                $time         = $chamber['time'] ?? '';
                $phone        = $chamber['phone'] ?? '';
                $chamberImage = $chamber['chamber_img'] ?? '';
                ?>
                <div class="dr-clinic-item">
                  <span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                  <div class="rn-recipe-title">
                    <input type="text" name="listygo_doctor_chamber[<?php echo esc_attr( $count ) ?>][cname]" class="form-control" placeholder="<?php esc_attr_e( 'Chamber', 'listygo' ); ?>" value="<?php echo esc_attr( $cname ? $cname : '' ); ?>">
                    <textarea rows="3" cols="10" class="form-control" name="listygo_doctor_chamber[<?php echo esc_attr( $count ) ?>][cloaction]" placeholder="<?php esc_attr_e( 'Location', 'listygo' ); ?>"><?php echo esc_html( $cloac ? $cloac : '' ); ?></textarea>
                  </div>
                  <div class="rn-ingredient-item">
                    <div class="rn-ingredient-fields">
                      <input type="text" placeholder="<?php esc_attr_e( 'Time', 'listygo' ); ?>"
                        class="form-control"
                        name="listygo_doctor_chamber[<?php echo esc_attr( $count ) ?>][time]"
                        value="<?php echo esc_attr( $time ? $time : '' ); ?>">
                      <input type="text" placeholder="<?php esc_attr_e( 'Phone', 'listygo' ); ?>"
                        class="form-control"
                        name="listygo_doctor_chamber[<?php echo esc_attr( $count ) ?>][phone]"
                        value="<?php echo esc_attr( $phone ? $phone : '' ); ?>">
                    </div>
                  </div>
                  <div class="chamber-image-wrap">
                    <?php if ( ! empty( $chamberImage ) ): ?>
                      <div class="chamber-image">
                        <input name="listygo_doctor_chamber[<?php echo esc_attr( $count ) ?>][attachment_id]" 
                          type="hidden" value="<?php echo esc_attr( $chamberImage ? $chamberImage : '' ); ?>">
                          <?php echo wp_get_attachment_image( $chamberImage, 'full' ); ?>
                          <div class="remove-chamber-image">
                            <a href="#" data-index="<?php echo esc_attr( $count ) ?>" data-post_id="<?php echo esc_attr( $post_id ); ?>" data-attachment_id="<?php echo esc_attr( $chamberImage ); ?>"><?php esc_html_e( 'Remove Image', 'listygo' ); ?></a>
                          </div>
                      </div>
                    <?php endif; ?>
                    <div class="chamber-input-wrapper <?php echo esc_attr( $chamberImage ? 'd-none' : '' ); ?>">
                      <input name="listygo_chamber_img[<?php echo esc_attr( $count ) ?>]" class="listygo-chamber-image" type="file"/>
                    </div>
                  </div>
              </div>
              <?php
              $count ++;
            }
          } ?>
        </div>
        <div class="rn-recipe-actions">
          <a href="javascript:void()" class="btn-upload add-ingredient add-recipe btn-sm btn-primary"><?php esc_html_e( 'Add Chamber', 'listygo' ); ?></a>
        </div>
      </div>
    </div>
    <?php endif; ?>
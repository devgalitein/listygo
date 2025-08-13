<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5.3
 */

    use Rtcl\Helpers\Functions;
    use radiustheme\listygo\Listing_Functions;

    $restaurant_cat_id = Listing_Functions::get_listing_restaurant_category_id();
    $has_restaurant = Listing_Functions::listygo_selected_category_fields( $category_id, $restaurant_cat_id );
    
    if ( $has_restaurant && Listing_Functions::is_enable_restaurant_listing() ): ?>

    <?php
      $generalSettings = Functions::get_option( 'rtcl_general_settings' );
      $sectionLabel = ! empty( $generalSettings['listygo_food_list_section_label'] ) ? $generalSettings['listygo_food_list_section_label'] : '';
    ?>
    
    <div class="additional-input-wrap">
      <div class="form-group">
        <label></label>
      </div>
      <div class="rtcl-post-section-title">
        <h3><i class="rtcl-icon rtcl-icon-food"></i><?php echo esc_html( $sectionLabel ); ?>:</h3>
      </div>

      <div class="listygo-food-menu-main-wrapper">
        <div class="additional-input-wrap group-bottom">
          <div class="form-group row">
            <div class="col-md-12">
              <div class="food-menu-wrapper">
                <div class="rn-recipe-wrap">
                  <?php
                  $recipes = get_post_meta( $post_id, "listygo_food_list", true );

                  if (!empty($recipes)) {
                    foreach ($recipes as $key => $recipe) {
                      
                      ?>
                      <div class="rn-recipe-item">
                        <span class="rn-remove"><i class="fa fa-times"
                         aria-hidden="true"></i></span>
                        <div class="rn-recipe-title">
                          <input type="text"
                          name="listygo_food_list[<?php echo absint($key); ?>][gtitle]"
                          class="form-control"
                          value="<?php echo isset($recipe['gtitle']) ? esc_attr($recipe['gtitle']) : '' ?>"
                          placeholder="<?php esc_attr_e( 'Food Group Title', 'listygo' ); ?>">
                        </div>
                        <div class="rn-ingredient-wrap">
                          <?php if (!empty($recipe['food_list'])) {
                            foreach ($recipe['food_list'] as $ikey => $food_list) {
                                $imgId = '';
                                if (isset($food_list['attachment_id'])) {
                                  $imgId = $food_list['attachment_id'];
                                }
                              ?>
                              <div class="rn-ingredient-item">
                                <div class="rn-ingredient-fields">
                                  <input type="text"
                                  placeholder="<?php esc_attr_e( 'Food Name', 'listygo' ); ?>"
                                  class="form-control"
                                  value="<?php echo isset($food_list['title']) ? esc_attr($food_list['title']) : '' ?>"
                                  name="listygo_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][title]">

                                  <input type="text"
                                  placeholder="<?php esc_attr_e( 'Food Price', 'listygo' ); ?>"
                                  class="form-control"
                                  value="<?php echo isset($food_list['foodprice']) ? esc_attr($food_list['foodprice']) : '' ?>"
                                  name="listygo_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][foodprice]">

                                  <textarea placeholder="<?php esc_attr_e( 'Description', 'listygo' ); ?>"
                                  class="form-control"
                                  name="listygo_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][description]"><?php echo isset($food_list['description']) ? esc_html($food_list['description']) : '' ?></textarea>
                                </div>
                                  
                                <div class="food-image-wrap">
                                  <?php
                                  $has_attachment = ! empty( $imgId ) && !empty(wp_get_attachment_image( $imgId ));
                                  if ($has_attachment){ ?>
                                    <div class="food-image">

                                      <input 
                                        name="listygo_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][attachment_id]"
                                        type="hidden" value="<?php echo esc_attr( $imgId ? $imgId : '' ); ?>">

                                        <?php echo wp_get_attachment_image( $imgId, 'full' ); ?>

                                      <div class="remove-food-image">
                                        <a href="#" data-index="<?php echo absint($key); ?>" data-post_id="<?php echo esc_attr( $post_id ); ?>" data-attachment_id="<?php echo esc_attr( $imgId ); ?>"><?php esc_html_e( 'Remove Image', 'listygo' ); ?></a>
                                      </div>

                                    </div>
                                  <?php } ?>
                                  <div class="food-input-wrapper <?php echo esc_attr( $has_attachment ? 'd-none' : '' ); ?>">
                                    <input name="listygo_food_images[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>]" class="listygo-food-image" type="file"/>
                                  </div>
                                </div>
                                <span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                              </div>
                              <?php
                              }
                          } ?>
                        </div>
                        <div class="rn-ingredient-actions">
                          <a href="javascript:void()"
                          class="btn-upload add-ingredient btn-sm btn-primary"><?php esc_html_e('Add New Food', 'listygo'); ?></a>
                        </div>
                      </div>
                      <?php
                      }
                    }
                  ?>
                </div>
                <div class="rn-recipe-actions">
                  <a href="javascript:void()" class="btn-upload add-recipe btn-sm btn-primary"><?php esc_html_e('Add New Foods', 'listygo'); ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>